<?php


$sql = "SELECT * FROM kdan_mask_pharmacies";
$rs = $db->db->Execute($sql);
$sell_all= $db->getAllSell('pharmacies');
if($rs && $rs->RecordCount() > 0){
    foreach ($rs as $key => $value) {
        $tpl->newBlock('PHARMACIES_LIST');
        $tpl->assign(array(
            'pharmacies_name' => $value['name'],
            'pharmacies_uuid' => $value['uuid'],
            'cash_balance' => $value['cashBalance'],
        ));
        $cond = array(
            'isDelete' => 0,
            'pharmaciesId' => $value['id']
        );
        $mask_arr = $db->getArrayByArray('kdan_mask_mask_item',$cond);

        foreach ($mask_arr as $key => $mask_data) {
            $tpl->newBlock('MASK_LIST');
            $tpl->assign(array(
                'mask_name'  => $mask_data['fullName'],
                'mask_color' => $mask_data['color'],
                'mask_per'   => $mask_data['per'],
                'mask_price' => $mask_data['price'],
                'uuid'       => $mask_data['uuid']
            ));
        }
        $sell_arr = isset($sell_all[$value['id']])?$sell_all[$value['id']]:array();

        foreach ($sell_arr as $key => $sell_data) {
            $tpl->newBlock('SELL_LIST');
            $tpl->assign(array(
                'shell_time' => $sell_data['time'],
                'shell_name' => $sell_data['name'],
                'shell_amounts' => $sell_data['amounts'],
                'shell_phar' => $sell_data['pharmacies'],
            ));
        }

    }
}else{
}
