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
$header = new header_class('���󥱡��ȴ���', BACK_TOP);

// �ǡ����������饹����
$data = new enquete_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['enquete_id']))
	$data->read_db($_GET['enquete_id']);

// �ʥӵ��������Խ��������饹����
$edit = new edit_class('���ܾ���');

$edit->set_item('���󥱡���ID');
$edit->set_string($data->enquete_id);

$edit->set_item('�ʥӥڡ���̾');
$edit->set_select('navi_page_id', select_navi_page_title('', $data->navi_page_id));

$edit->set_item('��Ͽ����');
$edit->set_string(format_datetime($data->nen_date));

$edit->set_item('�ǿ���������');
$edit->set_string(format_datetime($data->nen_date));

$edit2 = new edit_class();
$edit2->set_item('���󥱡��ȿ�');
$edit2->set_string($data->enq_count());

$edit2->set_item('���󥱡��Ȳ�����');
$edit2->set_string($data->ans_count());

// �����ʥӤΥ��󥱡��Ȳ��������
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php //$edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function input_check(f) {
	return true;
}

function onSubmit_edit(f) {
	if (input_check(f))
		return confirm("���󥱡��ȴ��ܾ���򹹿����ޤ���������Ǥ�����");
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
<?php $edit2->disp_edit() ?>
<br>
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="edit_kind" value='en'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
