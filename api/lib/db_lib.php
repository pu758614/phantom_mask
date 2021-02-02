<?php
include_once(dirname(__DIR__)."/lib/common.php");
include_once("$ROOT_PATH/api/lib/adodb/adodb.inc.php");
include_once("$ROOT_PATH/api/lib/db_crud.php");
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
}
