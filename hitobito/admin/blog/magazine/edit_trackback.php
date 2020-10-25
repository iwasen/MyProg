<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ޥ��ʼ��������ڡ���
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
$header = new header_class('�᡼��ޥ������Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new melmaga_contents_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['trackback_no']))
	$data->read_db_trackback($_GET['melmaga_contents_id'], $_GET['trackback_no']);

// �ʥӵ��������Խ��������饹����
$edit = new edit_class('�ȥ�å��Хå�');

$edit->set_item('�����ڡ���̾');
$edit->set_text('mmt_title', $data->mmt_title, 'kanji', 50);

$edit->set_item('���URL');
$edit->set_text('mmt_url', $data->mmt_url, 'alpha', 50);

$edit->set_item('����');
$edit->set_textarea('mmt_excerpt', 72, 15, $data->mmt_excerpt);

$edit->set_item('NG���');
$edit->set_string(decode_ngword($data->mmt_ng_word));

$edit->set_item('�ѥȥ���������å�');
if ($page_kind == 'patrol')
	$edit->set_select('mmt_patrol_check', select_patrol_check('', $data->mmt_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->mmt_patrol_check));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(format_datetime($data->mmt_patrol_date, ''));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(decode_patrol_name($data->mmt_patrol_id));

$edit->set_item('��̳�ɥ����å�', true);
if ($page_kind == 'admin')
	$edit->set_select('mmt_admin_check', select_admin_check('', $data->mmt_admin_check));
else
	$edit->set_string(decode_admin_check($data->mmt_admin_check));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(format_datetime($data->mmt_admin_date, ''));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(decode_admin_name($data->mmt_admin_id));

$edit->set_item('����', true);
$edit->set_select('mmt_open_flag', select_open_status('', $data->mmt_open_flag));

$edit->set_item('��Ͽ��');
$edit->set_string(format_datetime($data->mmt_date, ''));

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
		return confirm("�ȥ�å��Хå��򹹿����ޤ���������Ǥ�����");
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
<input type="hidden" name="melmaga_contents_id" <?=value($data->melmaga_contents_id)?>>
<input type="hidden" name="trackback_no" <?=value($data->trackback_no)?>>
<input type="hidden" name="edit_kind" value='tb'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
