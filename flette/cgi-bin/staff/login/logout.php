#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:ログオフ処理
'******************************************************/

session_start();
session_destroy();
header('location: login.php');
exit;
?>