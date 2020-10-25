<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」販売店様用ページ
' Content:ログアウト処理
'******************************************************/

session_start();

if ($_SESSION['s_campaign_id'] == '') {
	// セッション解放
	session_unset();
	session_destroy();

	// ログインページへ
	header('location: index.php');
	exit;
} else {
	// キャンペーン事務局用ページからの遷移
	unset($_SESSION['s_dealer_cd']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
</head>
<body onload="window.close()">
</body>
</html>
<?
}
?>