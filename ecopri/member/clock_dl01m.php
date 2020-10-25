<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ゾウママダウンロード
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
//include("$inc/mypage.php");

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" height="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"><br>
		<img src="img/nyuukai/zoumama/title_01.gif" width="346" height="26" alt="ゾウママ時計ダウンロードページへようこそ"><br>
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="middle">
		<hr width="95%" size="1">
		<br>
			<table width="630" border="1" cellspacing="3" cellpadding="0" bordercolor="#FFCCFF">
				<tr>
					<td><br>
					<div align="center"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="600" height="400">
					<param name=movie value="flash/zoumama.swf">
					<param name=quality value=high>
					<embed src="flash/zoumama.swf" quality=high pluginspage="http://www.macromedia.com/jp/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="600" height="400">
					</embed></object></div><br></td>
				</tr>
			</table>

			<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center" valign="middle">

					<td><a href="clock_dl02m.php"><img src="img/nyuukai/zoumama/iru.gif" border="0" alt="ダウンロードする" width="122" height="22"></a></td>

					<td width="10"><img src="img/spacer.gif" width="10" height="34"></td>

					<!-- 変更点 -->
					<td><img src="img/nyuukai/zoumama/iranai.gif" width="122" height="22" alt="ダウンロードしない" border="0"  onclick="window.close()"></td>
				</tr>

				<tr align="center" valign="middle">
					<td height="7" colspan="3"><img src="img/spacer.gif" width="130" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>