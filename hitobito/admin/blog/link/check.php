<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����ʼ��������ڡ���
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
include("$inc/data.php");
include('./data.php');

// �ڡ����إå�������
$header = new header_class('��󥯴���', BACK_TOP);

// �ǡ����������饹����
$data = new link_data_class();

// DB����ǡ����ɤ߹���
if(isset($_GET['link_id'])) {
	$data->read_db_contents($_GET['link_id']);
}
// ���ܾ���������饹����
$edit = new edit_class('�ʥӵ���');

$user_page_url = get_const('user_page_url');

if (!$data->new) {
	$edit->set_item('���ID');
	if ($page_kind == 'admin')
		$edit->set_string($data->link_id);
	else
		$edit->set_html("<a href='$data->link_url' target='_blank'>$data->link_id</a>");
}

$edit->set_item('�ʥӥڡ���');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->navi_page_id'>" . htmlspecialchars($data->nvp_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowNaviPage&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->nvp_title) . "</a>");

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

$checked = ($page_kind == 'patrol' ? $data->patrol_check : $data->admin_check) == 1;
$view->set_group('�����̾�Ρ� ' . $data->link_name, "edit_contents.php?link_id=$data->link_id", $data->class, $checked);
$view->set_data('�����̾��', $data->link_name);
$view->set_data('�����URL', $data->link_url);
$view->set_data('����賵��', $data->link_outline);
$view->set_data('NG���', decode_ngword($data->ng_word));
$view->set_data('�ѥȥ���������å�', decode_patrol_check($data->patrol_check));
$view->set_data('�ѥȥ���������å���', format_datetime($data->patrol_date, ''));
$view->set_data('�ѥȥ���������å���', decode_patrol_name($data->patrol_id));
$view->set_data('��̳�ɥ����å�', decode_admin_check($data->admin_check));
$view->set_data('��̳�ɥ����å���', format_datetime($data->admin_date, ''));
$view->set_data('��̳�ɥ����å���', decode_admin_name($data->admin_id));
$view->set_data('����', decode_open_status($data->open_flag));
$view->set_data('��Ͽ��', format_datetime($data->date, ''));
$view->set_data('�������', number_format($data->pvc_pv_count));
$view->set_data('����å����', number_format($data->click_count));

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function onSubmit_edit(f) {
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("��󥯾������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("��󥯾���򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("��󥯾���������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body onload="set_view_display()">
<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onSubmit_edit(this)">
<?php $edit->disp_edit(); ?>
<br>
<?php $view->disp_view() ?>
<br>
<input type="hidden" name="link_id" <?=value($data->link_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
