<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便り応募フォーム編集ページ
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
$header = new header_class('ひとびと便り応募フォーム編集', BACK_TOP);

// データ処理クラス生成
$data = new letter_data_class();

// DBからデータ読み込み
if (isset($_GET['letter_id']))
	$data->read_db($_GET['letter_id']);


// 基本情報編集処理クラス生成
$edit = new edit_class('ひとびと便り応募フォーム');

$edit->set_item('タイトル', true);
$edit->set_text('enq_title', $data->enq_title, 'kanji', 80, 100);
$edit->set_note('（全角１００文字まで）');

$edit->set_item('設問文', true);
$edit->set_textarea('enq_question', 72, 5, $data->enq_question);

$edit->set_item('選択肢', true);
for ($i = 1; $i <= 9; $i++) {
	$edit->set_string('選択肢' . mb_convert_kana($i, 'N'));
	$edit->set_text("enq_option[$i]", $data->enq_option[$i], 'kanji', 60);
	$edit->set_br();
}

$edit->set_item('ニックネーム入力欄', true);
$edit->set_radio('enq_nickname', '1', $data->enq_nickname, 'あり');
$edit->set_radio('enq_nickname', '0', $data->enq_nickname, 'なし');

$edit->set_item('コメント入力欄', true);
$edit->set_radio('enq_comment', '1', $data->enq_comment, 'あり');
$edit->set_radio('enq_comment', '0', $data->enq_comment, 'なし');

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	if (input_check(f))
		return confirm("ひとびと便りの応募フォームを更新します。よろしいですか？");
	return false;
}
function input_check(f) {
	if (f.enq_title.value == "") {
		alert("タイトルを入力してください。");
		f.enq_title.focus();
		return false;
	}
	if (f.enq_question.value == "") {
		alert("設問文を入力してください。");
		f.enq_question.focus();
		return false;
	}
	if (f["enq_option[1]"].value == "") {
		alert("選択肢を入力してください。");
		f["enq_option[1]"].focus();
		return false;
	}
	if (!f.enq_nickname[0].checked && !f.enq_nickname[1].checked) {
		alert("ニックネーム入力欄を選択してください。");
		f.enq_nickname[0].focus();
		return false;
	}
	if (!f.enq_comment[0].checked && !f.enq_comment[1].checked) {
		alert("コメント入力欄を選択してください。");
		f.enq_comment[0].focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>
<form method="post" name="edit" action="update_enq.php" onsubmit="return onsubmit_edit(this)">

<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="letter_id" <?=value($data->letter_id)?>>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
