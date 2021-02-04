<?php
include_once("lib/common.php");
include_once("$ROOT_PATH/lib/db_lib.php");

$json_str = file_get_contents('data/pharmacies.json');
$data_arr = json_decode($json_str, true);
$db = new db_lib();
foreach ($data_arr as  $data) {
    $openingHours = $data['openingHours'];
    $masks = $data['masks'];
    $opening_data = openingHoursChange($openingHours);
    $insert_pharmacies = array(
        'uuid'         => generate_uuid(),
        'name'         => $data['name'],
        'cashBalance'  => $data['cashBalance'],
        'MonTimeStart' => $opening_data['Mon']['start'],
        'MonTimeEnd'   => $opening_data['Mon']['end'],
        'TueTimeStart' => $opening_data['Tue']['start'],
        'TueTimeEnd'   => $opening_data['Tue']['end'],
        'WedTimeStart' => $opening_data['Wed']['start'],
        'WedTimeEnd'   => $opening_data['Wed']['end'],
        'ThuTimeStart' => $opening_data['Thu']['start'],
        'ThuTimeEnd'   => $opening_data['Thu']['end'],
        'FriTimeStart' => $opening_data['Fri']['start'],
        'FriTimeEnd'   => $opening_data['Fri']['end'],
        'SatTimeStart' => $opening_data['Sat']['start'],
        'SatTimeEnd'   => $opening_data['Sat']['end'],
        'SunTimeStart' => $opening_data['Sun']['start'],
        'SunTimeEnd'   => $opening_data['Sun']['end'],
        'modifyTime'   => date('Y-m-d H:i:s'),
        'createTime'   => date('Y-m-d H:i:s'),
    );
    $pharmacies_id = $db->insertData('kdan_mask_pharmacies',$insert_pharmacies);
    foreach ($masks as $key => $mask) {
        $opening_data_arr = masksChange($mask['name']);
        $mask_item_data = array(
            'uuid'         => generate_uuid(),
            'pharmaciesId' => $pharmacies_id,
            'name'         => $opening_data_arr['name'],
            'color'        => $opening_data_arr['color'],
            'per'          => $opening_data_arr['per'],
            'price'        => $mask['price'],
            'fullName'     => $mask['name'],
            'modifyTime'   => date('Y-m-d H:i:s'),
            'createTime'   => date('Y-m-d H:i:s'),
        );
        $db->insertData('kdan_mask_mask_item',$mask_item_data);
    }
}


function masksChange($masks){
    $masksArr = explode('(', $masks);
    $name  = trim($masksArr[0]);
    $color = str_replace(')', '', $masksArr[1]);
    $pre = str_replace(' per pack)', '', $masksArr[2]);
    return array(
        'name'  => trim($name),
        'color' => trim($color),
        'per'   => trim($pre),
    );
}


function openingHoursChange($openingHours){
    $openingHoursArr = explode('/', $openingHours);
    $time_arr = array(
        'start' => '',
        'end'   => ''
    );
    $return = array(
        'Mon' => $time_arr,
        'Tue' => $time_arr,
        'Wed' => $time_arr,
        'Thu' => $time_arr,
        'Fri' => $time_arr,
        'Sat' => $time_arr,
        'Sun' => $time_arr,
    );
    foreach ($openingHoursArr as  $openingHoursData) {
        $collect_day = array();
        if (strpos($openingHoursData, 'Mon') !== false) {
            $collect_day[] = 'Mon';
        }

        if (strpos($openingHoursData, 'Tue') !== false) {
            $collect_day[] = 'Tue';
        }
        if (strpos($openingHoursData, 'Wed') !== false) {
            $collect_day[] = 'Wed';
        }

        if (strpos($openingHoursData, 'Thu') !== false) {
            $collect_day[] = 'Thu';
        }
        if (strpos($openingHoursData, 'Fri') !== false) {
            $collect_day[] = 'Fri';
        }
        if (strpos($openingHoursData, 'Sat') !== false) {
            $collect_day[] = 'Sat';
        }
        if (strpos($openingHoursData, 'Sun') !== false) {
            $collect_day[] = 'Sun';
        }
        $openingHoursTime = str_replace($collect_day, '', $openingHoursData);
        $openingHoursTime = str_replace(',', '', $openingHoursTime);
        $openingHoursTime = trim($openingHoursTime);
        $openingHoursTimeArr = explode('-', $openingHoursTime);
        foreach ($collect_day as $day) {
            $start = isset($openingHoursTimeArr[2]) ? $openingHoursTimeArr[1] : $openingHoursTimeArr[0];
            $end = isset($openingHoursTimeArr[2]) ? $openingHoursTimeArr[2] : $openingHoursTimeArr[1];
            $return[$day] = array(
                'start' => trim($start),
                'end'   => trim($end)
            );
        }
    }
    return $return;
}
