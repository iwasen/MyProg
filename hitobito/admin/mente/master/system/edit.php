<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ƥ�ޥ����Խ��ڡ���
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
$header = new header_class('�����ƥ�ޥ����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new system_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['key']))
	$data->read_db($_GET['key']);

// �Խ��ڡ����������饹����
$edit = new edit_class('�����ƥ������������Ϥ��Ƥ���������');

$edit->set_item('����', true);
$edit->set_string($data->caption);

$edit->set_item('������', true);
$edit->set_text('value', $data->value, '', 80);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (input_check(f))
		return confirm("�����ƥ�ޥ�������򹹿����ޤ���������Ǥ�����");
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
<input type="hidden" name="key" <?=value($data->key)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
