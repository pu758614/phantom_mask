<?php
#####################################################################
# userBuyMask
# 使用者從藥局購買口罩
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

$user_data = $db->getSingleById('kdan_mask_user','uuid',$data['userUUID']);
if(empty($user_data)){
    $response_data['msg'] = 'userUUID查無資料';
    goto end;
}
$mask_cond = array(
    'uuid' => $data['maskUUID'],
    'isDelete' => 0,
);

$mask_dsta = $db->getSingleByArray('kdan_mask_mask_item',$mask_cond);
if(empty($mask_dsta)){
    $response_data['msg'] = 'maskUUID no data found';
    goto end;
}
$pharmacies_data = $db->getSingleById('kdan_mask_pharmacies','id',$mask_dsta['pharmaciesId']);
if(empty($mask_dsta)){
    $response_data['msg'] = 'Pharmacies data no  found';
    goto end;
}
if($user_data['cashBalance']<$mask_dsta['price']){
    $response_data['msg'] = 'User insufficient money';
    goto end;
}
$db->db->beginTrans();
$user_cashBalance = $user_data['cashBalance'] - $mask_dsta['price'];
$data = array(
    'cashBalance' => $user_cashBalance
    ,'modifyTime' => date('Y-m-d H:i:s')
);
$result = $db->updateData('kdan_mask_user',$data,array("id"=>$user_data['id']));

if(!$result){
    $response_data['msg'] = 'user update fail.';
    $db->db->rollbackTrans();
    goto end;
}

$pharm_cashBalance = $pharmacies_data['cashBalance']+$mask_dsta['price'];
$data = array(
    'cashBalance' => $pharm_cashBalance
    ,'modifyTime' => date('Y-m-d H:i:s')
);

$result = $db->updateData('kdan_mask_pharmacies',$data,array("id"=>$pharmacies_data['id']));

if(!$result){
    $response_data['msg'] = 'Pharmacies update fail.';
    $db->db->rollbackTrans();
    goto end;
}
$sell_record = array(
    'userId'       => $user_data['id'],
    'pharmaciesId' => $pharmacies_data['id'],
    'name'         => $mask_dsta['name'],
    'color'        => $mask_dsta['color'],
    'per'          => $mask_dsta['per'],
    'sellAmount'   => $mask_dsta['price'],
    'sellDate'     => date('Y-m-d H:i:s'),
    'createTime'   => date('Y-m-d H:i:s')
);
$result = $db->insertData('kdan_mask_sell_record',$sell_record);
if(!$result){
    $response_data['msg'] = 'update fail.';
    $db->db->rollbackTrans();
    goto end;
}
$db->db->commitTrans();
$api_status = 1;
$response_data['msg'] = "Transaction finish";
$response_data['error'] = false;

end:
    $db->saveApiResult('userBuyMask',$request_data,$response_data,$api_status);
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
    if(!isset($data['userUUID'])){
        $return['msg'] = 'Lack of userUUID';
        return $return;
    }
    if(!isset($data['maskUUID'])){
        $return['msg'] = 'Lack of userUUID';
        return $return;
    }


    $return['data'] = array(
        'userUUID'     => $data['userUUID'],
        'maskUUID'     => $data['maskUUID'],
    );
    $return['error'] = false;

    return $return;

}