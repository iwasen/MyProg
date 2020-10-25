<?php
/**
 * ひとびと･net 事務局ページ
 *
 * Blog活性化状況ページ
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
include("$inc/edit.php");
include("$inc/list.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('Blog活性化状況', BACK_TOP);

// 入力パラメータ
$year = $_POST['year'];
$month = $_POST['month'];

// デフォルトの年を設定
if (!$year)
	$year = date('Y');

// デフォルトの月を設定
if (!$month)
	$month = date('m');

// 前年
if (isset($_POST['prev_month'])) {
	if (--$month < 1) {
		$year--;
		$month = 12;
	}
}

// 翌年
if (isset($_POST['next_month'])) {
	if (++$month > 12) {
		$year++;
		$month = 1;
	}
}

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('BlogID', 'center', 'nvp_navi_page_id');

$list->set_header('ナビページ', 'left', 'nvp_title');
$list->set_search_text('navi_page_title', $_REQUEST['navi_page_title'], alpha, 20);

$list->set_header('記事投稿数', 'right', 'article_num');
$list->set_search_select('article_num', select_over_count('すべて', $_REQUEST['article_num']));

$list->set_header('訪問者数', 'right', 'CASE WHEN uu_num IS NOT NULL THEN uu_num ELSE 0 END');
$list->set_search_select('uu_num', select_over_count('すべて', $_REQUEST['uu_num']));

$list->set_header('ページビュー', 'right', 'CASE WHEN view_num IS NOT NULL THEN view_num ELSE 0 END');
$list->set_search_select('view_num', select_over_count('すべて', $_REQUEST['view_num']));

//$list->set_header('クリック数', 'right', '');
//$list->set_search_select('', select_over_count('すべて', ''));

$list->set_header('ﾄﾗｯｸﾊﾞｯｸ数', 'right', 'trackback_num');
$list->set_search_select('trackback_num', select_over_count('すべて', $_REQUEST['trackback_num']));

$list->set_header('コメント数', 'right', 'comment_num');
$list->set_search_select('comment_num', select_over_count('すべて', $_REQUEST['comment_num']));

$list->set_header('最終更新日', 'center', 'blg_update_date');
$list->set_search_select('blg_update_date', select_year_month('すべて', $_REQUEST['blg_update_date']));

$list->set_header('ｽﾃｰﾀｽ', 'center', 'nvp_open_flag');
$list->set_search_select('nvp_open_flag', select_navi_open_flag('すべて', $_REQUEST['nvp_open_flag']));

$sql_date = sql_date("$year/$month/1");
$where = "date_trunc('month', blg_regist_date) <= $sql_date";
$sub1 = "SELECT bla_blog_id AS sb1_blog_id,COUNT(*) AS article_num FROM t_blog_article WHERE date_trunc('month', bla_date)=$sql_date GROUP BY bla_blog_id";
$sub2 = "SELECT bla_blog_id AS sb2_blog_id,COUNT(*) AS trackback_num FROM t_blog_article JOIN t_blog_trackback ON blt_article_id=bla_article_id WHERE date_trunc('month', blt_date)=$sql_date GROUP BY bla_blog_id";
$sub3 = "SELECT bla_blog_id AS sb3_blog_id,COUNT(*) AS comment_num FROM t_blog_article JOIN t_blog_comment ON blo_article_id=bla_article_id WHERE date_trunc('month', blo_date)=$sql_date GROUP BY bla_blog_id";
$sub4 = "SELECT bla_blog_id AS sb4_blog_id,COUNT(*) AS view_num,COUNT(DISTINCT pvl_ip_addr) AS uu_num FROM l_page_view JOIN t_blog_article ON bla_pv_id=pvl_pv_id WHERE date_trunc('month', pvl_date)=$sql_date GROUP BY bla_blog_id";
$sql = "SELECT nvp_navi_page_id,nvp_title,blg_blog_id,article_num,view_num,uu_num,trackback_num,comment_num,blg_update_date,blg_status,nvp_open_flag"
	. " FROM t_navi_page"
	. " LEFT JOIN t_blog ON nvp_blog_id=blg_blog_id"
	. " LEFT JOIN ($sub1) sub1 ON sb1_blog_id=nvp_blog_id"
	. " LEFT JOIN ($sub2) sub2 ON sb2_blog_id=nvp_blog_id"
	. " LEFT JOIN ($sub3) sub3 ON sb3_blog_id=nvp_blog_id"
	. " LEFT JOIN ($sub4) sub4 ON sb4_blog_id=nvp_blog_id"
	. $list->get_where($where)
	. $list->get_order_by(0, 0)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nvp_navi_page_id);
	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=$fetch->blg_blog_id", 'ナビページ（Blog）情報を表示・変更します');
	$list->set_data(number_format($fetch->article_num), "$top/blog/article/list.php", 'ナビ記事・コメント・トラックバック情報を表示・変更します');
	$list->set_data(number_format($fetch->uu_num));																																// 訪問者数
	$list->set_data(number_format($fetch->view_num));
//	$list->set_data('');																																// クリック数
	$list->set_data(number_format($fetch->trackback_num), "$top/blog/article/list.php", 'ナビ記事・コメント・トラックバック情報を表示・変更します');
	$list->set_data(number_format($fetch->comment_num), "$top/blog/article/list.php", 'ナビ記事・コメント・トラックバック情報を表示・変更します');
	$list->set_data(format_datetime($fetch->blg_update_date, ''));
	$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag));
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("blog_active_{$year}_{$month}.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td width="50%" align="right" colspan=2>
			<nobr>
			<select name="year" onChange="document.list.submit()">
<?=select_year(min(2005, $year), '', $year)?>
			</select>
			<input type="submit" name="prev_month" value="≪">
			<select name="month" onChange="document.list.submit()">
<?=select_month('', $month)?>
			</select>
			<input type="submit" name="next_month" value="≫">
			<input type="submit" name="export" value="エクスポート">
			</nobr>
		</td>
		<td class="lb">
			<input type="submit" value="再検索">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</table>
</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
