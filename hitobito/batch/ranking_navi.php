<?php
/**
 * ひとびと･net バッチ処理
 *
 * ナビランキング集計バッチ処理
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
$batch = new batch_class('ranking_navi');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	$date = get_date_from_ut(CURRENT_TIME);

	// 更新頻度取得
	$sql = "SELECT nrp_update_mode FROM t_navi_ranking_param WHERE nrp_param_id=1";
	$update_mode = db_fetch1($sql);

	// 更新タイミングチェック
	$update_flag = false;
	switch ($update_mode) {
	case 1:		// 毎月1日更新
		if ($date['d'] == 1) {
			$update_flag = true;
			$date_from = add_month($date, -1);
		}
		break;
	case 2:		// 毎月1日、16日更新
		$day = date('j', CURRENT_TIME);
		if ($date['d'] == 1) {
			$update_flag = true;
			$date_from = add_month($date, -1);
			$date_from['d'] = 16;
		} elseif ($date['d'] == 16) {
			$update_flag = true;
			$date_from = $date;
			$date_from['d'] = 1;
		}
		break;
	case 3:		// 毎週月曜日更新
		$week = date('w', CURRENT_TIME);
		if ($week == 1) {
			$update_flag = true;
			$date_from = add_day($date, -7);
		}
		break;
	}

	if ($update_flag) {
		db_begin_trans();

		navi_ranking(sql_date2($date_from), sql_date2($date));

		// エラーチェック
		$err_msg = db_errormessage();
		if ($err_msg == '') {
			db_commit_trans();

			$batch->set_log(0, "ナビランキングを集計しました");
		} else {
			db_rollback();

			$batch->set_log(9, "ナビランキング集計処理でDBエラーが発生しました。($err_msg)");
		}
	}
}

// ■ナビランキング集計
function navi_ranking($date_from, $date_to) {
	$sql = "SELECT nrp_ml_member_num, nrp_ml_member_up, nrp_ml_total_remark, nrp_ml_page_view, nrp_ml_member_remark, nrp_blog_new_post, nrp_blog_page_view, nrp_blog_trackback, nrp_blog_comment"
 			. " FROM t_navi_ranking_param"
			. " WHERE nrp_param_id=1";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$param->ml_member_num = $fetch->nrp_ml_member_num;
		$param->ml_member_up = $fetch->nrp_ml_member_up;
		$param->ml_total_remark = $fetch->nrp_ml_total_remark;
		$param->ml_page_view = $fetch->nrp_ml_page_view;
		$param->ml_member_remark = $fetch->nrp_ml_member_remark;
		$param->blog_new_post = $fetch->nrp_blog_new_post;
		$param->blog_page_view = $fetch->nrp_blog_page_view;
		$param->blog_trackback = $fetch->nrp_blog_trackback;
		$param->blog_comment = $fetch->nrp_blog_comment;
	}

	// 各パラメータをナビページ毎に集計
	$ml_member_num_ary = get_ml_member_num();
	$ml_member_up_ary = get_ml_member_up($date_from, $date_to);
	$ml_total_remark_ary = get_ml_total_remark($date_from, $date_to);
	$ml_page_view_ary = get_ml_page_view($date_from, $date_to);
	$blog_new_post_ary = get_blog_new_post($date_from, $date_to);
	$blog_page_view_ary = get_blog_page_view($date_from, $date_to);
	$blog_trackback_ary = get_blog_trackback($date_from, $date_to);
	$blog_comment_ary = get_blog_comment($date_from, $date_to);

	// 事務局ナビID取得
	$admin_navipage_id = get_const('admin_navipage_id');

	// 各ナビページの指数を計算
	$ranking_ary = array();
	$sql = "SELECT nvp_navi_page_id"
			. " FROM t_navi_page"
			. " WHERE nvp_status=1 AND nvp_open_flag=1";
	if ($admin_navipage_id)
		$sql .= " AND nvp_navi_page_id<>$admin_navipage_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$navi_page_id = $fetch->nvp_navi_page_id;

		$ranking_ary[$navi_page_id]->ml_member_num = (int)$ml_member_num_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->ml_member_up = (int)$ml_member_up_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->ml_total_remark = (int)$ml_total_remark_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->ml_page_view = (int)$ml_page_view_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->ml_member_remark = (int)($ml_member_num_ary[$navi_page_id] ? $ml_total_remark_ary[$navi_page_id] / $ml_member_num_ary[$navi_page_id] * 100 : 0);
		$ranking_ary[$navi_page_id]->blog_new_post = (int)$blog_new_post_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->blog_page_view = (int)$blog_page_view_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->blog_trackback = (int)$blog_trackback_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->blog_comment = (int)$blog_comment_ary[$navi_page_id];
		$ranking_ary[$navi_page_id]->index = calc_index($ranking_ary[$navi_page_id], $param);
	}

	// 指数の大きい順にソート
	uasort($ranking_ary, create_function('$a, $b', 'return $b->index - $a->index;'));

	// 古いデータを削除
	db_delete('t_navi_ranking', 'true');

	// DBに書き込み
	$rank_no = 1;
	foreach ($ranking_ary as $navi_page_id => $ranking) {
		$rec['nvr_navi_page_id'] = sql_number($navi_page_id);
		$rec['nvr_rank_no'] = sql_number($rank_no++);
		$rec['nvr_ml_member_num'] = sql_number($ranking->ml_member_num);
		$rec['nvr_ml_member_up'] = sql_number($ranking->ml_member_up);
		$rec['nvr_ml_total_remark'] = sql_number($ranking->ml_total_remark);
		$rec['nvr_ml_page_view'] = sql_number($ranking->ml_page_view);
		$rec['nvr_ml_member_remark'] = sql_number($ranking->ml_member_remark);
		$rec['nvr_blog_new_post'] = sql_number($ranking->blog_new_post);
		$rec['nvr_blog_page_view'] = sql_number($ranking->blog_page_view);
		$rec['nvr_blog_trackback'] = sql_number($ranking->blog_trackback);
		$rec['nvr_blog_comment'] = sql_number($ranking->blog_comment);
		db_insert('t_navi_ranking', $rec);
	}
}

// ■MLメンバー数集計
function get_ml_member_num() {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " JOIN t_room_member ON rmm_room_id=rom_room_id"
			. " WHERE rom_status IN (1,2) AND rmm_status=3"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}
// ■メンバー増加数集計
function get_ml_member_up($date_from, $date_to) {
	$ary = array();

	// 入会者数
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " JOIN t_room_member ON rmm_room_id=rom_room_id"
			. " WHERE rom_status IN (1,2) AND rmm_regist_date>=$date_from AND rmm_regist_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	// 退会者数
	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " JOIN t_room_member ON rmm_room_id=rom_room_id"
			. " WHERE rom_status IN (1,2) AND rmm_resign_date>=$date_from AND rmm_resign_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] -= $fetch->cnt;
	}

	return $ary;
}

// ■ML全体発言数集計
function get_ml_total_remark($date_from, $date_to) {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " JOIN l_room_remark ON rmk_room_id=rom_room_id"
			. " WHERE rom_status IN (1,2) AND rmk_status=1 AND rmk_date>=$date_from AND rmk_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}

// ■MLページビュー集計
function get_ml_page_view($date_from, $date_to) {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_room ON rom_room_id=nvp_room_id"
			. " JOIN l_room_remark ON rmk_room_id=rom_room_id"
			. " JOIN l_page_view ON pvl_pv_id=rmk_pv_id"
			. " WHERE rom_status IN (1,2) AND rmk_status=1 AND pvl_date>=$date_from AND pvl_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}

// ■新規記事投稿数集計
function get_blog_new_post($date_from, $date_to) {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_blog ON blg_blog_id=nvp_blog_id"
			. " JOIN t_blog_article ON bla_blog_id=blg_blog_id"
			. " WHERE blg_status=1 AND bla_status=1 AND bla_date>=$date_from AND bla_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}

// ■記事ページビュー集計
function get_blog_page_view($date_from, $date_to) {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_blog ON blg_blog_id=nvp_blog_id"
			. " JOIN t_blog_article ON bla_blog_id=blg_blog_id"
			. " JOIN l_page_view ON pvl_pv_id=bla_pv_id"
			. " WHERE blg_status=1 AND bla_status=1 AND pvl_date>=$date_from AND pvl_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}

// ■記事トラックバック数集計
function get_blog_trackback($date_from, $date_to) {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_blog ON blg_blog_id=nvp_blog_id"
			. " JOIN t_blog_article ON bla_blog_id=blg_blog_id"
			. " JOIN t_blog_trackback ON blt_article_id=bla_article_id"
			. " WHERE blg_status=1 AND bla_status=1 AND blt_date>=$date_from AND blt_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}

// ■記事コメント数集計
function get_blog_comment($date_from, $date_to) {
	$ary = array();

	$sql = "SELECT nvp_navi_page_id, COUNT(*) AS cnt"
			. " FROM t_navi_page"
			. " JOIN t_blog ON blg_blog_id=nvp_blog_id"
			. " JOIN t_blog_article ON bla_blog_id=blg_blog_id"
			. " JOIN t_blog_comment ON blo_article_id=bla_article_id"
			. " WHERE blg_status=1 AND bla_status=1 AND blo_date>=$date_from AND blo_date<$date_to"
			. " GROUP BY nvp_navi_page_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_navi_page_id] = $fetch->cnt;
	}

	return $ary;
}

// ■指数計算
function calc_index($ranking, $param) {
	return
			$param->ml_member_num * $ranking->ml_member_num +
			$param->ml_member_up * $ranking->ml_member_up +
			$param->ml_total_remark * $ranking->ml_total_remark +
			$param->ml_page_view * $ranking->ml_page_view +
			$param->ml_member_remark * $ranking->ml_member_remark / 100 +
			$param->blog_new_post * $ranking->blog_new_post +
			$param->blog_page_view * $ranking->blog_page_view +
			$param->blog_trackback * $ranking->blog_trackback +
			$param->blog_comment * $ranking->blog_comment;
}
?>