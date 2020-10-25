<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
		<br>
			<table border="0" align="center">
				<tr align="center" valign="middle" height=60>
					<td><img src="img/result_input/calendar/no_input/riyuu.gif" width=356 height=42 alt="入力しない理由を教えて下さい"></td>
				</tr>
			</table>
			<table border="0" align="center" height=150>
				<tr align="center" valign="middle" height=60>
					<td>
						<a href="result_gm_q2.php?cd=1">
							<img src="img/result_input/calendar/no_input/a.gif" width=267 height=35 alt="使用しなかったから" border=0>
						</a>
					</td>
					<td>
						<a href="result_gm_q2.php?cd=2">
							<img src="img/result_input/calendar/no_input/b.gif" width=267 height=35 alt="使用したが入力しない" border=0>
						</a>
					</td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" height=200><img src="img/result_input/zou/05.gif" width=260 height=180 alt="どっちか教えて"></td>
		<td><br></td>
	</tr>
	<tr>
		<td align="center" colspan=3><hr width="95%" size="1"></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="middle"><input type="image" src="img/button/input/back1.gif" border=0 alt="戻る" onclick="history.back()"></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>

</body>
</html>