<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 問い合わせ管理ページ
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
include("$inc/csv.php");

// ページヘッダ情報設定
$header = new header_class('問い合わせ管理', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header('問合せID', 'center', 'nvi_inquiry_id');
$list->set_search_text('inquiry_id', $_REQUEST['inquiry_id'], 'number', 10);

$list->set_header('名前', 'left', 'nvi_name');

$list->set_header('ナビページ', 'left', 'nvp_title');
$list->set_search_text('navi_page', $_REQUEST['navi_page'], 'kanji', 20);

$list->set_header('内容', 'center', 'nvi_inquiry_type');
$list->set_search_select('inquiry_type', select_inquiry_type('すべて', $_REQUEST['inquiry_type']));

$list->set_header('問合せ日', 'center', 'nvi_date');
$list->set_search_select('inquiry_date', select_year_month('すべて', $_REQUEST['inquiry_date']));

$list->set_header('ステータス', 'center', 'nvi_status');
$list->set_search_select('status', select_inquiry_status('すべて', $_REQUEST['status']));

$list->set_header('削除', 'center', '', false, false);

// リストデータ設定
$sql = "SELECT nvi_inquiry_id,nvi_name,nvi_navi_page_id,nvp_title,nvi_inquiry_type,nvi_date,nvi_status"
	. " FROM t_navi_inquiry LEFT JOIN t_navi_page ON nvi_navi_page_id=nvp_navi_page_id"
	. $list->get_where()
	. $list->get_order_by(0, 0)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nvi_inquiry_id, "check.php?inquiry_id=" . $fetch->nvi_inquiry_id, 'ナビ問合せ情報を表示・変更します');
	$list->set_data($fetch->nvi_name);
	$list->set_data(decode_navi_page_name($fetch->nvi_navi_page_id));
	$list->set_data(decode_inquiry_type($fetch->nvi_inquiry_type));
	$list->set_data(format_datetime($fetch->nvi_date));
	$list->set_data(decode_inquiry_status($fetch->nvi_status, '-'));
	$list->set_html('<input type="button" name="delete_btn" value="削除" onclick="onClick_delete(' . $fetch->nvi_inquiry_id . ')">');

}

// 問い合わせ内容選択
function select_inquiry_type($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '記事について')
				. option_tag('2', $selected, 'ワイワイ会議について')
				. option_tag('3', $selected, '仕事の依頼')
				. option_tag('4', $selected, '取材の依頼')
				. option_tag('9', $selected, 'その他');

	return $tag;
}


// 問い合せステータス選択
function select_inquiry_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '未回答')
				. option_tag('2', $selected, '回答済');

	return $tag;
}

// 問い合せステータスデコード
function decode_inquiry_status($code, $def = '') {
	switch ($code) {
	case '1':
		return '未回答';
	case '2':
		return '回答済';
	}
	return $def;
}

// エクスポート処理
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("inquiry.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onClick_delete(inquiry_id) {
	var f = document.list;
	if (confirm("問合せ情報を削除します。よろしいですか？")) {
		f.action = "delete.php?inquiry_id=" + inquiry_id;
		f.submit();
	}
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
			<nobr>
			<input type="submit" name="export" value="エクスポート">
			<input type="submit" value="再検索">
			</nobr>
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
