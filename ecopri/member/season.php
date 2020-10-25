<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:先月の実績入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- 白丸角テーブル -->
<table border="0" cellpadding="0" cellspacing="0" width="760" height="100%" bgcolor="#FF0000">
	<tr>
		<td align="center">
		<!-- ここに入力 -->
		<!-- URL's used in the movie-->
		<!-- text used in the movie-->
		<!-- Flashタグ領域 -->
		<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
			 WIDTH="760" HEIGHT="570">
			<PARAM NAME=movie VALUE="flash/season.swf">
			<PARAM NAME=quality VALUE=high>
			<PARAM NAME=bgcolor VALUE=#FF0000>
			<PARAM NAME=play VALUE=TRUE>
			<PARAM NAME=loop VALUE=FALSE>
			<PARAM NAME=FlashVars VALUE="thisMonth=<?=date(m)?>">
			<EMBED src="flash/season.swf" play=TRUE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="760" HEIGHT="570" NAME="SWFlash" 
			FlashVars="thisMonth=<?=date(m)?>" 
			TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
			</EMBED>
		</OBJECT>

		<!-- Flashタグ領域終了 -->
		<!-- ここまで -->
		</td>
	</tr>
</table>
<!-- 白丸角テーブル終了 -->

</div>
</body>
</html>