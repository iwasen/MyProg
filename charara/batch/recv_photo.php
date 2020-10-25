<?
/******************************************************
' System :�֤�����Factory�ץХå�����
' Content:��̿��᡼���������
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/pop3.php");
include("$inc/mail.php");
include("$inc/status.php");
include("$inc/file.php");

// ��ŵ�ư�����å�
if (!dup_check('lock_recv_photo'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// ��̿��᡼���������
$count = recv_photo_mail();

// �Хå����񤭹���
if ($count > 0) {
	$desc = "��̿��᡼���{$count}��������ޤ�����";
	batch_log('0', __FILE__, $desc);
}
exit;

// ��̿��᡼���������
function recv_photo_mail() {
	// �᡼�륵����̾����
	$server = get_system_info('server_pop3');

	// �᡼�������������ȼ���
	$account = explode('/', get_system_info('account_photo_mail'), 2);

	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account[0], $account[1]);
	if (!$num_msg)
		return 0;

	db_begin_trans();

	$count = 0;
	for ($i = 1; $i <= $num_msg; $i++) {
		$header = $mail->get_header($i);

		// to���ɥ쥹������å�
		if (is_array($header->to)) {
			$to = &$header->to[0];
			$pos = strpos($to->mailbox, '-');
			if ($pos) {
				$id = substr($to->mailbox, $pos + 1);

				$body = $mail->get_text_body($i);

				$attachment = $mail->get_attachment($i, 5);
//				if (!$attachment)
//					$attachment = $mail->get_attachment($i);

				recv_photo($id, $header, $body, $attachment);

				$count++;

				if (db_errormessage() != '') {
					$mail->close(false);
					db_rollback();
					exit;
				}
			}
		}

		$mail->delete($i);
	}

	$mail->close(true);
	db_commit_trans();

	return $count;
}

// �̿��ե�������ս���
function recv_photo($id, &$header, &$body, &$attachment) {
	// ���ơ���������
	$sql = "SELECT nd_nigaoe_id,nd_status,nd_mail_addr FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow == 0)
		return;

	$fetch = pg_fetch_object($result, 0);
	$status = $fetch->nd_status;

	if ($status < 2)
		return;

	if ($attachment) {
		if ($status == 2 || $status == 3) {
			save_photo($id, $attachment[0]);
			send_reply($id, 'photo_ok');
		}
	} else {
		if ($status == 2) {
			send_reply($id, 'photo_no');
		}
	}

	// �᡼������������˽񤭹���
	$rec['lm_nigaoe_id'] = sql_number($fetch->nd_nigaoe_id);
	$rec['lm_send_recv'] = sql_char('R');
	$rec['lm_from_addr'] = sql_char("{$header->from[0]->mailbox}@{$header->from[0]->host}");
	$rec['lm_to_addr'] = sql_char($fetch->nd_mail_addr);
	$rec['lm_subject'] = sql_char(mb_decode_mimeheader($header->subject));
	$rec['lm_body'] = sql_char(trim($body));
	db_insert('l_mail', $rec);
}

// �̿��ե�������¸����
function save_photo($id, &$attachment) {
	// ��ĥ�Ҽ���
	switch ($attachment['subtype']) {
	case 'JPEG':
		$ext = 'jpg';
		break;
	case 'PNG':
		$ext = 'png';
		break;
	case 'GIF':
		$ext = 'gif';
		break;
	default:
		$pos = strrpos($attachment['name'], '.');
		if ($pos)
			$ext = strtolower(substr($attachment['name'], $pos + 1));
		break;
	}

	// �ե�����̾����
	$filename = "$id.$ext";

	// �����ե�������¸
	if (save_photo_file($attachment['body'], $filename)) {
		// ���鳨��������ǡ�������
		$rec['nd_status'] = STATUS_PHOTO_RECEIVED;
		$rec['nd_photo_date'] = 'current_timestamp';
		$rec['nd_photo_file'] = sql_char($filename);
		db_update('t_nigaoe_data', $rec, "nd_random_id=" . sql_char($id));
	}
}

// �ֿ��᡼������
function send_reply($id, $mail_id) {
	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template($mail_id, $subject, $from, $cc, $bcc, $repty_to, $body);

	// From���ɥ쥹��ID���ղ�
	$from = str_replace('@', "-$id@", $from);

	// �桼���Υ᡼�륢�ɥ쥹����
	$sql = "SELECT nd_mail_addr FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$mail_addr = db_fetch1($sql);

	// ���մ�λ�᡼������
	send_mail_one($subject, $from, $mail_addr, $reply_to, $body);
}
?>