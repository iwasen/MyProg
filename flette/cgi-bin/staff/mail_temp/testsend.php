#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�����åեڡ���
' Content:�����᡼��ƥ�ץ졼�ȥƥ�����������
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
include("$inc/decode.php");
include("$inc/reserve.php");
include("$inc/macro.php");
include("$inc/mail.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

// �ƥ��������ǡ������å�
function set_test_data(&$reserve) {
	global $s_staff_id;

	$time = time();

	$reserve->course_id = 1;
	$reserve->seq = '****';
	$reserve->date = date('Y/m/d H:i:s', $time);
	$reserve->course = '��������';
	$reserve->shop = '����Ź';
	$reserve->choice[0]->room = '��������������';
	$reserve->choice[0]->date = date('Y/m/d', $time + 24*60*60);
	$reserve->choice[0]->start_time = '10:00';
	$reserve->choice[0]->end_time = '11:00';
	$reserve->choice[0]->sex = 2;
	$reserve->choice[1]->room = '��������������';
	$reserve->choice[1]->date = date('Y/m/d', $time + 24*60*60 * 2);
	$reserve->choice[1]->start_time = '11:00';
	$reserve->choice[1]->end_time = '12:00';
	$reserve->choice[1]->sex = 1;
	$reserve->choice[2]->room = '��������������';
	$reserve->choice[2]->date = date('Y/m/d', $time + 24*60*60 * 2);
	$reserve->choice[2]->start_time = '14:00';
	$reserve->choice[2]->end_time = '15:30';
	$reserve->choice[2]->sex = 3;

	// �����åդ�̾���ȥ᡼�륢�ɥ쥹����
	$sql = "SELECT st_name1,st_name2,st_name1_kana,st_name2_kana,st_mail_addr"
			. " FROM m_staff"
			. " WHERE st_staff_id=$s_staff_id";
	$result = db_exec($sql);
	if (pg_numrows($result) != 0) {
		$fetch = pg_fetch_object($result, 0);
		$reserve->name_1 = $fetch->st_name1;
		$reserve->name_2 = $fetch->st_name2;
		$reserve->kana_1 = $fetch->st_name1_kana;
		$reserve->kana_2 = $fetch->st_name2_kana;
		$reserve->mail_addr = $fetch->st_mail_addr;
	}

	for ($i = 0; $i < 4; $i++)
		$reserve->contact_flag[$i] = true;
	$reserve->contact_mail = $reserve->mail_addr;
	$reserve->contact_keitai = '***-****-****';
	$reserve->contact_jitaku = '**-****-****';

	$reserve->contact_time = 5;
	$reserve->contact_time_memo = '����٤����֡�22�����ˤ��˾���ޤ��� ';

//	$reserve->birthday = '****/**/**';

	// ��������
	$reserve->counseling_id = array();

	$reserve->change = 1;
	$reserve->comment = rtrim(str_repeat(str_repeat('Z', 35) . "\n", 4));
}

// �ƥ��ȥǡ������å�
$reserve = new reserve_class;
set_test_data($reserve);

// �������ߥǡ�������
$macro_ary = get_macro($reserve);

// �䤤��碌�Ѻ����ޥ���
$macro_ary['INQUIRY_SUBJECT'] = 'FLETTE���䤤��碌';
$macro_ary['INQUIRY_CONTENT'] = '�䤤��碌��ʸ';
$macro_ary['INQUIRY_NAME'] = '';

// �������ߥޥ����ִ�
replace_macro($subject, $macro_ary);
replace_macro($from_name, $macro_ary);
replace_macro($from_addr, $macro_ary);
replace_macro($to_addr, $macro_ary);
replace_macro($cc_addr, $macro_ary);
replace_macro($bcc_addr, $macro_ary);
replace_macro($body, $macro_ary);

send_mail($subject, $reserve->mail_addr, $from_addr, $from_name, $body/*, $cc_addr, $bcc_addr*/);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('�����᡼��ƥ�ץ졼�ȥƥ�������', true, true) ?>

<div align="center">
<p class="msg">�����᡼��ƥ�ץ졼�Ȥ�ƥ����������ޤ�����</p>
<p><input type="button" id="ok" value="����롡" onclick="history.back()"></p>
</div>

<? page_footer() ?>

</body>
</html>
