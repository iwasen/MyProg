<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// セッション処理
if (!$_SESSION['ss_result_input'])
	$_SESSION['ss_result_input'] = new result_input;
$reg = &$_SESSION['ss_result_input'];

// 入力しない理由
$reg->no_inp_reason('gm', $cd);
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
					<td><img src="img/result_input/title/settei.gif" width=372 height=42 alt="今月は入力しないが設定されました"></td>
				</tr>
			</table>
			<table border="0" align="center" height=350>
				<tr align="center" valign="middle" height=60>
					<td><img src="img/result_input/zou/06.gif" width=380 height=300 alt="今月は入力しません"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top"><img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td align="center" colspan=3><hr width="95%" size="1"></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="middle">
			<a href="result_input.php"><img src="img/button/input/back5.gif" border=0 width=137 height=44 alt="入力画面に戻る"></a>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>

</body>
</html>