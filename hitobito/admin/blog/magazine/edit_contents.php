<?php
/**
 * ひとびと･net 事務局ページ
 *
 * メルマガ品質管理理ページ
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
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/ngword.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// ページヘッダ情報
$header = new header_class('メールマガジン編集', BACK_TOP);

// データ処理クラス生成
$data = new melmaga_contents_data_class();

// DBからデータ読み込み
if (isset($_GET['melmaga_contents_id']))
	$data->read_db_contents($_GET['melmaga_contents_id']);

// ナビ記事情報編集処理クラス生成
$edit = new edit_class('メールマガジン');

$edit->set_item('題名', true);
$edit->set_text('mmc_subject', $data->mmc_subject, 'kanji', 50);

$edit->set_item('内容', true);
$edit->set_textarea('mmc_body', 72, 15, $data->mmc_body);

$edit->set_item('フッタ');
$edit->set_string($data->mmc_footer);

$edit->set_item('NGワード');
$edit->set_string(decode_ngword($data->mmc_ng_word));

$edit->set_item('パトロール隊チェック');
if ($page_kind == 'patrol')
	$edit->set_select('mmc_patrol_check', select_patrol_check('', $data->mmc_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->mmc_patrol_check));

$edit->set_item('パトロール隊チェック日');
$edit->set_string(format_datetime($data->mmc_patrol_date, ''));

$edit->set_item('パトロール隊チェック者');
$edit->set_string(decode_patrol_name($data->mmc_patrol_id));

$edit->set_item('事務局チェック');
if ($page_kind == 'admin')
	$edit->set_select('mmc_admin_check', select_admin_check('', $data->mmc_admin_check));
else
	$edit->set_string(decode_admin_check($data->mmc_admin_check));

$edit->set_item('事務局チェック日');
$edit->set_string(format_datetime($data->mmc_admin_date, ''));

$edit->set_item('事務局チェック者');
$edit->set_string(decode_admin_name($data->mmc_admin_id));

$edit->set_item('公開');
$edit->set_select('mmc_open_flag', select_open_status('', $data->mmc_open_flag));

$edit->set_item('登録日');
$edit->set_string(format_datetime($data->mmc_date, ''));

$edit->set_item('閲覧回数');
$edit->set_string(number_format($data->pvc_pv_count));
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
			return confirm("メールマガジン詳細情報を登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("メールマガジン詳細情報を更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("メールマガジン詳細情報を削除します。よろしいですか？");
	}
	return false;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>
<form method="post" name="edit" action="update.php" onsubmit="return onSubmit_edit(this)">

<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="melmaga_contents_id" <?=value($data->melmaga_contents_id)?>>
<input type="hidden" name="edit_kind" value='ct'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
