<?php
/**
 * ひとびと･net 事務局ページ
 *
 * Blog（ナビ記事）管理ページ
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
$header = new header_class('Blog(ナビ記事)管理', BACK_TOP);

// データ処理クラス生成
$data = new blog_data_class();

// DBからデータ読み込み
if(isset($_GET['article_id'])) {
	$data->read_db($_GET['article_id']);
}
// 基本情報処理クラス生成
$edit = new edit_class('基本情報');

$user_page_url = get_const('user_page_url');

if (!$data->new) {
	$edit->set_item('記事ID');
	if ($page_kind == 'admin')
		$edit->set_string($data->article_id);
	else
		$edit->set_html("<a href='{$user_page_url}index.php?module=Blog&action=ViewStory&blog_story_id=$data->article_id' target='_blank'>$data->article_id</a>");
}

$edit->set_item('ナビページ');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->blog_id'>" . htmlspecialchars($data->navi_page_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowNaviPage&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->navi_page_title) . "</a>");

$edit->set_item('ナビ');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/navi/edit.php?navi_id=$data->navi_id'>" . decode_navi_name($data->navi_id) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowProfile&navipage_id=$data->navi_page_id' target='_blank'>" . decode_navi_name($data->navi_id) . "</a>");

$edit->set_item('pickup');
$edit->set_select('pickup', select_pickup('----', $data->pickup));

$edit->set_item('ナビセレクト');
$edit->set_select('navi_select', select_naviselect('---', $data->navi_select));

$edit->set_item('連絡欄');
$edit->set_textarea('communication_msg', 70, 4, $data->communication_msg);

// 詳細情報表示クラス生成
$view = new view_class('詳細情報');

$checked = ($page_kind == 'patrol' ? $data->bla_patrol_check : $data->bla_admin_check) == 1;
$view->set_group('ナビ記事： ' . $data->bla_title, "edit_article.php?article_id=$data->article_id", $data->bla_class, $checked);
$view->set_data('題名', $data->bla_title);
$view->set_data('内容', $data->bla_article_text);
$view->set_data('NGワード', decode_ngword($data->bla_ng_word));
$view->set_data('パトロール隊チェック', decode_patrol_check($data->bla_patrol_check));
$view->set_data('パトロール隊チェック日', format_datetime($data->bla_patrol_date, ''));
$view->set_data('パトロール隊チェック者', decode_patrol_name($data->bla_patrol_id));
$view->set_data('事務局チェック', decode_admin_check($data->bla_admin_check));
$view->set_data('事務局チェック日', format_datetime($data->bla_admin_date, ''));
$view->set_data('事務局チェック者', decode_admin_name($data->bla_admin_id));
$view->set_data('公開', decode_open_status($data->bla_open_flag));
$view->set_data('登録日', format_datetime($data->bla_date, ''));
$view->set_data('閲覧回数', number_format($data->pvc_pv_count));

if (is_array($data->fetch_tb)) {
	foreach ($data->fetch_tb as $tb) {
		$blt_class = $data->get_bar_class($tb->blt_patrol_check, $tb->blt_admin_check, $tb->blt_ng_word);
		$checked = ($page_kind == 'patrol' ? $tb->blt_patrol_check : $tb->blt_admin_check) == 1;
		$view->set_group("トラックバック： $tb->blt_title", "edit_trackback.php?article_id=$data->article_id&trackback_no=$tb->blt_trackback_no", $blt_class, $checked);
		$view->set_data('リンク先ページ名', $tb->blt_title);
		$view->set_html('リンク先URL', '<a href="' . $tb->blt_url . '" target="_blank">' . $tb->blt_url . '</a>');
		$view->set_data('内容', $tb->blt_excerpt);
		$view->set_data('NGワード', decode_ngword($tb->blt_ng_word));
		$view->set_data('パトロール隊チェック', decode_patrol_check($tb->blt_patrol_check));
		$view->set_data('パトロール隊チェック日', format_datetime($tb->blt_patrol_date, ''));
		$view->set_data('パトロール隊チェック者', decode_patrol_name($tb->blt_patrol_id));
		$view->set_data('事務局チェック', decode_admin_check($tb->blt_admin_check));
		$view->set_data('事務局チェック日', format_datetime($tb->blt_admin_date, ''));
		$view->set_data('事務局チェック者', decode_admin_name($tb->blt_admin_id));
		$view->set_data('公開', decode_open_status($tb->blt_open_flag));
		$view->set_data('登録日', format_datetime($tb->blt_date, ''));
		$view->set_data('閲覧回数', number_format($tb->pvc_pv_count));
	}
}
if (is_array($data->fetch_cm)) {
	foreach ($data->fetch_cm as $cm) {
		$blo_class = $data->get_bar_class($cm->blo_patrol_check, $cm->blo_admin_check, $cm->blo_ng_word);
		$checked = ($page_kind == 'patrol' ? $cm->blo_patrol_check : $cm->blo_admin_check) == 1;
		$view->set_group("コメント： " . mb_strimwidth($cm->blo_comment, 0, 20, '..'), "edit_comment.php?article_id=$data->article_id&comment_no=$cm->blo_comment_no", $blo_class, $checked);
		$view->set_data('投稿者', $cm->blo_user_name);
		$view->set_data('内容', $cm->blo_comment);
		$view->set_data('NGワード', decode_ngword($cm->blo_ng_word));
		$view->set_data('パトロール隊チェック', decode_patrol_check($cm->blo_patrol_check));
		$view->set_data('パトロール隊チェック日', format_datetime($cm->blo_patrol_date, ''));
		$view->set_data('パトロール隊チェック者', decode_patrol_name($cm->blo_patrol_id));
		$view->set_data('事務局チェック', decode_admin_check($cm->blo_admin_check));
		$view->set_data('事務局チェック日', format_datetime($cm->blo_admin_date, ''));
		$view->set_data('事務局チェック者', decode_admin_name($cm->blo_admin_id));
		$view->set_data('公開', decode_open_status($cm->blo_open_flag));
		$view->set_data('登録日', format_datetime($cm->blo_date, ''));
		$view->set_data('閲覧回数', number_format($cm->pvc_pv_count));
	}
}
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
			return confirm("ナビ記事情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("ナビ記事情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("ナビ記事情報を削除します。よろしいですか？");
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
<input type="hidden" name="article_id" <?=value($data->article_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
