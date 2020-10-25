<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 職業マスタ編集ページ
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
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('職業マスタ編集', BACK_TOP);

// データ処理クラス生成
$data = new shokugyo_data_class();

// DBからデータ読み込み
if (isset($_GET['shokugyo_id']))
	$data->read_db($_GET['shokugyo_id']);

// 編集ページ処理クラス生成
$edit = new edit_class('職業情報を入力してください。');

$edit->set_item('表示順序', true);
$edit->set_text('order', $data->order, 'number', 10, 4);
$edit->set_note('（半角数字）');

$edit->set_item('職業名', true);
$edit->set_text('shokugyo_name', $data->shokugyo_name, 'kanji', 70, 50);
$edit->set_note('（全角50文字まで）');
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
			return confirm("職業マスタ情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("職業マスタ情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("職業名を削除します。よろしいですか？");
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
<input type="hidden" name="shokugyo_id" <?=value($data->shokugyo_id)?>>
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
