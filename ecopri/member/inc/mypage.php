<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:Ｍｙページ認証確認
'******************************************************/

session_start();
if (!isset($_SESSION['ss_seq_no']) || $_SESSION['ss_member_step'] != 3) {
		header("location: $top/login.php");
		exit;
}
?>