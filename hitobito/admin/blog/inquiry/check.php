<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �䤤��碌�ʼ��������ڡ���
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
include("$inc/view.php");
include("$inc/data.php");
include('./data.php');

// �ڡ����إå�������
$header = new header_class('�䤤��碌����', BACK_TOP);

// �ǡ����������饹����
$data = new inquiry_data_class();

// DB����ǡ����ɤ߹���
if(isset($_GET['inquiry_id'])) {
	$data->read_db_contents($_GET['inquiry_id']);
}
// ���ܾ���������饹����
$edit = new edit_class('�䤤��碌');

$edit->set_item('�䤤��碌ID');
$edit->set_string($data->inquiry_id);

$edit->set_item('�ʥӥڡ���');
$edit->set_html("<a href='" . $top . "/navi/blog/edit.php?blog_id=" . $data->navi_page_id . "'>" . decode_navi_page_name($data->navi_page_id) . "</a>");

$edit->set_item('�ʥ�');
$edit->set_html("<a href='" . $top . "/navi/navi/edit.php?navi_id=" . $data->navi_id . "'>" . decode_navi_name($data->navi_id) . "</a>");

$edit->set_item('��̳�ɥ����å�');
$edit->set_select('admin_check', select_admin_check('', $data->admin_check));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(format_datetime($data->admin_date));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(decode_admin_name($data->admin_id));

$edit->set_item('Ϣ����');
$edit->set_textarea('communication_msg', 70, 4, $data->communication_msg);

// �ܺپ���ɽ�����饹����
$view = new view_class('�ܺپ���');

$view->set_group('�䤤��碌����');
$view->set_data('���̾', $data->company_name);
$view->set_data('����̾', $data->section_name);
$view->set_data('��̾��', $data->name);
$view->set_data('E-mail', $data->mail_addr);
$view->set_data('�����ֹ�', $data->tel_no);
$view->set_data('FAX�ֹ�', $data->fax_no);
$view->set_data('��������', decode_inquiry_type($data->inquiry_type));
$view->set_data('�Хʡ�', '');
$view->set_data('�ܺ�����', $data->content);
$view->set_data('�䤤��碌��', format_datetime($data->inquiry_date));

// �ʥӲ���
if (is_array($data->fetch)) {
	foreach ($data->fetch as $item) {

		$view->set_group('�ֿ�����');
		$view->set_data('��̾',$item->nva_subject);
		$view->set_data('�����襢�ɥ쥹',$item->nva_mail_addr);
		$view->set_data('��ʸ',$item->nva_content);
		$view->set_data('�ֿ���',format_datetime($item->nva_date));
	}
}
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
			return confirm("�䤤��碌�������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�䤤��碌����򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�䤤��碌����������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body onload="set_view_display()">
<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onSubmit_edit(this)">
<?php $edit->disp_edit(); ?>
<br>
<?php $view->disp_view() ?>
<br>
<input type="hidden" name="inquiry_id" <?=value($data->inquiry_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
