<?
/******************************************************
' System :Eco-footprint ����
' Content:���ۿ��᡼����������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// ���ۿ��᡼����������

// ������ǽ��¥�᡼�����
$sql = "SELECT am_seq_no,am_target,am_subject,am_body,am_send_date" .
			" FROM t_all_mail" .
			" WHERE am_status=0 AND (am_send_date IS NULL OR am_send_date<CURRENT_TIMESTAMP)";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if (!$nrow)
	exit;

$fetch = pg_fetch_object($result, 0);
$am_id = $fetch->am_seq_no;
$am_target = $fetch->am_target;
$send_num = 0;
$mail_header = "X-Ecofootprint: $am_id";

// ���������ɥ쥹����
$fetch2 = get_system_info('sy_batch_month,sy_mail_from,sy_mail_name');
$batch_month = $fetch2->sy_batch_month;
$from = $fetch2->sy_mail_from;
$personal = $fetch2->sy_mail_name;

// �оݲ��
if ($am_target == 4)
	$where = '';
else
	$where = "WHERE mb_step=$am_target";

// �᡼�������ơ��֥�˥��å�
$send_mail_id = send_mail_data($fetch->am_subject, $from, '', $fetch->am_body, $mail_header, $fetch->am_send_date, $personal);

$sql = "SELECT mb_mail_addr,mb_name1,mb_name2 FROM t_member $where";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$sub_seq = $i + 1;
	send_mail_list($send_mail_id, $fetch->mb_mail_addr, $sub_seq);
	send_mail_embed($send_mail_id, $sub_seq, '%MEMBER_NAME%', "$fetch->mb_name1 $fetch->mb_name2");

	$send_num++;
}

// ���ơ�������������
send_mail_ok($send_mail_id);

// ���ۿ��᡼����ۿ��Ѥߤ˥��å�
$sql = "UPDATE t_all_mail SET am_status=1,am_send_date=CURRENT_TIMESTAMP,am_send_num=$send_num WHERE am_seq_no=$am_id";
db_exec($sql);

// ���顼�����å�
$err_msg = db_errormessage();
if ($err_msg) {
	db_rollback();

	// �Хå����񤭹��ߡʥ��顼��
	$desc = "���ۿ��᡼�����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
	batch_log(9, FILE_NAME, $desc);
	exit;
} else {
	// �Хå����񤭹��ߡ������
	if ($send_num > 0) {
		$desc = "���ۿ��᡼��(ID=$am_id)��{$send_num}���������ޤ�����";
		batch_log(0, FILE_NAME, $desc);
	}
}

db_commit_trans();
?>