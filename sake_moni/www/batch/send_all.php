<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥����Хå�����
' Content:���ۿ�����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/database.php");
include("$inc/define.php");
include("$inc/mail.php");
include("$inc/batch_log.php");
include("mail_report.php");

define('FILE_NAME', __FILE__);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// ���������ɥ쥹����
$fetch = get_system_info('sy_mail_from,sy_mail_name');
$mail_from = $fetch->sy_mail_from;
$mail_name = $fetch->sy_mail_name;

// ���ۿ�����
for (;;) {
	// ������ǽ�᡼�����
	$sql = "SELECT sa_send_id,sa_subject,sa_body,sa_send_date,sa_net_kind" .
				" FROM t_send_all" .
				" WHERE sa_status=0 AND (sa_send_date IS NULL OR sa_send_date<CURRENT_TIMESTAMP)";
	$result = db_exec($sql);
	if (!$result)
		break;
	$nrow = pg_num_rows($result);
	if (!$nrow)
		break;

	$fetch = pg_fetch_object($result, 0);
	$send_id = $fetch->sa_send_id;
	$send_num = 0;
	$select = array();

	db_begin_trans();

	// �᡼�������ơ��֥�˥��å�
	$send_mail_id = send_mail_data($fetch->sa_subject, $mail_from, '', $fetch->sa_body, '', $fetch->sa_send_date, $mail_name);

	// �оݼ����
	$where = "mn_status=0";
	if ($fetch->sa_net_kind != '')
		$where .= " AND mn_net_kind=$fetch->sa_net_kind";

	$sql = "INSERT INTO t_send_mail_list (sl_send_mail_id,sl_to) (SELECT DISTINCT $send_mail_id,mn_mail_addr FROM t_monitor WHERE $where)";
	$result = db_exec($sql);
	$send_num = pg_cmdtuples($result);

	// ���ơ�������������
	send_mail_ok($send_mail_id);

	// ��̳�ɤ�������λ����
	report_admin('���ۿ��᡼��', $send_num, $send_num, $fetch->sa_subject, $fetch->sa_body);

	// �᡼����ۿ��Ѥߤ˥��å�
	$sql = "UPDATE t_send_all SET sa_status=1,sa_send_date=CURRENT_TIMESTAMP,sa_send_num=$send_num WHERE sa_send_id=$send_id";
	db_exec($sql);

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// �Хå����񤭹��ߡʥ��顼��
		$desc = "���ۿ����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
		batch_log(9, FILE_NAME, $desc);
		break;
	} else {
		// �Хå����񤭹��ߡ������
		if ($send_num > 0) {
			$desc = "���ۿ�(ID=$send_id)��{$send_num}���������ޤ�����";
			batch_log(0, FILE_NAME, $desc);
		}
	}

	db_commit_trans();
}
?>