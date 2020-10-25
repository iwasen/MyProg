<?
include('../inc/common.php');
include('../inc/haimail_letter.php');

//=== メイン処理 ===
session_start();
$h_letter = new CHaimailLetter;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>ビジネスレター退会確認</title>
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
						<form name="login" action="../8/index.php" method="post">
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
							<td colspan="3" align="right" valign="middle"><a href="../9/9-1.php"><img src="../img/password_right.gif" width="185" height="14" border="0"></a></td>
							<td width="62"></td>
						</tr>
						</form>
					</table></td>
			</tr>
			<tr>
				<td bgcolor="#5d85b8" align="left" valign="bottom" width="536"><img src="../img/head.jpg" width="535" height="66" alt="interactive communication network"></td>
			</tr>
			<tr height="18">

		<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="はいめーる・netって？"></a><a href="../movie/index.html"><img src="../img/tsushin2.gif" width="119" height="16" alt="はいめーる映画通信" border="0"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="お約束"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="プライバシーポリシー"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="ビジネスで利用する"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="個人で利用する"></a></td>
			</tr>
		</table><br>
		<br>
		<table cellspacing="0" cellpadding="0" border="0" width="596">
			<tr>
				<td width="50"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="546"><table border="0" cellspacing="0" width="546">
						<form name="FormName" action="6-6.php" method="post">
						<tr height="36">

						<td height="36" valign="top"><b><img height="26" width="435" src="img/title2.gif" alt="クライアント様お問い合わせ先"></b></td>
						</tr>
						<tr>
							<td align="left" valign="top"><table width="545" border="0" cellspacing="0" height="33">
									<tr>
										<td align="left" valign="top"><font size="2">
こちらのメールアドレスをはいめーるビジネスレター登録より退会してもよろしければ、<br>
下記退会ボタンを押してください。<br><br>
											</font></td>
									</tr>
								</table><table width="456" border="0" cellspacing="2" cellpadding="0">
									<tr height="20">
										<td width="140" height="20">
											<div align="right">
												<font size="2" color="#b2cce4">■</font><font size="2">メールアドレス：</font></div>
										</td>
										<td width="243" height="20"><font size="2"><?=ReplaceTag($h_letter->mail_adr_del)?></font></td>
									</tr>
									<tr>
										<td width="140"></td>
										<td width="243"><br>
											<br>
											<input type="submit" name="Submit" value="　退　会　"></td>
									</tr>
								</table><br>
								<hr width="100%" size="2">
							</td>
						</tr>
						</form>
					</table></td>
			</tr>
			<tr>
				<td colspan="2"><table border="0" cellspacing="0" mm_noconvert="TRUE" width="598">
						<tr height="16">
							<td width="50" height="16" align="right" valign="top"></td>
							<td height="16" align="left" valign="top" width="548"><font size="2">お問い合わせ先はこちらから　　<a href="mailto:info@haimail.net">info@haimail.net</a></font></td>
						</tr>
						<tr>
							<td align="right" valign="top" width="50">　</td>
							<td align="left" valign="top" width="548"><font size="2"><br>
								<br>
								<br>
								</font>
								<hr>
								<a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="ホームに戻る"></a></td>
						</tr>
						<tr>
							<td width="50"><img height="16" width="50" src="../img/space.gif"></td>
							<td align="center" valign="middle" width="548"><br>
								<hr width="100%" size="1">
						<font size="1">「はいめーる・net」は株式会社○○○○○○が運営しています。<br>
						Copyright (C) 2002-2002 xx xxxxx, Inc. All rights reserved. <br>
						運営協力：株式会社インプレス<br>
						<br>
						<a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"target="top">
						</a></font>
						<table width="200" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="100">
									<div align="center"><a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"><img src="../img/xxxxxxx_logo_i.gif" width="43" height="46" border="0" alt="xx xxxxx"></a></div>
								</td>
								<td width="100">
									<div align="center"><a href="http://www.impress.co.jp/"><img src="../img/impress_logo_i.gif" width="53" height="39" border="0" alt="impress"></a></div>
								</td>
							</tr>
						</table>
						<font size="1"><br>
						<br>
						</font></td>
						</tr>
					</table></td>
			</tr>
		</table>
		<p>　
	</body>

</html>
