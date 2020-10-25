<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ品質管理理ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/list.php");
include("$inc/format.php");
include("$inc/ngword.php");
include("$inc/decode.php");
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('メルマガ品質管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('マガジンID', 'center', 'mmc_melmaga_contents_id', true);
$list->set_search_text('melmaga_contents_id', $_REQUEST['melmaga_contents_id'], 'number', 10);

$list->set_header('題名', 'left', 'mmc_subject', true);
$list->set_search_text('subject', $_REQUEST['subject'], 'kanji', 10);

$list->set_header('メルマガ名', 'left', 'mmg_melmaga_name', true);
$list->set_search_text('melmaga_name', $_REQUEST['melmaga_name'], 'kanji', 10);

$list->set_header('Blog名', 'left', 'nvp_title', true);
$list->set_search_text('blog_name', $_REQUEST['blog_name'], 'kanji', 10);

$list->set_header('ステータス', 'center', 'mmc_status', true);
$list->set_search_select('status', select_melmaga_send_status('すべて', $_REQUEST['status']));

$list->set_header('pickup', 'center', 'mmc_pickup', true);
$list->set_search_select('pickup', select_pickup('すべて', $_REQUEST['pickup']));

$list->set_header('項目', 'center', 'sb1_kind');
$list->set_search_select('kind', select_remark_kind('すべて', $_REQUEST['kind']));

$list->set_header('NGワード', 'left', 'sb1_ng_word');
$list->set_search_select('ng_word', select_ngword('すべて', $_REQUEST['ng_word']));

$list->set_header('発行・登録日', 'center', 'sb1_date');
$list->set_search_select('date', select_year_month('すべて', $_REQUEST['date']));

$list->set_header('公開', 'center', 'sb1_open_flag');
$list->set_search_select('open_flag', select_open_status('すべて', $_REQUEST['open_flag']));

$list->set_header('パトロール隊CHK', 'center', 'sb1_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('すべて', $_REQUEST['patrol_check']));

$list->set_header('事務局CHK', 'center', 'sb1_admin_check');
$list->set_search_select('admin_check', select_admin_check('すべて', $_REQUEST['admin_check']));

// リストデータ設定
$sb1 = "SELECT mmc_melmaga_contents_id AS sb1_melmaga_contents_id"
	. ",1 AS sb1_kind,mmc_ng_word AS sb1_ng_word,mmc_send_date AS sb1_date,mmc_open_flag AS sb1_open_flag,mmc_patrol_check AS sb1_patrol_check,mmc_admin_check AS sb1_admin_check"
	. " FROM t_melmaga_contents"
	. " UNION SELECT mmt_melmaga_contents_id"
	. ",2 AS kind,mmt_ng_word,mmt_date,mmt_open_flag,mmt_patrol_check,mmt_admin_check"
	. " FROM l_melmaga_trackback"
	. " UNION SELECT mmo_melmaga_contents_id"
	. ",3 AS kind,mmo_ng_word,mmo_date,mmo_open_flag,mmo_patrol_check,mmo_admin_check"
	. " FROM l_melmaga_comment";

$sql = "SELECT mmc_melmaga_contents_id,mmc_subject,mmc_melmaga_id,mmg_melmaga_name,nvp_blog_id,nvp_title,mmc_status,mmc_pickup"
	. ",sb1_kind,sb1_ng_word,sb1_date,sb1_open_flag,sb1_patrol_check,sb1_admin_check"
	. " FROM t_melmaga_contents LEFT JOIN ($sb1) AS sb1 ON mmc_melmaga_contents_id=sb1_melmaga_contents_id"
	. " LEFT JOIN t_melmaga ON mmc_melmaga_id=mmg_melmaga_id"
	. " LEFT JOIN t_navi_page ON mmg_melmaga_id=nvp_melmaga_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mmc_melmaga_contents_id);
	$list->set_data($fetch->mmc_subject, 'check.php?melmaga_contents_id=' . $fetch->mmc_melmaga_contents_id, '発行メルマガ情報を表示・変更します');
	if ($page_kind == 'admin') {
		$list->set_data($fetch->mmg_melmaga_name, "$top/navi/publisher/edit.php?melmaga_id=" . $fetch->mmc_melmaga_id, 'メルマガ登録情報を表示・変更します');
		$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->nvp_blog_id, 'Blog登録情報を表示・変更します');
	} else {
		$list->set_data($fetch->mmg_melmaga_name);
		$list->set_data($fetch->nvp_title);
	}
	$list->set_data(decode_melmaga_send_status($fetch->mmc_status));
	$list->set_data(decode_pickup($fetch->mmc_pickup));
	$list->set_data(decode_contents_kind($fetch->sb1_kind));
	$list->set_data(decode_ngword($fetch->sb1_ng_word));
	$list->set_data(format_datetime($fetch->sb1_date));
	$list->set_data(decode_open_status($fetch->sb1_open_flag));
	$list->set_data(decode_patrol_check($fetch->sb1_patrol_check));
	$list->set_data(decode_admin_check($fetch->sb1_admin_check));
}

// メルマガステータス
function decode_melmaga_send_status($code, $def='') {
	switch ($code) {
	case '1':
		return '未配信';
	case '2':
		return '配信済';
	}
	return $def;
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("magazine.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<input type="submit" name="export" value="エクスポート">
			<input type="submit" value="再検索">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
