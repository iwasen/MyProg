<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:トップページ
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
$inc = "$top/../inc";
include("$inc/common.php");

redirect("menu.php");
?>