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
$pharmacies_data = $db->getSingleById('kdan_mask_pharmacies','uuid',$data['pharmaciesUUID']);
if(empty($pharmacies_data)){
    $response_data['msg'] = 'Error pharmaciesUUID';
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
        'maskUUID' => $mask_data['uuid'],
        'maskName' => "$name ($color) ($per per pack)",
        "price" => $mask_data['price']
    );
}

$response_data['error'] = false;
if(empty($data_list)){
    $response_data['msg'] = 'This pharmacy has no mask data.';
}else{
    $response_data['data'] = $data_list;
}
$api_status = 1;
goto end;



end:
    $db->saveApiResult('maskItemByPharmacies',$response_data,$request_data,$api_status);
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
    if(!isset($data['pharmaciesUUID']) && !isset($data['pharmaciesUUID'])){
        $return['msg'] = 'Lack of pharmaciesUUID';
        return $return;
    }

    $sort = isset($data['sort'])?$data['sort']:'';
    if($sort!='' && $sort!='name' && $sort!='price'){
        $return['msg'] = '"sort" is error.';
        return $return;
    }

    $return['data'] = array(
        'pharmaciesUUID' => $data['pharmaciesUUID'],
        'sort'           => $sort
    );
    $return['error'] = false;
    return $return;
}