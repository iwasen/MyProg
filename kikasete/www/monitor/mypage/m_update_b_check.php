<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

// ��Ͽ�Ѥߥ����å�
function check_regist($addr) {
	$sql = "SELECT mn_mail_addr FROM t_monitor WHERE mn_mail_addr_low=". sql_char(strtolower($addr)) . " AND mn_status<>9 AND mn_monitor_id<>{$_SESSION['ss_monitor_id']}";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('m_update_b.php');
$reg = &$_SESSION['ss_regist'];

// �ե����फ��ǡ�������
$reg->name1 = get_name($name1);
$reg->name2 = get_name($name2);
$reg->name1_kana = get_name_kana($name1_kana);
$reg->name2_kana = get_name_kana($name2_kana);
$reg->birthday_y = get_birthday($birthday_y);
$reg->birthday_m = get_birthday($birthday_m);
$reg->birthday_d = get_birthday($birthday_d);
$reg->sex = $sex;
$reg->mikikon = $mikikon;
$reg->mail_addr = get_mail_addr($mail_addr);
$reg->mail_addr2 = get_mail_addr($mail_addr2);
$reg->password = get_password($password);
$reg->password2 = get_password($password2);
$reg->html_mail = $html_mail;
$reg->haimail_flag = $haimail_flag;
$reg->jitaku_zip1 = get_zip($jitaku_zip1);
$reg->jitaku_zip2 = get_zip($jitaku_zip2);
$reg->jitaku_area = $jitaku_area;
$reg->shokugyou_cd = $shokugyou_cd;
$reg->gyoushu_cd = $gyoushu_cd;
$reg->shokushu_cd = $shokushu_cd;
$reg->kinmu_zip1 = get_zip($kinmu_zip1);
$reg->kinmu_zip2 = get_zip($kinmu_zip2);
$reg->kinmu_area = $kinmu_area;

// ���ƥ����å�
if ($reg->name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->name1_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_kana($reg->name1_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ����ϥ������ʤ����Ϥ��Ƥ���������';

if ($reg->name2_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_kana($reg->name2_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�ϥ������ʤ����Ϥ��Ƥ���������';

$birthday_ok = true;
if ($reg->birthday_y == '') {
	$msg[] = '��ǯ������ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_y, 1900, date('Y'))) {
	$msg[] = '��ǯ������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($reg->birthday_m == '') {
	$msg[] = '��ǯ�����η���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_m, 1, 12)) {
	$msg[] = '��ǯ�����η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($reg->birthday_d == ''){
	$msg[] = '��ǯ�������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_d, 1, 31)) {
	$msg[] = '��ǯ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($birthday_ok && !checkdate($reg->birthday_m, $reg->birthday_d, $reg->birthday_y))
	$msg[] = '��ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->mikikon == '')
	$msg[] = '̤���������򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->sex == '')
	$msg[] = '���̤����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->mail_addr == '')
	$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mail_addr2 == '')
	$msg[] = '�᡼�륢�ɥ쥹��ǧ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->mail_addr != $reg->mail_addr2)
	$msg[] = '�᡼�륢�ɥ쥹�ȥ᡼�륢�ɥ쥹��ǧ�����פ��Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($reg->mail_addr))
	$msg[] = '�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (check_regist($reg->mail_addr))
	$msg[] = '���Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���';

if ($reg->password == '')
	$msg[] = '�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->password2 == '')
	$msg[] = '�ѥ���ɳ�ǧ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->password != $reg->password2)
	$msg[] = '�ѥ���ɤȥѥ���ɳ�ǧ�����פ��Ƥ��ʤ��褦�Ǥ���';
elseif (strlen($reg->password) < 6)
	$msg[] = '�ѥ���ɤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($reg->password) > 20)
	$msg[] = '�ѥ���ɤϣ���ʸ����������Ϥ��Ƥ���������';
elseif (!check_password($reg->password))
	$msg[] = '�ѥ���ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������';

if ($reg->html_mail == '')
	$msg[] = 'HTML�᡼����������򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->haimail_flag == '')
	$msg[] = '�Ϥ��᡼����������򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->jitaku_zip1 == '' || $reg->jitaku_zip2 == '')
	$msg[] = '͹���ֹ�ʼ���ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_zip($reg->jitaku_zip1, $reg->jitaku_zip2))
	$msg[] = '͹���ֹ�ʼ���ˤ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
else
	$jitaku_zip_ok = true;

if ($reg->jitaku_area == '')
	$msg[] = '�ｻ�ϰ�ʼ���ˤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
else
	$jitaku_area_ok = true;

if ($jitaku_zip_ok && $jitaku_area_ok && !check_zip_area($reg->jitaku_zip1 . $reg->jitaku_zip2, $reg->jitaku_area))
	$msg[] = "͹���ֹ�ʼ���ˤȵｻ�ϰ�ʼ���ˤ����פ��Ƥ��ʤ��褦�Ǥ���";

if ($reg->shokugyou_cd == '')
	$msg[] = '���Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->gyoushu_cd == '')
	$msg[] = '�ȼ郎���򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->shokushu_cd == '')
	$msg[] = '���郎���򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->kinmu_zip1 != '' && $reg->kinmu_zip2 != '' && $reg->kinmu_area != '' && !check_zip_area($reg->kinmu_zip1 . $reg->kinmu_zip2, $reg->kinmu_area))
	$msg[] = '͹���ֹ���̶С��̳���ˤ��̶С��̳����ϰ褬���פ��Ƥ��ʤ��褦�Ǥ���';

if (!$msg)
	redirect('m_confirm_b.php');
?>
<? monitor_menu(); ?>

<form name="form1" method="post" action="regist2_check.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�����ץ�ե�����</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
			<? error_msg($msg) ?>
		</td>
	</tr>
</table>
</form>
</body>
</html>
