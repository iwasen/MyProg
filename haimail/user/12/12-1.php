<?
include('../inc/common.php');
include('../inc/database.php');

//=== �ᥤ����� ===
if ($id == '')
	redirect('../index.php');

$sql = "SELECT KT_status,KT_mail_adr,KT_pr_id FROM T_KARI_TOUROKU WHERE KT_seq_no=$id";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if ($ds->EOF())
	redirect('../index.php');

//����ץ쥹�б� =====================
$sql = "SELECT PR_baitai_id FROM T_PR WHERE PR_pr_id='" . $ds->GetValue('KT_pr_id') . "'";
$ds2 = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds2->EOF()) {
	if ($ds2->GetValue('PR_baitai_id') == BT_IMPRESS)
		redirect("../impress/12/12-1.php?id=$id");
}
//====================================

switch ($ds->GetValue('KT_status')) {
case '1':	//��Ͽ�Ѥ�
	redirect('12a-2_error.html');
case '2':	//����Ѥ�
	redirect('12b-2_error.html');
}

$sql = "SELECT MM_member_id FROM T_MEMBER WHERE MM_mail_adr=" . SqlStr($ds->GetValue('KT_mail_adr')) . " AND MM_status<>'9'";
$ds = CreateDynaset($sql, ORADYN_READONLY);
if (!$ds->EOF())
		redirect('12a-2_error.html');
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<meta HTTP-EQUIV="Pragma" Content="no-cache">
		<title></title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form method="post" action="12-1_check.php">
		<br>
		<input type="hidden" name="id" value="<?=$id?>">
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36">��<img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36" valign="top"><font color="#2f2180"><b>�֤Ϥ��᡼�롦net�פؤβ���Ͽ�򤢤꤬�Ȥ��������ޤ�����
					<hr>
					</b></font><font size="2"><br>
					������Υڡ������顢����Ͽ�ץܥ���򲡤��Ƥ���������<br>
					����Ͽ�ץܥ���򲡤��Ƥ����������ʳ��ǡ�������Ͽ����λ�������ޤ���<br>
					<br>
					<input type="submit" value="����Ͽ���롡" name="regist"><br>
					<br>
					<br>
					<br>
					�֤Ϥ��᡼�롦net�פ���Ͽ�����Ф��Τʤ����Ǥ��Υ᡼��������ä�����<br>
					������Ǥ������Ρֲ���ץܥ��󤫤顢����Ͽ�������Ƥ���������<br>
					<br>
					<input type="submit" value="���" name="cancel"><br>
					<br>
					<br>
					</font></td>
			</tr>
		</table>
		</form>
	</body>

</html>
