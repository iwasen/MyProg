<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:個人情報変更1/5
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_change01.php');
$reg = &$_SESSION['ss_regist2'];

//$reg->read_db1($_SESSION['ss_seq_no']);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>☆★☆xxxゾウさんClub☆★☆</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<form method="post" action="member_change01_1_check.php">
<table width="714" height ="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
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
		<td align="center" valign="top"> <br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="170">
					<div align="center"><img src="img/nyuukai/zou_img/m_change01.gif" width="250" height="132" alt="下の4つに入力してね"></div>
					</td>
				</tr>
			</table>
			<br>

			<table width="440" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td bgcolor="#FFCC66"><img src="img/spacer.gif" width="440" height="4"></td>
				</tr>
			</table>
			<br>
			<table border="0" width=520>
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
					<td><font size=2>パスワード：</font></td>
					<td>
					<input type="text" name="mb_password" size=30 maxlength=20 <?=value($reg->mb_password)?>>
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
					<td><font size=2>再度入力<br>パスワード：</font></td>
					<td>
					<input type="text" name="mb_password2" size=30 maxlength=20 <?=value($reg->mb_password)?>>
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
			</table>
			<br>
			<table width="440" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td bgcolor="#FFCC66"><img src="img/spacer.gif" width="440" height="4"></td>
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
			<img src="img/nyuukai/page/01.gif" alt="1ページ目">
		</td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
				<!-- ↓ダイアログボックスを表示させる -->
<?
if (!$reg->back_page) {
?>
					<td><a href="member_change01.php"><img src="img/nyuukai/button/back.gif" border="0" alt="戻る" width="99" height="40"></a></td>
<!--					<td width="10"></td>
					<td><a href="member_change01_1.php"><img src="img/nyuukai/button/cancell.gif" border="0" alt="キャンセル" width="99" height="40"></a></td>
-->
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/next.gif" border="0" alt="次へ" width="99" height="40"></td>
<?
} else {
?>
					<td><a href="javascript:history.back()"><img src="img/nyuukai/button/back.gif" width="99" height="40" border="0" alt="戻る"></a></td>
					<td width="10"></td>
					<td><input type="image" src="img/nyuukai/button/syuusei.gif" width="99" height="40" alt="修正" border="0"></td>
<?
}
?>
			</tr>
			<tr align="center">
					<td height="7" colspan="5"><img src="img/spacer.gif" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
</form>
</div>
</body>
</html>