<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメールフレームセット
'******************************************************/
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<frameset cols="*,250" framespacing=0>
	<frame name="mail" src="list<?=$_GET['list']?>.php" scrolling="yes">
	<frame name="user" src="user_info.php" scrolling="yes">
</frameset>
</html>
