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
$edit = new edit_class('�ʥӥ��󥱡���');

$edit->set_item('��̾', true);
$edit->set_text('nen_title', $data->nen_title, 'kanji', 50, 100);

$edit->set_item('����ʸ', true);
$edit->set_textarea('nen_question_text', 50, 5, $data->nen_question_text);

$edit->set_item('�����', true);
for ($i = 1; $i <= 9; $i++) {
	$edit->set_string('�����' . mb_convert_kana($i, 'N'));
	$edit->set_text("enq_option[$i]", $data->enq_option[$i], 'kanji', 60);
	$edit->set_br();
}

$edit->set_item('NG���');
$edit->set_string(decode_ngword($data->nen_ng_word));

$edit->set_item('�ѥȥ���������å�');
if ($page_kind == 'patrol')
	$edit->set_select('nen_patrol_check', select_patrol_check('', $data->nen_patrol_check));
else
	$edit->set_string(decode_patrol_check($data->nen_patrol_check));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(format_datetime($data->nen_patrol_date, ''));

$edit->set_item('�ѥȥ���������å���');
$edit->set_string(decode_patrol_name($data->nen_patrol_id));

$edit->set_item('��̳�ɥ����å�', true);
if ($page_kind == 'admin')
	$edit->set_select('nen_admin_check', select_admin_check('', $data->nen_admin_check));
else
	$edit->set_string(decode_admin_check($data->nen_admin_check));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(format_datetime($data->nen_admin_date, ''));

$edit->set_item('��̳�ɥ����å���');
$edit->set_string(decode_admin_name($data->nen_admin_id));

$edit->set_item('����', true);
$edit->set_select('nen_open_flag', select_open_status('', $data->nen_open_flag));

$edit->set_item('��Ͽ��');
$edit->set_string(format_datetime($data->nen_date, ''));

$edit->set_item('�������');
$edit->set_string(number_format($data->pvc_pv_count));

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php //$edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function input_check(f) {
	if (f.nen_title.value == "") {
		alert("��̾���Ϥ��Ƥ���������");
		f.enq_title.focus();
		return false;
	}
	if (f.nen_question_text.value == "") {
		alert("����ʸ�����Ϥ��Ƥ���������");
		f.enq_question.focus();
		return false;
	}
	return true;
}

function onSubmit_edit(f) {
	if (input_check(f))
		return confirm("�ʥӥ��󥱡��Ȥ򹹿����ޤ���������Ǥ�����");
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
<input type="hidden" name="edit_kind" value='ct'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
