<?php
/**
 * ひとびと･net バッチ処理
 *
 * メルマガ配信バッチ処理
 * （実行周期：毎時0,15,30,45分）
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
$batch = new batch_class('melmaga_send');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	$current_time = sql_unix_time(CURRENT_TIME);

	// 未配信で送信開始日時を過ぎたものを抽出
	$sql = "SELECT mmg_melmaga_id, mmg_melmaga_name, mmg_notify_mail, mmc_melmaga_contents_id, mmc_subject, mmc_body, mmc_footer"
			. " FROM t_melmaga"
			. " JOIN t_melmaga_contents ON mmc_melmaga_id=mmg_melmaga_id"
			. " WHERE mmg_status=1 AND mmc_status=1 AND mmc_send_date<=$current_time"
			. " ORDER BY mmc_melmaga_contents_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$melmaga_id = $fetch->mmg_melmaga_id;
		$melmaga_contents_id = $fetch->mmc_melmaga_contents_id;

		db_begin_trans();

		//  メルマガ配信処理
		$send_num = send_melmaga($melmaga_id, $fetch->mmg_melmaga_name, $fetch->mmc_subject, $fetch->mmc_body, $fetch->mmc_footer);

		// ステータス（送信済み）と配信数をセット
		$rec['mmc_status'] = 2;
		$rec['mmc_send_num'] = sql_number($send_num);
		db_update('t_melmaga_contents', $rec, sprintf("mmc_melmaga_contents_id=%s", sql_number($melmaga_contents_id)));

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "メルマガ(ID={$melmaga_id}:{$melmaga_contents_id})を{$send_num}通送信しました");
		} else {
			db_rollback();

			$batch->set_log(9, "メルマガ(ID={$melmaga_id}:{$melmaga_contents_id})の配信処理でDBエラーが発生しました。($err_msg)");
			break;
		}

	}
}

//  ■メルマガ配信処理
function send_melmaga($melmaga_id, $melmaga_name, $subject, $body, $footer) {
	$from = $melmaga_name . ' <' . get_const('melmaga_from') . '>';

	$contents = make_contents('', $body, $footer);

	$send_mail_id = send_mail_data($subject, $from, '', $contents);

	// メルマガメンバーを抽出
	$sql = "SELECT mbr_member_id,mbr_mail_addr"
			. " FROM t_melmaga_member"
			. " JOIN t_member ON mbr_member_id=mmm_member_id"
			. " WHERE mmm_melmaga_id=$melmaga_id AND mbr_status=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		send_mail_member($send_mail_id, $fetch->mbr_mail_addr, '', $fetch->mbr_member_id);
	}
	$send_num = $i;

	send_mail_ok($send_mail_id);

	// 読者数,発行回数をカウントアップ
	$rec['mmn_reader_cnt'] = sql_number($send_num);
	$rec['mmn_publish_cnt'] = 'mmn_publish_cnt + 1';
	$rec['mmn_last_publish_date'] = 'current_timestamp';
	db_update('c_melmaga_count', $rec, "mmn_melmaga_id=$melmaga_id");

	return $send_num;
}
?>