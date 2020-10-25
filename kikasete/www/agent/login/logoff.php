<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ログオフ処理
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");

session_start();
session_destroy();
redirect(http_url('login.php'));
?>