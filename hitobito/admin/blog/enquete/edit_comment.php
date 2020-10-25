<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���󥱡����ʼ��������ڡ���
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
$header = new header_class('���󥱡����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new enquete_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['comment_no']))
	$data->read_db_comment($_GET['enquete_id'], $_GET['comment_no']);

// �ʥӵ��������Խ��������饹����
$edit = new edit_class('������');

$edit->set_item('��Ƽ�̾');
$edit->set_text('neo_user_name', $data->neo_user_name, 'kanji', 20);

$edit->set_item('����');
$edit->set_textarea('neo_comment', 72, 15, $data->neo_comment);

$edit->set_item('NG���');
$edit->set_string(decode_ngword($data->neo_ng_word));

$edit->set_item('�ѥȥ���������å�');
if ($page_kind == 'patrol')
	$edit->set_select('neo_patrol_check', select_patrol_check('', $data->neo_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->neo_patrol_check));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(format_datetime($data->neo_patrol_date, ''));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(decode_patrol_name($data->neo_patrol_id));

$edit->set_item('��̳�ɥ����å�', true);
if ($page_kind == 'admin')
	$edit->set_select('neo_admin_check', select_admin_check('', $data->neo_admin_check));
else
	$edit->set_string(decode_admin_check($data->neo_admin_check));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(format_datetime($data->neo_admin_date, ''));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(decode_admin_name($data->neo_admin_id));

$edit->set_item('����', true);
$edit->set_select('neo_open_flag', select_open_status('', $data->neo_open_flag));

$edit->set_item('��Ͽ��');
$edit->set_string(format_datetime($data->neo_date, ''));

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
	if (input_check(f))
		return confirm("�����Ȥ򹹿����ޤ���������Ǥ�����");
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
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="comment_no" <?=value($data->comment_no)?>>
<input type="hidden" name="edit_kind" value='cm'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
