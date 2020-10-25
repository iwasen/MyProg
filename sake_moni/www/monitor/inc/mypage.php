<?
/******************************************************
' System :お酒と買物のアンケートモニター共通処理
' Content:モニター認証確認処理
'******************************************************/

session_start();
if (!isset($_SESSION['ss_monitor_id'])) {
		header("location: m_login.php");
		exit;
}
?>