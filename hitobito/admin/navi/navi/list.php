<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ナビ検索ページ
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
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/list.php");

// ヘッダ情報設定
$header = new header_class('ナビ検索', BACK_TOP);

// リスト処理クラス生成
$list = new list_class('small');

// 表示行数
$limit = $list->get_limit_offset();

// リストヘッダ設定
$list->set_header_group('ナビ', 3);
$list->set_header('ID', 'center', 'nav_navi_id', true);
$list->set_search_text('navi_id', $_POST['navi_id'], 'number', 8);

$list->set_header('メールアドレス', 'left', 'mbr_mail_addr', true);
$list->set_search_text('mail_addr', $_POST['mail_addr'], 'alpha', 20);

$list->set_header('名前', 'left', 'nav_name1 || nav_name2', true);
$list->set_search_text('navi_name', $_POST['navi_name'], 'kanji', 10);

$list->set_header_group('ナビページ', 3);
$list->set_header('名称', 'left', 'nvp_title');
$list->set_search_text('navi_page_title', $_POST['navi_page_title'], 'kanji', 20);

$list->set_header('公開日', 'center', 'nvp_open_date');
$list->set_search_select('nvp_open_date', select_year_month('すべて', $_REQUEST['nvp_open_date']));

$list->set_header('ｽﾃｰﾀｽ', 'center', 'nvp_open_flag');
$list->set_search_select('nvp_open_flag', select_navi_open_flag('すべて', $_REQUEST['nvp_open_flag']));

$list->set_header_group('ワイワイ会議室', 3);
$list->set_header('名称', 'left', 'rom_name');
$list->set_search_text('room_name', $_POST['room_name'], 'kanji', 20);

$list->set_header('公開日', 'center', 'rom_open_date');
$list->set_search_select('rom_open_date', select_year_month('すべて', $_REQUEST['rom_open_date']));

$list->set_header('ｽﾃｰﾀｽ', 'center', 'rom_status');
$list->set_search_select('rom_status', select_room_status('すべて', $_REQUEST['rom_status']));

// リストデータ設定
$sql = "SELECT nav_navi_id, mbr_mail_addr, trim(nav_name1||' '||COALESCE(nav_name2,'')) AS nav_name, nvp_title, nvp_open_date, blg_status, rom_name, rom_open_date, rom_status, blg_blog_id, rom_room_id, nvp_open_flag"
		. " FROM t_navi LEFT JOIN t_member ON nav_member_id = mbr_member_id"
		. " LEFT JOIN t_navi_page ON nav_navi_id = nvp_navi_id"
		. " LEFT JOIN t_room ON nvp_room_id = rom_room_id"
		. " LEFT JOIN t_blog ON nvp_blog_id = blg_blog_id"
		. $list->get_where()
		. $list->get_order_by(0, 1) . $limit;
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nav_navi_id, "$top/navi/navi/edit.php?navi_id=" . $fetch->nav_navi_id, 'ナビ登録情報を表示・変更します');
	$list->set_data($fetch->mbr_mail_addr);
	$list->set_data($fetch->nav_name);
	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=" . $fetch->blg_blog_id, 'ナビページ(Blog)登録情報を表示・変更します');
	$list->set_data(format_datetime($fetch->nvp_open_date, ''));
	$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag));
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rom_room_id, 'ナビページ(ML)登録情報を表示・変更します');
	$list->set_data(format_datetime($fetch->rom_open_date, ''));
	$list->set_data(decode_room_status($fetch->rom_status));
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
		<td class="lc" align="right">
		<input type="submit" value="再検索">
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
