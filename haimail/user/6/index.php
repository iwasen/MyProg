<?
include('../inc/common.php');
include('../inc/haimail_letter.php');

//=== �ᥤ����� ===
session_start();
$h_letter = new CHaimailLetter;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>�ӥ��ͥ������Ѥ���</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr height="45">
				<td width="202" rowspan="2" bgcolor="#2f2180" align="left" valign="top">
					<div align="right">
						<img height="111" width="202" src="../img/title_left.gif" alt="�Ϥ��᡼�롦net"></div>
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
							<td width="62" height="27" align="center" valign="middle"><input type="submit" value="������" name="login"></td>
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

		<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="�Ϥ��᡼�롦net�äơ�"></a><a href="../movie/index.html"><img src="../img/tsushin2.gif" width="119" height="16" alt="�Ϥ��᡼��ǲ��̿�" border="0"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="����«"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="�ץ饤�Х����ݥꥷ��"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="�ӥ��ͥ������Ѥ���"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="�Ŀͤ����Ѥ���"></a></td>
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
										<td height="59" colspan="2"> <font size="2">�֤Ϥ��᡼�롦net�פϣ�᡼��ޡ����ƥ��󥰥ͥåȥ���Ǥ���<br>
											��᡼���Ȥä��ץ�⡼������ޡ����ƥ��󥰳�ư�ˤ����Ѥ��������ޤ���<br>
											��Ȥ��ޤϡ�ǯ������̤ʤɤδ���°���ȶ�̣������ǥ������Ȥ��줿���С��ˡ� <br>
											��᡼��ǥ�å��������ۿ����뤳�Ȥ��Ǥ��ޤ��� </font></td>
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
										<td height="87" colspan="2"><font size="2">�Ϥ��᡼�롦net�Ǥϡ�<br>
��᡼��ޡ����ƥ��󥰤ˤ��ؿ��Τ������ؤϤ��᡼��ӥ��ͥ��쥿�������Ū�ˤ����ꤤ�����ޤ���<br>
����˾�����ϲ����Ϥ��᡼��ӥ��ͥ��쥿����Ͽ��˥᡼�륢�ɥ쥹�����Ϥ��ơ���Ͽ�ܥ���򲡤��Ƥ���������</font></td>
									</tr>
									<tr>
										<td colspan=2>
											<form action="6-1_check.php" method="post">
												<font size="2" color="#b2cce4">��</font><font size="2">�Ϥ��᡼��ӥ��ͥ��쥿����Ͽ<?ErrMark($h_letter->err_mail_adr)?></font>
												��<font size="2"><input type="text" name="mail_adr" size="30" maxlength=50 value="<?=$h_letter->mail_adr?>"></font>
												��<input type="submit" name="Submit" value="����Ͽ��">
											</form>
										</td>
									</tr>
									<tr><td><br><br></td></tr>
									<tr height="118">
										<td height="87" colspan="2"><font size="2">���˻�ǰ�Ǥ������Ϥ��᡼��쥿������ߤ򤴴�˾������<br>
�����Ϥ��᡼��ӥ��ͥ��쥿�������ˡ��᡼�륢�ɥ쥹�����Ϥ��ơ����ܥ���򲡤��Ƥ���������</font></td>
									</tr>
									<tr>
										<td colspan=2>
											<form action="6-4_check.php" method="post">
												<font size="2" color="#b2cce4">��</font><font size="2">�Ϥ��᡼��ӥ��ͥ��쥿�����<?ErrMark($h_letter->err_mail_adr_del || $h_letter->err_unregist)?></font>
												��<font size="2"><input type="text" name="mail_adr_del" size="30" maxlength=50 value="<?=$h_letter->mail_adr_del?>"></font>
												��<input type="submit" name="Submit" value="�����">
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
          <td height="8" align="left" valign="top" width="548"><font size="2">���䤤��碌��Ϥ����餫�顡��<a href="mailto:marketer@haimail.net">marketer@haimail.net</a></font></td>
        </tr>
        <tr height="16">
        </tr>
        <tr> 
          <td align="right" valign="top" width="50">��</td>
          <td align="left" valign="top" width="548"> 
            <div align="right"><font size="2"><br>
              <br>
              <img src="img/human_J_color.gif" width="134" height="42"><br>
              </font> </div>
            <hr>
            <a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="�ۡ�������"></a></td>
        </tr>
        <tr> 
          <td width="50"><img height="16" width="50" src="../img/space.gif"></td>
          <td align="center" valign="middle" width="548"><br>
            <hr width="100%" size="1">
            <font size="1">�֤Ϥ��᡼�롦net�פϳ�����ҡ����������������Ĥ��Ƥ��ޤ���<br>
            Copyright (C) 2001-2003 xx xxxxx, Inc. All rights reserved. <br>
            ���Ķ��ϡ�������ҥ���ץ쥹<br>
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
