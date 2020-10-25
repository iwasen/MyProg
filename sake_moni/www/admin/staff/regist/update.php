<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�����å���Ͽ
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ��Ͽ�Ѥߥ����å�
function check_regist($addr) {
	$sql = "SELECT st_staff_id FROM t_staff WHERE st_mail_addr=". sql_char(strtolower($addr)) . " AND st_status<>9";
	$result = db_exec($sql);
	return pg_num_rows($result);
}

set_global('staff', '�����åմ���', '�����å���Ͽ', BACK_TOP);

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

// ���ϥ����å�
if ($name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ��Ƥ���������';

if ($name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ��Ƥ���������';

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

if (!$msg) {
	db_begin_trans();

	$sql = sprintf("INSERT INTO t_staff (st_name1,st_name2,st_name1_kana,st_name2_kana,st_birthday,st_sex,st_mail_addr,st_password,st_kinmu_zip,st_kinmu_area,st_kinmu_addr1,st_kinmu_addr2,st_kinmu_addr3,st_kinmu_tel,st_kinmu_fax,st_kinmu_name,st_kinmu_unit,st_kinmu_post) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
				sql_char($name1),
				sql_char($name2),
				sql_char($name1_kana),
				sql_char($name2_kana),
				sql_date("$birthday_y/$birthday_m/$birthday_d"),
				sql_number($sex),
				sql_char($mail_addr),
				sql_char($password),
				sql_char($kinmu_zip1 . $kinmu_zip2),
				sql_number($kinmu_area),
				sql_char($kinmu_addr1),
				sql_char($kinmu_addr2),
				sql_char($kinmu_addr3),
				sql_char($kinmu_tel),
				sql_char($kinmu_fax),
				sql_char($kinmu_name),
				sql_char($kinmu_unit),
				sql_char($kinmu_post));
	db_exec($sql);

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
<p class="msg">�����åդ���Ͽ���ޤ�����</p>
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
