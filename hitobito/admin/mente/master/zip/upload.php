<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ͹���ֹ�ޥ������åץ��ɥڡ���
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
include("$inc/edit.php");

$header = new header_class('͹���ֹ�ޥ������åץ���', BACK_TOP);

// �Խ��ڡ����������饹����
$edit = new edit_class('͹���ֹ�CSV�ե���������Ϥ��Ƥ���������');

$edit->set_item('͹���ֹ�CSV�ե�����', true);
$edit->set_file('zip_csv', 60);
$edit->set_note('��KEN_ALL.CSV��');

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (!input_check(f))
		return false;
	if (!confirm("͹���ֹ�ޥ����򹹿����ޤ���������Ǥ�����"))
		return false;
	f.upload.disabled = true;
	document.getElementById("msg").innerHTML = "<br><font color='red'>���Ф餯���Ԥ�����������</font><br>";
	return true;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)" enctype="multipart/form-data">

<?php $edit->disp_edit() ?>

<br>
<?php
if ((int)ini_get('post_max_size') < 15 || (int)ini_get('upload_max_filesize') < 15) {
?>
<font color="red">ini.php �� post_max_size �� upload_max_filesize ��15M�ʾ�����ꤷ�Ƥ���������</font><br><br>
<?php
} else {
?>
<input type="submit" name="upload" value="���åץ���">
<?php
}
?>
<input type="button" value="����롡" onclick="history.back()">
</form>
<span id="msg"></span>

<?php $header->page_footer(); ?>

</body>
</html>
