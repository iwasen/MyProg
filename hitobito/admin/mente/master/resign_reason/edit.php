<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ͳ�ޥ����Խ��ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�����ͳ�ޥ����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new resign_reason_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['resign_id']))
	$data->read_db($_GET['resign_id']);

// �Խ��ڡ����������饹����
$edit = new edit_class('�����ͳ��������Ϥ��Ƥ���������');

$edit->set_item('�����ͳ', true);
$edit->set_text('reason_text', $data->reason_text, '', 50);

$edit->set_item('���ơ�����', true);
$edit->set_select('status', select_resign_reason_status($data->status));

// �����ͳ���ơ�����
function select_resign_reason_status($selected) {
	$tag  = option_tag('1', $selected, 'ͭ��');
	$tag .= option_tag('9', $selected, '̵��');

	return $tag;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("�����ͳ�ޥ����������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�����ͳ�ޥ�������򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�����ͳ�������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">

<?php $edit->disp_edit() ?>

<br>
<input type="hidden" name="resign_id" <?=value($data->resign_id)?>>
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">
<?php } ?>
<input type="button" value="����롡" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
