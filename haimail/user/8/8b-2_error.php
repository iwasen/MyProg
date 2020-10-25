<?
include('../inc/common.php');
include('../inc/shoukai.php');

session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$shoukai = new CShoukai;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY友達紹介エラー</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="はいめーる・net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
		<form name="FormName" action="8b-2.php" method="post">
		<table width="641" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="591" height="36">
					<div align="left">
						<font color="#2f2180"><b>正しく入力されていない項目があるようです。<br>
						</b></font><font size="2">
						<hr>
<?
if ($shoukai->err_nickname) {
	Writeln("<br>");
	Writeln("・あなたのお名前またはニックネームが入力されていないようです。<br>");
	Writeln("　あなたからのメッセージだとわかるように、あなたのお名前またはニックネームを入力してください。<br>");
}

if ($shoukai->err_tomodachi_adr) {
	Writeln("<br>");
	Writeln("・お友達のメールアドレスが正しく入力されていないようです。<br>");
	Writeln("　メールをお届けするためにご確認ください。<br>");
}

if ($shoukai->err_message) {
	Writeln("<br>");
	Writeln("・お友達に送るメッセージを入力してください。");
}

if ($shoukai->err_message_len) {
	Writeln("<br>");
	Writeln("・お友達に送るメッセージは全角200文字以内でお願いします。");
}
?>
					</font></div>
				</td>
			</tr>
			<tr height="66">
				<td height="66"></td>
				<td width="591" height="66"><br>
					<br>
					<hr>
					<input type="submit" value="　戻　る　" name="BACK"> </td>
			</tr>
		</table>
		</form>
	</body>

</html>
