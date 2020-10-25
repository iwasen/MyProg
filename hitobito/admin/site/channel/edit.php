<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ȴ���������ͥ��Խ��ڡ���
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

// �ڡ����إå���������
$header = new header_class('����ͥ��Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new channel_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['channel_id']))
	$data->read_db($_GET['channel_id']);


// ���ܾ����Խ��������饹����
$edit = new edit_class('����ͥ����');

if (!$data->new) {
	$edit->set_item('����ͥ�ID');
	$edit->set_string($data->channel_id);
}

$edit->set_item('����ͥ�̾��', true);
$edit->set_text('channel_name', $data->channel_name, 'kanji', 60);

$edit->set_item('ɽ����', true);
$edit->set_text('order', $data->order, 'number', 5);

$edit->set_item('����ͥ���Хʡ�����URL', true);
$edit->set_text('banner_url', $data->banner_url, 'url', 70);
$edit->set_button('ɽ��', 'disp_banner_image()');

$edit->set_item('����ͥ���Хʡ������URL', true);
$edit->set_text('banner_link_url', $data->banner_link_url, 'url', 70);
$edit->set_button('ɽ��', 'disp_banner_link()');

$edit->set_item('����', true);
$edit->set_select('status', select_channel_status($data->status));

$edit->set_item('���֥���ͥ�', false);
$edit->set_format('<table><tr><td rowspan=2>%s</td><td valign="top">%s<br>%s</td></tr><tr><td valign="bottom">%s</td></tr></table>%s%s');
$edit->set_select('subchannel', select_subchannel($data), '', 10, 'style="width:300px"');
$edit->set_button('��', 'subchannel_up()');
$edit->set_button('��', 'subchannel_down()');
$edit->set_button('���', 'subchannel_delete()');
$edit->set_button('�ɲ�', 'subchannel_add()');
$edit->set_text('subchannel_name', '', '', 60);

$edit->set_item('��������WEB���ƥ���', false);
$edit->set_html("<iframe name='kcom_category' src='".$top."/common/kcom_category.php?channel_id=$data->channel_id' height=300 width=450></iframe>");

// ����ͥ륹�ơ�����
function select_channel_status($selected) {
	$tag .= option_tag('1', $selected, 'ͭ��');
	$tag .= option_tag('9', $selected, '̵��');

	return $tag;
}

// ���֥���ͥ�
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
			return confirm("����ͥ����Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("����ͥ�򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("����ͥ�������ޤ���������Ǥ�����");
	}
	return false;
}
function subchannel_add() {
	var f = document.edit;
	if (f.subchannel_name.value == '') {
		alert("���֥���ͥ�̾�����Ϥ��Ƥ���������");
		f.subchannel_name.focus();
		return;
	}
	var s = f.subchannel;
	for (var i = 0; i < s.length; i++) {
		if (s.options[i].text == f.subchannel_name.value) {
			alert("��" + f.subchannel_name.value + "�פϤ��Ǥ���Ͽ����Ƥ��ޤ���");
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
		alert("��˰�ư�����������֥���ͥ�����򤷤Ƥ���������");
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
		alert("���˰�ư�����������֥���ͥ�����򤷤Ƥ���������");
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
		alert("������������֥���ͥ�����򤷤Ƥ���������");
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
<input type="submit" value="����Ͽ��" onclick="document.edit.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="submit" value="�������" onclick="document.edit.next_action.value='delete'">
<?php } ?>
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
