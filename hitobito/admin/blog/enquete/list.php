<?php
/**
 * ひとびと･net 事務局ページ
 *
 * アンケート品質管理理ページ
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
include("$inc/decode.php");
include("$inc/ngword.php");
include("$inc/format.php");
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('アンケート一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('ｱﾝｹｰﾄID', 'center', 'nen_enquete_id', true);
$list->set_search_text('enquete_id', $_REQUEST['enquete_id'], 'number', 10);

$list->set_header('アンケート名', 'left', 'nen_title', true);
$list->set_search_text('enq_title', $_REQUEST['enq_title'], 'kanji', 10);

$list->set_header('Blog名', 'left', 'nvp_title', true);
$list->set_search_text('blog_title', $_REQUEST['blog_title'], 'kanji', 10);

$list->set_header('pickup', 'center', 'nen_pickup', true);
$list->set_search_select('pickup', select_pickup('すべて', $_REQUEST['pickup']));

$list->set_header('開始日', 'center', 'nen_start_date', true);
$list->set_search_select('start_date', select_year_month('すべて', $_REQUEST['start_date']));

$list->set_header('締切日', 'center', 'nen_end_date', true);
$list->set_search_select('enq_date', select_year_month('すべて', $_REQUEST['enq_date']));

$list->set_header('項目', 'center', 'sb1_kind');
$list->set_search_select('kind', select_remark_kind('すべて', $_REQUEST['kind']));

$list->set_header('NGワード', 'left', 'sb1_ng_word');
$list->set_search_select('ng_word', select_ngword('すべて', $_REQUEST['ng_word']));

$list->set_header('公開日', 'center', 'sb1_date');
$list->set_search_select('open_date', select_year_month('すべて', $_REQUEST['open_date']));

$list->set_header('公開', 'center', 'sb1_open_flag');
$list->set_search_select('open_flag', select_open_status('すべて', $_REQUEST['open_flag']));

$list->set_header('パトロール隊CHK', 'center', 'sb1_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('すべて', $_REQUEST['patrol_check']));

$list->set_header('事務局CHK', 'center', 'sb1_admin_check');
$list->set_search_select('admin_check', select_admin_check('すべて', $_REQUEST['admin_check']));

// リストデータ設定

$sb1 = "SELECT nen_enquete_id as sb1_enquete_id"
	. ",1 AS sb1_kind,nen_ng_word AS sb1_ng_word,nen_date AS sb1_date,nen_open_flag AS sb1_open_flag,nen_patrol_check AS sb1_patrol_check,nen_admin_check AS sb1_admin_check"
	. " FROM t_navi_enquete "
	. " UNION SELECT net_enquete_id"
	. ",2 AS kind,net_ng_word,net_date,net_open_flag,net_patrol_check,net_admin_check"
	. " FROM l_navi_enq_trackback"
	. " UNION SELECT neo_enquete_id"
	. ",3 AS kind,neo_ng_word,neo_date,neo_open_flag,neo_patrol_check,neo_admin_check"
	. " FROM l_navi_enq_comment";

$sql = "SELECT nen_enquete_id,nen_title,nvp_blog_id,nvp_title,nen_pickup,nen_start_date,nen_end_date"
	. ",sb1_kind,sb1_ng_word,sb1_date,sb1_open_flag,sb1_patrol_check,sb1_admin_check"
	. " FROM t_navi_enquete LEFT JOIN ($sb1) AS sb1 ON nen_enquete_id=sb1_enquete_id"
	. " LEFT JOIN t_navi_page ON nen_navi_page_id=nvp_navi_page_id"
. $list->get_where($where)
. $list->get_order_by(0, 1)
. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nen_enquete_id);
	$list->set_data($fetch->nen_title, 'check.php?enquete_id=' . $fetch->nen_enquete_id, '発行メルマガ情報を表示・変更します');
	if ($page_kind == 'admin')
		$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->nvp_blog_id, 'Blog登録情報を表示・変更します');
	else
		$list->set_data($fetch->nvp_title);
	$list->set_data(decode_pickup($fetch->nen_pickup));
	$list->set_data(format_datetime($fetch->nen_start_date));
	$list->set_data(format_datetime($fetch->nen_end_date));
	$list->set_data(decode_contents_kind($fetch->sb1_kind));
	$list->set_data(decode_ngword($fetch->sb1_ng_word));
	$list->set_data(format_datetime($fetch->sb1_date));
	$list->set_data(decode_open_status($fetch->sb1_open_flag));
	$list->set_data(decode_patrol_check($fetch->sb1_patrol_check));
	$list->set_data(decode_admin_check($fetch->sb1_admin_check));
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("enquete.csv");
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
