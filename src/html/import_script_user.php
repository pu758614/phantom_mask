<?php
include_once("lib/common.php");
include_once("$ROOT_PATH/lib/db_lib.php");
if(isset($_SERVER['SERVER_NAME'])){
    exit('Prohibited operation source.');
}
$json_str = file_get_contents('data/users.json');
$data_arr = json_decode($json_str, true);
$db = new db_lib();
foreach ($data_arr as  $data) {
    $insert_pharmacies = array(
        'uuid' => generate_uuid(),
        'name' => $data['name'],
        'cashBalance' => $data['cashBalance'],
        'modifyTime' => date('Y-m-d H:i:s'),
        'createTime' => date('Y-m-d H:i:s'),
    );
    $user_id = $db->insertData('kdan_mask_user',$insert_pharmacies);
    $purchaseHistories = $data['purchaseHistories'];
    foreach ($purchaseHistories as  $purchaseHistoriesData) {
        $pharmacy_data = $db->getSingleById('kdan_mask_pharmacies','name',$purchaseHistoriesData['pharmacyName']);
        if(empty($pharmacy_data)){
            echo "ERROR NAME:".$purchaseHistoriesData['pharmacyName']."<br>";
            continue;
        }
        $mask_data = masksChange($purchaseHistoriesData['maskName']);
        $sell_record = array(
            'userId' => $user_id,
            'pharmaciesId' => $pharmacy_data['id'],
            'name' => $mask_data['name'],
            'color' => $mask_data['color'],
            'per' => $mask_data['per'],
            'sellAmount' =>  $purchaseHistoriesData['transactionAmount'],
            'sellDate' =>  $purchaseHistoriesData['transactionDate'],
            'createTime' => date('Y-m-d H:i:s')
        );
        $db->insertData('kdan_mask_sell_record',$sell_record);
    }
}


function masksChange($masks){
    $masksArr = explode('(', $masks);
    $name = trim($masksArr[0]);
    $color = str_replace(')', '', $masksArr[1]);
    $pre = str_replace(' per pack)', '', $masksArr[2]);
    return array(
        'name' => trim($name),
        'color' => trim($color),
        'per' => trim($pre),
    );
}
