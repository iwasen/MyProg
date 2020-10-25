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
include("$inc/decode.php");
include("$inc/ngword.php");
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/view.php");
include("$inc/const.php");
include("$inc/data.php");
include('./data.php');

// ページヘッダー情報
$header = new header_class('アンケート管理', BACK_TOP);

// データ処理クラス生成
$data = new enquete_data_class();

$user_page_url = get_const('user_page_url');

// DBからデータ読み込み
if(isset($_GET['enquete_id'])) {
	$data->read_db($_GET['enquete_id']);
	$data->read_db_comment_all($_GET['enquete_id']);
	$data->read_db_trackback_all($_GET['enquete_id']);
}

// 基本情報処理クラス生成
$edit = new edit_class('アンケート');

if (!$data->new) {
	$edit->set_item('アンケートID');
	if ($page_kind == 'admin')
		$edit->set_string($data->enquete_id);
	else
		$edit->set_html("<a href='{$user_page_url}index.php?module=Enquete&action=ShowResult&navipage_id=$data->navi_page_id&enquete_id=$data->enquete_id' target='_blank'>$data->enquete_id</a>");
}

$edit->set_item('回答数');
$edit->set_string(number_format($data->ans_count));

$edit->set_item('アンケート名');
if ($page_kind == 'admin')
	$edit->set_html("<a href='edit.php?enquete_id=$data->enquete_id'>" . htmlspecialchars($data->nen_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Enquete&action=ListEnquete&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->nen_title) . "</a>");

$edit->set_item('ナビページ');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->blog_id'>" . htmlspecialchars($data->nvp_title) . "</a>");
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

$checked = ($page_kind == 'patrol' ? $data->nen_patrol_check : $data->nen_admin_check) == 1;
$view->set_group('題名： ' . $data->nen_title, "edit_contents.php?enquete_id=$data->enquete_id", $data->nen_class, $checked);
$view->set_data('題名', $data->nen_title);
$view->set_data('設問文', $data->nen_question_text);
$view->set_data('選択肢', view_enq_option($data->enq_option));
$view->set_data('NGワード', decode_ngword($data->nen_ng_word));
$view->set_data('パトロール隊チェック', decode_patrol_check($data->nen_patrol_check));
$view->set_data('パトロール隊チェック日', format_datetime($data->nen_patrol_date, ''));
$view->set_data('パトロール隊チェック者', decode_patrol_name($data->nen_patrol_id));
$view->set_data('事務局チェック', decode_admin_check($data->nen_admin_check));
$view->set_data('事務局チェック日', format_datetime($data->nen_admin_date, ''));
$view->set_data('事務局チェック者', decode_admin_name($data->nen_admin_id));
$view->set_data('公開', decode_open_status($data->nen_open_flag));
$view->set_data('登録日', format_datetime($data->nen_date, ''));
$view->set_data('閲覧回数', number_format($data->pvc_pv_count));

// トラックバック
if (is_array($data->fetch_tb)) {
	foreach ($data->fetch_tb as $item) {
		$bar_class = $data->get_bar_class($item->net_patrol_check, $item->net_admin_check, $item->net_ng_word);
		$checked = ($page_kind == 'patrol' ? $item->net_patrol_check : $item->net_admin_check) == 1;
		$view->set_group("トラックバック： " . mb_strimwidth($item->net_excerpt, 0, 20, '..'), "edit_trackback.php?enquete_id=$data->enquete_id&trackback_no=$item->net_trackback_no", $bar_class, $checked);
		$view->set_data('リンク先ページ名', $item->net_title);
		$view->set_html('リンク先URL', '<a href="' . $item->net_url . '" target="_blank">' . $item->net_url . '</a>');
		$view->set_data('内容', $item->net_excerpt);
		$view->set_data('NGワード', decode_ngword($item->net_ng_word));
		$view->set_data('パトロール隊チェック', decode_patrol_check($item->net_patrol_check));
		$view->set_data('パトロール隊チェック日', format_datetime($item->net_patrol_date, ''));
		$view->set_data('パトロール隊チェック者', decode_patrol_name($item->net_patrol_id));
		$view->set_data('事務局チェック', decode_admin_check($item->net_admin_check));
		$view->set_data('事務局チェック日', format_datetime($item->net_admin_date, ''));
		$view->set_data('事務局チェック者', decode_admin_name($item->net_admin_id));
		$view->set_data('公開', decode_open_status($item->net_open_flag));
		$view->set_data('登録日', format_datetime($item->net_date, ''));
		$view->set_data('閲覧回数', number_format($item->pvc_pv_count));
	}
}

// コメント
if (is_array($data->fetch_cm)) {
	foreach ($data->fetch_cm as $item) {
		$bar_class = $data->get_bar_class($item->neo_patrol_check, $item->neo_admin_check, $item->neo_ng_word);
		$checked = ($page_kind == 'patrol' ? $item->neo_patrol_check : $item->neo_admin_check) == 1;
		$view->set_group("コメント： " . mb_strimwidth($item->neo_comment, 0, 20, '..'), "edit_comment.php?enquete_id=$data->enquete_id&comment_no=$item->neo_comment_no", $bar_class, $checked);
		$view->set_data('投稿者名', $item->neo_user_name);
		$view->set_data('内容', $item->neo_comment);
		$view->set_data('NGワード', decode_ngword($item->neo_ng_word));
		$view->set_data('パトロール隊チェック', decode_patrol_check($item->neo_patrol_check));
		$view->set_data('パトロール隊チェック日', format_datetime($item->neo_patrol_date, ''));
		$view->set_data('パトロール隊チェック者', decode_patrol_name($item->neo_patrol_id));
		$view->set_data('事務局チェック', decode_admin_check($item->neo_admin_check));
		$view->set_data('事務局チェック日', format_datetime($item->neo_admin_date, ''));
		$view->set_data('事務局チェック者', decode_admin_name($item->neo_admin_id));
		$view->set_data('公開', decode_open_status($item->neo_open_flag));
		$view->set_data('登録日', format_datetime($item->neo_date, ''));
		$view->set_data('閲覧回数', number_format($item->pvc_pv_count));
	}
}

// アンケートURL
$enquete_url = get_const('user_page_url') . "index.php?module=Enquete&action=AnswerEnquete&navipage_id=$data->navi_page_id&enquete_id=$data->enquete_id";

// 選択肢プレビュー
function view_enq_option($enq_option) {
	foreach ($enq_option as $option_no => $option_text)
		$s .= "選択肢{$option_no}：$option_text\n";

	return $s;
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
			return confirm("アンケート基本情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("アンケート基本情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("アンケート基本情報を削除します。よろしいですか？");
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
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="アンケートへ" onclick="window.open('<?=$enquete_url?>', 'navi_enquete')">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
