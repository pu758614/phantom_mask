<?php

ini_set('display_errors','0');
$ini_list = parse_ini_file(__DIR__.'/../conf.ini', true, INI_SCANNER_RAW);
$data_base = isset($ini_list['system'])?$ini_list['system']:array();
$ROOT_PATH = isset($data_base['root_path'])?$data_base['root_path']:'';
$TOKEN_TIME = isset($data_base['token_time'])?$data_base['token_time']:'';
$data_base = isset($ini_list['database'])?$ini_list['database']:array();
$host      = isset($data_base['host'])?$data_base['host']:'';
$domain      = isset($data_base['domain '])?$data_base['domain']:'';
//include_once($ROOT_PATH."../../../prepend.php");


function checkToken($token){
    $str = getDecodeStr($token);
    $token_arr = explode('_',$str);
    $return =  array(
        'error' => true,
        'msg'   => 'wrong token formate.'
    );
    if(!isset($token_arr[0])|| $token_arr[0]!='kdan'){
        return $return;
    }
    if(!isset($token_arr[1])){
        return $return;
    }
    if(date('Y-m-d H:i:s')>$token_arr[1]){
        $return['msg'] = 'The token has expired, please log in again.';
        return $return;
    }
    return array(
        'error' => false,
        'msg'   => ''
    );
}


function getEncodeStr($str){
    $hashKey4encode = '758614';
    if ( version_compare( phpversion() , '7.1.0', '<') ) {
        $str = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, PadKeyLength($hashKey4encode), $str, MCRYPT_MODE_CBC, md5($hashKey4encode)));
    }else{
        $str = openssl_encrypt($str,'des-ede3',PadKeyLength($hashKey4encode),0);
    }

    $str = str_replace(array('+', '/', '='), array('-', '_', ''), $str);
    return $str;
}

function getDecodeStr($str){
    $hashKey4encode = '758614';
    $str = str_replace(array('-', '_'), array('+', '/'), $str);
    if ( version_compare( phpversion() , '7.1.0', '<') ) {
        $str = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, PadKeyLength($hashKey4encode), base64_decode($str), MCRYPT_MODE_CBC, md5($hashKey4encode));
    }else{
        $str = openssl_decrypt($str,'des-ede3',PadKeyLength($hashKey4encode),0);
    }
    return trim($str);
}

function PadKeyLength($key){
    if(strlen($key) > 32) {
        return false;
    }
    $sizes = array(16,24,32);

    foreach($sizes as $s){
        while(strlen($key) < $s) $key = $key."\0";
        if(strlen($key) == $s) break; // finish if the key matches a size
    }
    return $key;
}

function generate_uuid($separator = '-') {
    if(function_exists('openssl_random_pseudo_bytes')){
        $data = openssl_random_pseudo_bytes(16);
        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s'.$separator.'%s'.$separator.'%s'.$separator.'%s'.$separator.'%s%s%s', str_split(bin2hex($data), 4));
    }else {
        return sprintf( '%04x%04x'.$separator.'%04x'.$separator.'%04x'.$separator.'%04x'.$separator.'%04x%04x%04x',
                        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                        mt_rand( 0, 0xffff ),
                        mt_rand( 0, 0x0fff ) | 0x4000,
                        mt_rand( 0, 0x3fff ) | 0x8000,
                        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
                      );
    }
}

function CheckDateTime($date_time){
    if(strlen($date_time) ==10){
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date_time)){
            $check = true;
        }else{
            $check = false;
        }
    }else{
        if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date_time)){
            $check = true;
        }else{
            $check = false;
        }
    }
 return $check;
}