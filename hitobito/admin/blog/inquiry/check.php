<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 問い合わせ品質管理理ページ
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
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/view.php");
include("$inc/data.php");
include('./data.php');

// ページヘッダー情報
$header = new header_class('問い合わせ管理', BACK_TOP);

// データ処理クラス生成
$data = new inquiry_data_class();

// DBからデータ読み込み
if(isset($_GET['inquiry_id'])) {
	$data->read_db_contents($_GET['inquiry_id']);
}
// 基本情報処理クラス生成
$edit = new edit_class('問い合わせ');

$edit->set_item('問い合わせID');
$edit->set_string($data->inquiry_id);

$edit->set_item('ナビページ');
$edit->set_html("<a href='" . $top . "/navi/blog/edit.php?blog_id=" . $data->navi_page_id . "'>" . decode_navi_page_name($data->navi_page_id) . "</a>");

$edit->set_item('ナビ');
$edit->set_html("<a href='" . $top . "/navi/navi/edit.php?navi_id=" . $data->navi_id . "'>" . decode_navi_name($data->navi_id) . "</a>");

$edit->set_item('事務局チェック');
$edit->set_select('admin_check', select_admin_check('', $data->admin_check));

$edit->set_item('事務局チェック日');
$edit->set_string(format_datetime($data->admin_date));

$edit->set_item('事務局チェック者');
$edit->set_string(decode_admin_name($data->admin_id));

$edit->set_item('連絡欄');
$edit->set_textarea('communication_msg', 70, 4, $data->communication_msg);

// 詳細情報表示クラス生成
$view = new view_class('詳細情報');

$view->set_group('問い合わせ内容');
$view->set_data('会社名', $data->company_name);
$view->set_data('部署名', $data->section_name);
$view->set_data('お名前', $data->name);
$view->set_data('E-mail', $data->mail_addr);
$view->set_data('電話番号', $data->tel_no);
$view->set_data('FAX番号', $data->fax_no);
$view->set_data('依頼内容', decode_inquiry_type($data->inquiry_type));
$view->set_data('バナー', '');
$view->set_data('詳細内容', $data->content);
$view->set_data('問い合わせ日', format_datetime($data->inquiry_date));

// ナビ回答
if (is_array($data->fetch)) {
	foreach ($data->fetch as $item) {

		$view->set_group('返信内容');
		$view->set_data('件名',$item->nva_subject);
		$view->set_data('送信先アドレス',$item->nva_mail_addr);
		$view->set_data('本文',$item->nva_content);
		$view->set_data('返信日',format_datetime($item->nva_date));
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
			return confirm("問い合わせ情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("問い合わせ情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("問い合わせ情報を削除します。よろしいですか？");
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
<input type="hidden" name="inquiry_id" <?=value($data->inquiry_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
