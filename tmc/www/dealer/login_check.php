<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」販売店様用ページ
' Content:ログイン済みチェック
'******************************************************/

session_start();

$g_dealer_cd = $_SESSION['s_dealer_cd'];

if ($g_dealer_cd == '') {
	header("location: index.php");
	exit;
}
?>