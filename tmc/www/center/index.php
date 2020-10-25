<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:フレームセット
'******************************************************/

include("../inc/common.php");

session_start();

if ($_SESSION['s_staff_id'] == '')
	redirect('login/login.php');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=SYSTEM_NAME?></title>
</head>
<frameset cols="148,*" framespacing=0>
	<frame name="menu" src="menu.php">
	<frame name="main" src="newdata/top.php" scrolling="yes">
</frameset>
</html>
