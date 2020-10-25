<?php
/**
 * ひとびと･net バッチ処理
 *
 * サイトプロファイル集計バッチ処理
 * （実行周期：毎月1日0時0分）
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
$batch = new batch_class('site_profile');

// メイン処理
if ($batch->executable)
	main_process();

exit;

// ■メイン処理
function main_process() {
	global $batch;

	db_begin_trans();

	// 対象年月取得
	$date = get_date_from_ut(CURRENT_TIME);
	$date['d'] = 1;
	$date_from = sql_date2($date);
	$date = add_month($date, 1);
	$date_to = sql_date2($date);

	// ナビ数取得
	$navi_num = get_navi_num();

	// メンバー数取得
	$member_num = get_member_num();

	// 便り登録者数取得
	$letter_member = get_letter_member();

	// Blog数取得
	$blog_num = get_blog_num();

	// ワイワイ会議数取得
	$room_num = get_room_num();

	// ナビ記事数／月取得
	$article_num = get_article_num($date_from, $date_to);

	// 発言人数／月取得
	$remark_num = get_remark_num($date_from, $date_to);

	// 発言数／月取得
	$remark_count = get_remark_count($date_from, $date_to);

	// メール配信数／月
	$mail_num = get_mail_num($date_from, $date_to);

	// RAM率／月取得
	$room_member_num = get_room_member_num();
	$ram_rate = $room_member_num ? round($remark_num / $room_member_num * 100) : 0;

	// ページビュー数／月
	$page_view = get_page_view($date_from, $date_to);

	// 訪問者数／月
	$visitor_num = get_visitor_num($date_from, $date_to);

	// クリック数／月
	$click_num = get_click_num($date_from, $date_to);

	// トラックバック数／月
	$trackback_num = get_trackback_num($date_from, $date_to);

	// コメント数／月
	$comment_num = get_comment_num($date_from, $date_to);

	// チャネル別ナビページ数取得
	$navi_page_num_ary = get_navi_page_num($date_from, $date_to);

	// サイトプロファイルをDBに書き込み
	$rec['stp_navi_num'] = sql_number($navi_num);
	$rec['stp_member_num'] = sql_number($member_num);
	$rec['stp_letter_member'] = sql_number($letter_member);
	$rec['stp_blog_num'] = sql_number($blog_num);
	$rec['stp_room_num'] = sql_number($room_num);
	$rec['stp_article_num'] = sql_number($article_num);
	$rec['stp_remark_num'] = sql_number($remark_num);
	$rec['stp_remark_count'] = sql_number($remark_count);
	$rec['stp_mail_num'] = sql_number($mail_num);
	$rec['stp_ram_rate'] = sql_number($ram_rate);
	$rec['stp_page_view'] = sql_number($page_view);
	$rec['stp_visitor_num'] = sql_number($visitor_num);
	$rec['stp_click_num'] = sql_number($click_num);
	$rec['stp_trackback_num'] = sql_number($trackback_num);
	$rec['stp_comment_num'] = sql_number($comment_num);
	db_insert_update('t_site_profile', $rec, 'stp_date', $date_from);

	// チャネル別ナビページ数をDBに書き込み
	db_delete('t_site_navi_page', "snp_date=$date_from");
	foreach ($navi_page_num_ary as $channel_id => $navi_page_num) {
		$rec['snp_date'] = $date_from;
		$rec['snp_channel_id'] = sql_number($channel_id);
		$rec['snp_navi_page_num'] = sql_number($navi_page_num);
		db_insert('t_site_navi_page', $rec);
	}

	// エラーチェック
	$err_msg = db_errormessage();
	if ($err_msg == '') {
		db_commit_trans();

		$batch->set_log(0, "サイトプロファイル({$date['y']}/{$date['m']})を集計しました");
	} else {
		db_rollback();

		$batch->set_log(9, "サイトプロファイル集計処理でDBエラーが発生しました。($err_msg)");
	}
}

// ■ナビ数取得
function get_navi_num() {
	$sql = "SELECT COUNT(*) FROM t_navi WHERE nav_status=1";
	return db_fetch1($sql);
}

// ■メンバー数取得
function get_member_num() {
	$sql = "SELECT COUNT(*) FROM t_member WHERE mbr_status=1";
	return db_fetch1($sql);
}

// ■便り登録者数取得
function get_letter_member() {
	$sql = "SELECT COUNT(*) FROM t_member WHERE mbr_status=1 AND mbr_hitobito_letter=1";
	return db_fetch1($sql);
}

// ■Blog数取得
function get_blog_num() {
	$sql = "SELECT COUNT(*) FROM t_blog WHERE blg_status=1";
	return db_fetch1($sql);
}

// ■ワイワイ会議数取得
function get_room_num() {
	$sql = "SELECT COUNT(*) FROM t_room WHERE rom_status IN (1,2)";
	return db_fetch1($sql);
}

// ■ナビ記事数／月取得
function get_article_num($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM t_blog_article WHERE bla_date>=$date_from AND bla_date<$date_to";
	return db_fetch1($sql);
}

// ■発言人数／月取得
function get_remark_num($date_from, $date_to) {
	$sql = "SELECT COUNT(DISTINCT rmk_member_id) FROM l_room_remark WHERE rmk_date>=$date_from AND rmk_date<$date_to";
	return db_fetch1($sql);
}

// ■発言数／月取得
function get_remark_count($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM l_room_remark WHERE rmk_date>=$date_from AND rmk_date<$date_to";
	return db_fetch1($sql);
}

// ■メール配信数／月
function get_mail_num($date_from, $date_to) {
	// ひとびと便り配信数
	$sql = "SELECT SUM(hbl_send_num) FROM t_hitobito_letter WHERE hbl_status=4 AND hbl_send_date>=$date_from AND hbl_send_date<$date_to";
	$letter = db_fetch1($sql);

	// メルマガ配信数
	$sql = "SELECT SUM(mmc_send_num) FROM t_melmaga_contents WHERE mmc_status=2 AND mmc_send_date>=$date_from AND mmc_send_date<$date_to";
	$melmaga = db_fetch1($sql);

	// ワイワイ会議配信数
	$sql = "SELECT SUM(rmk_send_num) FROM l_room_remark WHERE rmk_send_flag=true AND rmk_date>=$date_from AND rmk_date<$date_to";
	$room = db_fetch1($sql);

	return $letter + $melmaga + $room;
}

// ■ワイワイ会議数登録者数
function get_room_member_num() {
	$sql = "SELECT COUNT(*) FROM t_room_member WHERE rmm_status=3";
	return db_fetch1($sql);
}

// ■ページビュー数
function get_page_view($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM l_page_view WHERE pvl_date>=$date_from AND pvl_date<$date_to";
	return db_fetch1($sql);
}

// ■訪問者数数
function get_visitor_num($date_from, $date_to) {
	$sql = "SELECT COUNT(DISTINCT pvl_ip_addr) FROM l_page_view WHERE pvl_date>=$date_from AND pvl_date<$date_to";
	return db_fetch1($sql);
}

// ■クリック数／月
function get_click_num($date_from, $date_to) {
	$sql = "SELECT COUNT(*) FROM l_banner_click WHERE bnl_date>=$date_from AND bnl_date<$date_to";
	return db_fetch1($sql);
}

// ■トラックバック数／月
function get_trackback_num($date_from, $date_to) {
	// blogトラックバック数
	$sql = "SELECT COUNT(*) FROM t_blog_trackback WHERE blt_date>=$date_from AND blt_date<$date_to";
	$blog = db_fetch1($sql);

	// アンケートトラックバック数
	$sql = "SELECT COUNT(*) FROM l_navi_enq_trackback WHERE net_date>=$date_from AND net_date<$date_to";
	$enquete = db_fetch1($sql);

	return $blog + $enquete;
}

// ■コメント数／月
function get_comment_num($date_from, $date_to) {
	// blogコメント数
	$sql = "SELECT COUNT(*) FROM t_blog_comment WHERE blo_date>=$date_from AND blo_date<$date_to";
	$blog = db_fetch1($sql);

	// アンケートコメント数
	$sql = "SELECT COUNT(*) FROM l_navi_enq_comment WHERE neo_date>=$date_from AND neo_date<$date_to";
	$enquete = db_fetch1($sql);

	// メルマガコメント数
	$sql = "SELECT COUNT(*) FROM l_melmaga_comment WHERE mmo_date>=$date_from AND mmo_date<$date_to";
	$melmaga = db_fetch1($sql);

	return $blog + $enquete + $melmaga;
}

// ■チャネル別ナビページ数取得
function get_navi_page_num() {
	$ary = array();

	$sql = "SELECT nvp_channel_id, COUNT(*) AS navi_page_num FROM t_navi_page WHERE nvp_status=1 AND nvp_open_flag=1 GROUP BY nvp_channel_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->nvp_channel_id] = $fetch->navi_page_num;
	}

	return $ary;
}
?>