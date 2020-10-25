<?
/******************************************************
' System :きかせて・netモニターＭｙページ
' Content:Ｍｙページ認証確認
'******************************************************/

session_start();
if (!isset($_SESSION['ss_monitor_id'])) {
		header("location: $top/mypage/login_error_main.php");
		exit;
}
?>
