<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ý������Խ��ڡ���
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
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('�ý������Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new feature_articles_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['article_id']))
	$data->read_db($_GET['article_id']);


// ���ܾ����Խ��������饹����
$edit = new edit_class('�ý���������');

if (!$data->new) {
	$edit->set_item('����No.');
	$edit->set_string($data->article_id);
}

$edit->set_item('ɽ������', true);
$edit->set_select('order', select_order('', $data->order));

$edit->set_item('�������᡼��');
$edit->set_file('image', 60);
if ($data->image_id)
	$edit->set_html(html_input_button('ɽ��', "disp_image($data->image_id)"));

$edit->set_item('ALT̾');
$edit->set_text('image_alt', $data->image_alt, 'kanji', 90);

$edit->set_item('�����', true);
$edit->set_text('link_url', $data->link_url, 'alpha', 80);

$edit->set_item('�����ɽ��', true);
$edit->set_radio('link_page', '1', $data->link_page, '�̲���');
$edit->set_radio('link_page', '2', $data->link_page, 'Ʊ�����');

$edit->set_item('ɽ����������', true);
$edit->set_select('start_date_y', select_year('2005', '', $data->start_date['y']));
$edit->set_select('start_date_m', select_month('', $data->start_date['m']));
$edit->set_select('start_date_d', select_day('', $data->start_date['d']));
$edit->set_select('start_date_h', select_hour('', $data->start_date['h']));

$edit->set_item('ɽ����λ����', true);
$edit->set_select('end_date_y', select_year('2005', '', $data->end_date['y']));
$edit->set_select('end_date_m', select_month('', $data->end_date['m']));
$edit->set_select('end_date_d', select_day('', $data->end_date['d']));
$edit->set_select('end_date_h', select_hour('', $data->end_date['h']));

$edit->set_item('����');
$edit->set_textarea('description', 65, 3, $data->description);

$edit->set_item('����', true);
$edit->set_select('status', select_feature_articles_status('', $data->status));

if (!$data->new) {
	$edit->set_item('��Ͽ����');
	$edit->set_string(format_datetime($data->regist_date));
}

// ɽ������
function select_order($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	for ($i = 1; $i <= 6; $i++)
		$tag .= option_tag($i, $selected, $i);

	return $tag;
}

// �ý��������ơ�����
function select_feature_articles_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'ͭ��')
				. option_tag('9', $selected, '̵��');

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
	switch (f.next_action.value) {
	case "new":
		if (input_check2(f))
			return confirm("�ý���������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check2(f))
			return confirm("�ý������򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�ý������������ޤ���������Ǥ�����");
	}
	return false;
}
function input_check2(f) {
	if (!input_check(f))
		return false;
	var current_date = new Date();
	var start_date = new Date(f.start_date_y.value, f.start_date_m.value - 1, f.start_date_d.value, f.start_date_h.value);
	var end_date = new Date(f.end_date_y.value, f.end_date_m.value - 1, f.end_date_d.value, f.end_date_h.value);
	if (start_date <= current_date) {
		if (!confirm("ɽ�����������˲������������ꤵ��Ƥ��ޤ���������Ǥ�����")) {
			f.start_date_y.focus();
			return false;
		}
	}
	if (end_date <= start_date) {
		alert("ɽ����λ������ɽ�������������̤�����������ꤷ�Ƥ���������");
		f.end_date_y.focus();
		return false;
	}
	return true;
}
function disp_image(image_id) {
	window.open("<?=$top?>/common/disp_image.php?image_id=" + image_id);
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onsubmit_edit(this)" enctype="multipart/form-data">

<?php $edit->disp_edit() ?>

<br>
<input type="hidden" name="article_id" <?=value($data->article_id)?>>
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="����Ͽ��" onclick="document.edit.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="submit" value="�������" onclick="document.edit.next_action.value='delete'">
<?php } ?>
<input type="button" value="����롡" onClick='location.href="list.php"'>
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
