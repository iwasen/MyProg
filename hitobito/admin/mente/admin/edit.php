<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 管理者マスタ編集ページ
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
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報設定
$header = new header_class('管理者マスタ編集', BACK_TOP);

// データ処理クラス生成
$data = new admin_data_class();

// DBからデータ読み込み
if (isset($_GET['admin_id']))
	$data->read_db($_GET['admin_id']);

// 編集ページ処理クラス生成
$edit = new edit_class('管理者情報を入力してください。');

if (!$data->new) {
	$edit->set_item('管理者ID');
	$edit->set_string($data->admin_id);
}

$edit->set_item('メールアドレス', true);
$edit->set_text('mail_addr', $data->mail_addr, 'alpha', 50);

$edit->set_item('パスワード', true);
$edit->set_password('password', $data->password, 50);

$edit->set_item('名前', true);
$edit->set_string('姓');
$edit->set_text('name1', $data->name1, 'kanji', 20);
$edit->set_string('名');
$edit->set_text('name2', $data->name2, 'kanji', 20);

$edit->set_item('名前（カナ）');
$edit->set_string('セイ');
$edit->set_text('name1_kana', $data->name1_kana, 'kanji', 20);
$edit->set_string('メイ');
$edit->set_text('name2_kana', $data->name2_kana, 'kanji', 20);

$edit->set_item('種別', true);
$edit->set_select('type', select_admin_type(' ', $data->type));

$edit->set_item('部署', true);
$edit->set_text('unit', $data->unit, '', 50);

$edit->set_item('ステータス', true);
$edit->set_select('status', select_admin_status('', $data->status));

if (!$data->new) {
	$edit->set_item('登録日時');
	$edit->set_string(format_datetime($data->regist_date));

	$edit->set_item('最新更新日時');
	$edit->set_string(format_datetime($data->update_date));
}

// 管理者種別
function select_admin_type($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'システムメンバ');
	$tag .= option_tag('2', $selected, '事務局');
	$tag .= option_tag('3', $selected, '実施');

	return $tag;
}

// 管理者ステータス
function select_admin_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '有効');
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
			return confirm("管理者マスタ情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("管理者マスタ情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("管理者を削除します。よろしいですか？");
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
<input type="hidden" name="admin_id" <?=value($data->admin_id)?>>
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
