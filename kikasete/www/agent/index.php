<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:トップページ
'******************************************************/

$top = '.';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");

redirect("mypage.php");
?>