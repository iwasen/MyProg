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
include("$inc/mail.php");
include("$inc/remark.php");
include("$inc/const.php");

// ■入会メール送信
function send_regist_mail($room_id, $member_id) {
	$sql = "SELECT rom_regist_mail_flag FROM t_room WHERE rom_room_id=$room_id";
	if (!db_fetch1($sql))
		return;

	// メールテンプレート取得
	get_mail_template('room_regist', $subject, $from, $cc, $bcc, $repty_to, $body);

	// 入会者メール取得
	$sql = "SELECT rom_regist_mail FROM t_room WHERE rom_room_id=$room_id";
	$regist_mail = db_fetch1($sql);

	// 差し込み変数処理
	$var_ary = get_room_var($room_id);
	$body = replace_room_var($var_ary, $regist_mail != '' ? $regist_mail : $body);

	// メールアドレス取得
	$sql = "SELECT mbr_mail_addr FROM t_member WHERE mbr_member_id=$member_id";
	$to = db_fetch1($sql);

	// 入会メール送信
	send_mail_one($subject, $from, $to, $reply_to, $body, $cc, $bcc);
}

$flag = $_POST['flag'];
$reg_kari = $_POST['reg_kari'];
$reg_guest = $_POST['reg_guest'];

db_begin_trans();

switch($flag) {
case 'kari':
	if (!$reg_kari)
		break;

	foreach($reg_kari as $data => $check) {
		list($room_id, $member_id) = explode(':', $data);

		$sql = sprintf("UPDATE t_room_member SET rmm_status=CASE rmm_status WHEN 1 THEN 2 END, rmm_guest_date='now' WHERE rmm_room_id=%s AND rmm_member_id=%s"
			, sql_number($room_id)
			, sql_number($member_id));
		db_exec($sql);
	}
	break;
case 'guest':
	if (!$reg_guest)
		break;

	foreach($reg_guest as $data => $check) {
		list($room_id, $member_id) = explode(':', $data);

		$sql = sprintf("UPDATE t_room_member SET rmm_status=CASE rmm_status WHEN 2 THEN 3 END, rmm_regist_date='now' WHERE rmm_room_id=%s AND rmm_member_id=%s"
			, sql_number($room_id)
			, sql_number($member_id));
		db_exec($sql);

		send_regist_mail($room_id, $member_id);
	}
	break;
}

db_commit_trans();

redirect('list.php');

?>