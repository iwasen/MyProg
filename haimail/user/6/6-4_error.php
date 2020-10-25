<?
//=== メイン処理 ===
include('../inc/common.php');
include('../inc/haimail_letter.php');

session_start();
$h_letter = new CHaimailLetter;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>ビジネスレター退会エラー</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table cellspacing="0" cellpadding="0" border="0" height="58" width="691">
	<tr height="48">

		<td width="691" bgcolor="white" align="left" valign="top" height="49"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">

		<td width="691" height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form name="FormName" action="index.php" method="post">
		<br>
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td height="36" align="right" valign="top" width="50"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36">
					<div align="left">
						<font color="#2f2180"><b>正しく入力されていない項目があるようです。<br>
						</b></font><font size="2">
						<hr>
<?
if ($h_letter->err_mail_adr_del) {
	Writeln("<br>");
	Writeln("・メールアドレスが正しく入力されていないようです。<br>");
}

if ($h_letter->err_unregist) {
	Writeln("<br>");
	Writeln("・下記アドレスがはいめーるビジネスレター登録メンバーに存在しておりません。<br>　お手数ですが、下記戻るボタンを押してもう一度メールアドレスを確認してください。<br><br>");
	Writeln('　<font size="2" color="#b2cce4">■</font><font size="2">メールアドレス：　' . ReplaceTag($h_letter->mail_adr_del) . '</font><br>');
}
?>
					</font></div>
				</td>
			</tr>
			<tr>
				<td width="50">　</td>
				<td width="550"><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<hr>
					<input type="submit" value="　戻　る　" name="BACK"></td>
			</tr>
		</table>　
		</form>
	</body>

</html>
