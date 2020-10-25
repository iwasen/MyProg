<?php
/**
 * ひとびと･net バッチ処理
 *
 * ひとびと便り配信バッチ処理
 * （実行周期：毎時０分）
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
include("$inc/batch.php");

// 現日時取得
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// テスト用
else
	define('CURRENT_TIME', time());

// バッチ処理クラス生成
$batch = new batch_class('hitobito_letter');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	$current_time = sql_unix_time(CURRENT_TIME);

	// 未配信で送信開始日時を過ぎたものを抽出
	$sql = "SELECT hbl_letter_id, hbl_subject, hbl_from_addr, hbl_header, hbl_body, hbl_footer"
			. " FROM t_hitobito_letter"
			. " WHERE hbl_status=2 AND hbl_send_date<=$current_time"
			. " ORDER BY hbl_letter_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$letter_id = $fetch->hbl_letter_id;

		db_begin_trans();

		//  ひとびと便り配信処理
		$send_num = send_hitobito_letter($letter_id, $fetch->hbl_subject, $fetch->hbl_from_addr, $fetch->hbl_header, $fetch->hbl_body, $fetch->hbl_footer);

		// ステータス（送信中）と配信数をセット
		$rec['hbl_status'] = 3;
		$rec['hbl_send_num'] = sql_number($send_num);
		db_update('t_hitobito_letter', $rec, sprintf("hbl_letter_id=%s", sql_number($letter_id)));

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "ひとびと便り(ID={$letter_id})を{$send_num}通送信しました");
		} else {
			db_rollback();

			$batch->set_log(9, "ひとびと便り(ID={$letter_id})の配信処理でDBエラーが発生しました。($err_msg)");
			break;
		}
	}
}

//  ■ひとびと便り配信処理
function send_hitobito_letter($letter_id, $subject, $from_addr, $header, $body, $footer) {
	// 差出人アドレス取得
	$from = get_const('hitobito_letter_from');

	// ヘッダ、本文、フッタを結合
	$contents = make_contents($header, $body, $footer);

	// 送信メール設定
	$sql = sprintf("UPDATE t_hitobito_letter SET hbl_status=4, hbl_end_date=current_timestamp WHERE hbl_letter_id=%s", sql_number($letter_id));
	$send_mail_id = send_mail_data($subject, $from, '', $contents, '', '', '0', $sql);

	// 配信先設定
	$sql = "SELECT mbr_member_id,mbr_mail_addr FROM t_member WHERE mbr_status=1 AND mbr_hitobito_letter=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$sub_seq = $i + 1;

		// 送信先セット
		send_mail_member($send_mail_id, $fetch->mbr_mail_addr, $sub_seq, $fetch->mbr_member_id);

		// 差込変数セット
		send_mail_embed($send_mail_id, $sub_seq, '%MEMBER_ID%', $fetch->mbr_member_id);
	}
	$send_num = $i;

	send_mail_ok($send_mail_id);

	return $send_num;
}
?>