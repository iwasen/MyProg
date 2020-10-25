<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:トップページ
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/login_check.php");

redirect("menu.php");
?>