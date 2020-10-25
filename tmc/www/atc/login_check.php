<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」キャンペーン事務局用ページ
' Content:ログイン済みチェック
'******************************************************/

session_start();

$g_campaign_id = $_SESSION['s_campaign_id'];

if ($g_campaign_id == '') {
	header("location: index.php");
	exit;
}
?>