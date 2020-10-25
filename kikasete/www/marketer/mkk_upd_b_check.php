<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mk_regist.php");
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ��Ͽ�Ѥߥ����å�
function check_regist($addr) {
	$sql = "SELECT mr_mail_addr FROM t_marketer WHERE mr_mail_addr_low=". sql_char(strtolower($addr)) . " AND mr_status<>9 AND mr_marketer_id<>{$_SESSION['ss_marketer_id']}";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('mkk_upd_b.php');
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
$reg->mail_addr = get_mail_addr($mail_addr);
$reg->mail_addr2 = get_mail_addr($mail_addr);
$reg->password = get_password($password);
$reg->password2 = get_password($password2);
$reg->shokugyou_cd = $shokugyou_cd;
$reg->gyoushu_cd = $gyoushu_cd;
$reg->shokushu_cd = $shokushu_cd;

// ���ϥ����å�
if ($reg->name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ��Ƥ���������';

if ($reg->name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ��Ƥ���������';

if ($reg->name1_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ��������Ϥ��Ƥ���������';
elseif (!check_kana($reg->name1_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ����ϥ������ʤ����Ϥ��Ƥ���������';

if ($reg->name2_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�����Ϥ��Ƥ���������';
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

if ($reg->sex == '')
	$msg[] = '���̤����򤷤Ƥ���������';

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
	$msg[] = '�ѥ���ɤ����Ϥ��Ƥ���������';
elseif ($reg->password2 == '')
	$msg[] = '�ѥ���ɳ�ǧ�����Ϥ��Ƥ���������';
elseif ($reg->password != $reg->password2)
	$msg[] = '�ѥ���ɤȥѥ���ɳ�ǧ�����פ��Ƥ��ޤ���';
elseif (strlen($reg->password) < 6)
	$msg[] = '�ѥ���ɤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($reg->password) > 20)
	$msg[] = '�ѥ���ɤϣ���ʸ����������Ϥ��Ƥ���������';
elseif (!check_password($reg->password))
	$msg[] = '�ѥ���ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������';

if ($reg->shokugyou_cd == '')
	$msg[] = '���Ȥ����򤷤Ƥ���������';

if ($reg->gyoushu_cd == '')
	$msg[] = '�ȼ�����򤷤Ƥ���������';

if ($reg->shokushu_cd == '')
	$msg[] = '��������򤷤Ƥ���������';

if (!$msg)
	redirect('mkk_upd_b2.php');
?>
<? marketer_header('��Ͽ����ι���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		
    <td valign="top"><img src="images/t_mkk_upd_b.gif" width="538" height="20"><br>
      <br></td>
	</tr>
</table>


<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td><?
$hidden[] = '<input type="hidden" name="cont" value="1">';
error_msg($msg, 'mkk_upd_b.php', $hidden);
?></td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
