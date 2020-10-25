<?php
/**
 * ひとびと･net 事務局ページ
 *
 * NGワードマスタ編集ページ
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('NGワードマスタ編集', BACK_TOP);

// データ処理クラス生成
$data = new ngword_data_class();

// DBからデータ読み込み
if (isset($_GET['ngword_id']))
	$data->read_db($_GET['ngword_id']);

// 編集ページ処理クラス生成
$edit = new edit_class('NGワード情報を入力してください。');

$edit->set_item('NGワード', true);
$edit->set_text('word', $data->word, '', 50);

$edit->set_item('ステータス', true);
$edit->set_select('status', select_ngword_status($data->status));

// NGワードステータス
function select_ngword_status($selected) {
	$tag  = option_tag('1', $selected, '有効');
	$tag .= option_tag('9', $selected, '無効');

	return $tag;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("NGワードマスタ情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("NGワードマスタ情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("NGワードを削除します。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">

<?php $edit->disp_edit() ?>

<br>
<input type="hidden" name="ngword_id" <?=value($data->ngword_id)?>>
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<?php } ?>
<input type="button" value="　戻る　" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
