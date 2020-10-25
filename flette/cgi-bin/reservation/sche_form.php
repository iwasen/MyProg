#!/usr/local/bin/php4
<?
/******************************************************
' System :flette予約入力ページ
' Content:予約日時選択フレーム
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/reserve.php");

// セッションから予約情報取得
session_start();
if ($reserve == null)
	redirect('timeout.php');
?>
<HTML lang="ja">
<HEAD>
<TITLE>FLETTE / ご予約</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/style.css" type="text/css" media="screen">
</head>

<FRAMESET rows="340,*" FRAMEBORDER=0 framespacing=0 border=0>
<FRAME src="sche_head.php" name="head" noresize scrolling="no">
<FRAME src="sche_body.php" name="body" scrolling="yes">
</FRAMESET>

</HTML>
