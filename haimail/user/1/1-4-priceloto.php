<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/reginfo.php');

session_start();
$reginfo = new CRegInfo;

if (!isset($reginfo->mail_adr))
	redirect('../index.php');

//ͧã�Ҳ�ǡ����ֹ�����
$sql = "SELECT SK_shoukai_no"
		. " FROM T_SHOUKAI,T_HISHOUKAI"
		. " WHERE HS_mail_adr=" . SqlStr($reginfo->mail_adr) . " AND HS_regist_flag='0' AND SK_shoukai_no=HS_shoukai_no"
		. " ORDER BY SK_shoukai_no";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds->EOF()) {
	$reginfo->shoukai_no = $ds->GetValue('SK_shoukai_no');
}

//PRID������
$reginfo->pr_id = $_COOKIE['PRID'];

//Web������Ͽ
$reginfo->regist_type = '0';

$reginfo->StoreDB();

GetSendMailInfo('KARIREGW', $subject, $mailfrom, $body);
$body = str_replace('%PARAMETER%', $reginfo->seq_no, $body);
SendMail($reginfo->mail_adr, $mailfrom, $subject, $body);

//���ץ�󥰡������ڡ���======
$present_id = '06';
$present_no = $_SESSION['present'];
if ($present_no != '') {
	$sql = "SELECT * FROM T_PRESENT WHERE PS_present_id='$present_id' AND PS_seq_no=$reginfo->seq_no";
	$ds = CreateDynaset($sql, ORADYN_DEFAULT);
	if ($ds->EOF())
		$sql = "INSERT INTO T_PRESENT (PS_present_id,PS_seq_no,PS_present_no) VALUES ('$present_id',$reginfo->seq_no,%s)";
	else
		$sql = "UPDATE T_PRESENT SET PS_present_no=%s WHERE PS_present_id='$present_id' AND PS_seq_no=$reginfo->seq_no";
	$sql = sprintf($sql, $present_no);
	ExecuteSQL($sql);
}
//============================

session_destroy();
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>��Ͽ��λ</title>
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
				<td colspan="2" bgcolor="#3f659e" height="18" align="left" valign="middle"><img height="16" width="14" src="../img/space.gif"><a href="../index.php"><img src="../img/home.gif" width="53" height="16" alt="HOME" border="0"></a><a href="../2/index.html"><img src="../img/himail_net.gif" width="130" height="16" border="0" alt="�Ϥ��᡼�롦net�äơ�"></a><a href="../3/index.html"><img src="../img/promise.gif" width="44" height="16" border="0" alt="����«"></a><a href="../4/index.html"><img src="../img/policy.gif" width="119" height="16" border="0" alt="�ץ饤�Х����ݥꥷ��"></a><a href="../5/index.html"><img src="../img/faq.gif" width="42" height="16" border="0" alt="FAQ"></a><a href="../6/index.php"><img src="../img/use_business.gif" width="108" height="16" border="0" alt="�ӥ��ͥ������Ѥ���"></a><a href="../7/index.html"><img src="../img/use_personal.gif" width="88" height="16" border="0" alt="�Ŀͤ����Ѥ���"></a></td>
			</tr>
		</table><br>
		<br>

<table width="600" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
  <tr>
    <td align="right" valign="top" width="78"><img height="16" width="50" src="../img/space.gif"></td>
    <td align="left" valign="top" colspan="2"><font color="#2f2180"><b>����Ͽ����λ���ޤ�����<br>
      <br>
      </b></font><font size="2">���������Ȥ⤦������<br>
      ���ʤ�����Ͽ�����᡼�륢�ɥ쥹�˳�ǧ�᡼������ꤷ�ޤ��Τǡ�<br>
      �����ˤ���URL�򥯥�å����Ƥ���������<br>
      ɽ�����줿�ڡ�������Ͽ�ܥ���򲡤��ȡ���Ͽ�ϴ�λ���ޤ��� <br>
      ��<br>
      �֤Ϥ��᡼��פ�ڤ��ߤˤ��Ƥ��Ƥ���������<br>
      <br>
      <br>
      <br>
      ������Ͽ��λ�塢��ǧ�᡼�뤬�Ϥ��ޤ����᡼��˵��ܤ��줿�գң̤�<br>
��    ����å����Ƥ���������<font color="red">����Ͽ��³����ԤäƤ���������</font>����Ͽ��³��<br>
��    ��ԤäƤ��������ʤ���硢�ץ����ؤλ��ä�̵���ȤʤäƤ���<br>
��    ���ޤ�������դ���������<br>
      <br>
      <br>
      </font></td>
  </tr>
  <tr height="22" align="left" valign="middle">
    <td height="22" align="right" valign="top" width="78"></td>
    <td height="40" width="226">
      <div align="left"> <a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="�ۡ�������"></a></div>
    </td>
    <td height="40" width="296">
			<form method="post" name="priceloto" action="http://www.priceloto.com/chkck/Clt_Regok.php">
				<input type="hidden" name="sq_num" value="<?=$_SESSION['sq_num']?>">
				<a href="javascript:document.priceloto.submit()"><img src="img/goto_priceloto.gif" width="168" height="48" border="0"></a>
			</form>
    </td>
  </tr>
</table>
	</body>

</html>
