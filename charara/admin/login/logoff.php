<?
/******************************************************
' System :�֤�����Factory�״������ѥڡ�����
' Content:�����ս���
'******************************************************/

SetCookie('admin_login_id', '', 0, '/');
session_start();
session_destroy();
header('location: login.php');
exit;
?>