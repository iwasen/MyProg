<?
/******************************************************
' System :fletteスタッフページ
' Content:ログインチェック処理
'******************************************************/

session_start();

if ($s_staff_id == '') {
	header("location: $top/login/login.php");
	exit;
}
?>