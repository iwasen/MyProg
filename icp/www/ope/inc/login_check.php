<?
/******************************************************
' System :ICPオペレータページ
' Content:ログイン済みチェック
'******************************************************/

session_start();

$g_operator_id = $_SESSION[SCD]['operator_id'];

if ($g_operator_id == null) {
	header("location: $top/login/login.php");
	exit;
}
?>