<?
/******************************************************
' System :�֤�����Factory�ץХå�����
' Content:���鳨URL���Υ᡼����������
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");
include("$inc/mail.php");
include("$inc/status.php");

// ��ŵ�ư�����å�
if (!dup_check('lock_send_url'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// ���鳨URL���Υ᡼����������
$count = send_url_mail();

// �Хå����񤭹���
if ($count > 0) {
	$desc = "���鳨URL���Υ᡼���{$count}��������ޤ�����";
	batch_log('0', __FILE__, $desc);
}
exit;

// ���鳨URL���Υ᡼����������
function send_url_mail() {
	$sql = "SELECT nd_nigaoe_id,nd_random_id,nd_mail_addr FROM t_nigaoe_data WHERE nd_status=" . STATUS_CHECK_OK;
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow) {
		db_begin_trans();

		// �����᡼��ƥ�ץ졼�Ȥ����
		get_mail_template('send_url', $subject, $from, $cc, $bcc, $repty_to, $body);

		// ���鳨���������URL����
		$url = get_system_info('url_user') . 'dl.php';

		// �������ƥ��å�
		$send_mail_id = send_mail_data($subject, $from, $reply_to, $body);

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sub_seq = $i + 1;

			// �᡼�������襻�å�
			send_mail_list($send_mail_id, $fetch->nd_mail_addr, $sub_seq);

			// URL������
			send_mail_embed($send_mail_id, $sub_seq, '%DOWNLOAD_URL%', "$url?$fetch->nd_random_id");

			// ���鳨�ǡ����ơ��֥빹��
			$rec['nd_status'] = STATUS_USER_NOTIFIED;
			db_update('t_nigaoe_data', $rec, "nd_nigaoe_id=$fetch->nd_nigaoe_id");
		}

		// ��������
		send_mail_ok($send_mail_id);

		db_commit_trans();
	}
}
?>