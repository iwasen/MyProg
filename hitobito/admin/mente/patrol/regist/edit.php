<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ�����ޥ����Խ��ڡ���
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
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�ѥȥ�����ޥ����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new patrol_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['patrol_id']))
	$data->read_db($_GET['patrol_id']);

// �Խ��ڡ����������饹����
$edit = new edit_class('�ѥȥ������������Ϥ��Ƥ���������');

if (!$data->new) {
	$edit->set_item('�ѥȥ�����ID');
	$edit->set_string($data->patrol_id);
}

$edit->set_item('�᡼�륢�ɥ쥹', true);
$edit->set_text('mail_addr', $data->mail_addr, 'alpha', 50);

$edit->set_item('�ѥ����', true);
$edit->set_password('password', $data->password, 50);

$edit->set_item('̾��', true);
$edit->set_string('��');
$edit->set_text('name1', $data->name1, 'kanji', 20);
$edit->set_string('̾');
$edit->set_text('name2', $data->name2, 'kanji', 20);

$edit->set_item('̾���ʥ��ʡ�');
$edit->set_string('����');
$edit->set_text('name1_kana', $data->name1_kana, 'kanji', 20);
$edit->set_string('�ᥤ');
$edit->set_text('name2_kana', $data->name2_kana, 'kanji', 20);

$edit->set_item('���ơ�����', true);
$edit->set_select('status', select_patrol_status('', $data->status));

if (!$data->new) {
	$edit->set_item('��Ͽ����');
	$edit->set_string(format_datetime($data->regist_date));

	$edit->set_item('�ǿ���������');
	$edit->set_string(format_datetime($data->update_date));
}

// �ѥȥ����⥹�ơ�����
function select_patrol_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'ͭ��');
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
			return confirm("�ѥȥ�����ޥ����������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�ѥȥ�����ޥ�������򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�ѥȥ�����������ޤ���������Ǥ�����");
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
<input type="hidden" name="patrol_id" <?=value($data->patrol_id)?>>
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
