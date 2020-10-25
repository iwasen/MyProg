<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ҤȤӤ��ؤ�᡼���Խ��ڡ���
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
$header = new header_class('�ҤȤӤ��ؤ�᡼��ʸ�Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new letter_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['letter_id']))
	$data->read_db($_GET['letter_id']);


// ���ܾ����Խ��������饹����
$edit = new edit_class('�ҤȤӤ��ؤ�᡼��ʸ');

$edit->set_item('�����ȥ�', true);
$edit->set_text('subject', $data->subject, 'kanji', 80, 100);
$edit->set_note('�����ѣ�����ʸ���ޤǡ�');

$edit->set_item('�إå�', true);
$edit->set_textarea('header', 72, 10, $data->header);

$edit->set_item('��ʸ', true);
$edit->set_textarea('body', 72, 25, $data->body);

$edit->set_item('�եå�', true);
$edit->set_textarea('footer', 72, 8, $data->footer);

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	f.target = "";
	f.action = "update_mail.php";
	if (input_check(f))
		return confirm("�ҤȤӤ��ؤ�Υ᡼��ʸ�򹹿����ޤ���������Ǥ�����");
	return false;
}
function input_check(f) {
	if (f.subject.value == "") {
		alert("�����ȥ�����Ϥ��Ƥ���������");
		f.subject.focus();
		return false;
	}
	if (f.header.value == "") {
		alert("�إå������Ϥ��Ƥ���������");
		f.header.focus();
		return false;
	}
	if (f.body.value == "") {
		alert("��ʸ�����Ϥ��Ƥ���������");
		f.body.focus();
		return false;
	}
	if (f.footer.value == "") {
		alert("�եå������Ϥ��Ƥ���������");
		f.footer.focus();
		return false;
	}
	return true;
}
function onclick_counter() {
	var f = document.edit;
	f.target = "_blank";
	f.action = "click_counter.php";
	f.submit();
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update_mail.php" onsubmit="return onsubmit_edit(this)">
<?php $edit->disp_edit() ?>
<br>
<input type="hidden" name="letter_id" <?=value($data->letter_id)?>>
<input type="hidden" name="cc_start_date">
<input type="hidden" name="cc_end_date">
<input type="hidden" name="ct_id">
<input type="hidden" name="ct_count" value=0>
<input type="hidden" name="url_name">
<input type="hidden" name="jump_url">
  <input type="button" value="���؎��������ݎ�-����" onclick="onclick_counter()">
<input type="submit" value="��������">
<input type="reset" value="�ꥻ�å�">
<input type="button" value="����롡" onClick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
