<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:ログオフ処理
'******************************************************/

SetCookie('staff_login_id', '', 0, '/');
session_start();
session_destroy();
header('location: login.php');
exit;
?>