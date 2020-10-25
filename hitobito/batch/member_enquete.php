<?php
/**
 * ひとびと･net バッチ処理
 *
 * メンバーアンケート開始終了バッチ処理
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
include("$inc/batch.php");

// 現日時取得
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// テスト用
else
	define('CURRENT_TIME', time());

// バッチ処理クラス生成
$batch = new batch_class('member_enquete');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	// 対象年月取得
	$date = sql_date2(get_date_from_ut(CURRENT_TIME));

	// アンケート開始処理
	start_enquete($date);

	// アンケート終了処理
	end_enquete($date);
}

// ■アンケート開始処理
function start_enquete($date) {
	global $batch;

	$sql = "SELECT ren_enquete_id"
		. " FROM t_room_enquete"
		. " WHERE ren_status=1 AND ren_start_date<=$date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->ren_enquete_id;

		db_begin_trans();

		$rec['ren_status'] = sql_number(2);
		db_update('t_room_enquete', $rec, "ren_enquete_id=$enquete_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "メンバーアンケート（ID={$enquete_id}）を開始しました");
		} else {
			db_rollback();

			$batch->set_log(9, "メンバーアンケート開始処理でDBエラーが発生しました。($err_msg)");
		}
	}
}

// ■アンケート終了処理
function end_enquete($date) {
	global $batch;

	$sql = "SELECT ren_enquete_id"
		. " FROM t_room_enquete"
		. " WHERE ren_status=2 AND ren_end_date<$date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->ren_enquete_id;

		db_begin_trans();

		$rec['ren_status'] = sql_number(9);
		db_update('t_room_enquete', $rec, "ren_enquete_id=$enquete_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "メンバーアンケート（ID={$enquete_id}）を終了しました");
		} else {
			db_rollback();

			$batch->set_log(9, "メンバーアンケート終了処理でDBエラーが発生しました。($err_msg)");
		}
	}
}
?>