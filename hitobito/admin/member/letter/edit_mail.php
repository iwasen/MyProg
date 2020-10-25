<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便りメール編集ページ
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
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('ひとびと便りメール文編集', BACK_TOP);

// データ処理クラス生成
$data = new letter_data_class();

// DBからデータ読み込み
if (isset($_GET['letter_id']))
	$data->read_db($_GET['letter_id']);


// 基本情報編集処理クラス生成
$edit = new edit_class('ひとびと便りメール文');

$edit->set_item('タイトル', true);
$edit->set_text('subject', $data->subject, 'kanji', 80, 100);
$edit->set_note('（全角１００文字まで）');

$edit->set_item('ヘッダ', true);
$edit->set_textarea('header', 72, 10, $data->header);

$edit->set_item('本文', true);
$edit->set_textarea('body', 72, 25, $data->body);

$edit->set_item('フッタ', true);
$edit->set_textarea('footer', 72, 8, $data->footer);

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	f.target = "";
	f.action = "update_mail.php";
	if (input_check(f))
		return confirm("ひとびと便りのメール文を更新します。よろしいですか？");
	return false;
}
function input_check(f) {
	if (f.subject.value == "") {
		alert("タイトルを入力してください。");
		f.subject.focus();
		return false;
	}
	if (f.header.value == "") {
		alert("ヘッダを入力してください。");
		f.header.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("本文を入力してください。");
		f.body.focus();
		return false;
	}
	if (f.footer.value == "") {
		alert("フッタを入力してください。");
		f.footer.focus();
		return false;
	}
	return true;
}
function onclick_counter() {
	var f = document.edit;
	f.target = "_blank";
	f.action = "click_counter.php";
	f.submit();
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update_mail.php" onsubmit="return onsubmit_edit(this)">
<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="letter_id" <?=value($data->letter_id)?>>
<input type="hidden" name="cc_start_date">
<input type="hidden" name="cc_end_date">
<input type="hidden" name="ct_id">
<input type="hidden" name="ct_count" value=0>
<input type="hidden" name="url_name">
<input type="hidden" name="jump_url">
  <input type="button" value="ｸﾘｯｸｶｳﾝﾀ-設定" onclick="onclick_counter()">
<input type="submit" value="　更新　">
<input type="reset" value="リセット">
<input type="button" value="　戻る　" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
