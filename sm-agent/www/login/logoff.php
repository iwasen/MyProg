<?
/******************************************************
' System :�᡼���ۿ������ӥ�
' Content:�����ս���
'******************************************************/

session_start();
session_unset();
session_destroy();

header('location: login.php');
exit;
?>