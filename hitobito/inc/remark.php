<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �磻�磻���ȯ������
 *
 *
 * @package
 * @author
 * @version
 */

// ȯ������
define('RT_GUIDE', 1);
define('RT_START', 2);
define('RT_REMARK', 3);
define('RT_END', 4);
define('RT_HELP', 5);
define('RT_ANSWER', 6);

// ȯ����ˡ����
define('RM_MAIL', 1);
define('RM_WEB', 2);
define('RM_BATCH', 3);

// ɽ������
define('DT_REMARK', 1);
define('DT_HELP', 2);

// ȯ����Ͽ����
function set_remark($remark_type, $room_id, $member_id, $message_id, $parent_remark_id, &$subject, &$body, $web_mail, $mail_header = '', $nickname = '') {
	if ($member_id) {
		// ��ļ���������
		$sql = "SELECT rmm_member_code,rmm_nickname,mbr_mail_addr"
				. " FROM t_room_member"
				. " JOIN t_member ON mbr_member_id=rmm_member_id"
				. " WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id AND rmm_status=3";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$member_code = $fetch->rmm_member_code;
			$mail_addr = $fetch->mbr_mail_addr;
			$nickname = $fetch->rmm_nickname;
		} else
			return;
	} else {
		// ���̤�������
		$member_code = 'public';
		$mail_addr = '';
	}

	// ��ļ���������
	$sql = "SELECT rom_room_code,rom_header_flag,rom_footer_flag,rom_header,rom_footer"
			. " FROM t_room"
			. " WHERE rom_room_id=$room_id";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		return;
	$fetch = pg_fetch_object($result, 0);

	// From���ɥ쥹���Խ�
	$room_code = $fetch->rom_room_code;
	$from_addr = get_member_mail_addr($room_code, $member_code);
	$from = join_mail_from($from_addr, $nickname);

	// ȯ�����̤ˤ�����ʬ��
	$disp_type = DT_REMARK;
	$disp_member_id = 0;
	$seq_no = "(SELECT COALESCE(max(rmk_seq_no),0)+1 FROM l_room_remark WHERE rmk_room_id=$room_id AND rmk_disp_type=1)";

	// ���������ѿ�����
	$var_ary = get_room_var($room_id);
	$content = replace_room_var($var_ary, $body);
	$header = $fetch->rom_header_flag ? replace_room_var($var_ary, $fetch->rom_header) : '';
	$footer = $fetch->rom_footer_flag ? replace_room_var($var_ary, $fetch->rom_footer) : '';

	// ��ʸ��μ¥᡼�륢�ɥ쥹��Ŀͥޥ������ɥ쥹���ִ�
	$content = str_replace($mail_addr, get_member_mail_addr($room_code, $member_code), $content);

	// ��̵̾���ν���
	if ($subject == '')
		$subject = '�ʷ�̵̾����';
	else {
		// �᡼��󥰥ꥹ��̾��ȯ���ֹ����
		$reg = sprintf('\[%s:[0-9]+\] *(Re: *)*', $room_code);
		$subject = eregi_replace($reg, '', $subject);
	}

	db_begin_trans();

	// ȯ��������¸
	$rec['rmk_room_id'] = sql_number($room_id);
	$rec['rmk_member_id'] = sql_number($member_id);
	$rec['rmk_date'] = sql_date(get_received_date($mail_header));
	$rec['rmk_parent_remark_id'] = sql_number($parent_remark_id);
	$rec['rmk_disp_type'] = sql_number($disp_type);
	$rec['rmk_disp_member_id'] = sql_number($disp_member_id);
	$rec['rmk_remark_type'] = sql_number($remark_type);
	$rec['rmk_seq_no'] = $seq_no;
	$rec['rmk_from'] = sql_char($from);
	$rec['rmk_message_id'] = sql_char($message_id);
	$rec['rmk_mail_header'] = sql_char($mail_header);
	$rec['rmk_subject'] = sql_char($subject);
	$rec['rmk_content'] = sql_char($content);
	$rec['rmk_room_header'] = sql_char($header);
	$rec['rmk_room_footer'] = sql_char($footer);
	$rec['rmk_web_mail'] = sql_number($web_mail);
	$rec['rmk_ng_word'] = sql_char(check_ngword($subject . $content));
	$rec['rmk_pv_id'] = sql_number(get_new_pv());
	$rec['rmk_nickname'] = sql_char($nickname);
	db_insert('l_room_remark', $rec);
	$remark_id = get_current_seq('l_room_remark', 'rmk_remark_id');

	// ��ȯ��������
	set_child_num($parent_remark_id, 1);

	db_commit_trans();
}

// �᡼���ۿ�����
function send_remark($remark_id) {
	$send_num = 0;

	$sql = "SELECT rmk_room_id,rmk_seq_no,rmk_parent_remark_id,rmk_subject,rmk_from,rmk_message_id,rmk_content,rmk_room_header,rmk_room_footer,rom_room_code"
			. " FROM l_room_remark"
			. " JOIN t_room ON rom_room_id=rmk_room_id"
			. " WHERE rmk_remark_id=$remark_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// ��ļ�CD������
		$room_id = $fetch->rmk_room_id;
		$room_code = $fetch->rom_room_code;

		// �ƥ�å�����ID����
		if ($fetch->rmk_parent_remark_id) {
			$sql = "SELECT rmk_message_id FROM l_room_remark WHERE rmk_remark_id=$fetch->rmk_parent_remark_id AND rmk_status=1";
			$ref_message_id = db_fetch1($sql);
		} else
			$ref_message_id = '';

		// �᡼��إå��Խ�
		$mail_header = make_mail_header($fetch->rmk_message_id, $ref_message_id, $room_id);

		// �إå�����ʸ���եå����Խ�
		$content = make_contents($fetch->rmk_room_header, $fetch->rmk_content, $fetch->rmk_room_footer);

		// ��̾�˥᡼��󥰥ꥹ��̾��ȯ���ֹ���ղ�
		$subject = sprintf('[%s:%d] %s', $room_code, $fetch->rmk_seq_no, $fetch->rmk_subject);

		db_begin_trans();

		// �ֿ������
		$reply_to = get_room_mail_addr($room_code);

		// �����᡼��ǡ������å�
		$send_mail_id = send_mail_data($subject, $fetch->rmk_from, $reply_to, $content);

		// �ۿ�����С��������
		$sql = "SELECT mbr_mail_addr"
				. " FROM t_room_member"
				. " JOIN t_member ON mbr_member_id=rmm_member_id"
				. " WHERE rmm_room_id=$room_id AND rmm_status IN (2,3) AND rmm_mail_send_flag=1 AND mbr_status=1";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sub_seq = $i + 1;

			// �ۿ���ꥹ������
			send_mail_list($send_mail_id, $fetch->mbr_mail_addr, $sub_seq, $mail_header);

			// To:�إå���롼�ॢ�ɥ쥹������
			send_mail_embed($send_mail_id, $sub_seq, '%%TO_ADDR%%', get_room_mail_addr($room_code));

			$send_num++;
		}

		send_mail_ok($send_mail_id);

		db_commit_trans();
	}

	return $send_num;
}

// ��ȯ������������
function set_child_num($remark_id, $num = 0) {
	if ($remark_id) {
		// ��ȯ�����򹹿�
		if ($num)
			$rec['rmk_child_num'] = "rmk_child_num + $num";
		else
			$rec['rmk_child_num'] = "(SELECT coalesce(sum(rmk_child_num)+count(nullif(rmk_open_flag,0)),0) FROM l_room_remark WHERE rmk_parent_remark_id=$remark_id AND rmk_status=1)";
		db_update('l_room_remark', $rec, "rmk_remark_id=$remark_id");

		// �Ƥ⹹��
		$sql = "SELECT rmk_parent_remark_id FROM l_room_remark WHERE rmk_remark_id=$remark_id";
		set_child_num(db_fetch1($sql), $num);
	}
}

// �᡼��إå����������������
function get_received_date(&$mail_header) {
	$date = 'now';

	if ($mail_header != '') {
		$ary = explode("\n", $mail_header);
		foreach ($ary as $header) {
			if (eregi('Received:.+;(.+)', $header, $regs)) {
				$date = trim($regs[1]);
				break;
			}
		}
	}

	return $date;
}

// ��ļ��᡼�륢�ɥ쥹����
function get_room_mail_addr($room_code) {
	return sprintf("%s@%s", $room_code, get_const('room_mail_domain'));
}

// �Ŀͥޥ������ɥ쥹����
function get_member_mail_addr($room_code, $member_code) {
	return sprintf("%s-%s@%s", $room_code, $member_code, get_const('room_mail_domain'));
}

// �ʥӥڡ���URL����
function get_navipage_url($navi_page_id) {
	$url = get_const('user_page_url') . "index.php?module=Navi&action=ShowNaviPage&navipage_id=$navi_page_id";
	return $url;
}

// ��ļ���ƥڡ���URL����
function get_room_url($navi_page_id) {
	$url = get_const('user_page_url') . "?module=Waiwai&action=PostNew&navipage_id=$navi_page_id";
	return $url;
}

// ���������ѿ�����
function get_room_var($room_id) {
	$var_ary = array();

	$sql = "SELECT nvp_title, rom_name, rom_room_code, nav_name1, nav_name2, rom_room_code, nvp_navi_page_id"
			. " FROM t_room"
			. " JOIN t_navi_page ON nvp_room_id=rom_room_id"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE rom_room_id=$room_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$var_ary['%�ʥӥڡ���̾%'] = $fetch->nvp_title;
		$var_ary['%�ʥӥڡ���URL%'] = get_navipage_url($fetch->nvp_navi_page_id);
		$var_ary['%��ļ�̾%'] = $fetch->rom_name;
		$var_ary['%�ʥ�̾%'] = "$fetch->nav_name1 $fetch->nav_name2";
		$var_ary['%����ѥ��ɥ쥹%'] = get_room_mail_addr($fetch->rom_room_code);
		$var_ary['%�����URL%'] = get_room_url($fetch->nvp_navi_page_id);
	}

	return $var_ary;
}

// ��ļ���Ϣ�������߽���
function replace_room_var($var_ary, $content) {
	foreach ($var_ary as $key => $val)
		$content = str_replace($key, $val, $content);

	return $content;
}
?>