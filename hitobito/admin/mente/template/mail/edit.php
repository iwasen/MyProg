<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �᡼��ƥ�ץ졼�ȥޥ����Խ��ڡ���
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
$header = new header_class('�᡼��ƥ�ץ졼�ȥޥ����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new template_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['template_id']))
	$data->read_db($_GET['template_id']);

// �Խ��ڡ����������饹����
$edit = new edit_class('�᡼��ƥ�ץ졼�Ⱦ�������Ϥ��Ƥ���������');

$edit->set_item('�ƥ�ץ졼��̾', true);
$edit->set_text('guide', $data->guide, 'kanji', 70);

$edit->set_item('��̾', true);
$edit->set_text('subject', $data->subject, 'kanji', 70);

$edit->set_item('From���ɥ쥹', true);
$edit->set_text('from', $data->from, 'alpha', 70);

$edit->set_item('Cc���ɥ쥹');
$edit->set_text('cc', $data->cc, 'alpha', 70);

$edit->set_item('Bcc���ɥ쥹');
$edit->set_text('bcc', $data->bcc, 'alpha', 70);

$edit->set_item('Reply-To���ɥ쥹');
$edit->set_text('reply_to', $data->reply_to, 'alpha', 70);

$edit->set_item('��ʸ', true);
$edit->set_textarea('body', 78, 20, $data->body);

// �᡼��ƥ�ץ졼�ȥ��ơ�����
function select_template_status($selected) {
	$tag  = option_tag('1', $selected, 'ͭ��');
	$tag .= option_tag('9', $selected, '̵��');

	return $tag;
}
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
			return confirm("�᡼��ƥ�ץ졼�ȥޥ�������򹹿����ޤ���������Ǥ�����");
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
<input type="hidden" name="template_id" <?=value($data->template_id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<input type="button" value="����롡" onclick="history.back()">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
