<?php
include_once(dirname(__DIR__)."/lib/common.php");
include_once("$ROOT_PATH/lib/TemplatePower/class.TemplatePower.inc.php");
include_once("$ROOT_PATH/lib/db_lib.php");


$action = isset($_GET['action'])?$_GET['action']:'api_view';
$db = new db_lib;
$tpl_path = "tpl/".$action.".tpl";
$tpl = new TemplatePower ($tpl_path);
session_start();
$tpl->assignGlobal(array(
    "action" => $action,
));


$tpl -> prepare ();
$tpl_path = "tpl/".$action.".tpl";
$tpl->assignInclude( "content", $tpl_path );
$tpl -> prepare ();
if(is_file($action.".php")){
	include($action.".php");
}


$tpl -> printToScreen ();
