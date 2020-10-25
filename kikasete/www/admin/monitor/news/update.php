<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�᡼��˥塼���ۿ�
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/mail.php");

function test_mail() {
	global $subject, $body;

	// ���������ɥ쥹����
	$fetch = get_system_info('sy_news1_from,sy_news1_name');
	$from = "$fetch->sy_news1_name <$fetch->sy_news1_from>";

	// �����襢�ɥ쥹����
	$sql = "SELECT ad_mail_addr FROM m_admin WHERE ad_admin_id={$_SESSION['admin_login_id']}";
	$to = db_fetch1($sql);

	send_mail($subject, $to, $from, $body);
}

function set_mail_send_num($news_id) {
	db_delete('t_mail_news_send', "mns_news_id=$news_id");

	if ($_POST['send_type'] == 3) {
		$send_count = $_POST['send_count'];

		if ($_POST['send_assign_flag'] == 1)
			$send_num = $_POST['send_assign'];
		else {
			$sql = "SELECT COUNT(*) FROM t_monitor WHERE mn_status=0";
			$send_num = db_fetch1($sql);
		}

		$send_num1 = floor($send_num / $send_count);
		$send_num2 = $send_num1 + ($send_num - $send_num1 * $send_count);

		for ($i = 1; $i <= $send_count; $i++) {
			$rec['mns_news_id'] = sql_number($news_id);
			$rec['mns_seq_no'] = $i;
			$rec['mns_status'] = 0;
			$rec['mns_send_date'] = sql_date2($_POST["send_date_y_$i"], $_POST["send_date_m_$i"], $_POST["send_date_d_$i"], $_POST["send_date_h_$i"]);
			$rec['mns_send_num'] = $i < $send_count ? $send_num1 : $send_num2;
			db_insert('t_mail_news_send', $rec);
		}
	}
}

// �ᥤ�����
set_global('monitor', '��˥�������', '�᡼��˥塼���ۿ�', BACK_TOP);

// ������������
switch ($send_type) {
case 1:	// ¨��
	$send_date = 'null';
	break;
case 2:	// ���ֻ���
	$send_date = sql_date2($send_date_y, $send_date_m, $send_date_d, $send_date_h);
	break;
case 3:	// ʬ������
	$send_date = sql_date2($send_date_y_1, $send_date_m_1, $send_date_d_1, $send_date_h_1);
	break;
}

switch ($next_action) {
case 'new':
	$rec['ns_target'] = 1;
	$rec['ns_subject'] = sql_char($subject);
	$rec['ns_body'] = sql_char($body);
	$rec['ns_send_date'] = $send_date;
	$rec['ns_send_assign'] = sql_number($send_assign_flag == 1 ? $send_assign : '');
	$rec['ns_bunkatsu_flag'] = sql_bool($send_type == 3 ? DBTRUE : DBFALSE);
	db_insert('t_mail_news', $rec);
	$news_id = get_currval('t_mail_news_ns_news_id_seq');

	set_mail_send_num($news_id);

	$msg = '�᡼��˥塼������Ͽ���ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'update':
	$rec['ns_subject'] = sql_char($subject);
	$rec['ns_body'] = sql_char($body);
	$rec['ns_send_date'] = $send_date;
	$rec['ns_send_assign'] = sql_number($send_assign_flag == 1 ? $send_assign : '');
	$rec['ns_bunkatsu_flag'] = sql_bool($send_type == 3 ? DBTRUE : DBFALSE);
	db_update('t_mail_news', $rec, "ns_news_id=$news_id");

	set_mail_send_num($news_id);

	$msg = '�᡼��˥塼���򹹿����ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'delete':
	$sql = "DELETE FROM t_mail_news WHERE ns_news_id=$news_id";
	db_exec($sql);
	$msg = '�᡼��˥塼���������ޤ�����';
	$back = "location.href='list.php'";
	break;
case 'test':
	test_mail();
	$msg = '�ƥ��ȥ᡼����������ޤ�����';
	$back = "history.back()";
	break;
default:
	redirect('list.php');
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
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="����롡" onclick="<?=$back?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
