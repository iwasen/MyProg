<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ログオフ処理
'******************************************************/

session_start();
session_destroy();
header('location: login.php');
exit;
?>