<?php
#####################################################################
# maskPharmaciesByPriceRange
# 依照金額範圍取得藥局與口罩項目
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

$data = isset($check_data['data'])?$check_data['data']:array();
$opening_list = $db->maskPharmaciesByPriceRange($data['minPrice'],$data['maxPrice']);

$request_data['error'] = false;
if(empty($opening_list)){
    $request_data['msg'] = '無此範圍資料';
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
    if(!isset($data['minPrice']) && !isset($data['maxPrice'])){
        $return['msg'] = '缺少必要參數';
        return $return;
    }
    if(isset($data['minPrice']) && (!is_numeric($data['minPrice']) || $data['minPrice']<0)){
        $return['msg'] = 'minPrice必須為正數';
        return $return;
    }
    if(isset($data['maxPrice']) && (!is_numeric($data['maxPrice']) || $data['maxPrice']<0)){
        $return['msg'] = 'maxPrice必須為正數';
        return $return;
    }
    if( isset($data['minPrice']) && isset($data['minPrice']) ){
        if( ($data['minPrice']!=0) &&
        ($data['maxPrice']!=0) &&
        ($data['maxPrice']<$data['minPrice'])
        ){
            $return['msg'] = 'maxPrice必須大於或等於minPrice';
            return $return;
        }
    }
    $minPrice = isset($data['minPrice'])?$data['minPrice']:'';
    $maxPrice = isset($data['maxPrice'])?$data['maxPrice']:'';

    if($minPrice===0 && $maxPrice===0){
        $return['msg'] = '未填寫金額範圍';
        return $return;
    }
    $return['data'] = array(
        'minPrice' => $minPrice,
        'maxPrice' => $maxPrice,
    );
    $return['error'] = false;
    return $return;
}