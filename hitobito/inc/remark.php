<?php
/**
 * ひとびと･net 共通ファイル
 *
 * ワイワイ会議発言処理
 *
 *
 * @package
 * @author
 * @version
 */

// 発言種別
define('RT_GUIDE', 1);
define('RT_START', 2);
define('RT_REMARK', 3);
define('RT_END', 4);
define('RT_HELP', 5);
define('RT_ANSWER', 6);

// 発言方法種別
define('RM_MAIL', 1);
define('RM_WEB', 2);
define('RM_BATCH', 3);

// 表示種別
define('DT_REMARK', 1);
define('DT_HELP', 2);

// 発言登録処理
function set_remark($remark_type, $room_id, $member_id, $message_id, $parent_remark_id, &$subject, &$body, $web_mail, $mail_header = '', $nickname = '') {
	if ($member_id) {
		// 会議室情報を取得
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
		// 一般からの投稿
		$member_code = 'public';
		$mail_addr = '';
	}

	// 会議室情報を取得
	$sql = "SELECT rom_room_code,rom_header_flag,rom_footer_flag,rom_header,rom_footer"
			. " FROM t_room"
			. " WHERE rom_room_id=$room_id";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		return;
	$fetch = pg_fetch_object($result, 0);

	// Fromアドレスを編集
	$room_code = $fetch->rom_room_code;
	$from_addr = get_member_mail_addr($room_code, $member_code);
	$from = join_mail_from($from_addr, $nickname);

	// 発言種別による処理分け
	$disp_type = DT_REMARK;
	$disp_member_id = 0;
	$seq_no = "(SELECT COALESCE(max(rmk_seq_no),0)+1 FROM l_room_remark WHERE rmk_room_id=$room_id AND rmk_disp_type=1)";

	// 差し込み変数処理
	$var_ary = get_room_var($room_id);
	$content = replace_room_var($var_ary, $body);
	$header = $fetch->rom_header_flag ? replace_room_var($var_ary, $fetch->rom_header) : '';
	$footer = $fetch->rom_footer_flag ? replace_room_var($var_ary, $fetch->rom_footer) : '';

	// 本文中の実メールアドレスを個人マスクアドレスに置換
	$content = str_replace($mail_addr, get_member_mail_addr($room_code, $member_code), $content);

	// 件名無しの処置
	if ($subject == '')
		$subject = '（件名無し）';
	else {
		// メーリングリスト名と発言番号を削除
		$reg = sprintf('\[%s:[0-9]+\] *(Re: *)*', $room_code);
		$subject = eregi_replace($reg, '', $subject);
	}

	db_begin_trans();

	// 発言ログに保存
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

	// 子発言数更新
	set_child_num($parent_remark_id, 1);

	db_commit_trans();
}

// メール配信処理
function send_remark($remark_id) {
	$send_num = 0;

	$sql = "SELECT rmk_room_id,rmk_seq_no,rmk_parent_remark_id,rmk_subject,rmk_from,rmk_message_id,rmk_content,rmk_room_header,rmk_room_footer,rom_room_code"
			. " FROM l_room_remark"
			. " JOIN t_room ON rom_room_id=rmk_room_id"
			. " WHERE rmk_remark_id=$remark_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// 会議室CD等取得
		$room_id = $fetch->rmk_room_id;
		$room_code = $fetch->rom_room_code;

		// 親メッセージID取得
		if ($fetch->rmk_parent_remark_id) {
			$sql = "SELECT rmk_message_id FROM l_room_remark WHERE rmk_remark_id=$fetch->rmk_parent_remark_id AND rmk_status=1";
			$ref_message_id = db_fetch1($sql);
		} else
			$ref_message_id = '';

		// メールヘッダ編集
		$mail_header = make_mail_header($fetch->rmk_message_id, $ref_message_id, $room_id);

		// ヘッダ・本文・フッタを編集
		$content = make_contents($fetch->rmk_room_header, $fetch->rmk_content, $fetch->rmk_room_footer);

		// 件名にメーリングリスト名と発言番号を付加
		$subject = sprintf('[%s:%d] %s', $room_code, $fetch->rmk_seq_no, $fetch->rmk_subject);

		db_begin_trans();

		// 返信先取得
		$reply_to = get_room_mail_addr($room_code);

		// 送信メールデータセット
		$send_mail_id = send_mail_data($subject, $fetch->rmk_from, $reply_to, $content);

		// 配信先メンバー情報取得
		$sql = "SELECT mbr_mail_addr"
				. " FROM t_room_member"
				. " JOIN t_member ON mbr_member_id=rmm_member_id"
				. " WHERE rmm_room_id=$room_id AND rmm_status IN (2,3) AND rmm_mail_send_flag=1 AND mbr_status=1";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sub_seq = $i + 1;

			// 配信先リスト設定
			send_mail_list($send_mail_id, $fetch->mbr_mail_addr, $sub_seq, $mail_header);

			// To:ヘッダをルームアドレスに設定
			send_mail_embed($send_mail_id, $sub_seq, '%%TO_ADDR%%', get_room_mail_addr($room_code));

			$send_num++;
		}

		send_mail_ok($send_mail_id);

		db_commit_trans();
	}

	return $send_num;
}

// 子発言数更新処理
function set_child_num($remark_id, $num = 0) {
	if ($remark_id) {
		// 子発言数を更新
		if ($num)
			$rec['rmk_child_num'] = "rmk_child_num + $num";
		else
			$rec['rmk_child_num'] = "(SELECT coalesce(sum(rmk_child_num)+count(nullif(rmk_open_flag,0)),0) FROM l_room_remark WHERE rmk_parent_remark_id=$remark_id AND rmk_status=1)";
		db_update('l_room_remark', $rec, "rmk_remark_id=$remark_id");

		// 親も更新
		$sql = "SELECT rmk_parent_remark_id FROM l_room_remark WHERE rmk_remark_id=$remark_id";
		set_child_num(db_fetch1($sql), $num);
	}
}

// メールヘッダから受信日時取得
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

// 会議室メールアドレス取得
function get_room_mail_addr($room_code) {
	return sprintf("%s@%s", $room_code, get_const('room_mail_domain'));
}

// 個人マスクアドレス取得
function get_member_mail_addr($room_code, $member_code) {
	return sprintf("%s-%s@%s", $room_code, $member_code, get_const('room_mail_domain'));
}

// ナビページURL取得
function get_navipage_url($navi_page_id) {
	$url = get_const('user_page_url') . "index.php?module=Navi&action=ShowNaviPage&navipage_id=$navi_page_id";
	return $url;
}

// 会議室投稿ページURL取得
function get_room_url($navi_page_id) {
	$url = get_const('user_page_url') . "?module=Waiwai&action=PostNew&navipage_id=$navi_page_id";
	return $url;
}

// 差し込み変数設定
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

		$var_ary['%ナビページ名%'] = $fetch->nvp_title;
		$var_ary['%ナビページURL%'] = get_navipage_url($fetch->nvp_navi_page_id);
		$var_ary['%会議室名%'] = $fetch->rom_name;
		$var_ary['%ナビ名%'] = "$fetch->nav_name1 $fetch->nav_name2";
		$var_ary['%投稿用アドレス%'] = get_room_mail_addr($fetch->rom_room_code);
		$var_ary['%投稿用URL%'] = get_room_url($fetch->nvp_navi_page_id);
	}

	return $var_ary;
}

// 会議室関連差し込み処理
function replace_room_var($var_ary, $content) {
	foreach ($var_ary as $key => $val)
		$content = str_replace($key, $val, $content);

	return $content;
}
?>