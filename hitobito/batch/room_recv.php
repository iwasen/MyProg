<?php
/**
 * ひとびと･net バッチ処理
 *
 * ワイワイ会議投稿メール受信バッチ処理
 * （実行周期：毎分）
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

// バッチ処理クラス生成
$batch = new batch_class('room_recv');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	$count = 0;

	// POPサーバ／アカウント取得
	$pop3_server = get_const('pop3_server');
	list($pop3_account, $pop3_password) = explode('/', get_const('room_mail_account'));

	// メールサーバに接続
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
				$body = '（本文なし）';

			// ToアドレスからプロジェクトID，ジョブID，メンバIDを取得
			$room_id = get_id($header);

			// Fromアドレス取得
			$from_addr = "{$header->from[0]->mailbox}@{$header->from[0]->host}";

			// FromアドレスからメンバーID取得
			$member_id = get_member_id($room_id, $from_addr);

			// Subjectの取得
			$subject = mb_decode_mimeheader($header->subject);
			if ($subject == '' || !check_encoding($subject))
				$subject = '（件名なし）';

			// メッセージID取得
			$message_id = make_message_id();

			// 参照元メッセージID取得
			$reference_id = get_reference_id($header);
			if ($room_id && $reference_id != '') {
				// 親発言IDの取得
				$sql = "SELECT rmk_remark_id FROM l_room_remark WHERE rmk_room_id=$room_id AND rmk_status=1 AND rmk_message_id=" . sql_char($reference_id);
				$parent_remark_id = db_fetch1($sql, 0);
			} else
				$parent_remark_id = 0;

			// 発言受信処理
			$err_type = check_remark($room_id, $member_id);
			switch ($err_type) {
			case 0:		// 正常
				// 発言処理
				set_remark(RT_REMARK, $room_id, $member_id, $message_id, $parent_remark_id, $subject, $body, RM_MAIL, $mail_header);
				$ok_count++;
				break;
			case 2:		// メンバー未登録
				// 一般からの投稿に登録
				regist_public($room_id, $parent_remark_id, $from_addr, $subject, $body);
				break;
			default:	// その他のエラー
				send_err_mail($header, $body, $err_type, $room_id, $member_id);
				$err_count++;
				break;
			}
		}

		$mail->delete($i);
	}

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();
		$mail->close(true);

		// バッチログ書き込み（正常）
		$batch->set_log(0, "ワイワイ会議投稿メールを受信しました。（正常：{$ok_count}件，エラー：{$err_count}件）");
	} else {
		db_rollback();
		$mail->close(false);

		// バッチログ書き込み（エラー）
		$batch->set_log(9, "ワイワイ会議投稿メール受信処理でエラーが発生しました。($err_msg)");
	}
}

// Toアドレスから会議室IDを取得
function get_id(&$header) {
	// Toアドレス取得
	$to = trim($header->to[0]->mailbox);

	// 会議室ID取得
	if (check_encoding($to)) {
		$sql = "SELECT rom_room_id FROM t_room WHERE rom_status IN (1,2) AND rom_room_code=" . sql_char($to);
		return db_fetch1($sql);
	} else
		return 0;
}

// FromアドレスからメンバーID取得
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

// 発言チェック
function check_remark($room_id, $member_id) {
	//会議室IDがない場合はタイプ１エラー
	if (!$room_id)
		return 1;

	// メンバーが存在しない場合はタイプ２エラー
	if (!$member_id)
		return 2;

	return 0;
}

// エラーメール送信
function send_err_mail(&$header, &$body_org, $err_type, $room_id, $member_id) {
	if (!$header->from)
		return;

	$to = "{$header->from[0]->mailbox}@{$header->from[0]->host}";
	if (!check_encoding($to))
		return;

	// 送信メールテンプレート取得
	get_mail_template("room_err_$err_type", $subject, $from, $cc, $bcc, $repty_to, $body);

	$bcc_ary = array();

	// メールヘッダ編集
	$message_id = make_message_id();
	$reference_id = get_message_id($header);
	if (!check_encoding($reference_id))
		return;
	$mail_header_ary[] = "Message-ID: <$message_id>";
	$mail_header_ary[] = "In-Reply-To: <$reference_id>";
	$mail_header_ary[] = "References: <$reference_id>";
	$mail_header = join("\n", $mail_header_ary);

	// 本文引用
	$body = str_replace('%QUATE%', make_quote($body_org), $body);

	// 送信メールデータセット
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body, $mail_header);

	// 配信先リスト設定
	send_mail_list($send_mail_id, $to);

	send_mail_ok($send_mail_id);
}

// Message-ID取得
function get_message_id(&$header) {
	return get_id_value($header->message_id);
}

// 参照元Message-ID取得
function get_reference_id(&$header) {
	$id = get_id_value($header->in_reply_to);
	if ($id != '')
		return $id;

	return get_id_value($header->references);
}

// Message-IDの値を取得
function get_id_value($id) {
	$id_ary = split("[ \t\n\r]", $id);
	if (is_array($id_ary) && count($id_ary) >= 2)
		$id = array_pop($id_ary);

	if (ereg('<([^\>]*)\>', $id, $regs))
		return $regs[1];
	else
		return $id;
}

// 一般からの投稿に登録
function regist_public($room_id, $parent_remark_id, $mail_addr, $subject, $body) {
	$rec['rmp_room_id'] = sql_number($room_id);
	$rec['rmp_seq_no'] = "(SELECT COALESCE(max(rmp_seq_no),0)+1 FROM t_room_public WHERE rmp_room_id=$room_id)";
	$rec['rmp_mail_addr'] = sql_char($mail_addr);
	$rec['rmp_nickname'] = sql_char('未登録ユーザー');
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
			$body2 = str_replace('%NICKNAME%', '未登録ユーザー', $body2);
			$body2 = str_replace('%BODY%', $body, $body2);
			$body2 = str_replace('%NAVIEDIT_URL%', "${user_page_url}index.php?module=Waiwai&action=ListPublic&navipage_id=$fetch->nvp_navi_page_id", $body2);

			send_mail_one($subject, $from, $fetch->mbr_mail_addr, $reply_to, $body2, $cc, $bcc);
		}
	}
}

// 文字コードのチェック
function check_encoding($str) {
	return mb_detect_encoding($str . ' ', 'EUC-JP', true) != '';
}
?>