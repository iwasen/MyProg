<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ログインチェック処理
'******************************************************/

session_start();

if ($admin_login_id == '') {
	header("location: $top/login/login.php");
	exit;
}
?>
