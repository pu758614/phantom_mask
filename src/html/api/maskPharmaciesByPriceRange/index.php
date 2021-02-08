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

$data = isset($check_data['data'])?$check_data['data']:array();
$opening_list = $db->maskPharmaciesByPriceRange($data['minPrice'],$data['maxPrice']);

$response_data['error'] = false;
if(empty($opening_list)){
    $response_data['msg'] = '無此範圍資料';
}else{
    $response_data['data'] = $opening_list;
}
$api_status = 1;
goto end;



end:
    $db->saveApiResult('maskPharmaciesByPriceRange',$request_data,$response_data,$api_status);
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
    if(!isset($data['minPrice']) && !isset($data['maxPrice'])){
        $return['msg'] = 'Lack of minPrice';
        return $return;
    }
    if(isset($data['minPrice']) && (!is_numeric($data['minPrice']) || $data['minPrice']<0)){
        $return['msg'] = '"minPrice" must be a number greater than zero.';
        return $return;
    }
    if(isset($data['maxPrice']) && (!is_numeric($data['maxPrice']) || $data['maxPrice']<0)){
        $return['msg'] = 'maxPrice must be a number greater than zero.';
        return $return;
    }
    if( isset($data['minPrice']) && isset($data['minPrice']) ){
        if( ($data['minPrice']!=0) &&
        ($data['maxPrice']!=0) &&
        ($data['maxPrice']<$data['minPrice'])
        ){
            $return['msg'] = 'maxPrice must be greater than or equal to minPrice';
            return $return;
        }
    }
    $minPrice = isset($data['minPrice'])?$data['minPrice']:'';
    $maxPrice = isset($data['maxPrice'])?$data['maxPrice']:'';

    if($minPrice===0 && $maxPrice===0){
        $return['msg'] = 'Missing price range.';
        return $return;
    }
    $return['data'] = array(
        'minPrice' => $minPrice,
        'maxPrice' => $maxPrice,
    );
    $return['error'] = false;
    return $return;
}