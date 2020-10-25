<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:スレッド表示フレームセット
'******************************************************/
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>
<frameset rows="60%,*" framespacing=1>
	<frame name="thread" src="<?=$_SERVER['SCRIPT_NAME']?>?frame=1" scrolling="yes">
	<frame name="contents" src="contents.php" scrolling="yes">
</frameset>
</html>
