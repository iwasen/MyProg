<?
/******************************************************
' System :ICP�Хå�����
' Content:�᡼���������
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/mail.php");
include("$inc/pop3.php");
include("$inc/auth.php");
include("$inc/variable.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/status.php");
include("$inc/define.php");
include("$inc/remark.php");

// To���ɥ쥹�μ���
define('TO_NONE', 0);
define('TO_ROOM', 1);
define('TO_MEMBER', 2);
define('TO_MC', 3);
define('TO_HELP', 4);

// ��ŵ�ư�����å�
if (!dup_check('lock_mail_recv'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// �᡼�륵����̾����
$server = get_system_info('server_pop3');

// �᡼�������������ȡ��ѥ���ɼ���
$account = explode('/', get_system_info('mail_account'), 2);

mail_recv($server, $account[0], $account[1]);
exit;

// �᡼���������
function mail_recv($server, $account, $password) {
	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account, $password);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$ok_count = 0;
	$err_count = 0;

	for ($i = 1; $i <= $num_msg; $i++) {
		$mail_header = $mail->get_all_headers($i);
		$header = $mail->get_header($i);
		$body = trim($mail->get_text_body($i));
		$attachment = $mail->get_attachment($i);

		if ($body == '')
			$body = '����ʸ�ʤ���';
/*
		echo '<xmp>';var_dump($header);echo '</xmp>------------------';
		$st = imap_fetchstructure($mail->mbox, $i);
		echo '<xmp>';var_dump($st);echo '</xmp>------------------';
*/
		// To���ɥ쥹����ץ���������ID�������ID������ID�����
		$to_type = get_id($header, $pjt_id, $job_id, $to_member_id);

		// From���ɥ쥹������С�ID����
		$member_id = get_member_id($pjt_id, $header);

		// Subject�μ���
		$subject = mb_decode_mimeheader($header->subject);
		if ($subject == '')
			$subject = '�ʷ�̾�ʤ���';

		// ��å�����ID����
		$message_id = make_message_id();

		// ���ȸ���å�����ID����
		$reference_id = get_reference_id($header);

		// ȯ����������
		$err_type = check_remark($to_type, $job_id, $member_id, $attachment);
		if ($err_type != 0) {
			send_err_mail($header, $body, $err_type, $pjt_id, $job_id, $member_id);
			$err_count++;
		} else {
			// ��ȯ��ID�μ���
			$sql = "SELECT rm_remark_id FROM l_remark WHERE rm_job_id=$job_id AND rm_status=1 AND rm_message_id=" . sql_char($reference_id);
			$parent_remark_id = db_fetch1($sql, 0);

			// ȯ������Ƚ��
			switch ($to_type) {
			case TO_ROOM:
				$remark_type = RT_REMARK;
				break;
			case TO_MEMBER:
				$remark_type = RT_REMARK;
				if ($parent_remark_id != 0) {
					$sql = "SELECT rm_remark_type FROM l_remark WHERE rm_remark_id=$parent_remark_id AND rm_status=1";
					$parent_remark_type = db_fetch1($sql);
					if ($parent_remark_type == RT_HELP || $parent_remark_type == RT_ANSWER)
						$remark_type = RT_ANSWER;
				}
				break;
			case TO_MC:
				$remark_type = RT_REMARK;
				break;
			case TO_HELP:
				$remark_type = RT_HELP;
				break;
			}

			// ȯ������
			set_remark($remark_type, $job_id, $member_id, $to_member_id, $message_id, $parent_remark_id, $subject, $body, RM_MAIL, $mail_header, $attachment);
			$ok_count++;
		}

		$mail->delete($i);
	}

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();
		$mail->close(false);

		// �Хå������񤭹��ߡʥ��顼��
		$desc = "�᡼����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// �Хå������񤭹��ߡ������
		$desc = "�᡼���������ޤ����������{$ok_count}����顼��{$err_count}���";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
		$mail->close(true);
	}
}

// To���ɥ쥹����ץ���������ID�������ID������ID�����
function get_id(&$header, &$pjt_id, &$job_id, &$member_id) {
	$ary = explode('-', $header->to[0]->mailbox);

	$pjt_id = '';
	$job_id = '';
	$member_id = '';
	$to_type = TO_NONE;

	// �ץ���������ID����
	if ($ary[1] != '') {
		$sql = "SELECT pj_pjt_id FROM t_pjt WHERE pj_pjt_cd=" . sql_char($ary[1]);
		$pjt_id = db_fetch1($sql);

		// �����ID����
		if ($pjt_id != '' && $ary[2] != '') {
			$sql = "SELECT jb_job_id FROM t_job WHERE jb_pjt_id=" . sql_number($pjt_id) . " AND jb_job_cd=" . sql_char($ary[2]);
			$job_id = db_fetch1($sql);

			// ���С�ID����
			if ($job_id != '') {
				switch ($ary[3]) {
				case 'mc':
					$to_type = TO_MC;
					break;
				case 'help':
					$to_type = TO_HELP;
					break;
				case '':
					$to_type = TO_ROOM;
					break;
				default:
					if (strlen($ary[3]) == 6) {
						$member_cd = $ary[3];
						$sql = "SELECT jm_member_id"
								. " FROM t_job_member"
								. " WHERE jm_job_id=" . sql_number($job_id) . " AND jm_member_cd=" . sql_char($member_cd) . " AND jm_status=1";
						$member_id = db_fetch1($sql);
						if ($member_id != '')
							$to_type = TO_MEMBER;
					}
					break;
				}
			}
		}
	}

	return $to_type;
}

// From���ɥ쥹������С�ID����
function get_member_id($pjt_id, &$header) {
	$mail_addr = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

	$sql = "SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=" . sql_number($pjt_id) . " AND pm_mail_addr=" . sql_char(strtolower($mail_addr));
	return db_fetch1($sql);
}

// ȯ�������å�
function check_remark($to_type, $job_id, $member_id, &$attachment) {
	global $g_job_status;

	// ������̤��ʤ����ϥ����ף����顼
	if ($to_type == TO_NONE)
		return 1;

	// ���С���¸�ߥ����å�
	if (!$member_id)
		return 2;

	// ����֤ξ��֥����å�
	$sql = "SELECT jb_status,jb_attachment_file FROM t_job WHERE jb_job_id=" . sql_number($job_id);
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$job_status = $fetch->jb_status;
	if ($job_status == $g_job_status['�ٻ���'] || $job_status == $g_job_status['ͱͽ��'])
		return 7;
	elseif ($job_status != $g_job_status['�»���'])
		return 1;

	// ���С��λ��å����å�
	$sql = "SELECT jm_participant_auth FROM t_job_member WHERE jm_job_id=" . sql_number($job_id) . " AND jm_member_id=" . sql_number($member_id) . " AND jm_status=1";
	$participant_auth = db_fetch1($sql);
	if ($participant_auth == '')
		return 2;

	// �������¥����å�
	if (($to_type == TO_ROOM || $to_type == TO_MEMBER || $to_type == TO_MC) &&
			(!check_participant_auth('�᡼������', $participant_auth) || !check_participant_auth('ȯ��ON/OFF', $participant_auth)))
		return 3;

	// �ʲ�Ը��¥����å�
	if ($to_type == TO_MEMBER && !check_participant_auth('�ʲ��', $participant_auth))
		return 4;

	// ��ļ������ץ����å�
	$sql = "SELECT jb_room_type FROM t_job WHERE jb_job_id=" . sql_number($job_id);
	$room_type = db_fetch1($sql);
	if (($room_type == ROOM_PIR && $to_type == TO_ROOM) || ($room_type != ROOM_PIR && ($to_type == TO_MEMBER || $to_type == TO_MC)))
		return 5;

	// ź�եե�����¸�ߥ����å�
	if ($fetch->jb_attachment_file == DBFALSE && $attachment)
		return 6;

	return 0;
}

// ���顼�᡼������
function send_err_mail(&$header, &$body_org, $err_type, $pjt_id, $job_id, $member_id) {
	if (!$header->from)
		return;

	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template("err_type$err_type", $subject, $from, $cc, $bcc, $repty_to, $body);

	$bcc_ary = array();

	// �ץ��������Ƚ�ͭ���ڥ졼�����Υ᡼�륢�ɥ쥹����
	$sql = "SELECT op_mail_addr"
			. " FROM t_pjt"
			. " JOIN t_operator ON op_operator_id=pj_operator_id"
			. " WHERE pj_pjt_id=" . sql_number($pjt_id);
	$bcc_ary[] = db_fetch1($sql);

	// �ʲ�Ԥ�Bcc���ɲ�
	if ($err_type == 3 || $err_type == 4 || $err_type == 5 || $err_type == 6 || $err_type == 7) {
		$sql = "SELECT pm_mail_addr"
				. " FROM t_job_member"
				. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
				. " WHERE jm_job_id=" . sql_number($job_id) . " AND jm_status=1 AND " . cond_participant_auth('�ʲ��');
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$bcc_ary[] = $fetch->pm_mail_addr;
		}
	}

	// Bcc��᡼��إå����ɲ�
	$bcc = join(',', array_unique($bcc_ary));
	if ($bcc != '')
		$mail_header_ary[] = "Bcc: $bcc";

	// �᡼��إå��Խ�
	$message_id = make_message_id();
	$reference_id = get_message_id($header);
	$mail_header_ary[] = "Message-ID: <$message_id>";
	$mail_header_ary[] = "In-Reply-To: <$reference_id>";
	$mail_header_ary[] = "References: <$reference_id>";
	$mail_header = join("\n", $mail_header_ary);

	// �䤤��碌���ɥ쥹�ִ�
	$from = replace_all_var($job_id, $member_id, $from);

	// ��ʸ����
	$body = str_replace('%QUATE%', make_quote($body_org), $body);

	// ���������ѿ�����
	$body = replace_all_var($job_id, $member_id, $body);

	// �����᡼��ǡ������å�
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body, $mail_header);

	// �ۿ���ꥹ������
	send_mail_list($send_mail_id, "{$header->from[0]->mailbox}@{$header->from[0]->host}");

	send_mail_ok($send_mail_id);
}

// Message-ID����
function get_message_id(&$header) {
	return get_id_value($header->message_id);
}

// ���ȸ�Message-ID����
function get_reference_id(&$header) {
	$id = get_id_value($header->in_reply_to);
	if ($id != '')
		return $id;

	return get_id_value($header->references);
}

// Message-ID���ͤ����
function get_id_value($id) {
	$id_ary = split("[ \t\n\r]", $id);
	if (is_array($id_ary) && count($id_ary) >= 2)
		$id = array_pop($id_ary);

	if (ereg('<([^\>]*)\>', $id, $regs))
		return $regs[1];
	else
		return $id;
}
?>