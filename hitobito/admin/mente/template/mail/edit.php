<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メールテンプレートマスタ編集ページ
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
$header = new header_class('メールテンプレートマスタ編集', BACK_TOP);

// データ処理クラス生成
$data = new template_data_class();

// DBからデータ読み込み
if (isset($_GET['template_id']))
	$data->read_db($_GET['template_id']);

// 編集ページ処理クラス生成
$edit = new edit_class('メールテンプレート情報を入力してください。');

$edit->set_item('テンプレート名', true);
$edit->set_text('guide', $data->guide, 'kanji', 70);

$edit->set_item('件名', true);
$edit->set_text('subject', $data->subject, 'kanji', 70);

$edit->set_item('Fromアドレス', true);
$edit->set_text('from', $data->from, 'alpha', 70);

$edit->set_item('Ccアドレス');
$edit->set_text('cc', $data->cc, 'alpha', 70);

$edit->set_item('Bccアドレス');
$edit->set_text('bcc', $data->bcc, 'alpha', 70);

$edit->set_item('Reply-Toアドレス');
$edit->set_text('reply_to', $data->reply_to, 'alpha', 70);

$edit->set_item('本文', true);
$edit->set_textarea('body', 78, 20, $data->body);

// メールテンプレートステータス
function select_template_status($selected) {
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
	case "update":
		if (input_check(f))
			return confirm("メールテンプレートマスタ情報を更新します。よろしいですか？");
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
<input type="hidden" name="template_id" <?=value($data->template_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
