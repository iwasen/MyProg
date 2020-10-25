<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:�����������My�ڡ���������Ͽɽ��
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/check.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/get_form.php");

//== �ᥤ����� ==
set_global('My�ڡ���������Ͽ','');

//���ϥ����å�
$name1 = get_name($name1);
if ($name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';

$name2 = get_name($name2);
if ($name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

$name1_kana = get_name_kana($name1_kana);
if ($name1_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_kana($name1_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ����ϥ������ʤ����Ϥ��Ƥ���������';

$name2_kana = get_name_kana($name2_kana);
if ($name2_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_kana($name2_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�ϥ������ʤ����Ϥ��Ƥ���������';

$mail_addr = get_mail_addr($mail_addr);
if (!check_mail_addr($mail_addr))
	$msg[] = "�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���";

$password = get_password($password);
if (strlen($password) < 6)
	$msg[] = '�ѥ���ɤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($password) > 20)
	$msg[] = '�ѥ���ɤϣ���ʸ����������Ϥ��Ƥ���������';
elseif (!check_password($password))
	$msg[] = '�ѥ���ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������';

$birthday_ok = true;
$birthday_y = get_birthday($birthday_y);
if ($birthday_y == '') {
	$msg[] = '��ǯ������ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($birthday_y, 1900, date('Y'))) {
	$msg[] = '��ǯ������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

$birthday_m = get_birthday($birthday_m);
if ($birthday_m == '') {
	$msg[] = '��ǯ�����η���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($birthday_m, 1, 12)) {
	$msg[] = '��ǯ�����η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

$birthday_d = get_birthday($birthday_d);
if ($birthday_d == ''){
	$msg[] = '��ǯ�������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($birthday_d, 1, 31)) {
	$msg[] = '��ǯ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($birthday_ok && !checkdate($birthday_m, $birthday_d, $birthday_y))
	$msg[] = '��ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';

$back = 'history.back()';

//����Ͽ������¸
if (!$msg) {
	$sql = "SELECT ag_agent_id FROM t_agent WHERE ag_status != 9 AND ag_mail_addr=".sql_char($mail_addr);
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		db_begin_trans();
		$sql = "INSERT INTO t_agent (ag_status,ag_type,ag_name1,ag_name2,ag_name1_kana,ag_name2_kana,ag_birthday,ag_sex,ag_mail_addr,ag_mail_addr_low,ag_password,ag_kinmu_name,ag_kinmu_unit,ag_kinmu_post,ag_agent_unit,ag_self_introduction,ag_disp_flag,ag_bcc_addr,ag_signature,ag_agent_disc) VALUES (1,1,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		$sql = sprintf($sql,
			sql_char($name1),
			sql_char($name2),
			sql_char($name1_kana),
			sql_char($name2_kana),
			sql_date2($birthday_y,$birthday_m,$birthday_d),
			sql_number($sex),
			sql_char($mail_addr),
			sql_char(strtolower($mail_addr)),
			sql_char($password),
			sql_char($kinmu_name),
			sql_char($kinmu_unit),
			sql_char($kinmu_post),
			sql_char($agent_unit),
			sql_char($self_introduction),
			sql_bool($disp_flag),
			sql_char($bcc_addr),
			sql_char($signature),
			sql_number($agent_disc));
		db_exec($sql);

		//��Ͽ�����դ��᡼������
		$reg_ag_id = get_currval('t_agent_ag_agent_id_seq');
		get_mail_template('reg_ag1', $subject, $from, $cc, $bcc, $repty_to, $body);
		$fetch = get_system_info('sy_url_agant');
		$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
		$body = str_replace('%REGIST_URL%', "{$fetch->sy_url_agant}regi-login.php?key=" . encrypt_number($reg_ag_id, 8), $body);
		send_mail($subject, $mail_addr, $from, $body, $cc, $bcc, $reply_to);

		db_commit_trans();
		$back = "location.href='../index.php'";
	} else {
		$msg[] = '���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ���';
		$back = 'history.back()';
	}
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>

<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table align="center" width=100%>
	<tr>
		<td>
			<table align="center" width=700>
				<tr><td align="center" class="m4"><b>������Ͽ</b></td></tr>
				<tr>
					<td align="center"><br><br>
<?
if (!$msg) {
?>
						<p class="msg">��Ͽ������դ��ޤ�����</p>
						<table align="center" bgcolor=#eaeaff border=1 cellspacing=0 cellpadding=0 width=400>
							<tr>
								<td><font size=-1><br><b>����Ͽ��ǧ�᡼��ۤ����餻�Ƥ��������ޤ�����<br><br>���᡼��򤴳�ǧ�ξ塢�᡼��˵��ܤ��줿�գң̤򥯥�å�����<br>����Ͽ��³����λ�����Ƥ���������</b><br><br>���� ���μ�³����λ���ޤ���ȡ���Ͽ���줿���ȤˤϤʤ�ޤ���Τ�<br>����������ղ�������<br><br>
								</font>
								</td>
							</tr>
						</table>
<?
} else {
	echo '<table border=0 cellspacing=3 cellpadding=0>';
	disp_msg($msg, '<tr><td class="msg">��', '</td></tr>');
	echo '</table>';
}
?>
						<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</div>

<? page_footer() ?>

</body>
</html>
