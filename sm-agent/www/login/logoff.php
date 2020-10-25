<?
/******************************************************
' System :メール配信サービス
' Content:ログオフ処理
'******************************************************/

session_start();
session_unset();
session_destroy();

header('location: login.php');
exit;
?>