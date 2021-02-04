<?php
#####################################################################
# searchMaskPharmacies
# 依照藥局或口罩名稱搜尋資料
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
    $request_arr['msg'] = '資料解析失敗';
    goto end;
}
$check_data = check_sort_data($request_arr);
if($check_data['error']){
    $response_data['msg'] = $check_data['msg'];
    goto end;
}
$data = isset($check_data['data'])?$check_data['data']:'';
$keyword = isset($data['keyword'])?trim($data['keyword']):'';

$search_data= $db->searchMaskPharmacies($keyword,$data['condition']);

if(empty($search_data)){
    $response_data['msg'] = '無搜尋資料';
}else{
    $response_data['data'] = $search_data;
    $response_data['error'] = false;
    $api_status = 1;
}

end:
    $db->saveApiResult('searchMaskPharmacies',$request_data,$response_data,$api_status);
    echo json_encode($response_data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);



function check_sort_data($request){
    $return = array(
        'error' => true,
        'msg' => '',
        'data' => array(),
    );
    if(!isset($request['token'])){
        $return['msg'] = '缺少token';
        return $return;
    }

    $check_token= checkToken($request['token']);
    if($check_token['error']){
        $return['msg'] = $check_token['msg'];
        return $return;
    }
    $data = isset($request['data'])?$request['data']:array();
    if(empty($data)){
        $return['msg'] = '錯誤的data格式';
        return $return;
    }
    if(!isset($data['keyword']) || trim($data['keyword']) == ''){
        $return['msg'] = '缺少keyword';
        return $return;
    }

    if(!in_array($data['condition'],array('mask','pharmacies'))){
        $return['msg'] = '錯誤的搜尋條件';
        return $return;
    }

    $return['data'] = array(
        'keyword' => $data['keyword'],
        'condition' => $data['condition']
    );
    $return['error'] = false;

    return $return;

}