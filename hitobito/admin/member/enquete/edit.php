<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���С����󥱡����Խ��ڡ���
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
include("$inc/const.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");
include("$inc/edit.php");
include("$inc/data.php");
include("$inc/decode.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('���С����󥱡����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new member_enq_data_class();

// �桼���ڡ���URL����
$user_page_url = get_const('user_page_url');

// DB����ǡ����ɤ߹���
if (isset($_GET['enquete_id']))
	$data->read_db($_GET['enquete_id']);

// �����Խ��������饹����
$edit = new edit_class('<tr><td class="head" colspan=2>��������������</td></tr>'
	. '<tr><td class="chart" colspan="2">���󥱡��Ȥδ��������Ԥ��ޤ���', 'html');

$edit->set_item('�����̾', true);
$edit->set_text('job_name', $data->job_name, 'kanji', 60, 100);

$edit->set_item('��������', true);
$edit->set_select('start_y', select_year('2006', ' ', $data->start_date['y']));
$edit->set_string('ǯ');
$edit->set_select('start_m', select_month(' ', $data->start_date['m']));
$edit->set_string('��');
$edit->set_select('start_d', select_day(' ', $data->start_date['d']));
$edit->set_string('��');
$edit->set_select('start_h', select_hour(' ', $data->start_date['h']));
$edit->set_string('��');

$edit->set_item('��λ����', true);
$edit->set_select('end_y', select_year('2006', ' ', $data->end_date['y']));
$edit->set_string('ǯ');
$edit->set_select('end_m', select_month(' ', $data->end_date['m']));
$edit->set_string('��');
$edit->set_select('end_d', select_day(' ', $data->end_date['d']));
$edit->set_string('��');
$edit->set_select('end_h', select_hour(' ', $data->end_date['h']));
$edit->set_string('��');

$edit->set_item('��Ļ���', false);
$edit->set_checkbox('room_flag', '1', $data->room_id, '���ꡡ');
$edit->set_select('room_id', select_room('----------', $data->room_id));
$edit->set_html('<br>������������');
$edit->set_radio('enq_target', '1', $data->enq_target, '�������С��ʲ����Ͽ����');
$edit->set_radio('enq_target', '2', $data->enq_target, '��¸���С��ʥ��������');
$edit->set_radio('enq_target', '3', $data->enq_target, '��¸�ȿ���');

$edit->set_item('�»ܾ���', false);
$edit->set_select('status', select_member_enq_status('', $data->status));

$edit->set_item('���󥱡���URL', false);
$edit->set_string($data->room_id ? "{$user_page_url}index.php?module=Waiwai&action=Enquete&navipage_id=". get_navi_page_url($data->room_id) : '');

// �����ȥ��Խ��������饹����
$edit2 = new edit_class('<tr><td class="head" colspan=2>�������ȥ롦����</td></tr>'
	. '<tr><td class="chart" colspan="2">���󥱡��ȥ����ȥ������ʸ�������Ԥ��ޤ���', 'html');


$edit2->set_item('���󥱡��ȥ����ȥ�', false);
$edit2->set_text('title', $data->title, 'kanji', 60, 100);

$edit2->set_item('����ʸ', false);
$edit2->set_note('��HTML�����б�');
$edit2->set_textarea('enq_text', 70, 20, $data->enq_text);

// ���󥱡��������Խ��������饹����
$edit3 = new edit_class('<tr><td class="head" colspan=2>�����󥱡�������</td></tr>'
	. '<tr><td class="chart" colspan="2">���󥱡��Ȥ����Ƥ����ꤷ�ޤ���SAM,MA,FA�����Ѥ��뤳�Ȥ��Ǥ��ޤ���', 'html');

$count = count($data->quest_text) ? count($data->quest_text) : 1;
for ($i = 0; $i < $count; $i++) {
	$edit3->set_item('�� ' . ($i + 1));
	$edit3->set_textarea('quest_text[]', 70, 3, $data->quest_text[$i]);
	$edit3->set_html('<br>');
	$edit3->set_select('quest_type[]', select_quest_type('', $data->quest_type[$i]));
	$edit3->set_html('<br>');
	$edit3->set_textarea('quest_option[]', 50, 10, $data->quest_option[$i]);
	$edit3->set_html('<br>');
	$edit3->set_checkbox('free_flag['.$i.']', '1',  $data->free_flag[$i], '�Ǹ�������ǥե꡼���������');
}

$edit4 = new edit_class();
$edit4->set_item('');
$edit4->set_html('<input type="button" value="���󥱡��Ȥ��ɲä���" onclick="add_question()">');

// �ץ�ӥ塼URLURL
$waiwai_edit_url = $data->room_id ? "{$user_page_url}index.php?module=Waiwai&action=Enquete&navipage_id=". get_navi_page_url($data->room_id) : '';

// ���С����󥱡��ȥ��ơ�����
function select_member_enq_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '̤�»�')
				. option_tag('2', $selected, '�»���')
				. option_tag('3', $selected, '������')
				. option_tag('9', $selected, '��λ');

	return $tag;
}

// ���С����󥱡������䥿����
function select_quest_type($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, 'ñ������(SA)')
				. option_tag('2', $selected, 'ʣ������(MA)')
				. option_tag('3', $selected, '��ͳ����(FA)');

	return $tag;
}

// �ʥӥڡ���ID����
function get_navi_page_url($room_id) {
	$sql = "SELECT nvp_navi_page_id,nav_member_id"
		. " FROM t_navi_page"
		. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
		. " WHERE nvp_room_id=$room_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);

		return  $fetch->nvp_navi_page_id;
	}
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f)) {
			f.action = "update.php";
			f.target = "_self";
			return confirm("���С��������Ͽ���ޤ���������Ǥ�����");
		}
		break;
	case "update":
		if (input_check(f)) {
			f.action = "update.php";
			f.target = "_self";
			return confirm("���С���Ͽ����򹹿����ޤ���������Ǥ�����");
		}
		break;
	case "delete":
		f.action = "update.php";
		return confirm("���С���Ͽ����������ޤ���������Ǥ�����");
	case "export":
		f.action = "";
		f.submit();
		break;
	case "preview":
		var wname = 'enq';

		f.action = "preview.php";
		window.open('', wname, 'width=620, scrollbars=yes');
		f.target = wname;
		f.submit();
		break;
	}
	return false;
}

function add_question() {
	var quest = document.all.question;
	var count = Number(document.form1.count.value) + 1;
	var num = Number(document.form1.num.value);

	if (!num)
		num = count;

	quest.innerHTML += '<table border=0 cellspacing=2 cellpadding=3 width="85%">'
							 + '<tr>'
							 + '<td class="face" width="25%">��' + num + '</td>'
							 + '<td class="bg3"><textarea name="quest_text[]" cols="70" rows="3"></textarea>'
							 + '<br>'
							 + '<select name="quest_type[]" size="1">'
							 + '<option value="1">ñ������(SA)</option>'
							 + '<option value="2">ʣ������(MA)</option>'
							 + '<option value="3">��ͳ����(FA)</option>'
							 + '</select>'
							 + '<br>'
							 + '<textarea name="quest_option[]" cols="50" rows="10"></textarea>'
							 + '<br>'
							 + '<input type="checkbox" name="free_flag[' + (num - 1) + ']" value="1">�Ǹ�������ǥե꡼���������'
							 + '</td>'
							 + '</tr>'
							 + '</table>';

	document.form1.num.value = num + 1;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" onsubmit="return onSubmit_form1(this)">

<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<?php $edit3->disp_edit() ?>
<span id="question"></span>
<?php $edit4->disp_edit() ?>

<br>
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="count" value="<?=$count?>">
<input type="hidden" name="num">

<input type="hidden" name="next_action">
<?php if ($data->new) { ?>
<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
<?php } else { ?>
<input type="submit" value="����Ͽ���󹹿���" onclick="document.form1.next_action.value='update'">
<?php } ?>
<input type="submit" value="���ץ�ӥ塼��"  onclick="document.form1.next_action.value='preview'">
<?php if (!$data->new) { ?>
<input type="button" value="��CSV������" onclick="location.href='export.php?enquete_id=<?=$data->enquete_id?>'">
<? } ?>
<input type="button" value="����롡" onclick="location.href='list.php'">
</form>

<?php $header->page_footer(); ?>

</body>
</html>
