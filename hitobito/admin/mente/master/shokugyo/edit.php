<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ȥޥ����Խ��ڡ���
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
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('���ȥޥ����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new shokugyo_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['shokugyo_id']))
	$data->read_db($_GET['shokugyo_id']);

// �Խ��ڡ����������饹����
$edit = new edit_class('���Ⱦ�������Ϥ��Ƥ���������');

$edit->set_item('ɽ�����', true);
$edit->set_text('order', $data->order, 'number', 10, 4);
$edit->set_note('��Ⱦ�ѿ�����');

$edit->set_item('����̾', true);
$edit->set_text('shokugyo_name', $data->shokugyo_name, 'kanji', 70, 50);
$edit->set_note('������50ʸ���ޤǡ�');
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
			return confirm("���ȥޥ����������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("���ȥޥ�������򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("����̾�������ޤ���������Ǥ�����");
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
<input type="hidden" name="shokugyo_id" <?=value($data->shokugyo_id)?>>
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
