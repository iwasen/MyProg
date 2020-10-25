<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ҤȤӤ��ؤ����ե������Խ��ڡ���
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
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�ҤȤӤ��ؤ����ե������Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new letter_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['letter_id']))
	$data->read_db($_GET['letter_id']);


// ���ܾ����Խ��������饹����
$edit = new edit_class('�ҤȤӤ��ؤ����ե�����');

$edit->set_item('�����ȥ�', true);
$edit->set_text('enq_title', $data->enq_title, 'kanji', 80, 100);
$edit->set_note('�����ѣ�����ʸ���ޤǡ�');

$edit->set_item('����ʸ', true);
$edit->set_textarea('enq_question', 72, 5, $data->enq_question);

$edit->set_item('�����', true);
for ($i = 1; $i <= 9; $i++) {
	$edit->set_string('�����' . mb_convert_kana($i, 'N'));
	$edit->set_text("enq_option[$i]", $data->enq_option[$i], 'kanji', 60);
	$edit->set_br();
}

$edit->set_item('�˥å��͡���������', true);
$edit->set_radio('enq_nickname', '1', $data->enq_nickname, '����');
$edit->set_radio('enq_nickname', '0', $data->enq_nickname, '�ʤ�');

$edit->set_item('������������', true);
$edit->set_radio('enq_comment', '1', $data->enq_comment, '����');
$edit->set_radio('enq_comment', '0', $data->enq_comment, '�ʤ�');

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	if (input_check(f))
		return confirm("�ҤȤӤ��ؤ�α���ե�����򹹿����ޤ���������Ǥ�����");
	return false;
}
function input_check(f) {
	if (f.enq_title.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.enq_title.focus();
		return false;
	}
	if (f.enq_question.value == "") {
		alert("����ʸ�����Ϥ��Ƥ���������");
		f.enq_question.focus();
		return false;
	}
	if (f["enq_option[1]"].value == "") {
		alert("���������Ϥ��Ƥ���������");
		f["enq_option[1]"].focus();
		return false;
	}
	if (!f.enq_nickname[0].checked && !f.enq_nickname[1].checked) {
		alert("�˥å��͡�������������򤷤Ƥ���������");
		f.enq_nickname[0].focus();
		return false;
	}
	if (!f.enq_comment[0].checked && !f.enq_comment[1].checked) {
		alert("����������������򤷤Ƥ���������");
		f.enq_comment[0].focus();
		return false;
	}
	return true;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>
<form method="post" name="edit" action="update_enq.php" onsubmit="return onsubmit_edit(this)">

<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="letter_id" <?=value($data->letter_id)?>>
<input type="submit" value="��������">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
