<?php
include_once(dirname(__DIR__)."/html/lib/common.php");
include_once("$ROOT_PATH/lib/db_lib.php");

$db = new db_lib();
$sql = file_get_contents("$ROOT_PATH/sql/kdan_mask.sql");
$rs = $db->db->Execute($sql);