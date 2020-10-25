<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガーデニング
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mypage.php");

?>
<html lang="ja">
<head>
<STYLE TYPE="text/css">
<!--
body { margin:0pt; }
-->
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<!--<meta http-equiv="Content-Type" content="text/html;charset=iso-2022-jp">-->
<title>ガーデニングゲーム</title>
</head>

<body bgcolor=#ff0000>
<div align="center">
<table border=0 width="714" height="99%" cellspacing="0" cellpadding="0">
<tr valign=middle><td align=center valign="middle">
<!-- Shockwave Flashのオブジェクトを定義します -->
<OBJECT ID=SWFlash classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 WIDTH="626" HEIGHT="470">
	<PARAM NAME=movie VALUE="flash/gardening_LoadVars.swf">
	<PARAM NAME=quality VALUE=high>
	<PARAM NAME=bgcolor VALUE=#FFFFFF>
	<PARAM NAME=loop    VALUE=FALSE>
	<PARAM NAME=play VALUE=false>
	<EMBED src="flash/gardening_LoadVars.swf" play=FALSE loop=FALSE quality=high bgcolor=#FFFFFF  WIDTH="626" HEIGHT="470" NAME="SWFlash" ALIGN=""
	TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>

<Form ID="sendFrm" action="servlet/SendSWFData" method="POST">

	<Span ID="sendData">
		<!-- 送信データが記述される -->
	</Span>

</Form>
</td>
</tr>
</table>
</div>

</body>
</html>