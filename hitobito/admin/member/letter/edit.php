<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ひとびと便り配信ジョブ編集ページ
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
include("$inc/decode.php");
include("$inc/edit.php");
include("$inc/view.php");
include("$inc/data.php");
include("./data.php");

// ページヘッダ情報設定
$header = new header_class('ひとびと便り配信ジョブ編集', BACK_TOP);

// データ処理クラス生成
$data = new letter_data_class();

// DBからデータ読み込み
if (isset($_GET['letter_id']))
	$data->read_db($_GET['letter_id']);


// 基本情報編集処理クラス生成
$edit = new edit_class('基本情報');

if (!$data->new) {
	$edit->set_item('ID');
	$edit->set_string($data->letter_id);
}

$edit->set_item('ジョブ名', true);
$edit->set_text('job_name', $data->job_name, 'alpha', 50, 100);
$edit->set_note('（半角100文字まで）');

if (!$data->new) {
	$edit->set_item('状態');
	$edit->set_string(decode_letter_status2($data->status));
}

$edit->set_item('発信開始日時', true);
$edit->set_select('send_date_y', select_year('2005', '', $data->send_date['y']));
$edit->set_select('send_date_m', select_month('', $data->send_date['m']));
$edit->set_select('send_date_d', select_day('', $data->send_date['d']));
$edit->set_select('send_date_h', select_hour('', $data->send_date['h']));

$edit->set_item('アンケート〆切日時');
$edit->set_select('enquete_date_y', select_year('2005', '', $data->enquete_date['y']));
$edit->set_select('enquete_date_m', select_month('', $data->enquete_date['m']));
$edit->set_select('enquete_date_d', select_day('', $data->enquete_date['d']));
$edit->set_select('enquete_date_h', select_hour('', $data->enquete_date['h']));

// 詳細情報プレビュー表示クラス生成
$view = new view_class('詳細情報');
$view->set_group('メール文', $data->status == 1 ? "edit_mail.php?letter_id=$data->letter_id" : '');
$view->set_data('タイトル', $data->subject);
$view->set_data('ヘッダ', $data->header);
$view->set_data('本文', $data->body);
$view->set_data('フッタ', $data->footer);

$view->set_group('応募フォーム', $data->status == 1 ? "edit_enq.php?letter_id=$data->letter_id" : '');
$view->set_data('タイトル', $data->enq_title);
$view->set_data('設問文', $data->enq_question);
$view->set_data('選択肢', view_enq_option($data->enq_option));
$view->set_data('ニックネーム入力欄', decode_umu($data->enq_nickname));
$view->set_data('コメント入力欄', decode_umu($data->enq_comment));

// 選択肢プレビュー
function view_enq_option($enq_option) {
	foreach ($enq_option as $option_no => $option_text)
		$s .= "選択肢{$option_no}：$option_text\n";

	return $s;
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
		if (input_check2(f))
			return confirm("ひとびと便りを登録します。よろしいですか？");
		break;
	case "update":
		if (input_check2(f))
			return confirm("ひとびと便りを更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("ひとびと便りを削除します。よろしいですか？");
	}
	return false;
}
function input_check2(f) {
	if (!input_check(f))
		return false;
	var current_date = new Date();
	var send_date = new Date(f.send_date_y.value, f.send_date_m.value - 1, f.send_date_d.value, f.send_date_h.value);
	var enquete_date = new Date(f.enquete_date_y.value, f.enquete_date_m.value - 1, f.enquete_date_d.value, f.enquete_date_h.value);
	if (send_date <= current_date) {
		if (!confirm("発信開始日時に過去の日時が指定されていますがよろしいですか？")) {
			f.send_date_y.focus();
			return false;
		}
	}
	if (enquete_date <= send_date) {
		alert("アンケート〆切日時は発信開始日時より未来の日時を指定してください。");
		f.enquete_date_y.focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit->disp_edit() ?>
<br>
<?php if (!$data->new) $view->disp_view() ?>
<br>
<input type="hidden" name="letter_id" <?=value($data->letter_id)?>>
<input type="hidden" name="next_action">
<?php
if ($data->status == 1) {
	if ($data->new) {
?>
<input type="submit" value="　登録　" onclick="document.edit.next_action.value='new'">
<?php
	} else {
?>
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.edit.next_action.value='delete'">
<?php
	}
}
?>
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
