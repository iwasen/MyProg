<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 会議室ヘッダ・フッタテンプレート編集ページ
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
$header = new header_class('会議室ヘッダ・フッタテンプレート編集', BACK_TOP);

// データ処理クラス生成
$data = new template_data_class();

$data->read_db();

// 編集ページ処理クラス生成
$edit = new edit_class('会議室ヘッダ・フッタ情報を入力してください。');

$edit->set_item('ヘッダ', true);
$edit->set_textarea('header', 78, 10, $data->header);

$edit->set_item('フッタ', true);
$edit->set_textarea('footer', 78, 10, $data->footer);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (input_check(f))
			return confirm("会議室ヘッダ・フッタテンプレート情報を更新します。よろしいですか？");
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
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
