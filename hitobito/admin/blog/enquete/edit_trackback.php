<?php
/**
 * ひとびと･net 事務局ページ
 *
 * アンケート品質管理理ページ
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
$header = new header_class('アンケート編集', BACK_TOP);

// データ処理クラス生成
$data = new enquete_data_class();

// DBからデータ読み込み
if (isset($_GET['trackback_no']))
	$data->read_db_trackback($_GET['enquete_id'], $_GET['trackback_no']);

// ナビ記事情報編集処理クラス生成
$edit = new edit_class('トラックバック');

$edit->set_item('リンク先ページ名');
$edit->set_text('net_title', $data->net_title, 'kanji', 50);

$edit->set_item('リンクURL');
$edit->set_text('net_url', $data->net_url, 'alpha', 50);

$edit->set_item('内容');
$edit->set_textarea('net_excerpt', 72, 15, $data->net_excerpt);

$edit->set_item('NGワード');
$edit->set_string(decode_ngword($data->net_ng_word));

$edit->set_item('パトロール隊チェック');
if ($page_kind == 'patrol')
	$edit->set_select('net_patrol_check', select_patrol_check('', $data->net_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->net_patrol_check));

$edit->set_item('パトロール隊チェック日');
$edit->set_string(format_datetime($data->net_patrol_date, ''));

$edit->set_item('パトロール隊チェック者');
$edit->set_string(decode_patrol_name($data->net_patrol_id));

$edit->set_item('事務局チェック', true);
if ($page_kind == 'admin')
	$edit->set_select('net_admin_check', select_admin_check('', $data->net_admin_check));
else
	$edit->set_string(decode_admin_check($data->net_admin_check));

$edit->set_item('事務局チェック日');
$edit->set_string(format_datetime($data->net_admin_date, ''));

$edit->set_item('事務局チェック者');
$edit->set_string(decode_admin_name($data->net_admin_id));

$edit->set_item('公開', true);
$edit->set_select('net_open_flag', select_open_status('', $data->net_open_flag));

$edit->set_item('登録日');
$edit->set_string(format_datetime($data->net_date, ''));

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
	if (input_check(f))
		return confirm("トラックバックを更新します。よろしいですか？");
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
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="trackback_no" <?=value($data->trackback_no)?>>
<input type="hidden" name="edit_kind" value='tb'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
