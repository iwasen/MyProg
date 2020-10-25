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
$header = new header_class('Blog �������Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new blog_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['comment_no']))
	$data->read_db_cm($_GET['article_id'], $_GET['comment_no']);

// �ʥӵ��������Խ��������饹����
$edit = new edit_class('������');

$edit->set_item('��Ƽ�̾');
$edit->set_text('blo_user_name', $data->blo_user_name, 'kanji', 20);

$edit->set_item('����');
$edit->set_textarea('blo_comment', 72, 15, $data->blo_comment);

$edit->set_item('NG���');
$edit->set_string(decode_ngword($data->blo_ng_word));

$edit->set_item('�ѥȥ���������å�');
if ($page_kind == 'patrol')
	$edit->set_select('blo_patrol_check', select_patrol_check('', $data->blo_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->blo_patrol_check));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(format_datetime($data->blo_patrol_date, ''));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(decode_patrol_name($data->blo_patrol_id));

$edit->set_item('��̳�ɥ����å�');
if ($page_kind == 'admin')
	$edit->set_select('blo_admin_check', select_admin_check('', $data->blo_admin_check));
else
	$edit->set_string(decode_admin_check($data->blo_admin_check));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(format_datetime($data->blo_admin_date, ''));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(decode_admin_name($data->blo_admin_id));

$edit->set_item('����');
$edit->set_select('blo_open_flag', select_open_status('', $data->blo_open_flag));

$edit->set_item('��Ͽ��');
$edit->set_string(format_datetime($data->blo_date, ''));

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
			return confirm("�����Ⱦ������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�����Ⱦ���򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�����Ⱦ���������ޤ���������Ǥ�����");
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
<input type="hidden" name="comment_no" <?=value($data->comment_no)?>>
<input type="hidden" name="edit_kind" value='cm'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
