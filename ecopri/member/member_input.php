<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ID,Password入力
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/regist.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist1']))
	$_SESSION['ss_regist1'] = new mm_regist1;
$reg = &$_SESSION['ss_regist1'];
?>
<html>
<head>
<title>☆★☆xxxゾウさんClub☆★☆</title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<form name="form1" method="post" action="member_check.php">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"><img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/title/m_input01.gif" width="214" height="38" alt="さっそく登録しよう"></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="middle">
       
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="170">
					<div align="center"><img src="img/nyuukai/zou_img/m_input.gif" width="210" height="115" alt="下の4つに入力してね"></div>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="10">
				<tr bgcolor="#00CCFF">
					<td colspan="3" height="2"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
				<tr>
					<td><nobr><font size=2>会員ID：</font></nobr></td>
					<td>
					<input type="text" name="mb_id" size=30 maxlength=20 <?=value($reg->mb_id)?>>
					</td>
					<td>
						<table>
							<tr>
								<td valign="top"><font size=2>*</font></td>
								<td><font size=2>xxxゾウさんClubで使うあなたの会員ID（ニックネーム）を、4〜20文字の半角英数で入力してください。</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><nobr><font size=2>メールアドレス：</font></nobr></td>
					<td>
					<input type="text" name="mb_mail_addr" size=30 maxlength=100 <?=value($reg->mb_mail_addr)?>>
					</td>
					<td>
						<table>
							<tr>
								<td valign="top"><font size=2>*</font></td>
								<td><font size=2>パソコンで使用しているメールアドレスを入力してください。</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><nobr><font size=2>パスワード：</font></nobr></td>
					<td>
					<input type="password" name="mb_password" size=30 maxlength=20 <?=value($reg->mb_password)?>>
					</td>
					<td>
						<table>
							<tr>
								<td valign="top"><font size=2>*</font></td>
								<td><font size=2>4〜20文字の半角英数で入力してください。</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><font size=2>再度入力<br>
					パスワード：</font></td>
					<td>
					<input type="password" name="mb_password2" size=30 maxlength=20 <?=value($reg->mb_password2)?>>
					</td>
					<td>
						<table>
							<tr>
								<td valign="top"><font size=2>*</font></td>
								<td><font size=2>確認のため、もう一度入力してください。</font></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr bgcolor="#00CCFF">
					<td colspan="3" height="2"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
			<br>
			<table border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/cyuui.gif" alt="注意"></td>
					<td><font color="#FF0000" size=2>
					・メールアドレスは事務局との大事な連絡手段になりますので、<br>お間違いのないようお願いします。<br><br>
					・パスワードは忘れないように個人管理してください。</font>
				<!-- HNの重複や未入力項目がある場合、ダイアログを出す --></td>
				</tr>
			</table>
			<br>

			<hr width="95%" size="1"></td>
			<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><a href="member_exp.php"><img width="99" height="40" border="0" alt="戻る" src="img/nyuukai/button/back.gif"></a></td>
					<td width="10"><img src="img/spacer.gif" width="10" height="40"></td>
          <td><input type="image" src="img/nyuukai/button/next.gif" width="99" height="40" border="0" alt="次へ"></td>
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
</form>
</body>
</html>