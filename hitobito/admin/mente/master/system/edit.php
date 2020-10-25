<?php
/**
 * ひとびと･net 事務局ページ
 *
 * システムマスタ編集ページ
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
$header = new header_class('システムマスタ編集', BACK_TOP);

// データ処理クラス生成
$data = new system_data_class();

// DBからデータ読み込み
if (isset($_GET['key']))
	$data->read_db($_GET['key']);

// 編集ページ処理クラス生成
$edit = new edit_class('システム設定情報を入力してください。');

$edit->set_item('項目', true);
$edit->set_string($data->caption);

$edit->set_item('設定値', true);
$edit->set_text('value', $data->value, '', 80);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (input_check(f))
		return confirm("システムマスタ情報を更新します。よろしいですか？");
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
<input type="hidden" name="key" <?=value($data->key)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
