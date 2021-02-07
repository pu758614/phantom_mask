<?php
#####################################################################
# deleteMask
# 依葯局uuid和口罩名稱刪除口罩
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
    $response_data['msg'] = '資料解析失敗';
    goto end;
}
$check_data = check_sort_data($request_arr);
if($check_data['error']){
    $response_data['msg'] = $check_data['msg'];
    goto end;
}

$data = isset($check_data['data'])?$check_data['data']:'';

$pharmacies_data = $db->getSingleById('kdan_mask_pharmacies','uuid',$data['pharmaciesUUID']);
if(empty($pharmacies_data)){
    $response_data['msg'] = '無pharmaciesUUID資料';
    goto end;
}

$cond = array(
    'pharmaciesId' => $pharmacies_data['id'],
    'fullName' => $data['maskName'],
    'isDelete' => 0
);
$mask_data = $db->getSingleByArray('kdan_mask_mask_item',$cond);
if(empty($mask_data)){
    $response_data['msg'] = $pharmacies_data['name'].'查無此Mask資料';
    goto end;
}
$cond = array(
    'id' => $mask_data['id'],
);
$data = array(
    'isDelete' => 1,
    'modifyTime' => date('Y-m-d H:i:s')
);
$result = $db->updateData('kdan_mask_mask_item',$data,$cond);
if($result){
    $response_data['msg'] = "刪除成功";
    $response_data['error'] = false;
    $api_status = 1;
}else {
    $response_data['msg'] = "更新失敗";
}


end:
    $db->saveApiResult('deleteMask',$request_data,$response_data,$api_status);
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
    if(!isset($data['pharmaciesUUID'])){
        $return['msg'] = '缺少pharmaciesUUID';
        return $return;
    }
    if(!isset($data['maskName'])){
        $return['msg'] = '缺少maskName';
        return $return;
    }


    $return['data'] = array(
        'pharmaciesUUID' => $data['pharmaciesUUID'],
        'maskName' => $data['maskName'],
    );
    $return['error'] = false;

    return $return;

}