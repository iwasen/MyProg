<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:ͭ���ޡ�������������Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/get_form.php");
include("$inc/check.php");

// ��Ͽ�Ѥߥ����å�
function check_regist($addr) {
	$sql = "SELECT mr_marketer_id FROM t_marketer WHERE mr_mail_addr_low=". sql_char(strtolower($addr)) . " AND mr_status<>9 AND mr_type<>1";
	$result = db_exec($sql);
	return pg_numrows($result);
}

// ��˾�᡼�륢���������Ͽ
function choice_email($marketer_id, $choice_no, $account) {
	if ($account != '') {
		$sql = sprintf("INSERT INTO w_choice_email (we_member_id,we_member_kind,we_choice_no,we_account) VALUES (%s,2,%s,%s)",
				sql_number($marketer_id),
				sql_number($choice_no),
				sql_char($account));
		db_exec($sql);
	}
}

set_global('marketer', '�ޡ�����������', 'ͭ���ޡ�������������Ͽ', BACK_TOP);

// �ե����फ��ǡ�������
$name1 = get_name($name1);
$name2 = get_name($name2);
$name1_kana = get_name_kana($name1_kana);
$name2_kana = get_name_kana($name2_kana);
$birthday_y = get_birthday($birthday_y);
$birthday_m = get_birthday($birthday_m);
$birthday_d = get_birthday($birthday_d);
$mail_addr = get_mail_addr($mail_addr);
$password = get_password($password);
$kinmu_name = trim($kinmu_name);
$kinmu_unit = trim($kinmu_unit);
$kinmu_post = trim($kinmu_post);
$kinmu_zip1 = get_zip($kinmu_zip1);
$kinmu_zip2 = get_zip($kinmu_zip2);
$kinmu_addr1 = trim($kinmu_addr1);
$kinmu_addr2 = trim($kinmu_addr2);
$kinmu_addr3 = trim($kinmu_addr3);
$kinmu_tel = strip_blank($kinmu_tel);
$kinmu_fax = strip_blank($kinmu_fax);
//$choice_email1 = strip_blank(mb_convert_kana($choice_email1, 'as'));

// ���ϥ����å�
if ($name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ��Ƥ���������';

if ($name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ��Ƥ���������';

if ($name1_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ��������Ϥ��Ƥ���������';
elseif (!check_kana($name1_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ����ϥ������ʤ����Ϥ��Ƥ���������';

if ($name2_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�����Ϥ��Ƥ���������';
elseif (!check_kana($name2_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�ϥ������ʤ����Ϥ��Ƥ���������';

$birthday_ok = true;
if ($birthday_y == '') {
	$msg[] = '��ǯ������ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($birthday_y, 1900, date('Y'))) {
	$msg[] = '��ǯ������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($birthday_m == '') {
	$msg[] = '��ǯ�����η���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($birthday_m, 1, 12)) {
	$msg[] = '��ǯ�����η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($birthday_d == ''){
	$msg[] = '��ǯ�������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($birthday_d, 1, 31)) {
	$msg[] = '��ǯ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($birthday_ok && !checkdate($birthday_m, $birthday_d, $birthday_y))
	$msg[] = '��ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($sex == '')
	$msg[] = '���̤����򤷤Ƥ���������';

if ($mail_addr == '')
	$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($mail_addr))
	$msg[] = '�᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (check_regist($mail_addr))
	$msg[] = '���Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ����Ƥ���褦�Ǥ���';

if ($password == '')
	$msg[] = '�ѥ���ɤ����Ϥ��Ƥ���������';
elseif (strlen($password) < 6)
	$msg[] = '�ѥ���ɤϣ�ʸ���ʾ�����Ϥ��Ƥ���������';
elseif (strlen($password) > 20)
	$msg[] = '�ѥ���ɤϣ���ʸ����������Ϥ��Ƥ���������';
elseif (!check_password($password))
	$msg[] = '�ѥ���ɤ�Ⱦ�ѱѿ��������Ϥ��Ƥ���������';

if ($shokugyou_cd == '')
	$msg[] = '���Ȥ����򤷤Ƥ���������';

if ($gyoushu_cd == '')
	$msg[] = '�ȼ�����򤷤Ƥ���������';

if ($shokushu_cd == '')
	$msg[] = '��������򤷤Ƥ���������';

if ($kinmu_name == '')
	$msg[] = '���̾�����Ϥ��Ƥ���������';

if ($kinmu_zip1 == '' || $kinmu_zip2 == '')
	$msg[] = '��̳��͹���ֹ�����Ϥ��Ƥ���������';
elseif (!check_zip($kinmu_zip1, $kinmu_zip2))
	$msg[] = '��̳��͹���ֹ椬���������Ϥ���Ƥ��ޤ���';
else
	$kinmu_zip_ok = true;

if ($kinmu_area == '')
	$msg[] = '��̳�轻�����ƻ�ܸ��ˤ����Ϥ��Ƥ���������';
else
	$kinmu_area_ok = true;

if ($kinmu_zip_ok && $kinmu_area_ok && !check_zip_area($kinmu_zip1 . $kinmu_zip2, $kinmu_area))
	$msg[] = '��̳��͹���ֹ�ȶ�̳�轻�����ƻ�ܸ��ˤ����פ��Ƥ��ޤ���';

if ($kinmu_addr1 == '')
	$msg[] = '��̳�轻��ʻԶ�ˤ����Ϥ��Ƥ���������';

if ($kinmu_addr2 == '')
	$msg[] = '��̳�轻���Į¼�ˤ����Ϥ��Ƥ���������';

if ($kinmu_tel == '')
	$msg[] = '��̳�������ֹ�����Ϥ��Ƥ���������';
/*
if ($choice_email1 == '')
	$msg[] = '��˾�᡼�륢������Ȥ����Ϥ��Ƥ���������';
elseif (strlen($choice_email1) < 4)
	$msg[] = '��˾���������̾�ϣ���ʾ�����Ϥ��Ƥ���������';
elseif (!check_hankaku($choice_email1))
	$msg[] = '��˾���������̾��Ⱦ�ѱѿ��������Ϥ��Ƥ�������';
*/
if (!$msg) {
	db_begin_trans();

	$sql = sprintf("INSERT INTO t_marketer (mr_type,mr_name1,mr_name2,mr_name1_kana,mr_name2_kana,mr_birthday,mr_sex,mr_mail_addr,mr_mail_addr_low,mr_password,mr_shokugyou_cd,mr_gyoushu_cd,mr_shokushu_cd,mr_kinmu_zip,mr_kinmu_area,mr_kinmu_addr1,mr_kinmu_addr2,mr_kinmu_addr3,mr_kinmu_tel,mr_kinmu_fax,mr_kinmu_name,mr_kinmu_unit,mr_kinmu_post,mr_agent_id,mr_permission,mr_regist_type) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
				sql_number(3),
				sql_char($name1),
				sql_char($name2),
				sql_char($name1_kana),
				sql_char($name2_kana),
				sql_date("$birthday_y/$birthday_m/$birthday_d"),
				sql_number($sex),
				sql_char($mail_addr),
				sql_char(strtolower($mail_addr)),
				sql_char($password),
				sql_number($shokugyou_cd),
				sql_number($gyoushu_cd),
				sql_number($shokushu_cd),
				sql_char($kinmu_zip1 . $kinmu_zip2),
				sql_number($kinmu_area),
				sql_char($kinmu_addr1),
				sql_char($kinmu_addr2),
				sql_char($kinmu_addr3),
				sql_char($kinmu_tel),
				sql_char($kinmu_fax),
				sql_char($kinmu_name),
				sql_char($kinmu_unit),
				sql_char($kinmu_post),
				sql_number($agent_id),
				sql_char(PMR_DEF_MARKETER),
				sql_number(1));
	db_exec($sql);

	$marketer_id = get_currval('t_marketer_mr_marketer_id_seq');
//	choice_email($marketer_id, 1, $choice_email1);

	db_commit_trans();
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<?
if (!$msg) {
?>
<p class="msg">ͭ���ޡ�����������Ͽ���ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="location.href='<?=$top?>/menu.php'"></p>
<?
} else {
?>
<p>
<table border=0 cellspacing=3 cellpadding=0>
	<?=disp_msg($msg, '<tr><td class="msg">��', '</td></tr>')?>
</table>
</p>
<p><input type="button" id="ok" value="����롡" onclick="history.back()"></p>
<?
}
?>
</div>

<? page_footer() ?>
</body>
</html>
