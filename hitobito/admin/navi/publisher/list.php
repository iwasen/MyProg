<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ一覧ページ
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
include("$inc/csv.php");
include("$inc/format.php");
include("$inc/decode.php");

// ページヘッダ情報設定
$header = new header_class('メールマガジン一覧', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('メルマガID', 'center', 'mmg_melmaga_id');
$list->set_search_text('melmaga_id', $_REQUEST['melmaga_id'], 'number', 8);

$list->set_header('メルマガ名', 'left', 'mmg_melmaga_name');
$list->set_search_text('melmaga_name', $_REQUEST['melmaga_name'], 'kanji', 10);

$list->set_header('ナビページ名', 'left', 'nvp_title');
$list->set_search_text('nvp_title', $_REQUEST['nvp_title'], 'kanji', 10);

$list->set_header('発行者', 'left', 'nav_name1 || nav_name2');
$list->set_search_text('navi_name', $_REQUEST['navi_name'], 'kanji', 10);

$list->set_header('読者数', 'right', 'mmn_reader_cnt');
$list->set_search_select('reader_cnt', select_over_count('すべて', $_REQUEST['reader_cnt']));

$list->set_header('配信回数', 'right', 'mmn_publish_cnt');
$list->set_search_select('publish_cnt', select_over_count('すべて', $_REQUEST['publish_cnt']));

$list->set_header('最終発行日', 'center', 'mmn_last_publish_date');
$list->set_search_select('last_publish', select_year_month('すべて', $_REQUEST['last_publish']));

$list->set_header('ｽﾃｰﾀｽ', 'center', 'mmg_status');
$list->set_search_select('status', select_open_status('すべて', $_REQUEST['status']));

// リストデータ設定
$sql = "SELECT mmg_melmaga_id, mmg_melmaga_name, nvp_title, trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name, mmn_reader_cnt, mmn_publish_cnt, mmn_last_publish_date, mmg_status, nvp_blog_id, nav_navi_id"
		. " FROM t_melmaga"
		. " LEFT JOIN t_navi_page ON mmg_melmaga_id = nvp_melmaga_id"
		. " LEFT JOIN c_melmaga_count ON mmg_melmaga_id = mmn_melmaga_id"
		. " LEFT JOIN t_navi ON nvp_navi_id = nav_navi_id"
		. $list->get_where()
		. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mmg_melmaga_id);
	$list->set_data($fetch->mmg_melmaga_name, "edit.php?melmaga_id=$fetch->mmg_melmaga_id", 'メルマガ登録情報を表示・変更します');
	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=$fetch->nvp_blog_id", 'Blog登録情報を表示・変更します');
	$list->set_data($fetch->nav_name, "$top/navi/navi/edit.php?navi_id=$fetch->nav_navi_id", 'ナビ登録情報を表示・変更します');
	$list->set_data($fetch->mmn_reader_cnt);
	$list->set_data($fetch->mmn_publish_cnt);
	$list->set_data(format_datetime($fetch->mmn_last_publish_date, ''));
	$list->set_data(decode_open_status($fetch->mmg_status));
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("publisher.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function mail_search(f) {
	var f = document.list;
	if (f.mail_addr.value == ''){
		alert("メールアドレスを入力してください。");
		f.mail_addr.focus();
		return false;
	}
	location.href = "personal.php?mail_addr=" + f.mail_addr.value;
}

//-->
</script>
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
			<span class="lc">メールアドレス</span>
		</td>
		<td class="lb">
			<input type="text" name="mail_addr" size=20 maxlength=100>
			<input type="button" value="読者検索" onclick="mail_search(this)">
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
