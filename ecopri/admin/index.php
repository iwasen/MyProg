<?
/******************************************************
' System :Eco footprint 管理用ページ
' Content:トップページ
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
$inc = "$top/../inc";
include("$inc/common.php");

redirect("menu.php");
?>