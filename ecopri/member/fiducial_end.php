<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���ϥǡ���ɽ��
'******************************************************/
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/regist3.php");

// ��Ͽ���դ��᡼������
function mail_send($fetch) {
	get_mail_template('regist', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%NAME1%', $fetch->mb_name1, $body);
	$body = str_replace('%NAME2%', $fetch->mb_name2, $body);
	send_mail($subject, $fetch->mb_mail_addr, $from, $body, $cc, $bcc, $reply_to);
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist3']))
	redirect('top.php');
$reg = &$_SESSION['ss_regist3'];

// �ǡ�����¸
$reg->save_db($_SESSION['ss_seq_no']);

// �᡼������
$sql = "select mb_id,mb_mail_addr,mb_name1, mb_name2 from t_member where mb_seq_no=" . sql_number($_SESSION['ss_seq_no']);
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	mail_send($fetch);

	// main�ڡ�����̾�����å�
	$_SESSION['ss_name1'] = $fetch->mb_name1;
}
// ���å�����ѿ����
unset($_SESSION['ss_regist3']);
session_unregister('ss_regist3');

$_SESSION['ss_member_step'] = 3;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������Eco���������Ȥ�Eco���������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" height ="90%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/f_end01.gif" width="295" height="38" alt="���꤬���ƴ�λ���ޤ���"></td>
				</tr>
			</table>
		</td>
		<td width="35" align="right" valign="top">
		<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>

	<tr>
		<td><br></td>
		<td align="center" valign="top">
		<hr width="95%" size="1">
		<br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="500"><font size=2>����ǡ�<font color="#FF0000">���Ƥι��ܤ����ϤϽ�λ�Ǥ�</font>������줵�ޤǤ�����<br>
					�ѹ����ब������ϡ��ᥤ���˥塼��<font color="#0000FF">��������ѹ�</font>����ԤäƤ���������</font></td>
				</tr>
			</table>
			<br>
			<table width="520" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					<div align="center"><a href="season.php"><img src="img/nyuukai/youkoso.gif" width="644" height="476" alt="�褦����" border="0"></a>
					</div>
					</td>
				</tr>
			</table>
			<br>
			<table width="500" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><font size=2>
					</td>
				</tr>
			</table>
			<hr width="95%" size="1"></td>
		<td><br></td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom">
		<img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><a href="season.php"><img src="img/nyuukai/button/member_main01.gif" width="143" height="40" alt="�ᥤ���" border="0"></a></td>
					</tr>
					<tr>
					<td height="7"><img src="img/spacer.gif" width="99" height="7"></td>
				</tr>
			</table></td>
		<td width="35" height="35" align="right" valign="bottom">
		<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>