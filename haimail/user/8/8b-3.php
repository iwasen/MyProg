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
		<title>MY友達紹介確認</title>
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
		<form method="post" action="8b-4.php">
		<br>
		<table width="601" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr>
				<td align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" align="left" valign="top">
					<div align="left">
						<font size="2"><img height="26" width="435" src="img/shokai_title.gif" alt="紹介メールの内容"><br>
						<br>
						お友達にお送りするメッセージの内容です。<br>
						</font><br>
						<table border="1" cellpadding="5" cellspacing="2" width="450" height="309">
							<tr>
								<td valign="top"><?=ReplaceTag($shoukai->message)?></td>
							</tr>
						</table></div>
				</td>
			</tr>
			<tr height="36">
				<td height="36">　 </td>
				<td width="550" height="36"><font size="2"><br>
					送信ボタンを押すと、あなたからの紹介メールがお友達に送信されます。
					<hr>
					<input type="submit" value="　戻　る　" name="BACK"> <input type="submit" value="　送　信　" name="send"> </font></td>
			</tr>
		</table>
		</form>
	</body>

</html>
