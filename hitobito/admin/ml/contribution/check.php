<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ȯ���������ڡ���
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
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/ngword.php");
include("$inc/edit.php");
include("$inc/view.php");
include("$inc/room.php");
include("$inc/data.php");
include('./data.php');

// �ڡ����إå�������
$header = new header_class('ȯ������', BACK_TOP);

// �ǡ����������饹����
$data = new room_remark_data_class();

// DB����ǡ����ɤ߹���
if(isset($_REQUEST['remark_id'])) {
	$data->read_db($_REQUEST['remark_id']);
}

$viewline = isset($_POST['viewline']) ? $_POST['viewline'] : 20;

// ��ļ����饹����
$room = new room_class($data->room_id, $viewline);

$viewoption = isset($_POST['viewoption']) ? $_POST['viewoption'] : 'thread';
$view_remark_id = isset($_POST['view_remark_id']) ? $_POST['view_remark_id'] : $data->remark_id;
$viewpage = isset($_POST['viewpage']) ? $_POST['viewpage'] : $room->get_remark_page($data->remark_id);

$change_mode = $_POST['change_mode'];
set_viewoption($change_mode, $viewoption);

$page_num = $room->get_page_num();
$room->set_list_mode($viewoption);
$remark_ary = $room->get_list($viewpage, $view_remark_id);

$user_page_url = get_const('user_page_url');

// ���ܾ���������饹����
$edit = new edit_class('�磻�磻������');

if (!$data->new) {
	$edit->set_item('ȯ��ID');
	if ($page_kind == 'admin')
		$edit->set_string($data->remark_id);
	else
		$edit->set_html("<a href='{$user_page_url}index.php?module=Waiwai&action=Browse&navipage_id=$data->navi_page_id&waiwai_id=$data->seq_no#waiwai' target='_blank'>" . $data->remark_id . "</a>");
}

$edit->set_item('�磻�磻���');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/ml/edit.php?room_id=$data->room_id'>" . htmlspecialchars($data->room_name) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Waiwai&action=Browse&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->room_name) . "</a>");

$edit->set_item('�ʥ�');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/navi/edit.php?navi_id=$data->navi_id'>" . decode_navi_name($data->navi_id) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowProfile&navipage_id=$data->navi_page_id' target='_blank'>" . decode_navi_name($data->navi_id) . "</a>");

$edit->set_item('pickup');
$edit->set_select('pickup', select_pickup('----', $data->pickup));

$edit->set_item('Ϣ����');
$edit->set_textarea('communication_msg', 70, 4, $data->communication_msg);


// �ܺپ���ɽ�����饹����
$view = new view_class('�ܺپ���');

$checked = ($page_kind == 'patrol' ? $data->rmk_patrol_check : $data->rmk_admin_check) == 1;
$view->set_group('��̾�� ' . $data->rmk_subject, "edit_contents.php?remark_id=$data->remark_id", $data->rmk_class, $_POST['view_remark_id'] ? $_POST['check0'] : $checked);
$view->set_data('��̾', $data->rmk_subject);
$view->set_data('����', $data->rmk_room_header . "\n\n" . $data->rmk_content . "\n\n" . $data->rmk_room_footer);
$view->set_data('NG���', decode_ngword($data->rmk_ng_word));
$view->set_data('�ѥȥ���������å�', decode_patrol_check($data->rmk_patrol_check));
$view->set_data('�ѥȥ���������å���', format_datetime($data->rmk_patrol_date, ''));
$view->set_data('�ѥȥ���������å���', decode_patrol_name($data->rmk_patrol_id));
$view->set_data('��̳�ɥ����å�', decode_admin_check($data->rmk_admin_check));
$view->set_data('��̳�ɥ����å���', format_datetime($data->rmk_admin_date, ''));
$view->set_data('��̳�ɥ����å���', decode_admin_name($data->rmk_admin_id));
$view->set_data('����', decode_open_status($data->rmk_open_flag));
$view->set_data('��Ͽ��', format_datetime($data->rmk_date, ''));
$view->set_data('�������', number_format($data->pvc_pv_count));

$view->set_group('�磻�磻��ġ� ����ȯ��', "edit_contents.php?remark_id=$view_remark_id", '', $_POST['check1'], 'waiwai');
$view->set_html('ɽ������', view_set($viewline, $viewpage, $viewoption,$page_num, $change_mode));
$view->set_html('����å�', show_remark_list($remark_ary));
$view->set_data('����', $room->get_contents($view_remark_id));

// ɽ���������
function view_set($viewline, $viewpage, $viewoption, $page_num, $change_mode) {
	$mode_menu = $viewoption == 'thread' ? '��ƽ��ɽ��': '����å�ɽ��';
	$tag = '<table width="100%">'
		. '<tr>'
		. '<td width="30%"><a href="javascript:document.edit.change_mode.value=\'on\';document.edit.submit();">' . $mode_menu . '</a></td>'
		. '<td width="30%"><select name="viewline" onChange="document.edit.submit()">' . select_viewline($viewline) . '</select></td>'
		. '<td width="30%"><select name="viewpage" onChange="document.edit.submit()">' . select_viewpage($viewpage, $page_num) . "</select> / $page_num �ڡ��� </td>"
		. '<input type="hidden" name="viewoption" value=' . $viewoption . '>'
		. '</tr>'
		. '</table>';
	return $tag;
}

// ɽ���⡼������
function set_viewoption($change_mode, &$viewoption) {
	if ($change_mode) {
		if ($viewoption == 'thread')
			$viewoption = 'time';
		else
			$viewoption = 'thread';
	}
}

// ɽ����
function select_viewline($selected) {
	$ary = array(10, 20, 50, 100);
	$tag = '<option '. value_selected(0, $selected) . ">- ���� -</option>\n";
	foreach ($ary as $line)
		$tag .= '<option ' . value_selected($line, $selected) . ">$line ��ɽ��</option>\n";
	return $tag;
}

// ɽ���ڡ���
function select_viewpage($selected, $max) {
	for ($i = 1; $i <= $max; $i++)
		$tag .= '<option ' . value_selected($i, $selected) . ">$i �ڡ�����</option>\n";
	return $tag;
}

// ȯ���ꥹ��ɽ��
function show_remark_list($ary) {
	foreach ($ary as $line)
		$list .= $line . '<br>';
	return $list;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onClick_edit() {
	var f = document.edit;
	if (confirm("�ʥӵ�������򹹿����ޤ���������Ǥ�����")) {
		f.next_action.value = 'update';
		f.action = "update.php";
		f.submit();
	}
}

function click_remark(id) {
	var f = document.edit;
	f.view_remark_id.value = id;
	f.action = "check.php#waiwai";
	f.submit();
}

//-->
</script>
</head>
<body onload='set_view_display()'>
<?php $header->page_header(); ?>

<form action="check.php" method="post" name="edit">
<?php $edit->disp_edit(); ?>
<br>
<?php $view->disp_view() ?>
<br>
<input type="hidden" name="view_remark_id" <?=value($view_remark_id)?>>
<input type="hidden" name="change_mode">
<input type="hidden" name="remark_id" <?=value($data->remark_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="onClick_edit()">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
