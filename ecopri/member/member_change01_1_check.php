<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��������ѹ���ID,Password�����å�
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_change01.php');
$reg = &$_SESSION['ss_regist2'];

// ��Ͽ�Ѥߥ����å�
function check_regist($addr) {
	$sql = "SELECT mb_mail_addr FROM t_member WHERE mb_mail_addr=". sql_char(strtolower($addr)) . " AND mb_step<>9";
	$result = db_exec($sql);
	return pg_numrows($result);
}
// ID��ʣ�����å�
function check_mb_id($id) {
	$sql = "SELECT mb_id FROM t_member WHERE mb_id='$id' AND mb_step<>9";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// �ե����फ��ǡ��������������å�
$reg->mb_id = $mb_id;
$reg->mb_mail_addr = get_mail_addr($mb_mail_addr);
$reg->mb_password = get_password($mb_password);
$reg->mb_password2 = get_password($mb_password2);

if ($reg->mb_id == '')
	$msg[] = '�ɣĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (strlen($reg->mb_id) < 4)
	$msg[] = '�ɣĤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($reg->mb_id) > 20)
	$msg[] = '�ɣĤϣ���ʸ����������Ϥ��Ƥ���������';
elseif (!check_id($reg->mb_id))
	$msg[] = '�ɣĤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������';
elseif ($reg->mb_id_org != $reg->mb_id && check_mb_id($reg->mb_id))
	$msg[] = '���Ϥ��줿�ɣĤϤ��Ǥ���Ͽ����Ƥ���褦�Ǥ���';

if ($reg->mb_mail_addr == '')
	$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($reg->mb_mail_addr))
	$msg[] = '�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mb_mail_addr_org != $reg->mb_mail_addr && check_regist($reg->mb_mail_addr))
	$msg[] = '���Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���';

if ($reg->mb_password == '')
	$msg[] = '�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mb_password2 == '')
	$msg[] = '�ѥ���ɳ�ǧ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mb_password != $reg->mb_password2)
	$msg[] = '�ѥ���ɤȥѥ���ɳ�ǧ�����פ��Ƥ��ʤ��褦�Ǥ���';
elseif (strlen($reg->mb_password) < 4)
	$msg[] = '�ѥ���ɤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($reg->mb_password) > 20)
	$msg[] = '�ѥ���ɤϣ���ʸ����������Ϥ��Ƥ���������';
elseif (!check_password($reg->mb_password))
	$msg[] = '�ѥ���ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������';

if (!$msg) {
	if ($reg->back_page)
		redirect("$reg->back_page");
	else
		redirect('member_change02.php');
}

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="img/nyuukai/title/m_input01.gif" width="214" height="38" alt="���ä�����Ͽ���褦"></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
			<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>

		<td align="center" valign="top">
			<br><br><br><br>
			<img src="img/nyuukai/zou_img/m_error.gif" width="162" height="124" alt="���졩">
			<br>
			<table border="0" width="350" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
				<td>
					<font size="2"><br><?=disp_msg($msg, '��', '<br><br>')?><br></font>
				</td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
			<br>
		</td>
		<td><br></td>
	</tr>
	<tr>

		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><input type="image" width="99" height="40" border="0" alt="���ľ��" src="img/nyuukai/button/naosu.gif" onclick="history.back()"></td>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
				</tr>
			</table>
		</td>
		<td width="35" height="35" align="right" valign="bottom">
			<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>