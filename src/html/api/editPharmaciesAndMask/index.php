<?php
#####################################################################
# editPharmaciesAndMask
# 編輯藥局名稱和口罩名稱.價格
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

if($data['editType']=='pharmacies'){
    $table = 'kdan_mask_pharmacies';
    $cond = array(
        'uuid' => $data['uuid']
    );
}else{
    $table = 'kdan_mask_mask_item';
    $cond = array(
        'uuid' => $data['uuid'],
        'isDelete' => 0
    );
}
$check_data = $db->getSingleByArray($table,$cond);
if(empty($check_data)){
    $response_data['msg'] = 'No such uuid data';
    goto end;
}
$editData = $data['editData'];
$editData['modifyTime'] = date('Y-m-d H:i:s');
$cond = array(
    'uuid' => $data['uuid']
);
$result= $db->updateData($table,$editData,$cond);

if(!$result || $db->db->ErrorMsg()!=''){
    $response_data['msg'] = 'Update fail';
}else{
    if($data['editType']=='mask'){
        if($db->updateMaskFullName($check_data['id'])){
            $response_data['msg'] = '';
            $response_data['error'] = false;
            $api_status = 1;
        }else{
            $response_data['msg'] = 'Update fail';
        }
    }else{
        $response_data['msg'] = '';
        $response_data['error'] = false;
        $api_status = 1;
    }
}

end:
    $db->saveApiResult('editPharmaciesAndMask',$request_data,$response_data,$api_status);
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
    if(!isset($data['editType'])){
        $return['msg'] = 'Lack of count';
        return $return;
    }
    if(!in_array($data['editType'],array('pharmacies','mask'))){
        $return['msg'] = 'Wrong editType';
        return $return;
    }
    if(!isset($data['uuid']) || $data['uuid']==''){
        $return['msg'] = 'Lack of uuid';
        return $return;
    }
    if(!isset($data['editData'])){
        $return['msg'] = 'Lack of editData.';
        return $return;
    }
    if(!is_array($data['editData'])){
        $return['msg'] = 'Wrong editData formate.';
        return $return;
    }
    $up_data = array();
    foreach ($data['editData'] as $key => $value) {

        switch ($key) {
            case 'name':
                $up_data[$key] = $value;
                break;
            case 'price':
                if($data['editType']=='pharmacies'){
                    $return['msg'] = $key." not undefined in".$data['editType'];
                    return $return;
                }
                $up_data[$key] = $value;
                break;
            default:
                $return['msg'] = $key." not undefined in".$data['editType'];
                return $return;
                break;
        }
    }
    if(empty($up_data)){
        $return['msg'] = 'Lack of edit content.';
        return $return;
    }

    $return['data'] = array(
        'editType' => $data['editType'],
        'editData' => $up_data,
        'uuid'     => $data['uuid'],
    );
    $return['error'] = false;

    return $return;

}