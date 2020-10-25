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
		<title>ビジネスで利用する</title>
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

		<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="はいめーる・netって？"></a><a href="../movie/index.html"><img src="../img/tsushin2.gif" width="119" height="16" alt="はいめーる映画通信" border="0"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="お約束"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="プライバシーポリシー"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="ビジネスで利用する"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="個人で利用する"></a></td>
			</tr>
		</table><br>
		<br>
		<table cellspacing="0" cellpadding="0" border="0" width="596">
			<tr>
    		<td width="50"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="546"><table border="0" cellspacing="0" width="546">
						<tr height="36">
							<td height="36" valign="top"><img src="img/title3.gif" width="435" height="26"></td>
								</tr>
						<tr>
							<td align="left" valign="top">
								<table width="545" border="0" cellspacing="0" height="118">
									<tr height="118">
										<td height="59" colspan="2"> <font size="2">「はいめーる・net」はｅメールマーケティングネットワークです。<br>
											ｅメールを使ったプロモーションやマーケティング活動にご利用いただけます。<br>
											企業さまは、年齢や性別などの基本属性と興味ジャンルでセグメントされたメンバーに、 <br>
											ｅメールでメッセージを配信することができます。 </font></td>
									</tr>
									<tr height="118">
										<td height="53" width="448" valign="top">
											<div align="left"> </div>
										</td>
										<td height="53" width="136"><a href="top.html"><img src="img/shosai_b.gif" width="57" height="58" border="0"></a></td>
									</tr>
									<tr height="118">
										<td height="38" colspan="2"><img src="img/title2.gif" width="435" height="26"></td>
									</tr>
									<tr height="118">
										<td height="87" colspan="2"><font size="2">はいめーる・netでは、<br>
ｅメールマーケティングにご関心のある方へはいめーるビジネスレターを定期的にお送りいたします。<br>
ご希望の方は下記はいめーるビジネスレター登録欄にメールアドレスを入力して、登録ボタンを押してください。</font></td>
									</tr>
									<tr>
										<td colspan=2>
											<form action="6-1_check.php" method="post">
												<font size="2" color="#b2cce4">■</font><font size="2">はいめーるビジネスレター登録<?ErrMark($h_letter->err_mail_adr)?></font>
												　<font size="2"><input type="text" name="mail_adr" size="30" maxlength=50 value="<?=$h_letter->mail_adr?>"></font>
												　<input type="submit" name="Submit" value="　登録　">
											</form>
										</td>
									</tr>
									<tr><td><br><br></td></tr>
									<tr height="118">
										<td height="87" colspan="2"><font size="2">非常に残念ですが、はいめーるレターの中止をご希望の方は<br>
下記はいめーるビジネスレター退会欄に、メールアドレスを入力して、退会ボタンを押してください。</font></td>
									</tr>
									<tr>
										<td colspan=2>
											<form action="6-4_check.php" method="post">
												<font size="2" color="#b2cce4">■</font><font size="2">はいめーるビジネスレター退会<?ErrMark($h_letter->err_mail_adr_del || $h_letter->err_unregist)?></font>
												　<font size="2"><input type="text" name="mail_adr_del" size="30" maxlength=50 value="<?=$h_letter->mail_adr_del?>"></font>
												　<input type="submit" name="Submit" value="　退会　">
											</form>
										</td>
									</tr>
								</table>
								<br>
								<br>
								<hr width="100%" size="2">
							</td>
						</tr>
					</table></td>
			</tr>
			<tr>
				<td colspan="2">
      <table border="0" cellspacing="0" mm_noconvert="TRUE" width="598">
        <tr height="16"> 
          <td width="50" height="16" align="right" valign="top" rowspan="2"></td>
          <td height="8" align="left" valign="top" width="548"><font size="2">お問い合わせ先はこちらから　　<a href="mailto:marketer@haimail.net">marketer@haimail.net</a></font></td>
        </tr>
        <tr height="16">
        </tr>
        <tr> 
          <td align="right" valign="top" width="50">　</td>
          <td align="left" valign="top" width="548"> 
            <div align="right"><font size="2"><br>
              <br>
              <img src="img/human_J_color.gif" width="134" height="42"><br>
              </font> </div>
            <hr>
            <a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="ホームに戻る"></a></td>
        </tr>
        <tr> 
          <td width="50"><img height="16" width="50" src="../img/space.gif"></td>
          <td align="center" valign="middle" width="548"><br>
            <hr width="100%" size="1">
            <font size="1">「はいめーる・net」は株式会社○○○○○○が運営しています。<br>
            Copyright (C) 2001-2003 xx xxxxx, Inc. All rights reserved. <br>
            運営協力：株式会社インプレス<br>
            <br>
            </font> <a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"><img src="../img/xxxxxxx_logo_i.gif" width="43" height="46" border="0" alt="xx xxxxx"></a>
            </td>
        </tr>
      </table>
    </td>
			</tr>
		</table>
		</form>
	</body>

</html>
