<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/sel_profile1.php');
include('../inc/sel_profile2.php');

//=== �ᥤ����� ===
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$member = new CMember;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY ��Ͽ�����ǧ</title>
<SCRIPT LANGUAGE=javascript>
<!--
function ZipKensaku(pfc) {
	open("../10/10-1.php?pfc=" + pfc);
}
//-->
</SCRIPT>
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
						<form name="login" action="index.php" method="post">
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
		<form name="form1" action="8a-2_check.php" method="post">
		<br>
		<table border="0" cellspacing="0" width="570" cellpadding="0">
			<tr height="35">
				<td width="20" height="35"></td>
				<td width="550" height="35" valign="top"><b><img height="26" width="435" src="img/your.gif" alt="��Ͽ����γ�ǧ"></b></td>
			</tr>
			<tr>
				<td width="20"><img height="16" width="20" src="../img/space.gif"></td>
				<td width="550" valign="top"><font size="2">���ʤ�����Ͽ����Ϥ�����Ǥ����ѹ����������ϡ������餫�齤���򤷤�<br>
					���ѹ���ǧ�ץܥ���򲡤��Ƥ���������</font></td>
			</tr>
		</table><br>
		<br>
		<table cellspacing="0" cellpadding="0" border="0" width="605">
			<tr>
				<td width="15" valign="top"><img height="16" width="20" src="../img/space.gif"></td>
				<td valign="top" width="342"><font size="2"><table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td width="40" align="left" valign="top" bgcolor="white"></td>
							<td width="200" bgcolor="#b2cfef"><font size="2">��̣�����롡����</font></td>
							<td width="30" bgcolor="#b2cfef" align="center" valign="middle">��</td>
							<td width="30" bgcolor="#b2cfef" align="center" valign="middle">��</td>
							<td width="30" bgcolor="#b2cfef" align="center" valign="middle">��</td>
						</tr>
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="13" align="left" valign="top" bgcolor="white"><img src="../img/eat_little.gif" width="40" height="53" alt="��"></td>
							<td width="200" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr height="18">
							<td width="40" rowspan="2" bgcolor="white"><img src="../img/clothes_little.gif" width="40" height="52" alt="��"></td>
							<td colspan="4" height="18"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="6" align="left" valign="top" bgcolor="white"><img src="../img/living_little.gif" width="40" height="52" alt="��"></td>
							<td width="200" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="16">
							<td width="40" rowspan="7" align="left" valign="top" bgcolor="white"><img src="../img/work_little.gif" width="40" height="52" alt="Ư"></td>
							<td colspan="4" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('Ư', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr bgcolor="#ffffcf" height="16">
							<td width="40" rowspan="5" align="left" valign="top" bgcolor="white"><img src="../img/life_little.gif" width="40" height="52" alt="��"></td>
							<td colspan="4" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr height="16">
							<td width="40" rowspan="3" align="left" valign="top"><img src="../img/relax_little.gif" width="40" height="52" alt="��"></td>
							<td colspan="4" bgcolor="white" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table></font></td>
				<td width="25" valign="top"><img height="16" width="25" src="../img/space.gif"></td>
				<td valign="top" width="342"><font size="2"><table border="0" cellpadding="0" cellspacing="2">
						<tr>
							<td width="40" bgcolor="white"></td>
							<td bgcolor="#b2cfef" width="200"><font size="2">��̣������</font></td>
							<td bgcolor="#b2cfef" width="30" align="center" valign="middle">��</td>
							<td bgcolor="#b2cfef" width="30" align="center" valign="middle">��</td>
							<td bgcolor="#b2cfef" width="30" align="center" valign="middle">��</td>
						</tr>
						<tr bgcolor="white" height="16">
							<td width="40" rowspan="17" valign="top" bgcolor="white"><img src="../img/play_little.gif" width="40" height="52" alt="ͷ"></td>
							<td colspan="4" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('ͷ', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr align="left" valign="top" bgcolor="white" height="16">
							<td width="40" rowspan="4" bgcolor="white"><img src="../img/beauty_little.gif" width="40" height="52" alt="��"></td>
							<td width="200" bgcolor="white" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table>
					<hr size="1">
					<table border="0" cellpadding="0" cellspacing="2">
						<tr align="left" valign="top" bgcolor="white" height="16">
							<td width="40" rowspan="15" bgcolor="white"><img src="../img/knowridge_little.gif" width="40" height="52" alt="��"></td>
							<td width="200" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
							<td width="30" height="16"></td>
						</tr>
<?
WriteKyoumiGenre('��', $member->kyoumi_genre);
?>
					</table></font></td>
			</tr>
		</table><br>
		<br>
		<table width="608" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="left" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td height="36">
					<div align="left">
						<table width="569" border="0" cellspacing="2" cellpadding="2">
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2"><?ErrMark($member->err_mail_adr)?>�᡼�륢�ɥ쥹</font></td>
								<td colspan="2" align="left" valign="middle"><input type="text" name="mail_adr" size="18" maxlength=50 value="<?=$member->mail_adr?>"> <font size="2">��Ⱦ�ѱѿ���</font></td>
							</tr>
							<tr>
								<td width="140">��</td>
								<td colspan="2" align="left" valign="middle"><input type="text" name="mail_adr2" size="18" maxlength=50 value="<?=$member->mail_adr2?>"> <font size="2">�ʳ�ǧ�Τ���2�����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">�ѥ����<?ErrMark($member->err_password)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="password" name="password" size="18" maxlength=14 value="<?=$member->password?>"> <font size="2">��Ⱦ�ѱѿ�6ʸ���ʾ�14ʸ�������</font></td>
							</tr>
							<tr>
								<td width="140">��</td>
								<td colspan="2" align="left" valign="middle"><input type="password" name="password2" size="18" maxlength=14 value="<?=$member->password2?>"> <font size="2">�ʳ�ǧ�Τ���2�����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">����<?ErrMark($member->err_seibetsu)?></font></td>
								<td width="170" bgcolor="white" align="left" valign="middle"><input type="radio" name="seibetsu" value="0"<?=RadioChecked($member->seibetsu, "0")?>> <font size="2">��</font>������ <input type="radio" name="seibetsu" value="1"<?=RadioChecked($member->seibetsu, "1")?>> <font size="2">��</font> </td>
								<td width="268">��</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">̤����<?ErrMark($member->err_mikikon)?></font></td>
								<td width="170" bgcolor="white" align="left" valign="middle"><input type="radio" name="mikikon" value="0"<?=RadioChecked($member->mikikon, "0")?>> <font size="2">�ȿ�</font>���� <input type="radio" name="mikikon" value="1"<?=RadioChecked($member->mikikon, "1")?>> <font size="2">����</font> </td>
								<td width="268">��</td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">��ǯ����<?ErrMark($member->err_seinengappi)?></font></td>
								<td colspan="2"><font size="2">����</font> <select name="sei_nen" size=1>
									<option></option>
<?
WriteYear($member->sei_nen);
?>
									</select> <font size="2">ǯ</font> �� <select name="sei_getsu" size="1">
<?
WriteMonth($member->sei_getsu);
?>
									</select> <font size="2">��</font> �� <select name="sei_hi" size="1">
<?
WriteDay($member->sei_hi);
?>
									</select> <font size="2">��</font> </td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle" rowspan=2><font size="2">�ｻ��͹���ֹ�<?ErrMark($member->err_zip_kyojuu)?></font></td>
								<td width="170" align="left" valign="middle"><input type="text" name="zip_kyojuu1" size="3" maxlength=3 value="<?=$member->zip_kyojuu1?>"> - <input type="text" name="zip_kyojuu2" size="4" maxlength=4 value="<?=$member->zip_kyojuu2?>"> <font size="2">��Ⱦ�ѿ�����</font> �� </td>
								<td width="268"><font size="2">͹���ֹ椬����������<a href="JavaScript:ZipKensaku('1')">�������</a></font></td>
							</tr>
							<tr>
								<td colspan=2 valign="top"><font size="2">�����߳����ˤ����ޤ������ϡ���000��0000�פ����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle" rowspan=2><font size="2">��̳��͹���ֹ�</font></td>
								<td width="170" align="left" valign="middle"><input type="text" name="zip_kinmu1" size="3" maxlength=3 value="<?=$member->zip_kinmu1?>"> - <input type="text" name="zip_kinmu2" size="4" maxlength=4 value="<?=$member->zip_kinmu2?>"> <font size="2">��Ⱦ�ѿ�����</font> �� �� </td>
								<td width="268"><font size="2">͹���ֹ椬����������<a href="JavaScript:ZipKensaku('2')">�������</a></font></td>
							</tr>
							<tr>
								<td colspan=2 valign="top"><font size="2">�����߳����Ƕ�̳����Ƥ������ϡ���000��0000�פ����Ϥ��Ƥ���������</font></td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">����<?ErrMark($member->err_shokugyou)?></font></td>
								<td colspan="2" align="left" valign="middle"><select name="shokugyou" size="1">
										<option value=""<?=Selected($member->shokugyou, "")?>>�����Ӥ�������</option>
<?
WriteShokugyou($member->shokugyou);
?>
									</select> </td>
							</tr>
							<tr>
								<td width="140" bgcolor="#b2cfef" align="left" valign="middle"><font size="2">HTML�᡼���������<?ErrMark($member->err_html_mail)?></font></td>
								<td colspan="2" align="left" valign="middle"><input type="radio" name="html_mail" value="1"<?=RadioChecked($member->html_mail, "1")?>> <font size="2">��˾����</font> ���� <input type="radio" name="html_mail" value="0"<?=RadioChecked($member->html_mail, "0")?>> <font size="2">��˾���ʤ�</font> </td>
							</tr>
						</table><br>
						<br>
					</div>
				</td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><table width="600" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td width="234" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="225">
									<tr>
										<td bgcolor="#b2cce4"><font size="2">�褯�Ԥ�����ӥ�</font></td>
									</tr>
								</table></font></td>
							<td width="300" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="225">
									<tr>
										<td bgcolor="#b2cce4"><font size="2">�褯�Ԥ������ѡ�</font></td>
									</tr>
								</table></font></td>
						</tr>
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td width="234"><font size="2">���ʤ��Τ褯�Ԥ�����ӥˤ�<br>
								�����ĤǤ�����å����Ƥ���������</font></td>
							<td width="300"><font size="2">���ʤ��Τ褯�Ԥ������ѡ���<br>
								�����ĤǤ�����å����Ƥ�������</font> </td>
						</tr>
						<tr>
							<td width="45"></td>
							<td width="234"><table width="225" border="0" cellspacing="0" cellpadding="0">
<?
WriteYokuikuMise('01', $member->yokuiku_mise);
?>
								</table></td>
							<td width="300"><table width="225" border="0" cellspacing="0" cellpadding="0">
<?
WriteYokuikuMise('02', $member->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table></td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><table width="604" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td width="234">��</td>
							<td width="313">��</td>
						</tr>
						<tr align="left" valign="bottom">
							<td width="45"></td>
							<td colspan="2" align="left" valign="top"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="465">
									<tr>
										<td bgcolor="#b2cce4"><font size="2">����¾�Τ褯�Ԥ���Ź</font></td>
									</tr>
								</table></font></td>
						</tr>
						<tr align="left" valign="bottom">
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td width="234" align="left" valign="top"><font size="2">���ʤ����褯���Ѥ�����֤䥵���ӥ���<br>
								�����ĤǤ�����å����Ƥ���������</font></td>
							<td width="313"><font size="2">���ʤ����褯���Ѥ��뤪Ź�䥵���ӥ���<br>
								�����ĤǤ�����å����Ƥ���������</font></td>
						</tr>
						<tr>
							<td width="45"></td>
							<td width="234" align="left" valign="top"><table width="225" border="0" cellspacing="0" height="220" cellpadding="0">
<?
WriteYokuikuMise('03', $member->yokuiku_mise);
?>
								</table></td>
							<td width="313" valign="top" align="left"><table width="225" border="0" cellspacing="0" height="312">
<?
WriteYokuikuMise('04', $member->yokuiku_mise);
?>
								</table></td>
						</tr>
					</table></td>
			</tr>
			<tr height="36">
				<td colspan="2" height="36"><br>
					<br>
					<table width="606" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td colspan="2"><font size="2"><table border="0" cellpadding="2" cellspacing="0" width="468">
									<tr>
										<td bgcolor="#b2cce4"><font size="2">���ʤ��Ϥɤä��ɡ�<br>
											</font></td>
									</tr>
								</table>���ʤ��Ϥɤä��ɤǤ��������ֶᤤ��Τ򣱤�����Ǥ�������</font><br>
							</td>
						</tr>
						<tr>
							<td width="45"></td>
							<td colspan="2"><table border="0" cellspacing="0">
<?
WriteDocchiHa($member->docchiha);
?>
								</table></td>
						</tr>
					</table><br>
					<br>
					<table width="520" border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="45"></td>
							<td colspan="2"><font size="2">�֤Ϥ��᡼�롦net�פ򱿱Ĥ��������ҡ������������ϡ� <br>
								��ȯ����������ԡפ���礹�뤪�Τ餻���᡼��Ǥ��Ϥ����褦 <br>
								�ȻפäƤ��ޤ������������ˤʤ�ޤ�����<?ErrMark($member->err_oshirase_mail)?></font></td>
						</tr>
						<tr height="70">
							<td width="45" height="70"></td>
							<td width="97" height="70"></td>
							<td height="70"><input type="radio" name="oshirase_mail" value="1"<?=RadioChecked($member->oshirase_mail, "1")?>> <font size="2">�Ϥ�</font> ��<input type="radio" name="oshirase_mail" value="0"<?=RadioChecked($member->oshirase_mail, "0")?>> <font size="2">������</font> </td>
						</tr>
						<tr>
							<td width="45"><img height="16" width="45" src="../img/space.gif"></td>
							<td colspan="2"><br>
								<input type="submit" value="���ѹ���ǧ��" name="change"><font size="2"><br>
								<br>
								<br>
								<br>
								<hr>
								</font><a href="index.php"><img height="17" width="80" src="img/mypage.gif" alt="my page�����" border="0"></a><br>
								<br>
							</td>
						</tr>
					</table></td>
			</tr>
		</table><br>
		</form>
	</body>

</html>
