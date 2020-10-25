<?php
/**
 * ひとびと･net 事務局ページ
 *
 * クリックカウンター編集ページ
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
include("$inc/const.php");
include("$inc/database.php");
include("$inc/edit.php");
include("$inc/crypt.php");
include("$inc/data.php");
include("./data.php");

// ページヘッダ情報設定
$header = new header_class('クリックカウンター編集', BACK_TOP);

// データ処理クラス生成
$data = new recruit_data_class();

// DBからデータ読み込み
if (isset($_GET['recruit_id']))
	$data->read_db($_GET['recruit_id']);

// 基本情報編集処理クラス生成
$edit = new edit_class('基本情報');

if (!$data->new) {
	$edit->set_item('カウンターID');
	$edit->set_string($data->recruit_id);
}

$edit->set_item('カウンター名', true);
$edit->set_text('counter_name', $data->counter_name, 'alpha', 80);

$edit->set_item('飛び先URL', true);
$edit->set_text('jump_url', $data->jump_url, 'url', 80);
$edit->set_button('確認', 'window.open(document.edit.jump_url.value)');
$edit->set_note('（半角英数）');

if (!$data->new) {
	$edit->set_item('クリックカウンター生成URL');
	$edit->set_string(get_const('user_page_url') .  'cl.php?' . encrypt_id($data->recruit_id));
}

$edit->set_item('利用目的', true);
$edit->set_text('usage', $data->usage, 'alpha', 40);

if (!$data->new) {
	$edit->set_item('クリック数');
	$edit->set_string(number_format($data->click_count));
	$edit->set_html('　<a href="javascript:reset_counter()">リセットする</a>');

	$edit->set_item('登録者数');
	$edit->set_string(number_format($data->regist_count));
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("クリックカウンターを登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("クリックカウンターを更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("クリックカウンターを削除します。よろしいですか？");
	}
	return false;
}
function reset_counter() {
	if (confirm("クリック数をリセットします。よろしいですか？"))
		location.href = "reset.php?recruit_id=<?=$data->recruit_id?>";
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="reset">
<input type="hidden" name="recruit_id" <?=value($data->recruit_id)?>>
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="　登録　" onclick="document.edit.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="日付別集計" onclick="location.href='sum_date.php?recruit_id=<?=$data->recruit_id?>'">
<input type="button" value="時間別集計" onclick="location.href='sum_time.php?recruit_id=<?=$data->recruit_id?>'">
<?php } ?>
<input type="reset" value="リセット">
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
