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
