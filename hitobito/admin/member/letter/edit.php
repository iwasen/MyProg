<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ҤȤӤ��ؤ��ۿ�������Խ��ڡ���
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

// �ڡ����إå���������
$header = new header_class('�ҤȤӤ��ؤ��ۿ�������Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new letter_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['letter_id']))
	$data->read_db($_GET['letter_id']);


// ���ܾ����Խ��������饹����
$edit = new edit_class('���ܾ���');

if (!$data->new) {
	$edit->set_item('ID');
	$edit->set_string($data->letter_id);
}

$edit->set_item('�����̾', true);
$edit->set_text('job_name', $data->job_name, 'alpha', 50, 100);
$edit->set_note('��Ⱦ��100ʸ���ޤǡ�');

if (!$data->new) {
	$edit->set_item('����');
	$edit->set_string(decode_letter_status2($data->status));
}

$edit->set_item('ȯ����������', true);
$edit->set_select('send_date_y', select_year('2005', '', $data->send_date['y']));
$edit->set_select('send_date_m', select_month('', $data->send_date['m']));
$edit->set_select('send_date_d', select_day('', $data->send_date['d']));
$edit->set_select('send_date_h', select_hour('', $data->send_date['h']));

$edit->set_item('���󥱡��ȡ�������');
$edit->set_select('enquete_date_y', select_year('2005', '', $data->enquete_date['y']));
$edit->set_select('enquete_date_m', select_month('', $data->enquete_date['m']));
$edit->set_select('enquete_date_d', select_day('', $data->enquete_date['d']));
$edit->set_select('enquete_date_h', select_hour('', $data->enquete_date['h']));

// �ܺپ���ץ�ӥ塼ɽ�����饹����
$view = new view_class('�ܺپ���');
$view->set_group('�᡼��ʸ', $data->status == 1 ? "edit_mail.php?letter_id=$data->letter_id" : '');
$view->set_data('�����ȥ�', $data->subject);
$view->set_data('�إå�', $data->header);
$view->set_data('��ʸ', $data->body);
$view->set_data('�եå�', $data->footer);

$view->set_group('����ե�����', $data->status == 1 ? "edit_enq.php?letter_id=$data->letter_id" : '');
$view->set_data('�����ȥ�', $data->enq_title);
$view->set_data('����ʸ', $data->enq_question);
$view->set_data('�����', view_enq_option($data->enq_option));
$view->set_data('�˥å��͡���������', decode_umu($data->enq_nickname));
$view->set_data('������������', decode_umu($data->enq_comment));

// �����ץ�ӥ塼
function view_enq_option($enq_option) {
	foreach ($enq_option as $option_no => $option_text)
		$s .= "�����{$option_no}��$option_text\n";

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
			return confirm("�ҤȤӤ��ؤ����Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check2(f))
			return confirm("�ҤȤӤ��ؤ�򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�ҤȤӤ��ؤ�������ޤ���������Ǥ�����");
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
		if (!confirm("ȯ�����������˲������������ꤵ��Ƥ��ޤ���������Ǥ�����")) {
			f.send_date_y.focus();
			return false;
		}
	}
	if (enquete_date <= send_date) {
		alert("���󥱡��ȡ���������ȯ�������������̤�����������ꤷ�Ƥ���������");
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
<input type="submit" value="����Ͽ��" onclick="document.edit.next_action.value='new'">
<?php
	} else {
?>
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="submit" value="�������" onclick="document.edit.next_action.value='delete'">
<?php
	}
}
?>
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
