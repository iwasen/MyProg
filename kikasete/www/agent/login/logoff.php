<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�����ս���
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");

session_start();
session_destroy();
redirect(http_url('login.php'));
?>