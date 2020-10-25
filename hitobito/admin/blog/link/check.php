<?php
/**
 * ひとびと･net 事務局ページ
 *
 * リンク品質管理理ページ
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
include("$inc/const.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/ngword.php");
include("$inc/edit.php");
include("$inc/view.php");
include("$inc/data.php");
include('./data.php');

// ページヘッダー情報
$header = new header_class('リンク管理', BACK_TOP);

// データ処理クラス生成
$data = new link_data_class();

// DBからデータ読み込み
if(isset($_GET['link_id'])) {
	$data->read_db_contents($_GET['link_id']);
}
// 基本情報処理クラス生成
$edit = new edit_class('ナビ記事');

$user_page_url = get_const('user_page_url');

if (!$data->new) {
	$edit->set_item('リンクID');
	if ($page_kind == 'admin')
		$edit->set_string($data->link_id);
	else
		$edit->set_html("<a href='$data->link_url' target='_blank'>$data->link_id</a>");
}

$edit->set_item('ナビページ');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->navi_page_id'>" . htmlspecialchars($data->nvp_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowNaviPage&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->nvp_title) . "</a>");

$edit->set_item('ナビ');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/navi/edit.php?navi_id=$data->navi_id'>" . decode_navi_name($data->navi_id) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowProfile&navipage_id=$data->navi_page_id' target='_blank'>" . decode_navi_name($data->navi_id) . "</a>");

$edit->set_item('pickup');
$edit->set_select('pickup', select_pickup('----', $data->pickup));

$edit->set_item('連絡欄');
$edit->set_textarea('communication_msg', 70, 4, $data->communication_msg);

// 詳細情報表示クラス生成
$view = new view_class('詳細情報');

$checked = ($page_kind == 'patrol' ? $data->patrol_check : $data->admin_check) == 1;
$view->set_group('リンク先名称： ' . $data->link_name, "edit_contents.php?link_id=$data->link_id", $data->class, $checked);
$view->set_data('リンク先名称', $data->link_name);
$view->set_data('リンク先URL', $data->link_url);
$view->set_data('リンク先概要', $data->link_outline);
$view->set_data('NGワード', decode_ngword($data->ng_word));
$view->set_data('パトロール隊チェック', decode_patrol_check($data->patrol_check));
$view->set_data('パトロール隊チェック日', format_datetime($data->patrol_date, ''));
$view->set_data('パトロール隊チェック者', decode_patrol_name($data->patrol_id));
$view->set_data('事務局チェック', decode_admin_check($data->admin_check));
$view->set_data('事務局チェック日', format_datetime($data->admin_date, ''));
$view->set_data('事務局チェック者', decode_admin_name($data->admin_id));
$view->set_data('公開', decode_open_status($data->open_flag));
$view->set_data('登録日', format_datetime($data->date, ''));
$view->set_data('閲覧回数', number_format($data->pvc_pv_count));
$view->set_data('クリック回数', number_format($data->click_count));

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onSubmit_edit(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("リンク情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("リンク情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("リンク情報を削除します。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body onload="set_view_display()">
<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onSubmit_edit(this)">
<?php $edit->disp_edit(); ?>
<br>
<?php $view->disp_view() ?>
<br>
<input type="hidden" name="link_id" <?=value($data->link_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
