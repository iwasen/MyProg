<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist.php");

/******************************************************
' System :Eco-footprint 会員ページ
' Content:ID,Password入力
'******************************************************/

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist1']))
	redirect('member_input.php');
$reg = &$_SESSION['ss_regist1'];

?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/title/m_check.gif" width="361" height="37"></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<br><br><br><br>
		<img src="img/nyuukai/zou_img/m_check.gif" width="335" height="128" alt="これであってる？">
		<br>

			<table border="0" width="350" cellspacing="10">
					<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
					<td width="95"><font size=2>会員ID：</font></td>
					<td width="188"><?=htmlspecialchars($reg->mb_id)?></td>
				</tr>
				<tr>
					<td width="95"><font size=2>メールアドレス：</font></td>
					<td width="188"><?=htmlspecialchars($reg->mb_mail_addr)?></td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
			<br>

			<table border="0">
				<tr>
					<td><img src="img/nyuukai/cyuui.gif" alt="注意"></td>
					<td><font color="#FF0000" size=2>
					・メールアドレスは事務局との大事な連絡手段になります。<br>　間違いがないか確認してください。
					</font>
					<!-- HNの重複や未入力項目がある場合、ダイアログを出す --></td>
				</tr>
			</table>
			<br>

		</td>
		<td><br></td>
	</tr>
	<tr>

		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="member_input.php"><img width="99" height="40" border="0" alt="やり直す" src="img/nyuukai/button/naosu.gif"></a></td>
					<td width="10"><img src="img/spacer.gif" width="10" height="40"></td>
					<td><a href="member_end.php"><img src="img/nyuukai/button/next.gif" width="99" height="40" border="0" alt="次へ"></a></td>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
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
