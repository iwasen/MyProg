<?
/******************************************************
' System :ICPバッチ処理
' Content:ジョブ開始終了処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/status.php");

// 二重起動チェック
if (!dup_check('lock_job'))
	exit;

// 最大実行時間（１０分）
set_time_limit(600);

// 現時刻取得
$g_time = time();
$g_sql_date = sql_date(date('Y-m-d', $g_time));

job_start();
job_end();
job_stop();
exit;

// ジョブ開始処理
function job_start() {
	global $g_job_status, $g_sql_date;

	// 待機中でジョブ開始時間になったものを検索
	$sql = "SELECT jb_job_id"
			. " FROM t_job"
			. " WHERE jb_status={$g_job_status['待機中']} AND jb_start_date<=$g_sql_date AND jb_reorder_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id = $fetch->jb_job_id;

		db_begin_trans();

		// 状態コードを実施中に設定
		$rec['jb_status'] = sql_number($g_job_status['実施中']);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "JOBID={$job_id}の開始処理でエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "JOBID={$job_id}のジョブを開始しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// ジョブ停止処理
function job_stop() {
	global $g_job_status, $g_sql_date;

	// 実施中または休止中で終了時間になったジョブを検索
	$sql = "SELECT jb_job_id"
			. " FROM t_job"
			. " WHERE (jb_status={$g_job_status['実施中']} OR jb_status={$g_job_status['休止中']}) AND jb_end_date<$g_sql_date";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id = $fetch->jb_job_id;

		db_begin_trans();

		// 状態コードを再申請猶予期間中に設定
		$rec['jb_status'] = sql_number($g_job_status['猶予中']);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "JOBID={$job_id}の停止処理でエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "JOBID={$job_id}のジョブを停止しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// ジョブ終了処理
function job_end() {
	global $g_job_status, $g_sql_date;

	// 再申請猶予期間を15日過ぎたジョブを検索
	$sql = "SELECT jb_job_id"
			. " FROM t_job"
			. " WHERE jb_status={$g_job_status['猶予中']} AND (jb_end_date + interval '15 day')<$g_sql_date AND jb_reorder_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$job_id = $fetch->jb_job_id;

		db_begin_trans();

		// 状態コードを終了に設定
		$rec['jb_status'] = sql_number($g_job_status['終了']);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// バッチログ書き込み（エラー）
			$desc = "JOBID={$job_id}の終了処理でエラーが発生しました。($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// バッチログ書き込み（正常）
			$desc = "JOBID={$job_id}のジョブを終了しました。";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}
?>