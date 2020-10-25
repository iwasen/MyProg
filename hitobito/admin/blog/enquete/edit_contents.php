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
$edit = new edit_class('ナビアンケート');

$edit->set_item('題名', true);
$edit->set_text('nen_title', $data->nen_title, 'kanji', 50, 100);

$edit->set_item('設問文', true);
$edit->set_textarea('nen_question_text', 50, 5, $data->nen_question_text);

$edit->set_item('選択肢', true);
for ($i = 1; $i <= 9; $i++) {
	$edit->set_string('選択肢' . mb_convert_kana($i, 'N'));
	$edit->set_text("enq_option[$i]", $data->enq_option[$i], 'kanji', 60);
	$edit->set_br();
}

$edit->set_item('NGワード');
$edit->set_string(decode_ngword($data->nen_ng_word));

$edit->set_item('パトロール隊チェック');
if ($page_kind == 'patrol')
	$edit->set_select('nen_patrol_check', select_patrol_check('', $data->nen_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->nen_patrol_check));

$edit->set_item('パトロール隊チェック日');
$edit->set_string(format_datetime($data->nen_patrol_date, ''));

$edit->set_item('パトロール隊チェック者');
$edit->set_string(decode_patrol_name($data->nen_patrol_id));

$edit->set_item('事務局チェック', true);
if ($page_kind == 'admin')
	$edit->set_select('nen_admin_check', select_admin_check('', $data->nen_admin_check));
else
	$edit->set_string(decode_admin_check($data->nen_admin_check));

$edit->set_item('事務局チェック日');
$edit->set_string(format_datetime($data->nen_admin_date, ''));

$edit->set_item('事務局チェック者');
$edit->set_string(decode_admin_name($data->nen_admin_id));

$edit->set_item('公開', true);
$edit->set_select('nen_open_flag', select_open_status('', $data->nen_open_flag));

$edit->set_item('登録日');
$edit->set_string(format_datetime($data->nen_date, ''));

$edit->set_item('閲覧回数');
$edit->set_string(number_format($data->pvc_pv_count));

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php //$edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function input_check(f) {
	if (f.nen_title.value == "") {
		alert("題名入力してください。");
		f.enq_title.focus();
		return false;
	}
	if (f.nen_question_text.value == "") {
		alert("設問文を入力してください。");
		f.enq_question.focus();
		return false;
	}
	return true;
}

function onSubmit_edit(f) {
	if (input_check(f))
		return confirm("ナビアンケートを更新します。よろしいですか？");
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
<input type="hidden" name="edit_kind" value='ct'>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="button" value="　戻る　" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
