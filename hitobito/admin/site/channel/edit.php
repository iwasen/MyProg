<?php
/**
 * ひとびと･net 事務局ページ
 *
 * サイト管理／チャネル編集ページ
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
include("$inc/edit.php");
include("$inc/data.php");
include("./data.php");

// ページヘッダ情報設定
$header = new header_class('チャネル編集', BACK_TOP);

// データ処理クラス生成
$data = new channel_data_class();

// DBからデータ読み込み
if (isset($_GET['channel_id']))
	$data->read_db($_GET['channel_id']);


// 基本情報編集処理クラス生成
$edit = new edit_class('チャネル情報');

if (!$data->new) {
	$edit->set_item('チャネルID');
	$edit->set_string($data->channel_id);
}

$edit->set_item('チャネル名称', true);
$edit->set_text('channel_name', $data->channel_name, 'kanji', 60);

$edit->set_item('表示順', true);
$edit->set_text('order', $data->order, 'number', 5);

$edit->set_item('チャネル内バナー画像URL', true);
$edit->set_text('banner_url', $data->banner_url, 'url', 70);
$edit->set_button('表示', 'disp_banner_image()');

$edit->set_item('チャネル内バナーリンク先URL', true);
$edit->set_text('banner_link_url', $data->banner_link_url, 'url', 70);
$edit->set_button('表示', 'disp_banner_link()');

$edit->set_item('状態', true);
$edit->set_select('status', select_channel_status($data->status));

$edit->set_item('サブチャネル', false);
$edit->set_format('<table><tr><td rowspan=2>%s</td><td valign="top">%s<br>%s</td></tr><tr><td valign="bottom">%s</td></tr></table>%s%s');
$edit->set_select('subchannel', select_subchannel($data), '', 10, 'style="width:300px"');
$edit->set_button('▲', 'subchannel_up()');
$edit->set_button('▼', 'subchannel_down()');
$edit->set_button('削除', 'subchannel_delete()');
$edit->set_button('追加', 'subchannel_add()');
$edit->set_text('subchannel_name', '', '', 60);

$edit->set_item('クチコミWEBカテゴリ', false);
$edit->set_html("<iframe name='kcom_category' src='".$top."/common/kcom_category.php?channel_id=$data->channel_id' height=300 width=450></iframe>");

// チャネルステータス
function select_channel_status($selected) {
	$tag .= option_tag('1', $selected, '有効');
	$tag .= option_tag('9', $selected, '無効');

	return $tag;
}

// サブチャネル
function select_subchannel($data) {
	foreach ($data->subchannel as $subchannel_id => $subchannel_name)
		$tag .= "<option value='$subchannel_id'>$subchannel_name</option>";
	return $tag;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	get_subchannel();
	get_kcom_category();
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("チャネルを登録します。よろしいですか？");
		break;
	case "update":
		if (input_check(f))
			return confirm("チャネルを更新します。よろしいですか？");
		break;
	case "delete":
		return confirm("チャネルを削除します。よろしいですか？");
	}
	return false;
}
function subchannel_add() {
	var f = document.edit;
	if (f.subchannel_name.value == '') {
		alert("サブチャネル名を入力してください。");
		f.subchannel_name.focus();
		return;
	}
	var s = f.subchannel;
	for (var i = 0; i < s.length; i++) {
		if (s.options[i].text == f.subchannel_name.value) {
			alert("「" + f.subchannel_name.value + "」はすでに登録されています。");
			f.subchannel_name.focus();
			return;
		}
	}
	var opt = document.createElement("option");
	opt.text = f.subchannel_name.value;
	s.add(opt);
	s.selectedIndex = s.length - 1;
	f.subchannel_name.value = "";
	f.subchannel_name.focus();
}
function subchannel_up() {
	var f = document.edit;
	var s = f.subchannel;
	var i = s.selectedIndex;
	if (i < 0) {
		alert("上に移動させたいサブチャネルを選択してください。");
		s.focus();
		return;
	}
	if (i > 0) {
		var t = s.options[i];
		s.remove(i);
		s.add(t, i - 1);
		s.selectedIndex = i - 1;
	}
}
function subchannel_down() {
	var f = document.edit;
	var s = f.subchannel;
	var i = s.selectedIndex;
	if (i < 0) {
		alert("下に移動させたいサブチャネルを選択してください。");
		s.focus();
		return;
	}
	if (i < s.length - 1) {
		var t = s.options[i];
		s.remove(i);
		s.add(t, i + 1);
		s.selectedIndex = i + 1;
	}
}
function subchannel_delete() {
	var f = document.edit;
	var s = f.subchannel;
	var i = s.selectedIndex;
	if (i < 0) {
		alert("削除したいサブチャネルを選択してください。");
		s.focus();
		return;
	}
	s.remove(i);
}
function get_subchannel() {
	var f = document.edit;
	var s = f.subchannel;
	f.subchannel_id.value = "";
	for (var i = 0; i < s.length; i++)
		f.subchannel_id.value += s.options[i].value + "\t" + s.options[i].text + "\n";
}
function get_kcom_category() {
	var f = document.edit;
	var f2 = document.kcom_category.form1;
	f.kcom_category_id.value = "";
	if (f2.kcom_category) {
		for (var i = 0; i < f2.kcom_category.length; i++) {
			if (f2.kcom_category[i].checked)
				f.kcom_category_id.value += f2.kcom_category[i].value + "\t";
		}
	}
}

function disp_banner_image() {
	var f = document.edit;
	if (f.banner_url.value != "")
		window.open(f.banner_url.value, "_blank");
}
//-->

function disp_banner_link() {
	var f = document.edit;
	if (f.banner_link_url.value != "")
		window.open(f.banner_link_url.value, "_blank");
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit->disp_edit('85%', '30%') ?>
<br>
<input type="hidden" name="channel_id" <?=value($data->channel_id)?>>
<input type="hidden" name="subchannel_id">
<input type="hidden" name="kcom_category_id">
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="　登録　" onclick="document.edit.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="　更新　" onclick="document.edit.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.edit.next_action.value='delete'">
<?php } ?>
<input type="button" value="　戻る　" onClick='location.href="list.php"'>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
