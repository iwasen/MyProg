<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��������ѹ�������������å�
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/regist2.php");
include("$inc/login_log.php");

if (isset($member_id)) {
	if ($member_id == '')
		$msg[] = '�ɣĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

	if ($password == '')
		$msg[] = '�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
} else
	$msg[] = '�����󤷤Ƥ��ʤ������ޤ��ϥ����󤬥����ॢ���Ȥ��ޤ������⤦���٥����󤷤Ƥ���������';

if (!$msg) {
	$sql = "select mb_seq_no,mb_id,mb_password,mb_step"
				. " from t_member where mb_id=" . sql_char($member_id) . " and mb_step<>9";
	$result = db_exec($sql);

	if (pg_numrows($result) == 0)
		$msg[] = 'ID���ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
	else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mb_seq_no != $_SESSION['ss_seq_no'])
			$msg[] = '�ɣĤ��ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
		elseif ($fetch->mb_password != $password)
			$msg[] = '�ѥ���ɤ��ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
		else {
			member_login_log($member_id, $password, $fetch->mb_step, $fetch->mb_seq_no);
			redirect($url != '' ? $url : 'member_change01_1.php');
		}
	}
}

member_login_log($member_id, $password, null, null);
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
					<td><img src="img/nyuukai/title/m_check.gif"></td>
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
					<td><font size="2"><br><?=disp_msg($msg, '��', '<br><br>')?><br></font></td>
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
					<td><a href="member_change01.php"><img width="99" height="40" border="0" alt="���ľ��" src="img/nyuukai/button/naosu.gif"></a></td>
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
