<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:会員情報入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/fiducial1.php");
include("$inc/mypage.php");

// セッション処理
if (!isset($_SESSION['ss_fiducial1']))
	redirect('main.php');
$reg = &$_SESSION['ss_fiducial1'];

$reg->save_db1();

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
					<td><img src="img/result_input/title/soushin2.gif" width=356 height=42 alt="データを送信しました"></td>
				</tr>
			</table>
			<table border="0" align="center" height=350>
				<tr align="center" valign="middle" height=60>
					<td><img src="img/result_input/zou/07.gif" width=425 height=347 alt="お届けものだよ"></td>
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
			<a href="fiducial_change1.php"><img src="img/button/input/back3.gif" border=0 width=137 height=44 alt="過去の数値に戻る"></a>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>

</body>
</html>