<?php
/**
 * ひとびと･net バッチ処理
 *
 * メンバー登録バッチ処理
 * （実行周期：毎日0時0分）
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/date.php");
include("$inc/const.php");
include("$inc/format.php");
include("$inc/mail.php");
include("$inc/batch.php");

// 現日時取得
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// テスト用
else
	define('CURRENT_TIME', time());

// バッチ処理クラス生成
$batch = new batch_class('regist');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	$current_time = sql_unix_time(CURRENT_TIME);

	// 仮登録から１週間経過したらメンバーに確認メール送信
	$sql = "SELECT rmm_room_id,rmm_member_id"
			. " FROM t_room_member"
			. " WHERE rmm_status=1 AND rmm_kari_date + interval '1 week' < $current_time AND rmm_kari_varify_mail=0";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$room_id = $fetch->rmm_room_id;
		$member_id = $fetch->rmm_member_id;

		db_begin_trans();

		// 仮登録確認メール送信
		send_kari_varify_mail($room_id, $member_id);

		// 仮登録確認メール送信済みフラグセット
		$rec['rmm_kari_varify_mail'] = sql_number(1);
		db_update('t_room_member', $rec, "rmm_room_id=$room_id AND rmm_member_id=$member_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			// バッチログ書き込み
			$batch->set_log(0, "仮登録確認メールを送信しました。（会議室ID：{$room_id}，メンバーID：{$member_id}）");
		} else {
			db_rollback();

			// バッチログ書き込み（エラー）
			$batch->set_log(9, "仮登録確認メール送信処理でエラーが発生しました。($err_msg)");
			break;
		}
	}

	// 仮登録から２週間経過したら削除
	$sql = "SELECT rmm_room_id,rmm_member_id"
			. " FROM t_room_member"
			. " WHERE rmm_status=1 AND rmm_kari_date + interval '2 week' < $current_time";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$room_id = $fetch->rmm_room_id;
		$member_id = $fetch->rmm_member_id;

		// 仮登録メンバー削除
		db_delete('t_room_member', "rmm_room_id=$room_id AND rmm_member_id=$member_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			// バッチログ書き込み
			$batch->set_log(0, "仮登録を解除しました。（会議室ID：{$room_id}，メンバーID：{$member_id}）");
		} else {
			// バッチログ書き込み（エラー）
			$batch->set_log(9, "仮登録解除処理でエラーが発生しました。($err_msg)");
			break;
		}
	}

	// ゲスト登録から１週間経過したらナビに確認メール送信
	$sql = "SELECT rmm_room_id,rmm_member_id"
			. " FROM t_room_member"
			. " WHERE rmm_status=2 AND rmm_guest_date + interval '1 week' < $current_time AND rmm_regist_verify_mail=0";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$room_id = $fetch->rmm_room_id;
		$member_id = $fetch->rmm_member_id;

		db_begin_trans();

		// ゲスト登録確認メール送信
		send_regist_verify_mail($fetch->rmm_room_id, $fetch->rmm_member_id);

		// ゲスト登録確認メール送信済みフラグセット
		$rec['rmm_regist_verify_mail'] = sql_number(1);
		db_update('t_room_member', $rec, "rmm_room_id=$room_id AND rmm_member_id=$member_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			// バッチログ書き込み
			$batch->set_log(0, "ゲスト登録確認メールを送信しました。（会議室ID：{$room_id}，メンバーID：{$member_id}）");
		} else {
			db_rollback();

			// バッチログ書き込み（エラー）
			$batch->set_log(9, "ゲスト登録確認メール送信処理でエラーが発生しました。($err_msg)");
			break;
		}
	}
}

// ■仮登録確認メール送信
function send_kari_varify_mail($room_id, $member_id) {
	$sql = "SELECT mbr_mail_addr,rmm_nickname,rmm_kari_date,rom_name,nav_name1,nav_name2"
			. " FROM t_room_member"
			. " JOIN t_member ON mbr_member_id=rmm_member_id"
			. " JOIN t_room ON rom_room_id=rmm_room_id"
			. " JOIN t_navi_page ON nvp_room_id=rmm_room_id"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// 送信メールテンプレート取得
		get_mail_template("member_regist2", $subject, $from, $cc, $bcc, $repty_to, $body);

		// 登録URL生成
		$regist_url = get_const('user_page_url') . "?module=xxx&action=xxx&id=xxx";

		// 差込処理
		$body = str_replace('%MAIL_ADDR%', $fetch->mbr_mail_addr, $body);
		$body = str_replace('%NICKNAME%', $fetch->rmm_nickname, $body);
		$body = str_replace('%KARI_DATE%', format_datetime($fetch->rmm_kari_date), $body);
		$body = str_replace('%ML_NAME%', $fetch->rom_name, $body);
		$body = str_replace('%NAVI_NAME%', trim("$fetch->nav_name1 $fetch->nav_name2"), $body);
		$body = str_replace('%REGIST_URL%', $regist_url, $body);

		// 送信メールデータセット
		send_mail_one($subject, $from, $fetch->mbr_mail_addr, $reply_to, $body, $cc, $bcc);
	}
}

// ■ゲスト登録確認メール送信
function send_regist_verify_mail($room_id, $member_id) {
	$sql = "SELECT M1.mbr_mail_addr,rmm_nickname,rmm_guest_date,rom_name,nav_name1,nav_name2,M2.mbr_mail_addr AS navi_mail_addr"
			. " FROM t_room_member"
			. " JOIN t_member M1 ON M1.mbr_member_id=rmm_member_id"
			. " JOIN t_room ON rom_room_id=rmm_room_id"
			. " JOIN t_navi_page ON nvp_room_id=rmm_room_id"
			. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
			. " JOIN t_member M2 ON M2.mbr_member_id=nav_member_id"
			. " WHERE rmm_room_id=$room_id AND rmm_member_id=$member_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// 送信メールテンプレート取得
		get_mail_template("member_regist5", $subject, $from, $cc, $bcc, $repty_to, $body);

		// 差込処理
		$body = str_replace('%MAIL_ADDR%', $fetch->mbr_mail_addr, $body);
		$body = str_replace('%NICKNAME%', $fetch->rmm_nickname, $body);
		$body = str_replace('%GUEST_DATE%', format_datetime($fetch->rmm_guest_date), $body);
		$body = str_replace('%ML_NAME%', $fetch->rom_name, $body);
		$body = str_replace('%NAVI_NAME%', trim("$fetch->nav_name1 $fetch->nav_name2"), $body);

		// 送信メールデータセット
		send_mail_one($subject, $from, $fetch->navi_mail_addr, $reply_to, $body, $cc, $bcc);
	}
}
?>