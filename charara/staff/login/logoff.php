<?
/******************************************************
' System :�֤�����Factory�׺�襹���å��ѥڡ���
' Content:�����ս���
'******************************************************/

SetCookie('staff_login_id', '', 0, '/');
session_start();
session_destroy();
header('location: login.php');
exit;
?>