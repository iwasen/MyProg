<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/point.php');

//=== �ᥤ����� ===
ini_set('session.cache_limiter', 'none');
session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$member = new CMember;
?>
<html>

<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<title>MY�ݥ���ȸ�</title>
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
<br>
<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
	<tr height="36">
		<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
		<td width="550" height="36" valign="top">
			<div align="left">
			<b><img height="26" width="435" src="img/point_title.gif" alt="�ݥ���ȸ�"></b></div>
		</td>
	</tr>
	<tr>
		<td width="50" align="right" valign="top">��</td>

		<td width="550" bordercolor="#CCCCCC"> 
			<table width="100%" border="1" bordercolor="#BEC9DC" bgcolor="#BEC9DC">
				<tr>
					<td><font color="#2f2180"><b>���ʤ��Υݥ���Ȥϸ��� 
						<?=GetTotalPoint($member->member_id)?>
					�ݥ���ȤǤ���</b></font></td>
				</tr>
			</table>
			<div align="right"><b></b></div>
			<form name="form1" method="post" action="8c-2-1.php">
			<table width="100%" border="0" bordercolor="#BEC9DC" bgcolor="#BEC9DC">
				<tr>
					<td> <font size="2">��Կ����ǥݥ���Ȥ�򴹤���</font> </td>
				</tr>
				<tr>
					<td> 
						<table width="100%" border="1" cellpadding="0" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
							<tr>
								<td width="35%">��</td>
								<td width="65%" align="right"><font size="2">�������Х󥯡�͹��������б��������Ƥ���ޤ���</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">�򴹥ݥ����</font></td>
								<td width="65%"> <font size="2"> 
									<select name="point">
									<option value="3000" selected><font size="2">3000</font></option>
									<option value="4000"><font size="2">4000</font></option>
									<option value="5000"><font size="2">5000</font></option>
									<option value="6000"><font size="2">6000</font></option>
									<option value="7000"><font size="2">7000</font></option>
									<option value="8000"><font size="2">8000</font></option>
									<option value="9000"><font size="2">9000</font></option>
									<option value="10000"><font size="2">10000</font></option>
									<option value="15000"><font size="2">15000</font></option>
									<option value="20000"><font size="2">20000</font></option>
									<option value="30000"><font size="2">30000</font></option>
									<option value="40000"><font size="2">40000</font></option>
									<option value="50000"><font size="2">50000</font></option>
									<option value="60000"><font size="2">60000</font></option>
									<option value="70000"><font size="2">70000</font></option>
									<option value="80000"><font size="2">80000</font></option>
									<option value="90000"><font size="2">90000</font></option>
									<option value="100000"><font size="2">100000</font></option>
									</select>
								�ݥ����</font></td>
							</tr>
							<tr>
								<td width="35%" height="26"><font size="2">�ڿ����߼������<br>
								��5000�߰ʾ�ϻ�̳����ô</font></td>
								<td width="65%" height="26"><font size="2">���ꤽ�ʶ�ԡ����ֺ��Ź����������<br>
									���ꤽ�ʶ�ԡ�������¾��Ź����������<br>
								���ꤽ�ʰʳ��ζ�ԡ�����������������</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">��ԡ���Ź̾</font></td>
								<td width="65%"><font size="2"> 
									<input type="text" name="bank" maxlength="20" size="12">
									��)�ꤽ�� 
									<input type="text" name="bank_branch" maxlength="20" size="12">
								��)��ëĮ</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">���󥳥������ƥ�̾</font></td>
								<td width="65%"> <font size="2"> 
									<input type="text" name="bank_kana" size="12" maxlength="20">
									��)�꥽�� 
									<input type="text" name="bank_branch_kana" size="12" maxlength="20">
								��)���ߥ���襦</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">�����ֹ�</font></td>
								<td width="65%"> <font size="2"> 
									<select name="acc_kind">
									<option value="����" selected>����</option>
									<option value="����">����</option>
									<option value="����">����</option>
									</select>
									<input type="text" name="acc_number" size="16" maxlength="10">
								(Ⱦ�ѿ���)�� ��)0123456</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">����̾��</font></td>
								<td width="65%"><font size="2">��</font><font size="2">�� 
									<input type="text" name="acc_name1" size="12" maxlength="20">
									������̾ 
									<input type="text" name="acc_name2" size="12" maxlength="20">
								��)���ġ���Ϻ</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">�������ᥤ��</font></td>
								<td width="65%"> <font size="2">���� 
									<input type="text" name="acc_name1_kana" size="12" maxlength="20">
									�����ᥤ 
									<input type="text" name="acc_name2_kana" size="12" maxlength="20">
								��)��ޥ�������</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center"> 
									<input type="submit" name="submit1" value="��ǧ���̤�">
									���� 
									<input type="reset" value="�ꥻ�å�">
								</td>
							</tr>
							<tr align="center"> 
								<td colspan="2"><font size="2">���20�����������Ȥʤ�ޤ���������˿����ߤ򤵤��Ƥ��������ޤ���</font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>

			<form name="form2" method="post" action="8c-2-1.php">
			<table width="100%" border="0" bordercolor="#BEC9DC" bgcolor="#BEC9DC">
				<tr>
					<td><font size="2">���ʷ��ǥݥ���Ȥ�򴹤���</font> </td>
				</tr>
				<tr>
					<td> 
						<table width="100%" border="1" cellpadding="0" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
							<tr>
								<td width="35%">��</td>
								<td width="65%" align="right"><font size="2">���ģå��եȥ����ɤ��Ϥ����ޤ���</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">�򴹥ݥ����</font></td>
								<td width="65%"> <font size="2"> 
									<select name="point">
									<option value="3000" selected>3000</option>
									<option value="4000">4000</option>
									<option value="5000">5000</option>
									<option value="6000">6000</option>
									<option value="7000">7000</option>
									<option value="8000">8000</option>
									<option value="9000">9000</option>
									<option value="10000">10000</option>
									<option value="15000">15000</option>
									<option value="20000">20000</option>
									<option value="30000">30000</option>
									<option value="40000">40000</option>
									<option value="50000">50000</option>
									<option value="60000">60000</option>
									<option value="70000">70000</option>
									<option value="80000">80000</option>
									<option value="90000">90000</option>
									<option value="100000">100000</option>
									</select>
								�ݥ����</font></td>
							</tr>
							<tr>
								<td width="35%" height="25"><font size="2">��̾��</font></td>
								<td width="65%" height="25"><font size="2">�� 
									<input type="text" name="name1" size="12" maxlength="20">
									̾ 
									<input type="text" name="name2" size="12" maxlength="20">
								��)���ġ���Ϻ </font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">͹���ֹ�</font></td>
								<td width="65%"><font size="2">�� 
									<input type="text" name="zip1" maxlength="3" size="6">
									- 
									<input type="text" name="zip2" maxlength="4" size="8">
								(Ⱦ�ѿ���)����)105 - 0004</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">��ƻ�ܸ�</font></td>
								<td width="65%"> <font size="2"> 
									<input type="text" name="addr_pref" size="10" maxlength="10">
								��������</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">���Į¼</font></td>
								<td width="65%"> <font size="2"> 
									<input type="text" name="addr_city" size="30" maxlength="40">
								��)���迷��</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">����</font></td>
								<td width="65%"> <font size="2"> 
									<input type="text" name="addr_number" size="30" maxlength="40">
								��)�����ܡ����ϡ���</font></td>
							</tr>
							<tr>
								<td width="35%"><font size="2">�ӥ롦���ѡ��ȡ��ޥ󥷥��̾</font></td>
								<td width="65%"> <font size="2"> 
									<input type="text" name="addr_building" size="30" maxlength="40">
								��)�����ޥ󥷥�󣲣�����</font></td>
							</tr>
							<tr>
								<td colspan="2" align="center"> 
									<input type="submit" name="submit2" value="��ǧ���̤�">
									���� 
									<input type="reset" value="�ꥻ�å�">
								</td>
							</tr>
							<tr align="center"> 
								<td colspan="2"><font size="2">���20�����������Ȥʤ�ޤ��������ܤ�ȯ���򤵤��Ƥ��������ޤ���</font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>
			<table width="100%" border="0" bordercolor="#FFCCFF" bgcolor="#FFCCFF">
				<tr>

					<td><font size="2">�嵭�ݥ���ȸ򴹿����ե�����ˤƤ�����ĺ��������ϡ���Կ�����̳���ڤӾ��ʷ�ȯ����̳�ʳ������Ѥ��ʤ����Ȥ���«�������ޤ���</font></td>
				</tr>
			</table>
			<font size="2"></font><font size="2"><br>
			�����ݥ���Ȥϡ����ݥ����1�ߤβ��ͤ��������ޤ���<br>
			�����ݥ���ȸ򴹤�3000�ݥ���Ȥ������դ��Ƥ���ޤ���<br>
			�����ݥ���Ȥ�ͭ�����֤�ȯ�����Ƥ��飳ǯ�Ǥ���<br>
			�����ʥݥ����ȯ����飳ǯ�������������ͭ�����֤ˤʤ�ޤ���<br>
			����������񤵤��Ⱥ��ޤ����᤿�ݥ���Ȥ�̵���Ȥʤ�ޤ���<br>
			<br>
			<b>��</b><font color="#cf2a38"><b>�������ơ�net����</b></font></font><font size="2"><b>��<a href="http://www.kikasete.net" target="_blank">http://www.kikasete.net</a></b><br>
			<b>��</b><font color="#cf2a38"><b>����Ѥ�net��</b></font><b>��<a href="http://www.melonpan.net" target="_blank">http://www.melonpan.net</a></b><br>
			<b>��</b><font color="#cf2a38"><b>����롦net����</b></font><b>��<a href="http://www.melmel.net" target="_blank">http://www.melmel.net</a></b><br>
			<b>��</b><font color="#cf2a38"><b>��������WEB������</b></font><b>��<a href="http://www.kuchi-komi.net/" target="_blank">http://www.kuchi-komi.net/</a></b><br>
			<b>��</b><font color="#cf2a38"><b>�ҤȤӤȡ�net����</b></font><b>��<a href="http://www.hitobito.net" target="_blank">http://www.hitobito.net</a></b></font><br>
		</td>
	</tr>
	<tr>
		<td width="50">��</td>
		<td width="550"><br>
			<br>
			<font size="2">
			<hr>
			</font><a href="index.php"><img height="17" width="80" src="img/mypage.gif" alt="my page�����" border="0"></a><br>
			<br>
		</td>
	</tr>
</table>
</body>

</html>
