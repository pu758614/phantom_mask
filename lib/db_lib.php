<?php
include_once(dirname(__DIR__)."/lib/common.php");
include_once("$ROOT_PATH/lib/adodb/adodb.inc.php");
include_once("$ROOT_PATH/lib/db_crud.php");
class db_lib {
    use DB_CRUD\DB_CRUD;
    function __construct(){
        date_default_timezone_set('asia/taipei');
        header("Content-type: text/html; charset=utf-8");
        $ini_list = parse_ini_file('conf.ini', true, INI_SCANNER_RAW);
        $data_base = isset($ini_list['database'])?$ini_list['database']:array();
        $host = isset($data_base['host'])?$data_base['host']:'';
        $user = isset($data_base['username'])?$data_base['username']:'';
        $passwd = isset($data_base['password'])?$data_base['password']:'';
        $database = isset($data_base['database'])?$data_base['database']:'';
        $this->db = ADONewConnection('mysqli');
        $this->db->setCharset('utf8');
        $this->db->Connect($host,$user,$passwd,$database);
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
    }

    function sellTotalMaskTotalByDate($startDate,$endDate){
        $tb_sell_record = "`kdan_mask_sell_record`";
        $sql = "SELECT sum(per) as maskTotal ,
                       ROUND(sum(sellAmount),2) as amountTotal
                FROM $tb_sell_record
                WHERE sellDate>=? AND sellDate<= ?";
        $rs = $this->db->Execute($sql,array($startDate,$endDate));
        $data = array(
            'maskTotal' => 0,
            'amountTotal' => 0,
        );
        if($rs && $rs->RecordCount() > 0){
            $data = $rs->FetchRow();
        }
        return $data;
    }

    function sellTotalTopByDate($count,$startDate,$endDate){
        $tb_sell_record = "`kdan_mask_sell_record`";
        $tb_user = "`kdan_mask_user`";
        $sql = "SELECT $tb_user.name,
                        $tb_user.uuid as userUUID,
                        ROUND(sum($tb_sell_record.`sellAmount`),2) as total
                FROM $tb_sell_record
                LEFT JOIN $tb_user
                ON $tb_sell_record.userId = $tb_user.id
                WHERE $tb_sell_record.sellDate >= ? AND $tb_sell_record.sellDate<= ?
                group by $tb_user.name
                order by total DESC
                limit $count";
        $rs = $this->db->Execute($sql,array($startDate,$endDate));
        $data = array();
        if($rs && $rs->RecordCount() > 0){
            $data = $rs->getAll();
        }
        return $data;
    }

    function searchMaskPharmacies($keyword,$cond){
        $tb_mask_item = "`kdan_mask_mask_item`";
        $tb_pharmacies = "`kdan_mask_pharmacies`";
        $search_data = array();
        if($cond == 'mask'){
            $sql = "SELECT  $tb_mask_item.fullName as maskName,
                            $tb_mask_item.uuid as maskUUID,
                            $tb_pharmacies.name as pharmaciesName,
                            $tb_pharmacies.uuid as pharmaciesUUID
                    FROM $tb_mask_item
                    LEFT JOIN $tb_pharmacies
                    ON $tb_mask_item.pharmaciesId = $tb_pharmacies.id
                    WHERE $tb_mask_item.fullName LIKE ? AND isDelete=?
                    order by $tb_mask_item.fullName ASC
            ";
            $rs = $this->db->Execute($sql,array("%$keyword%",'0'));
            if($rs && $rs->RecordCount() > 0){
                $search_data = $rs->getAll();
            }
        }else{
            $sql = "SELECT uuid,name
                    FROM $tb_pharmacies
                    WHERE name LIKE ?
                    order by name ASC";
            $rs = $this->db->Execute($sql,array("%$keyword%"));
            if($rs && $rs->RecordCount() > 0){
                $search_data = $rs->getAll();
            }
        }
        return $search_data;
    }


    function maskPharmaciesByPriceRange($min,$max){
        $where_arr = array();
        $arr = array();
        if($min !=0 ){
            $where_arr[] = "price>=?";
            $arr[] = $min;
        }
        if($max !=0 ){
            $where_arr[] = "price<=?";
            $arr[] = $max;
        }
        $where = implode(' AND ',$where_arr);
        $arr[] = 0;
        $mask_item_tb = "`kdan_mask_mask_item`";
        $sql = "SELECT *  FROM $mask_item_tb WHERE $where AND isDelete=? order by price ASC";
        $rs = $this->db->Execute($sql,$arr);
        $data = $pharm_tmp =array();
        if($rs && $rs->RecordCount() > 0){
            foreach ($rs as $mask_item) {
                if(!isset($data[$mask_item['pharmaciesId']])){
                    $pharm_data = $this->getSingleById('kdan_mask_pharmacies','id',$mask_item['pharmaciesId']);
                    $pharm_tmp[$mask_item['pharmaciesId']] = $pharm_data;
                    $data[$mask_item['pharmaciesId']] = array(
                        'pharmaciesUUID' => $pharm_data['uuid'],
                        'name' => $pharm_data['name'],
                    );
                }else{
                    $pharm_data = $pharm_tmp[$mask_item['pharmaciesId']];
                }
                $name = $mask_item['name'];
                $color= $mask_item['color'];
                $per= $mask_item['per'];
                $data[$mask_item['pharmaciesId']]['maskList'][] = array(
                    'maskUUID' => $mask_item['uuid'],
                    'price' => $mask_item['price'],
                    'name'  => "$name ($color) ($per per pack)",
                );
            }
            $data = array_values($data);
        }
        return $data;
    }


    function getOpeningByWeekDay($week_day){
        $start_field = $week_day."TimeStart";
        $end_field = $week_day."TimeEnd";
        $table_name = "`kdan_mask_pharmacies`";
        $sql = "SELECT uuid as pharmaciesUUID,
                       name,
                       $start_field as  start_time,
                       $end_field as end_time
                FROM $table_name
                WHERE  $start_field!=? AND $end_field!=?";
        $rs = $this->db->Execute($sql,array('00:00:00','00:00:00'));
        if($rs && $rs->RecordCount() > 0){
            return $rs->getAll();
        }else{
            return array();
        }
    }

    function getOpeningByDateTime($data){
        $week_en_change = array(
            '0' => 'Sun',
            '1' => 'Mon',
            '2' => 'Tue',
            '3' => 'Wed',
            '4' => 'Thu',
            '5' => 'Fri',
            '6' => 'Sat',
        );
        $weekday = date('w', strtotime($data));
        $week_en = isset($week_en_change[$weekday])?$week_en_change[$weekday]:'';
        $start_field = $week_en."TimeStart";
        $end_field = $week_en."TimeEnd";
        $data_arr = explode(' ',$data);
        $table_name = "`kdan_mask_pharmacies`";
        $sql = "SELECT uuid  as pharmaciesUUID,
                       name,
                       $start_field as  start_time,
                       $end_field as end_time
                FROM $table_name
                WHERE  $start_field<? AND $end_field>?";
        $rs = $this->db->Execute($sql,array($data_arr[1],$data_arr[1]));
        if($rs && $rs->RecordCount() > 0){
            return $rs->getAll();
        }else{
            return array();
        }
    }

    function saveApiResult($action,$response,$request,$status){
        if(is_array($response)){
            $response = json_encode($response,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        if(is_array($request)){
            $request = json_encode($request,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        $data =array(
            'action'     => $action,
            'ip'         => $_SERVER['REMOTE_ADDR'],
            'request'    => $response,
            'response'   => $request,
            'status'     => $status,
            'createTime' => date('Y-m-d H:i:s')
        );
        $this->insertData('kdan_mask_api_record',$data);
    }
}
