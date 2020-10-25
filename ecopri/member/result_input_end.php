<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:先月の実績入力終了
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// セッション処理
if (!isset($_SESSION['ss_result_input']))
	redirect('result_input.php');
$reg = &$_SESSION['ss_result_input'];
$reg->save_db($_SESSION['ss_seq_no'], 0);

// セッション変数削除
unset($_SESSION['ss_result_input']);
session_unregister('ss_result_input');

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">

<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="99%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"><br></td>
		<td width="35" align="right" valign="top"> <img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="middle">
			<table width="90%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center" valign="middle" width="644">
					<img src="img/result_input/result_end.gif" border="0" width="425" height="347">
					</td>
				</tr>
			</table>
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td width="10">　</td>
					<td><a href="main.php"><img src="img/button/main.gif" border="0" width="61" height="30"></a></td>
					</tr>
					<tr align="center">
					<td height="7" colspan="3">　</td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</div>

</body>
</html>