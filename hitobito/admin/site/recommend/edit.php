<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����Υ��������ʥ��Խ��ڡ���
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
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/edit.php");
include("$inc/data.php");
include("./data.php");

// �ڡ����إå���������
$header = new header_class('�����Υ��������ʥӴ���', BACK_TOP);

// �ǡ����������饹����
$data = new recommend_data_class();

// DB����ǡ����ɤ߹���
$data->read_db($_POST['channel_id']);

// ��󥭥�����������饹����
$edit1 = new edit_class('�����⡼��');
$edit1->set_command('����ͥ�' . html_select('channel_id', select_channel('���ȥåץڡ�����', $_POST['channel_id']), 1, '', 'submit_self()'));

$position_ary = array(1 => '����', 2 => '����', 3 => '����');
for ($i = 1; $i <= 3; $i++) {
	$edit1->set_item($position_ary[$i], true);
	$edit1->set_radio("selection_mode[$i]", '1', $data->selection_mode[$i], '����');
	$edit1->set_select("fix_navi_page_id[$i]", select_navi_page_title('', $data->fix_navi_page_id[$i], $_POST['channel_id']));
	$edit1->set_br();
	$edit1->set_radio("selection_mode[$i]", '2', $data->selection_mode[$i], '������(�͵�����Ϣư1-5��)');
	$edit1->set_br();
	$edit1->set_radio("selection_mode[$i]", '3', $data->selection_mode[$i], '������');
	$edit1->set_br();
	$edit1->set_radio("selection_mode[$i]", '4', $data->selection_mode[$i], '�롼��');
	$edit1->set_br();
	$edit1->set_string('����');
	$edit1->set_select("loop_navi_page_id[$i][]", select_navi_page_title('', explode(',', $data->loop_navi_page_id[$i]), $_POST['channel_id']), '', 5, 'multiple');
}

// �������ٽ������饹����
$edit2 = new edit_class('��������');

$edit2->set_item('��������', true);
$edit2->set_radio('update_mode', '1', $data->update_mode, '��ư����<br>');
$edit2->set_radio('update_mode', '2', $data->update_mode, '��ư����');
$edit2->set_select('update_time', select_hour('', $data->update_time));
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit2->js_input_check(); ?>
<script type="text/javascript">
<!--
function onsubmit_edit(f) {
	if (!input_check2(f))
		return false;
	return confirm("�����Υ��������ʥӤ򹹿����ޤ���������Ǥ�����");
}
function input_check2(f) {
	var a = new Array("", "����", "����", "����");
	for (var i = 1; i <= 3; i++) {
		for (var j = 0; j < 4; j++) {
			if (f["selection_mode[" + i + "]"][j].checked)
				break;
		}
		if (j < 4) {
			if (j == 0) {
				if (f["fix_navi_page_id[" + i  +"]"].selectecIndex == -1) {
					alert("����⡼�ɤΥʥӥڡ��������򤷤Ƥ���������");
					f["fix_navi_page_id[" + i  +"]"].focus();
					return false;
				}
			} else if (j == 3) {
				for (var k = 0; k < f["loop_navi_page_id[" + i  +"][]"].length; k++) {
					if (f["loop_navi_page_id[" + i  +"][]"].options[k].selected)
						break;
				}
				if (k == f["loop_navi_page_id[" + i  +"][]"].length) {
					alert("�롼�ץ⡼�ɤΥʥӥڡ��������򤷤Ƥ���������");
					f["loop_navi_page_id[" + i  +"][]"].focus();
					return false;
				}
			}
		} else {
			alert(a[i] + "�ι����⡼�ɤ����򤷤Ƥ���������");
			f["selection_mode[" + i + "]"][0].focus();
			return false;
		}
	}
	return input_check(f);
}
function submit_self() {
	var f = document.edit;
	f.action = "edit.php";
	f.submit();
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="edit" method="post" action="update.php" onsubmit="return onsubmit_edit(this)">
<?php $edit1->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<br>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
