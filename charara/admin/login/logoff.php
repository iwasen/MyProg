<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:ログオフ処理
'******************************************************/

SetCookie('admin_login_id', '', 0, '/');
session_start();
session_destroy();
header('location: login.php');
exit;
?>