<?
/******************************************************
' System :「きゃららFactory」クレオキャンペーン
' Content:入力画面
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>「にがおえ」キャンペーン♪</title>

<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName = "css/mac.css";
}
else	{
	if(ie)	{
	fName = "css/win_ie.css";
	}
	else if(ns)	{
	fName = "css/win_ns.css";
	}
}

if(fName != null)	{
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>



</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br>
<table>
	<tr>
		<td>
			<table border="0" width="400" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					<tr>
						<td align="center">
							<font size="2" color="red"><br>ERROR!!<br></font>
						</td>
					</tr>
					<tr>
						<td align="center">
							<font size="2"><br>・URLが正しくないようです。<br><br></font>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="400" height="2"></td>
					</tr>
				</td>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="image" src="img/close.gif" onclick="window.close()" width=106 height=46 alt="閉じる">
		</td>
	</tr>
<table>
</div>
</body>
</html>