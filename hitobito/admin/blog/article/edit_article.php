<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * Blog�ʥʥӵ����˴����ڡ���
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
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/ngword.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå�����
$header = new header_class('Blog �ʥӵ����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new blog_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['article_id']))
	$data->read_db($_GET['article_id']);

// �ʥӵ��������Խ��������饹����
$edit = new edit_class('�ʥӵ���');

$edit->set_item('��̾', true);
$edit->set_text('bla_title', $data->bla_title, 'kanji', 50, 100);

$edit->set_item('����', true);
$edit->set_textarea('bla_article_text', 72, 20, $data->bla_article_text);

$edit->set_item('NG���');
$edit->set_string(decode_ngword($data->bla_ng_word));

$edit->set_item('�ѥȥ���������å�');
if ($page_kind == 'patrol')
	$edit->set_select('bla_patrol_check', select_patrol_check('', $data->bla_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->bla_patrol_check));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(format_datetime($data->bla_patrol_date, ''));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(decode_patrol_name($data->bla_patrol_id));

$edit->set_item('��̳�ɥ����å�');
if ($page_kind == 'admin')
	$edit->set_select('bla_admin_check', select_admin_check('', $data->bla_admin_check));
else
	$edit->set_string(decode_admin_check($data->bla_admin_check));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(format_datetime($data->bla_admin_date, ''));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(decode_admin_name($data->bla_admin_id));

$edit->set_item('����');
$edit->set_select('bla_open_flag', select_open_status('', $data->bla_open_flag));

$edit->set_item('��Ͽ��');
$edit->set_string(format_datetime($data->bla_date, ''));

$edit->set_item('�������');
$edit->set_string(number_format($data->pvc_pv_count));

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
			return confirm("�ʥӵ����������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�ʥӵ�������򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�ʥӵ�������������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>
<form method="post" name="edit" action="update.php" onsubmit="return onSubmit_edit(this)">

<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="article_id" <?=value($data->article_id)?>>
<input type="hidden" name="edit_kind" value='ct'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
