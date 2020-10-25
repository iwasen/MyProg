<?php
/**
 * ひとびと･net バッチ処理
 *
 * スケジュール集計バッチ処理
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

// バッチ処理クラス生成
$batch = new batch_class('schedule');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	db_begin_trans();

	// スケジュール
	schedule_count();

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();

		$batch->set_log(0, "スケジュール集計処理を実行しました");
	} else {
		db_rollback();

		$batch->set_log(9, "スケジュール集計処理でDBエラーが発生しました。($err_msg)");
	}
}

// ■スケジュールデータ集計
function schedule_count() {
	$cnt = array();

	// Blog
	$sql = "SELECT COUNT(*) AS cnt_total,date(bla_date) AS cnt_date,"
			. " COUNT(CASE WHEN bla_patrol_check=1 THEN 1 END) AS cnt_nocheck,"
			. " COUNT(CASE WHEN bla_patrol_check=3 THEN 1 END) AS cnt_problem,"
			. " COUNT(CASE WHEN bla_pickup=1 THEN 1 END) AS cnt_pickup"
			. " FROM t_blog_article"
			. " WHERE bla_status=1"
			. " GROUP BY cnt_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$date = $fetch->cnt_date;
		$cnt[$date]['sch_blog_total'] = $fetch->cnt_total;
		$cnt[$date]['sch_blog_nocheck'] = $fetch->cnt_nocheck;
		$cnt[$date]['sch_blog_problem'] = $fetch->cnt_problem;
		$cnt[$date]['sch_blog_pickup'] = $fetch->cnt_pickup;
	}

	// ML
	$sql = "SELECT COUNT(*) AS cnt_total,date(rmk_date) AS cnt_date,"
			. " COUNT(CASE WHEN rmk_patrol_check=1 THEN 1 END) AS cnt_nocheck,"
			. " COUNT(CASE WHEN rmk_patrol_check=3 THEN 1 END) AS cnt_problem,"
			. " COUNT(CASE WHEN rmk_pickup=1 THEN 1 END) AS cnt_pickup"
			. " FROM l_room_remark"
			. " WHERE rmk_status=1"
			. " GROUP BY cnt_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$date = $fetch->cnt_date;
		$cnt[$date]['sch_ml_total'] = $fetch->cnt_total;
		$cnt[$date]['sch_ml_nocheck'] = $fetch->cnt_nocheck;
		$cnt[$date]['sch_ml_problem'] = $fetch->cnt_problem;
		$cnt[$date]['sch_ml_pickup'] = $fetch->cnt_pickup;
	}

	// メルマガ
	$sql = "SELECT COUNT(*) AS cnt_total,date(mmc_date) AS cnt_date,"
			. " COUNT(CASE WHEN mmc_patrol_check=1 THEN 1 END) AS cnt_nocheck,"
			. " COUNT(CASE WHEN mmc_patrol_check=3 THEN 1 END) AS cnt_problem,"
			. " COUNT(CASE WHEN mmc_pickup=1 THEN 1 END) AS cnt_pickup"
			. " FROM t_melmaga_contents"
			. " GROUP BY cnt_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$date = $fetch->cnt_date;
		$cnt[$date]['sch_melmaga_total'] = $fetch->cnt_total;
		$cnt[$date]['sch_melmaga_nocheck'] = $fetch->cnt_nocheck;
		$cnt[$date]['sch_melmaga_problem'] = $fetch->cnt_problem;
		$cnt[$date]['sch_melmaga_pickup'] = $fetch->cnt_pickup;
	}

	// アンケート
	$sql = "SELECT COUNT(*) AS cnt_total,date(nen_date) AS cnt_date,"
			. " COUNT(CASE WHEN nen_patrol_check=1 THEN 1 END) AS cnt_nocheck,"
			. " COUNT(CASE WHEN nen_patrol_check=3 THEN 1 END) AS cnt_problem,"
			. " COUNT(CASE WHEN nen_pickup=1 THEN 1 END) AS cnt_pickup"
			. " FROM t_navi_enquete"
			. " GROUP BY cnt_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$date = $fetch->cnt_date;
		$cnt[$date]['sch_enquete_total'] = $fetch->cnt_total;
		$cnt[$date]['sch_enquete_nocheck'] = $fetch->cnt_nocheck;
		$cnt[$date]['sch_enquete_problem'] = $fetch->cnt_problem;
		$cnt[$date]['sch_enquete_pickup'] = $fetch->cnt_pickup;
	}

	// リンク
	$sql = "SELECT COUNT(*) AS cnt_total,date(nol_date) AS cnt_date,"
			. " COUNT(CASE WHEN nol_patrol_check=1 THEN 1 END) AS cnt_nocheck,"
			. " COUNT(CASE WHEN nol_patrol_check=3 THEN 1 END) AS cnt_problem,"
			. " COUNT(CASE WHEN nol_pickup=1 THEN 1 END) AS cnt_pickup"
			. " FROM t_navi_osusume_link"
			. " GROUP BY cnt_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$date = $fetch->cnt_date;
		$cnt[$date]['sch_link_total'] = $fetch->cnt_total;
		$cnt[$date]['sch_link_nocheck'] = $fetch->cnt_nocheck;
		$cnt[$date]['sch_link_problem'] = $fetch->cnt_problem;
		$cnt[$date]['sch_link_pickup'] = $fetch->cnt_pickup;
	}

	db_delete('t_schedule', "true");

	foreach ($cnt as $date => $rec) {
		$rec['sch_date'] = sql_date($date);
		$rec['sch_blog_total'] = sql_number($rec['sch_blog_total'], 0);
		$rec['sch_blog_nocheck'] = sql_number($rec['sch_blog_nocheck'], 0);
		$rec['sch_blog_problem'] = sql_number($rec['sch_blog_problem'], 0);
		$rec['sch_blog_pickup'] = sql_number($rec['sch_blog_pickup'], 0);
		$rec['sch_ml_total'] = sql_number($rec['sch_ml_total'], 0);
		$rec['sch_ml_nocheck'] = sql_number($rec['sch_ml_nocheck'], 0);
		$rec['sch_ml_problem'] = sql_number($rec['sch_ml_problem'], 0);
		$rec['sch_ml_pickup'] = sql_number($rec['sch_ml_pickup'], 0);
		$rec['sch_melmaga_total'] = sql_number($rec['sch_melmaga_total'], 0);
		$rec['sch_melmaga_nocheck'] = sql_number($rec['sch_melmaga_nocheck'], 0);
		$rec['sch_melmaga_problem'] = sql_number($rec['sch_melmaga_problem'], 0);
		$rec['sch_melmaga_pickup'] = sql_number($rec['sch_melmaga_pickup'], 0);
		$rec['sch_enquete_total'] = sql_number($rec['sch_enquete_total'], 0);
		$rec['sch_enquete_nocheck'] = sql_number($rec['sch_enquete_nocheck'], 0);
		$rec['sch_enquete_problem'] = sql_number($rec['sch_enquete_problem'], 0);
		$rec['sch_enquete_pickup'] = sql_number($rec['sch_enquete_pickup'], 0);
		$rec['sch_link_total'] = sql_number($rec['sch_link_total'], 0);
		$rec['sch_link_nocheck'] = sql_number($rec['sch_link_nocheck'], 0);
		$rec['sch_link_problem'] = sql_number($rec['sch_link_problem'], 0);
		$rec['sch_link_pickup'] = sql_number($rec['sch_link_pickup'], 0);

		db_insert('t_schedule', $rec);
	}
}
?>