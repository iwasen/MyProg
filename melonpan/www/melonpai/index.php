<?php include ( "../sub/head.inc" ); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>メルマガナビ　ログイン</TITLE>
<link rel="stylesheet" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	with (document.form1) {
		if (w_melonpai_mail_addr.value == "") {
			alert("IDを入力してください。");
			w_melonpai_mail_addr.focus();
			return false;
		}
		if (w_melonpai_pswd.value == "") {
			alert("パスワードを入力してください。");
			w_melonpai_pswd.focus();
			return false;
		}
	}
}

//-->
</SCRIPT>
</head>
<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600" leftmargin="0" marginwidth="0">
<? draw_head2(".."); ?>
<br>
<br>
<TABLE width="750" border="0" cellspacing="0" cellpadding="0">
	<TR>
		<TD valign="top" colspan="3" align="center">
			<TABLE width="513" border="0" cellspacing="0" cellpadding="0" bgcolor="#6AB05D">
				<TR>
					<TD>
						<TABLE width="513" border="0" cellspacing="1" cellpadding="5">
							<TR bgcolor="#FFFFFF">
								<TD>
									<form name="form1" action="login_chk.php" method="post" onSubmit="return OnSubmit_form1();">
									<p><font color="#99CCFF">■</font><font color="#003399"><b>メルマガナビ　ログイン</b></font></p>
										<table width="300" border="0" cellspacing="1" cellpadding="3" align="center">
											<tr>
												<td width="34%" bgcolor="#99CCFF">
													<div align="right"><b><font color="#003399">ＩＤ</font></b></div>
												</td>
												<td width="66%">
													<input type="text" name="w_melonpai_mail_addr" size="30">
												</td>
											</tr>
											<tr>
												<td width="34%" bgcolor="#99CCFF">
													<div align="right"><b><font color="#003399">パスワード</font></b></div>
												</td>
												<td width="66%">
													<input type="password" name="w_melonpai_pswd" size="20">
												</td>
											</tr>
											<tr>
												<td width="34%">　</td>
													<td width="66%" align="right">
													<input type="submit" name="Submit" value="ログイン">
												</td>
											</tr>
										</table>
									</form>
								</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
			<table>
			<br>
			<br>
			<br>
			</table>
		</TD>
	</TR>
<? draw_footer2(".."); ?>
</TABLE>
</body>
</html>
