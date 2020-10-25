<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:ログインチェック処理
'******************************************************/

if ($admin_login_id == '') {
	header("location: $top/login/login.php");
}
?>