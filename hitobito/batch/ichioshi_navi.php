<?php
/**
 * ひとびと･net バッチ処理
 *
 * イチオシナビ集計バッチ処理
 * （実行周期：毎時０分）
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
include("$inc/ichioshi_navi.php");

// 現日時取得
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// テスト用
else
	define('CURRENT_TIME', time());

// バッチ処理クラス生成
$batch = new batch_class('ichioshi_navi');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	$sql = "SELECT inu_update_mode, inu_update_time FROM t_ichioshi_navi_update";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($fetch->inu_update_mode == 2 && $fetch->inu_update_time == date('H', CURRENT_TIME)) {
			db_begin_trans();

			select_ichioshi_navi();

			// エラーチェック
			$err_msg = db_errormessage();
			if ($err_msg == '') {
				db_commit_trans();

				$batch->set_log(0, "今日のイチオシナビを更新しました");
			} else {
				db_rollback();

				$batch->set_log(9, "今日のイチオシナビ更新処理でDBエラーが発生しました。($err_msg)");
			}
		}
	}
}
?>