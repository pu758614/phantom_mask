<?php
#####################################################################
# openingByDateTime
# 依照日期時間回傳有營業的藥局
# Date : 2021-02-03
#####################################################################
include_once(dirname(__DIR__)."../../lib/common.php");
include_once("$ROOT_PATH/lib/db_lib.php");
$response_data = array(
    'error' => true,
    'msg' => '',
);
$api_status = 0;
$db = new db_lib();
$request_data = file_get_contents("php://input");
$request_arr = json_decode($request_data,'true');
if(!is_array($request_arr)){
    $response_data['msg'] = 'Error json format';
    goto end;
}
$check_data = check_sort_data($request_arr);
if($check_data['error']){
    $response_data['msg'] = $check_data['msg'];
    goto end;
}
$data = isset($check_data['data'])?$check_data['data']:'';
$opening_list = $db->getOpeningByDateTime($data['time']);
$response_data['error'] = false;
if(empty($opening_list)){
    $response_data['msg'] = 'No opening phantom.';
}else{
    $response_data['data'] = $opening_list;
}
$api_status = 1;
goto end;



end:
    $db->saveApiResult('openingByDateTime',$request_data,$response_data,$api_status);
    echo json_encode($response_data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


function check_sort_data($response){

    $return = array(
        'error' => true,
        'msg' => '',
        'data' => array(),
    );
    if(!isset($response['token'])){
        $return['msg'] = 'Lack of food';
        return $return;
    }

    $check_token= checkToken($response['token']);
    if($check_token['error']){
        $return['msg'] = $check_token['msg'];
        return $return;
    }
    $data = isset($response['data'])?$response['data']:array();
    if(empty($data)){
        $return['msg'] = 'Error data formate.';
        return $return;
    }
    if(!isset($data['dateTime'])){
        $return['msg'] = 'Lack dateTime';
        return $return;
    }

    if(!CheckDateTime($data['dateTime'])){
        $return['msg'] = 'Wrong dateTime';
        return $return;
    }
    $return['data'] = array(
        'time' => $data['dateTime']
    );
    $return['error'] = false;
    return $return;
}