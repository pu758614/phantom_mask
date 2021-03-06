<?php
#####################################################################
# sellTotalTopByDate
# 某時段內交易總額前X位
# Date : 2021-02-04
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

$get_data= $db->sellTotalTopByDate($data['count'],$data['startDate'],$data['endDate']);

if(empty($get_data)){
    $response_data['msg'] = 'Not search data.';
}else{
    $response_data['data'] = $get_data;
    $response_data['error'] = false;
    $api_status = 1;
}

end:
    $db->saveApiResult('sellTotalTopByDate',$request_data,$response_data,$api_status);
    echo json_encode($response_data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);



function check_sort_data($request){
    $return = array(
        'error' => true,
        'msg' => '',
        'data' => array(),
    );
    if(!isset($request['token'])){
        $return['msg'] = 'Lack of food';
        return $return;
    }

    $check_token= checkToken($request['token']);
    if($check_token['error']){
        $return['msg'] = $check_token['msg'];
        return $return;
    }
    $data = isset($request['data'])?$request['data']:array();
    if(empty($data)){
        $return['msg'] = 'Error data formate.';
        return $return;
    }
    if(!isset($data['count'])){
        $return['msg'] = '缺少count';
        return $return;
    }
    if(!is_numeric($data['count'])){
        $return['msg'] = 'count must be a number';
        return $return;
    }

    if(!isset($data['startDate'])){
        $return['msg'] = 'Lack of startDate';
        return $return;
    }
    if(!CheckDateTime($data['startDate'])){
        $return['msg'] = 'Wrong startDate formate';
        return $return;
    }
    if(!isset($data['endDate'])){
        $return['msg'] = 'Lack of startDate';
        return $return;
    }
    if(!CheckDateTime($data['endDate'])){
        $return['msg'] = 'Wrong startDate formate.';
        return $return;
    }

    $return['data'] = array(
        'count'     => $data['count'],
        'startDate' => $data['startDate'],
        'endDate'   => $data['endDate'],
    );
    $return['error'] = false;

    return $return;

}