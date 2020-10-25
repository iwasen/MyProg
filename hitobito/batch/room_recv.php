<?php
/**
 * �ҤȤӤȎ�net �Хå�����
 *
 * �磻�磻�����ƥ᡼������Хå�����
 * �ʼ¹Լ�������ʬ��
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/const.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/pop3.php");
include("$inc/batch.php");
include("$inc/remark.php");
include("$inc/ngword.php");
include("$inc/page_view.php");

// �Хå��������饹����
$batch = new batch_class('room_recv');

// �ᥤ�����
if ($batch->executable)
	main_process();

exit;

// ���ᥤ�����
function main_process() {
	global $batch;

	$count = 0;

	// POP�����С���������ȼ���
	$pop3_server = get_const('pop3_server');
	list($pop3_account, $pop3_password) = explode('/', get_const('room_mail_account'));

	// �᡼�륵���Ф���³
	$mail = new pop3_class();
	$num_msg = $mail->open($pop3_server, $pop3_account, $pop3_password);
	if (!$num_msg)
		return;

	db_begin_trans();

	$ok_count = 0;
	$err_count = 0;

	for ($i = 1; $i <= $num_msg; $i++) {
		$mail_header = $mail->get_all_headers($i);
		$header = $mail->get_header($i);
		$body = trim($mail->get_text_body($i));

		if (check_encoding($body)) {
			if ($body == '')
				$body = '����ʸ�ʤ���';

			// To���ɥ쥹����ץ�������ID�������ID������ID�����
			$room_id = get_id($header);

			// From���ɥ쥹����
			$from_addr = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

			// From���ɥ쥹������С�ID����
			$member_id = get_member_id($room_id, $from_addr);

			// Subject�μ���
			$subject = mb_decode_mimeheader($header->subject);
			if ($subject == '' || !check_encoding($subject))
				$subject = '�ʷ�̾�ʤ���';

			// ��å�����ID����
			$message_id = make_message_id();

			// ���ȸ���å�����ID����
			$reference_id = get_reference_id($header);
			if ($room_id && $reference_id != '') {
				// ��ȯ��ID�μ���
				$sql = "SELECT rmk_remark_id FROM l_room_remark WHERE rmk_room_id=$room_id AND rmk_status=1 AND rmk_message_id=" . sql_char($reference_id);
				$parent_remark_id = db_fetch1($sql, 0);
			} else
				$parent_remark_id = 0;

			// ȯ����������
			$err_type = check_remark($room_id, $member_id);
			switch ($err_type) {
			case 0:		// ����
				// ȯ������
				set_remark(RT_REMARK, $room_id, $member_id, $message_id, $parent_remark_id, $subject, $body, RM_MAIL, $mail_header);
				$ok_count++;
				break;
			case 2:		// ���С�̤��Ͽ
				// ���̤������Ƥ���Ͽ
				regist_public($room_id, $parent_remark_id, $from_addr, $subject, $body);
				break;
			default:	// ����¾�Υ��顼
				send_err_mail($header, $body, $err_type, $room_id, $member_id);
				$err_count++;
				break;
			}
		}

		$mail->delete($i);
	}

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();
		$mail->close(true);

		// �Хå����񤭹��ߡ������
		$batch->set_log(0, "�磻�磻�����ƥ᡼���������ޤ����������{$ok_count}����顼��{$err_count}���");
	} else {
		db_rollback();
		$mail->close(false);

		// �Хå����񤭹��ߡʥ��顼��
		$batch->set_log(9, "�磻�磻�����ƥ᡼����������ǥ��顼��ȯ�����ޤ�����($err_msg)");
	}
}

// To���ɥ쥹�����ļ�ID�����
function get_id(&$header) {
	// To���ɥ쥹����
	$to = trim($header->to[0]->mailbox);

	// ��ļ�ID����
	if (check_encoding($to)) {
		$sql = "SELECT rom_room_id FROM t_room WHERE rom_status IN (1,2) AND rom_room_code=" . sql_char($to);
		return db_fetch1($sql);
	} else
		return 0;
}

// From���ɥ쥹������С�ID����
function get_member_id($room_id, $mail_addr) {
	if (check_encoding($mail_addr)) {
		$sql = "SELECT mbr_member_id"
				. " FROM t_member"
				. " JOIN t_room_member ON rmm_room_id=" . sql_number($room_id) . " AND rmm_member_id=mbr_member_id"
				. " WHERE mbr_status<>9 AND rmm_status=3 AND mbr_mail_addr_low=" . sql_char(strtolower($mail_addr));
		return db_fetch1($sql);
	} else
		return 0;
}

// ȯ�������å�
function check_remark($room_id, $member_id) {
	//��ļ�ID���ʤ����ϥ����ף����顼
	if (!$room_id)
		return 1;

	// ���С���¸�ߤ��ʤ����ϥ����ף����顼
	if (!$member_id)
		return 2;

	return 0;
}

// ���顼�᡼������
function send_err_mail(&$header, &$body_org, $err_type, $room_id, $member_id) {
	if (!$header->from)
		return;

	$to = "{$header->from[0]->mailbox}@{$header->from[0]->host}";
	if (!check_encoding($to))
		return;

	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template("room_err_$err_type", $subject, $from, $cc, $bcc, $repty_to, $body);

	$bcc_ary = array();

	// �᡼��إå��Խ�
	$message_id = make_message_id();
	$reference_id = get_message_id($header);
	if (!check_encoding($reference_id))
		return;
	$mail_header_ary[] = "Message-ID: <$message_id>";
	$mail_header_ary[] = "In-Reply-To: <$reference_id>";
	$mail_header_ary[] = "References: <$reference_id>";
	$mail_header = join("\n", $mail_header_ary);

	// ��ʸ����
	$body = str_replace('%QUATE%', make_quote($body_org), $body);

	// �����᡼��ǡ������å�
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body, $mail_header);

	// �ۿ���ꥹ������
	send_mail_list($send_mail_id, $to);

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

// ���̤������Ƥ���Ͽ
function regist_public($room_id, $parent_remark_id, $mail_addr, $subject, $body) {
	$rec['rmp_room_id'] = sql_number($room_id);
	$rec['rmp_seq_no'] = "(SELECT COALESCE(max(rmp_seq_no),0)+1 FROM t_room_public WHERE rmp_room_id=$room_id)";
	$rec['rmp_mail_addr'] = sql_char($mail_addr);
	$rec['rmp_nickname'] = sql_char('̤��Ͽ�桼����');
	$rec['rmp_title'] = sql_char($subject);
	$rec['rmp_content'] = sql_char($body);
	$rec['rmp_web_mail'] = sql_number(RM_MAIL);
	$rec['rmp_parent_remark_id'] = sql_number($parent_remark_id);
	db_insert('t_room_public', $rec);

	$sql = "SELECT rom_name,rom_public_user_notify_flag,nvp_navi_page_id,mbr_mail_addr"
		. " FROM t_room"
		. " JOIN t_navi_page ON nvp_room_id=rom_room_id"
		. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
		. " JOIN t_member ON mbr_member_id=nav_member_id"
		. " WHERE rom_room_id=$room_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($fetch->rom_public_user_notify_flag) {
			get_mail_template('room_public', $subject, $from, $cc, $bcc, $repty_to, $body2);
			$user_page_url = get_const('user_page_url');

			$body2 = str_replace('%ROOM_NAME%', $fetch->rom_name, $body2);
			$body2 = str_replace('%SUBJECT%', $subject, $body2);
			$body2 = str_replace('%NICKNAME%', '̤��Ͽ�桼����', $body2);
			$body2 = str_replace('%BODY%', $body, $body2);
			$body2 = str_replace('%NAVIEDIT_URL%', "${user_page_url}index.php?module=Waiwai&action=ListPublic&navipage_id=$fetch->nvp_navi_page_id", $body2);

			send_mail_one($subject, $from, $fetch->mbr_mail_addr, $reply_to, $body2, $cc, $bcc);
		}
	}
}

// ʸ�������ɤΥ����å�
function check_encoding($str) {
	return mb_detect_encoding($str . ' ', 'EUC-JP', true) != '';
}
?>