<?php
#####################################################################
# openingByWeekday
# 依照一週的某一日回傳有營業的藥局
# Date : 2021-02-03
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
$opening_list = $db->getOpeningByWeekDay($data['weekDay']);
$request_data['error'] = false;
if(empty($opening_list)){
    $request_data['msg'] = '無營業店家';
}else{
    $request_data['data'] = $opening_list;
}
$api_status = 1;
goto end;



end:
    $db->saveApiResult('openingByWeekday',$response_data,$request_data,$api_status);
    echo json_encode($request_data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


function check_sort_data($response){

    $return = array(
        'error' => true,
        'msg' => '',
        'data' => array(),
    );
    if(!isset($response['token'])){
        $return['msg'] = '缺少token';
        return $return;
    }

    $check_token= checkToken($response['token']);
    if($check_token['error']){
        $return['msg'] = $check_token['msg'];
        return $return;
    }
    $data = isset($response['data'])?$response['data']:array();
    if(empty($data)){
        $return['msg'] = '錯誤的data格式';
        return $return;
    }
    if(!isset($data['weekDay'])){
        $return['msg'] = '缺少必要參數';
        return $return;
    }
    if(!in_array($data['weekDay'],array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'))){
        $return['msg'] = '錯誤的日期名稱';
        return $return;
    }
    $return['data'] = array(
        'weekDay' => $data['weekDay']
    );
    $return['error'] = false;
    return $return;
}