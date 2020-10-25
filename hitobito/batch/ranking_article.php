<?php
/**
 * ひとびと･net バッチ処理
 *
 * 人気記事ランキング集計バッチ処理
 * （実行周期：毎日0時0分）
 *
 * @package
 * @author
 * @version
 */

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/const.php");
include("$inc/date.php");
include("$inc/batch.php");

// 現日時取得
if (isset($_GET['dt']))
	define('CURRENT_TIME', strtotime($_GET['dt']));	// テスト用
else
	define('CURRENT_TIME', time());

// バッチ処理クラス生成
$batch = new batch_class('ranking_article');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	// 人気記事ランキングパラメータ取得
	$sql = "SELECT * FROM t_blog_ranking_param WHERE brp_param_id=1";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$param->page_view = $fetch->brp_page_view;
		$param->trackback = $fetch->brp_trackback;
		$param->comment = $fetch->brp_comment;

		$date_to = get_date_from_ut(CURRENT_TIME);
		$date_from = add_day($date_to, -$fetch->brp_update_period);

		db_begin_trans();

		article_ranking($param, sql_date2($date_from), sql_date2($date_to));

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "人気記事ランキングを集計しました");
		} else {
			db_rollback();

			$batch->set_log(9, "人気記事ランキング集計処理でDBエラーが発生しました。($err_msg)");
		}
	}
}

// ■人気記事ランキング集計
function article_ranking($param, $date_from, $date_to) {
	$ranking_ary = array();

	// 事務局ナビID取得
	$admin_navipage_id = get_const('admin_navipage_id');

	// トラックバック数取得サブクエリ
	$sb1 = "SELECT COUNT(*) FROM t_blog_trackback WHERE blt_article_id=bla_article_id";

	// コメント数取得サブクエリ
	$sb2 = "SELECT COUNT(*) FROM t_blog_comment WHERE blo_article_id=bla_article_id";

	$sql = "SELECT bla_article_id, nvp_channel_id, pvc_pv_count, ($sb1) AS sb1_trackback_count, ($sb2) AS sb2_commant_count"
			. " FROM t_blog_article"
			. " JOIN c_page_view ON pvc_pv_id=bla_pv_id"
			. " JOIN t_blog ON blg_blog_id=bla_blog_id"
			. " JOIN t_navi_page ON nvp_blog_id=blg_blog_id"
			. " WHERE blg_status=1 AND bla_status=1 AND bla_open_flag=1 AND bla_date>=$date_from AND bla_date<$date_to";
	if ($admin_navipage_id)
		$sql .= " AND nvp_navi_page_id<>$admin_navipage_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$article_id = $fetch->bla_article_id;

		$ranking_ary[$article_id]->channel_id = $fetch->nvp_channel_id;
		$ranking_ary[$article_id]->page_view = (int)$fetch->pvc_pv_count;
		$ranking_ary[$article_id]->trackback = (int)$fetch->sb1_trackback_count;
		$ranking_ary[$article_id]->comment = (int)$fetch->sb2_commant_count;

		$ranking_ary[$article_id]->index = calc_index($ranking_ary[$article_id], $param);
	}

	// 旧人気記事ランキングデータ削除
	db_delete('t_blog_ranking', 'true');

	// DBに書き込み
	foreach ($ranking_ary as $article_id => $ranking) {
		$rec['blr_article_id'] = sql_number($article_id);
		$rec['blr_channel_id'] = sql_number($ranking->channel_id);
		$rec['blr_index'] = sql_number($ranking->index);
		$rec['blr_page_view'] = sql_number($ranking->page_view);
		$rec['blr_trackback'] = sql_number($ranking->trackback);
		$rec['blr_comment'] = sql_number($ranking->comment);
		db_insert('t_blog_ranking', $rec);
	}
}

// ■指数計算
function calc_index($ranking, $param) {
	return
				$param->page_view * $ranking->page_view +
				$param->trackback * $ranking->trackback +
				$param->comment * $ranking->comment;
}
?>