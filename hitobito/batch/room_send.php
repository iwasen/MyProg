<?php
/**
 * ひとびと･net バッチ処理
 *
 * ワイワイ会議投稿メール送信バッチ処理
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
include("$inc/batch.php");
include("$inc/remark.php");

// バッチ処理クラス生成
$batch = new batch_class('room_send');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	// 未配信メール取得
	$sql = "SELECT rmk_remark_id"
			. " FROM l_room_remark"
			. " WHERE rmk_send_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$remark_id = $fetch->rmk_remark_id;

		db_begin_trans();

		// 対象者に配信
		$send_num = send_remark($remark_id);

		// 発言を配信済みにセット
		$rec['rmk_send_flag'] = sql_bool(true);
		$rec['rmk_send_num'] = sql_number($send_num);
		db_update('l_room_remark', $rec, "rmk_remark_id=$remark_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			// バッチログ書き込み（正常）
			$batch->set_log(0, "ワイワイ会議（投稿ID={$remark_id}）を{$send_num}通送信しました。");
		} else {
			db_rollback();

			// バッチログ書き込み（エラー）
			$batch->set_log(9, "ワイワイ会議（投稿ID={$remark_id}）の送信処理でエラーが発生しました。($err_msg)");
			break;
		}
	}
}
?>