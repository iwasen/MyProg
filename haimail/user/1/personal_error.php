<?
include('../inc/common.php');
include('../inc/reginfo.php');

session_start();
$reginfo = new CRegInfo;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>個人情報・よく行くお店の登録エラー</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form name="FormName" action="1-2.php" method="post">
		<br>
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="561" height="36">
					<div align="left">
						<font color="#2f2180" size="2"><b>正しく入力されていない項目があるようです。<br>
						</b></font><font size="2">
						<hr>
<?
if ($reginfo->err_mail_adr) {
	Writeln("<br>");
	Writeln("・メールアドレスが正しく入力されていないようです。<br>");
	Writeln("　メールをお届けするために、ご確認ください。<br>");
}

if ($reginfo->err_already_regist) {
	Writeln("<br>");
	Writeln("・入力されたメールアドレスはすでに登録済みです。<br>");
}

if ($reginfo->err_password) {
	Writeln("<br>");
	Writeln("・パスワードが正しく入力されていないようです。ご確認ください。 <br>");
}

if ($reginfo->err_seibetsu) {
	Writeln("<br>");
	Writeln("・あなたの性別を教えてください。 <br>");
}

if ($reginfo->err_mikikon) {
	Writeln("<br>");
	Writeln("・あなたはご結婚されていますか？未既婚の項目で教えてください。 <br>");
}

if ($reginfo->err_seinengappi) {
	Writeln("<br>");
	Writeln("・生年月日が正しく入力されていないようです。ご確認ください。 <br>");
}


if ($reginfo->err_zip_kyojuu) {
	Writeln("<br>");
	Writeln("・居住地郵便番号が正しく入力されていないようです。ご確認ください。 <br>");
}

if ($reginfo->err_zip_kinmu) {
	Writeln("<br>");
	Writeln("・勤務地郵便番号が正しく入力されていないようです。ご確認ください。 <br>");
}

if ($reginfo->err_shokugyou) {
	Writeln("<br>");
	Writeln("・あなたの職業について教えてください。 <br>");
}

if ($reginfo->err_html_mail) {
	Writeln("<br>");
	Writeln("・HTMLメール受信を希望しますか？教えてください。<br>");
}

if ($reginfo->err_oshirase_mail) {
	Writeln("<br>");
	Writeln("・「発信する生活者」を応援するｅメールをお受け取りになりますか？<br>");
	Writeln("　教えてください。");
}
?>
					</font></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">　</td>
				<td width="561" height="36"><br>
					<br>
					<hr>
					<input type="submit" value="　戻　る　" name="BACK"></td>
			</tr>
		</table>
		</form>
	</body>

</html>
