<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 登録アドレス以外の投稿管理ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/remark.php");
include("$inc/mail.php");
include("$inc/const.php");
include("$inc/ngword.php");
include("$inc/page_view.php");

// ■投稿処理
function post_public($room_id, $seq_no) {
	$sql = "SELECT rmp_date,rmp_mail_addr,rmp_nickname,rmp_title,rmp_content,rmp_web_mail,rmp_parent_remark_id"
		. " FROM t_room_public"
		. " WHERE rmp_room_id=$room_id AND rmp_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		set_remark(RT_REMARK, $room_id, 0, make_message_id(), $fetch->rmp_parent_remark_id, $fetch->rmp_title, $fetch->rmp_content, $fetch->rmp_web_mail, '', $fetch->rmp_nickname);
	}
}

// ■お断りメール送信
function send_refule_mail($room_id, $seq_no) {
	$sql = "SELECT rmp_mail_addr,rmp_nickname,rmp_title,rmp_content"
		. " FROM t_room_public"
		. " WHERE rmp_room_id=$room_id AND rmp_seq_no=$seq_no";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// メールテンプレート取得
		get_mail_template('room_post_ng', $subject, $from, $cc, $bcc, $repty_to, $body);


		// 差し込み変数処理
		$var_ary = get_room_var($room_id);
		$body = replace_room_var($var_ary, $body);
		$body = str_replace('%MAIL_ADDR%', $fetch->rmp_mail_addr, $body);
		$body = str_replace('%NICKNAME%', $fetch->rmp_nickname, $body);
		$body = str_replace('%TITE%', $fetch->rmp_title, $body);
		$body = str_replace('%CONTENT%', $fetch->rmp_content, $body);

		send_mail_one($subject, $from, $fetch->rmp_mail_addr, $reply_to, $body, $cc, $bcc);
	}
}

$status_ary = $_POST['status_ary'];

if (is_array($status_ary)) {
	db_begin_trans();

	foreach($status_ary as $room_id => $status_ary2) {
		foreach ($status_ary2 as $seq_no => $status) {
			if ($status == 9) {
				// 削除
				db_delete('t_room_public', "rmp_room_id=$room_id AND rmp_seq_no=$seq_no");
			} else {
				// 現ステータス取得
				$sql = "SELECT rmp_status FROM t_room_public WHERE rmp_room_id=$room_id AND rmp_seq_no=$seq_no";
				$current_status = db_fetch1($sql);

				if ($status != $current_status) {
					switch ($status) {
					case 2:
						// 投稿処理
						post_public($room_id, $seq_no);
						break;
					case 4:
						// お断りメール送信
						send_refule_mail($room_id, $seq_no);
					}

					$rec['rmp_status'] = sql_number($status);
					db_update('t_room_public', $rec, "rmp_room_id=$room_id AND rmp_seq_no=$seq_no");
				}
			}
		}
	}

	db_commit_trans();
}

redirect('list.php');
?>