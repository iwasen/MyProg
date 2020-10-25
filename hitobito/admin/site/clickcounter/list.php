<?php
/**
 * ひとびと･net 事務局ページ
 *
 * クリックカウンター一覧ページ
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
include("$inc/const.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");
include("$inc/crypt.php");

// ページヘッダ情報設定
$header = new header_class('クリックカウンター管理', BACK_TOP);

// ユーザページURL取得
$user_page_url = get_const('user_page_url');

// リスト処理クラス生成
$list = new list_class('small');

// リストヘッダ設定
$list->set_header('カウンターID', 'center', 'rct_recruit_id');
$list->set_search_text('recruit_id', $_REQUEST['recruit_id'], 'number', 8);

$list->set_header('カウンター名', 'left', 'rct_counter_name');
$list->set_search_text('counter_name', $_REQUEST['counter_name'], 'kanji', 20);

$list->set_header('飛び先URL', 'left', 'rct_jump_url');

$list->set_header('生成URL', 'left');

$list->set_header('利用目的', 'left', 'rct_usage');
$list->set_search_text('usage', $_REQUEST['usage'], 'kanji', 12);

$list->set_header('登録日', 'center', 'rct_regist_date');
$list->set_search_select('regist_date', select_year_month('すべて', $_REQUEST['regist_date']));

$list->set_header('クリック数', 'right', 'rcc_click_count');
$list->set_search_select('click_count', select_over_count('すべて', $_REQUEST['click_count']));

$list->set_header('登録メンバー数', 'right', 'rcc_regist_count');
$list->set_search_select('regist_count', select_over_count('すべて', $_REQUEST['regist_count']));

// リストデータ設定
$sql = "SELECT rct_recruit_id, rct_regist_date, rct_counter_name, rct_jump_url, rct_usage, rcc_click_count, rcc_regist_count"
		. " FROM t_recruit_click"
		. " JOIN c_recruit_click ON rcc_recruit_id=rct_recruit_id"
		. $list->get_where()
		. $list->get_order_by(0, 1)
		. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rct_recruit_id, "edit.php?recruit_id=$fetch->rct_recruit_id", 'クリックカウンターを表示・変更します');
	$list->set_data($fetch->rct_counter_name);
	$list->set_data($fetch->rct_jump_url);
	$list->set_data("{$user_page_url}cl.php?" . encrypt_id($fetch->rct_recruit_id));
	$list->set_data($fetch->rct_usage);
	$list->set_data(format_date($fetch->rct_regist_date));
	$list->set_data(number_format($fetch->rcc_click_count));
	$list->set_data(number_format($fetch->rcc_regist_count));
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
			<input type="button" value="新規登録" onclick="location.href='edit.php'">
		</td>
	</tr>
</table>

<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>

</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
