<?
/******************************************************
' System :Eco-footprint ����
' Content:��λ��¥�᡼����������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/batch_log.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// ��λ��¥�᡼����������

// ������ǽ��¥�᡼�����
$sql = "SELECT cm_seq_no,cm_subject,cm_body,cm_send_date" .
			" FROM t_close_mail" .
			" WHERE cm_status=0 AND (cm_send_date IS NULL OR cm_send_date<CURRENT_TIMESTAMP)";
$result = db_exec($sql);
$nrow = pg_numrows($result);
if (!$nrow)
	exit();

$fetch = pg_fetch_object($result, 0);
$cm_id = $fetch->cm_seq_no;
$send_num = 0;
$mail_header = "X-Ecofootprint: $cm_id";

// ���������ɥ쥹����
$fetch2 = get_system_info('sy_batch_month,sy_mail_from,sy_mail_name');
$batch_month = $fetch2->sy_batch_month;
$from = $fetch2->sy_mail_from;
$personal = $fetch2->sy_mail_name;

// �᡼�������ơ��֥�˥��å�
$send_mail_id = send_mail_data($fetch->cm_subject, $from, '', $fetch->cm_body, $mail_header, $fetch->cm_send_date, $personal);

$sql = "SELECT mb_mail_addr,mb_name1,mb_name2 FROM t_member LEFT JOIN t_base_data ON mb_seq_no=bd_mb_seq_no AND"
	. " bd_month='$batch_month' WHERE mb_step=3 AND (bd_commit_flag<>1 OR bd_commit_flag IS NULL)";
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

// ��λ��¥�᡼����ۿ��Ѥߤ˥��å�
$sql = "UPDATE t_close_mail SET cm_status=1,cm_send_date=CURRENT_TIMESTAMP,cm_send_num=$send_num WHERE cm_seq_no=$cm_id";
db_exec($sql);

// ���顼�����å�
$err_msg = db_errormessage();
if ($err_msg) {
	db_rollback();

	// �Хå����񤭹��ߡʥ��顼��
	$desc = "��λ��¥�᡼�����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
	batch_log(9, FILE_NAME, $desc);
	exit;
} else {
	// �Хå����񤭹��ߡ������
	if ($send_num > 0) {
		$desc = "��λ��¥�᡼��(ID=$cm_id)��{$send_num}���������ޤ�����";
		batch_log(0, FILE_NAME, $desc);
	}
}

db_commit_trans();
?>