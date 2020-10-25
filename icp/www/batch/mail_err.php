<?
/******************************************************
' System :ICP�Хå�����
' Content:��ã�᡼���������
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/auth.php");
include("$inc/mail.php");
include("$inc/pop3.php");
include("$inc/variable.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/decode.php");

// ��ŵ�ư�����å�
if (!dup_check('lock_mail_err'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// �᡼�륵����̾����
$server = get_system_info('server_pop3');

// �᡼�������������ȡ��ѥ���ɼ���
$account = explode('/', get_system_info('err_mail_account'), 2);

mail_recv($server, $account[0], $account[1]);
exit;

// �᡼���������
function mail_recv($server, $account, $password) {
	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account, $password);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$count = 0;

	for ($i = 1; $i <= $num_msg; $i++) {
		// �᡼����ʸ����
		$body = $mail->get_text_body($i);

		$warning = false;

		// ��ã�ʳ������
		if (strpos($body, '(warning-timeout)') !== false)
			$warning = true;

		if (!$warning) {
			// �������줿�᡼��إå������
			$header = imap_rfc822_parse_headers($body);

			// Reply-To���ɥ쥹����ץ�������ID,�����ID�����
			get_id($header->reply_to[0]->mailbox, $pjt_id, $job_id);
			if ($job_id) {
				// To���ɥ쥹�������ID�����
				$member_id = get_member_id($pjt_id, $header->toaddress);
				if ($member_id) {
					send_err_notify($pjt_id, $job_id, $member_id, $body);
					$count++;
				}
			}
		}

		// �᡼�륵���о�Υ᡼�����
		$mail->delete($i);
	}

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();
		$mail->close(false);

		// �Хå����񤭹��ߡʥ��顼��
		$desc = "��ã�᡼����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// �Хå����񤭹��ߡ������
		$desc = "��ã�᡼���{$count}��������ޤ�����";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
		$mail->close(true);
	}
}

// �᡼�륢�ɥ쥹����ץ�������ID,�����ID�����
function get_id($mail_addr, &$pjt_id, &$job_id) {
	$ary = explode('-', $mail_addr);

	// �ץ�������ID����
	if ($ary[1] != '') {
		$sql = "SELECT pj_pjt_id FROM t_pjt WHERE pj_pjt_cd=" . sql_char($ary[1]);
		$pjt_id = db_fetch1($sql);

		// �����ID����
		if ($pjt_id != '' && $ary[2] != '') {
			$sql = "SELECT jb_job_id FROM t_job WHERE jb_pjt_id=$pjt_id AND jb_job_cd=" . sql_char($ary[2]);
			$job_id = db_fetch1($sql);
		}
	}
}

// �᡼�륢�ɥ쥹�������ID�����
function get_member_id($pjt_id, $mail_addr) {
	$sql = "SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=$pjt_id AND pm_mail_addr=" . sql_char($mail_addr);
	return db_fetch1($sql);
}

// �ط��Ԥ�����
function send_err_notify($pjt_id, $job_id, $member_id, &$body_org) {
	$to_ary = array();

	// �ץ������Ƚ�ͭ���ڥ졼�����Υ᡼�륢�ɥ쥹����
	$sql = "SELECT op_mail_addr"
			. " FROM t_pjt"
			. " JOIN t_operator ON op_operator_id=pj_operator_id"
			. " WHERE pj_pjt_id=$pjt_id";
	$to_ary[] = db_fetch1($sql);

	// ����ִ����Ը��½�ͭ���ФΥ᡼�륢�ɥ쥹����
	$sql = "SELECT pm_mail_addr"
			. " FROM t_pjt_member"
			. " WHERE pm_pjt_id=$pjt_id AND pm_status=1 AND " . cond_staff_auth('����ִ���');
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->pm_mail_addr;
	}

	// �ʲ�Ը��½�ͭ�ԥ��ФΥ᡼�륢�ɥ쥹����
	$sql = "SELECT pm_mail_addr"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " WHERE jm_job_id=$job_id AND jm_status=1 AND " . cond_participant_auth('�ʲ��');
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->pm_mail_addr;
	}

	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template('err_notify', $subject, $from, $cc, $bcc, $repty_to, $body);

	// ��ʸ����
	$body = str_replace('%QUATE%', make_quote($body_org), $body);

	// ���������ѿ�����
	$body = replace_all_var($job_id, $member_id, $body);

	// �����᡼��ǡ������å�
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body);

	// �ۿ���ꥹ������
	foreach (array_unique($to_ary) as $to)
		send_mail_list($send_mail_id, $to);

	send_mail_ok($send_mail_id);
}
?>