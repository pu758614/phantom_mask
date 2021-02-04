<?php
#####################################################################
# maskItemByPharmacies
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
$pharmacies_data = $db->getSingleById('kdan_mask_pharmacies','uuid',$data['pharmaciesUUID']);
if(empty($pharmacies_data)){
    $request_data['msg'] = '錯誤的pharmaciesUUID';
    goto end;
}

$cond = array(
    'pharmaciesId' => $pharmacies_data['id'],
    'isDelete' => 0,
);

$mask_list = $db->getArrayByArray('kdan_mask_mask_item',$cond,$data['sort']);
$data_list = array();
foreach ($mask_list as $mask_data) {
    $name = $mask_data['name'];
    $color = $mask_data['color'];
    $per = $mask_data['per'];
    $data_list[] = array(
        'name' => "$name ($color) ($per per pack)",
        "price" => $mask_data['price']
    );
}

$request_data['error'] = false;
if(empty($data_list)){
    $request_data['msg'] = '此藥局無口罩資料';
}else{
    $request_data['data'] = $data_list;
}
$api_status = 1;
goto end;



end:
    $db->saveApiResult('maskItemByPharmacies',$response_data,$request_data,$api_status);
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
    if(!isset($data['pharmaciesUUID']) && !isset($data['pharmaciesUUID'])){
        $return['msg'] = '缺少必要參數';
        return $return;
    }

    $sort = isset($data['sort'])?$data['sort']:'';
    if($sort!='' && $sort!='name' && $sort!='price'){
        $return['msg'] = '錯誤的排序條件';
        return $return;
    }

    $return['data'] = array(
        'pharmaciesUUID' => $data['pharmaciesUUID'],
        'sort'           => $sort
    );
    $return['error'] = false;
    return $return;
}