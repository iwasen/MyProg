<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ��ļ��إå����եå��ƥ�ץ졼���Խ��ڡ���
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
$header = new header_class('��ļ��إå����եå��ƥ�ץ졼���Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new template_data_class();

$data->read_db();

// �Խ��ڡ����������饹����
$edit = new edit_class('��ļ��إå����եå���������Ϥ��Ƥ���������');

$edit->set_item('�إå�', true);
$edit->set_textarea('header', 78, 10, $data->header);

$edit->set_item('�եå�', true);
$edit->set_textarea('footer', 78, 10, $data->footer);
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	switch (f.next_action.value) {
	case "update":
		if (input_check(f))
			return confirm("��ļ��إå����եå��ƥ�ץ졼�Ⱦ���򹹿����ޤ���������Ǥ�����");
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
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
