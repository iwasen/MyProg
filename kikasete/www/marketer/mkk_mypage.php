<?
/******************************************************
' System :きかせて・netマーケターＭｙページ
' Content:認証確認処理
'******************************************************/

session_start();
if (!isset($_SESSION['ss_marketer_id']) || ($_SESSION['ss_marketer_type'] != 3 && $_SESSION['ss_marketer_type'] != 4)) {
		header("location: mk_login.php");
		exit;
}
?>