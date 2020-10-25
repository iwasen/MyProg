<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����å������󥿡��Խ��ڡ���
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
include("$inc/const.php");
include("$inc/database.php");
include("$inc/edit.php");
include("$inc/crypt.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('����å������󥿡��Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new recruit_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['recruit_id']))
	$data->read_db($_GET['recruit_id']);

// ���ܾ����Խ��������饹����
$edit = new edit_class('���ܾ���');

if (!$data->new) {
	$edit->set_item('�����󥿡�ID');
	$edit->set_string($data->recruit_id);
}

$edit->set_item('�����󥿡�̾', true);
$edit->set_text('counter_name', $data->counter_name, 'alpha', 80);

$edit->set_item('������URL', true);
$edit->set_text('jump_url', $data->jump_url, 'url', 80);
$edit->set_button('��ǧ', 'window.open(document.edit.jump_url.value)');
$edit->set_note('��Ⱦ�ѱѿ���');

if (!$data->new) {
	$edit->set_item('����å������󥿡�����URL');
	$edit->set_string(get_const('user_page_url') .  'cl.php?' . encrypt_id($data->recruit_id));
}

$edit->set_item('������Ū', true);
$edit->set_text('usage', $data->usage, 'alpha', 40);

if (!$data->new) {
	$edit->set_item('����å���');
	$edit->set_string(number_format($data->click_count));
	$edit->set_html('��<a href="javascript:reset_counter()">�ꥻ�åȤ���</a>');

	$edit->set_item('��Ͽ�Կ�');
	$edit->set_string(number_format($data->regist_count));
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
		if (input_check(f))
			return confirm("����å������󥿡�����Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("����å������󥿡��򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("����å������󥿡��������ޤ���������Ǥ�����");
	}
	return false;
}
function reset_counter() {
	if (confirm("����å�����ꥻ�åȤ��ޤ���������Ǥ�����"))
		location.href = "reset.php?recruit_id=<?=$data->recruit_id?>";
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="reset">
<input type="hidden" name="recruit_id" <?=value($data->recruit_id)?>>
<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="����Ͽ��" onclick="document.edit.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="�����̽���" onclick="location.href='sum_date.php?recruit_id=<?=$data->recruit_id?>'">
<input type="button" value="�����̽���" onclick="location.href='sum_time.php?recruit_id=<?=$data->recruit_id?>'">
<?php } ?>
<input type="reset" value="�ꥻ�å�">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
