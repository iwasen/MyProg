<?
include('../inc/common.php');

session_start();
if (!$_SESSION['login'])
	redirect('../index.php');
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY退会</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr height="45">
				<td width="202" rowspan="2" bgcolor="#2f2180" align="left" valign="top">
					<div align="right">
						<img height="111" width="202" src="../img/title_left.gif" alt="はいめーる・net"></div>
				</td>
				<td height="45" valign="bottom" bgcolor="#2f2180" align="left" width="536">
					<table border="0" cellspacing="0" bgcolor="#2f2180" height="43">
						<form name="login" action="index.php" method="post">
						<tr height="27">
							<td width="58" align="left" valign="middle" height="27"><img src="../img/mypage.gif" width="58" height="22"></td>
							<td width="67" height="27" align="right" valign="middle"><img src="../img/mailaddress.gif" width="65" height="12"></td>
							<td width="86" height="27"><input type="text" name="mail_adr" size="23"></td>
							<td height="27" width="55" align="right" valign="middle"><img src="../img/password.gif" width="49" height="12"> </td>
							<td height="27" width="61" align="left" valign="middle"><input type="password" name="password" size="10"></td>
							<td width="62" height="27" align="center" valign="middle"><input type="submit" value="ログイン" name="login"></td>
						</tr>
						<tr>
							<td width="58"></td>
							<td width="67"></td>
							<td colspan="3" align="right" valign="middle"><a href="../9/9-1.php"><img src="../img/password_right.gif" width="185" height="14" border=0></a></td>
							<td width="62"></td>
						</tr>
						</form>
					</table></td>
			</tr>
			<tr>
				<td bgcolor="#5d85b8" align="left" valign="bottom" width="536"><img src="../img/head.jpg" width="535" height="66" alt="interactive communication network"></td>
			</tr>
			<tr height="18">
				<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="はいめーる・netって？"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="お約束"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="プライバシーポリシー"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="ビジネスで利用する"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="個人で利用する"></a></td>
			</tr>
		</table><br>
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<form name="FormName" action="8e-3.php" method="post">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36" valign="top">
					<div align="left">
						<img height="26" width="435" src="img/taikai_title.gif" alt="退会"></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36" align="right" valign="top">　</td>
				<td width="550" height="36"><table width="500" border="0" cellspacing="0">
						<tr>
							<td>
								<div align="left">
									<font size="2">はいめーる・netを退会をすると、これまでのポイントがすべて無効になります。<br>
									メールアドレスなどの変更をされる場合は、<br>
									登録変更を行えますのでメンバーを退会する必要はありません。⇒登録変更は<a href="8a-2.php">こちら</a></font></div>
								<p><font size="2">退会の理由についてよろしければ教えてください。今後の参考にさせていただきます。<br>
								<br>
								</font><textarea name="taikai_riyuu" rows="7" cols="70"></textarea> </td>
						</tr>
					</table><br>
					<input type="submit" name="Submit" value="　退会する　"></td>
			</tr>
			<tr>
				<td width="50">　</td>
				<td width="550"><br>
					<font size="2"><br>
					<br>
					<br>
					<hr>
					</font><a href="index.php"><img height="17" width="80" src="img/mypage.gif" alt="my pageに戻る" border="0"></a><br>
					<br>
				</td>
			</tr>
			</form>
		</table>
	</body>

</html>
