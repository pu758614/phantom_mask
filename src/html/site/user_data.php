<?php


$sql = "SELECT * FROM kdan_mask_user ";
$rs = $db->db->Execute($sql);
$sell_all= $db->getAllSell();
if($rs && $rs->RecordCount() > 0){
    foreach ($rs as $key => $value) {
        $tpl->newBlock('USER_LIST');
        $tpl->assign(array(
            'user_name' => $value['name'],
            'user_uuid' => $value['uuid'],
            'cash_balance' => $value['cashBalance'],
        ));
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
