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

    function maskPharmaciesByPriceRange($min,$max){
        $where_arr = array();
        echo "$min";
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
                    if(empty($pharm_data)){
                        echo '<pre>';
                        print_r($pharm_data);
                        echo '</pre>';
                    }
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
        $sql = "SELECT uuid,
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
        $sql = "SELECT uuid,
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
