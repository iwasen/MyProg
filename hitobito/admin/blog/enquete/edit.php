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
$header = new header_class('アンケート管理', BACK_TOP);

// データ処理クラス生成
$data = new enquete_data_class();

// DBからデータ読み込み
if (isset($_GET['enquete_id']))
	$data->read_db($_GET['enquete_id']);

// ナビ記事情報編集処理クラス生成
$edit = new edit_class('基本情報');

$edit->set_item('アンケートID');
$edit->set_string($data->enquete_id);

$edit->set_item('ナビページ名');
$edit->set_select('navi_page_id', select_navi_page_title('', $data->navi_page_id));

$edit->set_item('登録日時');
$edit->set_string(format_datetime($data->nen_date));

$edit->set_item('最新更新日時');
$edit->set_string(format_datetime($data->nen_date));

$edit2 = new edit_class();
$edit2->set_item('アンケート数');
$edit2->set_string($data->enq_count());

$edit2->set_item('アンケート回答数');
$edit2->set_string($data->ans_count());

// 該当ナビのアンケート回答数合計
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php //$edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function input_check(f) {
	return true;
}

function onSubmit_edit(f) {
	if (input_check(f))
		return confirm("アンケート基本情報を更新します。よろしいですか？");
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
<?php $edit2->disp_edit() ?>
<br>
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="edit_kind" value='en'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
