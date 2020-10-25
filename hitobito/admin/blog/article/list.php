<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ記事・トラックバック・コメント管理ページ
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
include("$inc/decode.php");
include("$inc/ngword.php");

// ページヘッダ情報設定
$header = new header_class('ナビ記事・トラックバック・コメント管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('記事ID', 'center', 'bla_article_id', true);
$list->set_search_text('article_id', $_REQUEST['article_id'], 'number', 10);

$list->set_header('記事タイトル', 'left', 'bla_title', true);
$list->set_search_text('bla_title', $_REQUEST['bla_title'], 'kanji', 20);

$list->set_header('ナビページ', 'left', 'nvp_title', true);
$list->set_search_text('nvp_title', $_REQUEST['nvp_title'], 'kanji', 20);

$list->set_header('ナビセレクト', 'center', 'bla_navi_select_flag', true);
$list->set_search_select('navi_select', select_naviselect('すべて', $_REQUEST['navi_select']));

$list->set_header('pickup', 'center', 'bla_pickup', true);
$list->set_search_select('navi_pickup', select_pickup('すべて', $_REQUEST['navi_pickup']));

$list->set_header('項目', 'center', 'sb1_kind');
$list->set_search_select('remark_kind', select_remark_kind('すべて', $_REQUEST['remark_kind']));

$list->set_header('NGワード', 'left', 'sb1_ng_word');
$list->set_search_select('ng_word', select_ngword('すべて', $_REQUEST['ng_word']));

$list->set_header('登録日', 'center', 'sb1_date');
$list->set_search_select('regist_date', select_year_month('すべて', $_REQUEST['regist_date']));

$list->set_header('閲覧回数', 'right', 'sb1_pv_count');
$list->set_search_select('view_count', select_over_count('すべて', $_REQUEST['view_count']));

$list->set_header('公開', 'center', 'sb1_open_flag');
$list->set_search_select('open_status', select_open_status('すべて', $_REQUEST['open_status']));

$list->set_header('パトロール隊CHK', 'center', 'sb1_patrol_check');
$list->set_search_select('patrol_check', select_admin_check('すべて', $_REQUEST['patrol_check']));

$list->set_header('事務局CHK', 'center', 'sb1_admin_check');
$list->set_search_select('admin_check', select_admin_check('すべて', $_REQUEST['admin_check']));

// リストデータ設定
$sb1 = "SELECT bla_article_id AS sb1_article_id"
	. ",1  AS sb1_kind"
	. ",bla_ng_word AS sb1_ng_word, bla_date AS sb1_date, pvc_pv_count AS sb1_pv_count, bla_open_flag AS sb1_open_flag,bla_patrol_check AS sb1_patrol_check,bla_admin_check AS sb1_admin_check"
	. " FROM t_blog_article LEFT JOIN c_page_view ON bla_pv_id=pvc_pv_id"
	. " UNION SELECT blt_article_id"
	. ",2 AS kind"
	. ",blt_ng_word,blt_date,pvc_pv_count,blt_open_flag,blt_patrol_check,blt_admin_check"
	. " FROM t_blog_trackback LEFT JOIN c_page_view ON blt_pv_id=pvc_pv_id"
	. " UNION SELECT blo_article_id"
	. ",3 AS kind"
	. ",blo_ng_word,blo_date,pvc_pv_count,blo_open_flag,blo_patrol_check,blo_admin_check"
	. " FROM t_blog_comment LEFT JOIN c_page_view ON blo_pv_id=pvc_pv_id";

$sql = "SELECT bla_article_id,bla_title,bla_blog_id,bla_navi_select_flag,bla_pickup,nvp_title"
	. ",sb1_kind,sb1_ng_word,sb1_date,sb1_pv_count,sb1_open_flag,sb1_patrol_check,sb1_admin_check"
	. " FROM t_blog_article LEFT JOIN ($sb1) sb1 ON bla_article_id=sb1_article_id"
	. " JOIN t_navi_page ON nvp_blog_id=bla_blog_id"
	. $list->get_where($where)
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->bla_article_id);
	$list->set_data($fetch->bla_title, "check.php?article_id=" . $fetch->bla_article_id, 'ナビ記事情報を表示・変更します');
	if ($page_kind == 'admin')
		$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->bla_blog_id, 'ナビページ（Blog）情報を表示・変更します');
	else
		$list->set_data($fetch->nvp_title);
	$list->set_data(decode_navi_select($fetch->bla_navi_select_flag));
	$list->set_data(decode_pickup($fetch->bla_pickup));
	$list->set_data(decode_contents_kind($fetch->sb1_kind));
	$list->set_data(decode_ngword($fetch->sb1_ng_word));
	$list->set_data(format_datetime($fetch->sb1_date));
	$list->set_data(number_format($fetch->sb1_pv_count));
	$list->set_data(decode_open_status($fetch->sb1_open_flag));
	$list->set_data(decode_patrol_check($fetch->sb1_patrol_check));
	$list->set_data(decode_admin_check($fetch->sb1_admin_check));
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
