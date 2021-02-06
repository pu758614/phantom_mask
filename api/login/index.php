<?php
#####################################################################
# login
# 登入取得token
# Date : 2021-02-02
#####################################################################
include_once(dirname(__DIR__)."../../lib/common.php");
include_once("$ROOT_PATH/lib/db_lib.php");
$request_data = array(
    'error' => true,
    'msg' => '',
);
$api_status = 0;
$db = new db_lib();
$response_data = file_get_contents("php://input");
$response_arr = json_decode($response_data,'true');
if(!is_array($response_arr)){
    $request_data['msg'] = '資料解析失敗';
    goto end;
}
$check_data = check_sort_data($response_arr);
if($check_data['error']){
    $request_data['msg'] = $check_data['msg'];
    goto end;
}



$data = isset($check_data['data'])?$check_data['data']:'';
$token_msg = $data['login_name']."_".date('Y-m-d H:i:s',strtotime("+$TOKEN_TIME second"));
$token = getEncodeStr($token_msg);
$request_data['data'] = array(
    'token' => $token
);
$request_data['error'] = false;
$api_status = 1;


end:
    $db->saveApiResult('login',$response_data,$request_data,$api_status);
    echo json_encode($request_data);

function check_sort_data($data){

    $return = array(
        'error' => true,
        'msg' => '',
        'data' => array(),
    );

    if(!isset($data['login_name'])){
        $return['msg'] = '缺少login_name';
        return $return;
    }

    if(!$data['login_name']=='kdan'){
        $return['msg'] = '錯誤的login_name';
        return $return;
    }
    $return['error'] = false;
    $return['data'] = array(
        'login_name' => $data['login_name'],
    );
    return $return;
}