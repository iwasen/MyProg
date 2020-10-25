<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ޥ��ʼ��������ڡ���
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
$header = new header_class('�᡼��ޥ��������', BACK_TOP);

// �ǡ����������饹����
$data = new melmaga_contents_data_class();

// DB����ǡ����ɤ߹���
if(isset($_GET['melmaga_contents_id'])) {
	$data->read_db_contents($_GET['melmaga_contents_id']);
	$data->read_db_comment_all($_GET['melmaga_contents_id']);
	$data->read_db_trackback_all($_GET['melmaga_contents_id']);
}
// ���ܾ���������饹����
$edit = new edit_class('�᡼��ޥ�����');

$user_page_url = get_const('user_page_url');

if (!$data->new) {
	$edit->set_item('�ޥ�����ID');
	if ($page_kind == 'admin')
		$edit->set_string($data->melmaga_contents_id);
	else
		$edit->set_html("<a href='{$user_page_url}index.php?module=MailMag&action=BacknumberContents&navipage_id=$data->navi_page_id&contents_id=$data->melmaga_contents_id' target='_blank'>$data->melmaga_contents_id</a>");
}

$edit->set_item('�̿�');
$edit->set_string(number_format($data->send_num));

$edit->set_item('���ޥ�̾');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/publisher/edit.php?melmaga_id=$data->melmaga_id'>" . htmlspecialchars($data->melmaga_name) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=MailMag&action=BacknumberList&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->melmaga_name) . "</a>");

$edit->set_item('�ʥӥڡ���');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->blog_id'>" . htmlspecialchars($data->blog_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowNaviPage&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->blog_title) . "</a>");

$edit->set_item('ȯ�Լ�');
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

$checked = ($page_kind == 'patrol' ? $data->mmc_patrol_check : $data->mmc_admin_check) == 1;
$view->set_group('��̾�� ' . $data->mmc_subject, "edit_contents.php?melmaga_contents_id=$data->melmaga_contents_id", $data->mmc_class, $checked);
$view->set_data('��̾', $data->mmc_subject);
$view->set_data('����', $data->mmc_body . "\n\n" . $data->mmc_footer);
$view->set_data('NG���', decode_ngword($data->mmc_ng_word));
$view->set_data('�ѥȥ���������å�', decode_patrol_check($data->mmc_patrol_check));
$view->set_data('�ѥȥ���������å���', format_datetime($data->mmc_patrol_date, ''));
$view->set_data('�ѥȥ���������å���', decode_patrol_name($data->mmc_patrol_id));
$view->set_data('��̳�ɥ����å�', decode_admin_check($data->mmc_admin_check));
$view->set_data('��̳�ɥ����å���', format_datetime($data->mmc_admin_date, ''));
$view->set_data('��̳�ɥ����å���', decode_admin_name($data->mmc_admin_id));
$view->set_data('����', decode_open_status($data->mmc_open_flag));
$view->set_data('��Ͽ��', format_datetime($data->mmc_date, ''));
$view->set_data('�������', number_format($data->pvc_pv_count));

// �ȥ�å��Хå�
if (is_array($data->fetch_tb)) {
	foreach ($data->fetch_tb as $item) {
		$bar_class = $data->get_bar_class($item->mmt_patrol_check, $item->mmt_admin_check, $item->mmt_ng_word);
		$checked = ($page_kind == 'patrol' ? $item->mmt_patrol_check : $item->mmt_admin_check) == 1;
		$view->set_group("�ȥ�å��Хå��� " . mb_strimwidth($item->mmt_excerpt, 0, 20, '..'), "edit_trackback.php?melmaga_contents_id=$data->melmaga_contents_id&trackback_no=$item->mmt_trackback_no", $bar_class, $checked);
		$view->set_data('�����ڡ���̾', $item->mmt_title);
		$view->set_html('�����URL', '<a href="' . $item->mmt_url . '" target="_blank">' . $item->mmt_url . '</a>');
		$view->set_data('����', $item->mmt_excerpt);
		$view->set_data('NG���', decode_ngword($item->mmt_ng_word));
		$view->set_data('�ѥȥ���������å�', decode_patrol_check($item->mmt_patrol_check));
		$view->set_data('�ѥȥ���������å���', format_datetime($item->mmt_patrol_date, ''));
		$view->set_data('�ѥȥ���������å���', decode_patrol_name($item->mmt_patrol_id));
		$view->set_data('��̳�ɥ����å�', decode_admin_check($item->mmt_admin_check));
		$view->set_data('��̳�ɥ����å���', format_datetime($item->mmt_admin_date, ''));
		$view->set_data('��̳�ɥ����å���', decode_admin_name($item->mmt_admin_id));
		$view->set_data('����', decode_open_status($item->mmt_open_flag));
		$view->set_data('��Ͽ��', format_datetime($item->mmt_date, ''));
		$view->set_data('�������', number_format($item->pvc_pv_count));
	}
}

// ������
if (is_array($data->fetch_cm)) {
	foreach ($data->fetch_cm as $item) {
		$mmo_class = $data->get_bar_class($item->mmo_patrol_check, $item->mmo_admin_check, $item->mmo_ng_word);
		$checked = ($page_kind == 'patrol' ? $item->mmo_patrol_check : $item->mmo_admin_check) == 1;
		$view->set_group("�����ȡ� " . mb_strimwidth($item->mmo_comment, 0, 20, '..'), "edit_comment.php?melmaga_contents_id=$data->melmaga_contents_id&comment_no=$item->mmo_comment_no", $mmo_class, $checked);
		$view->set_data('��Ƽ�̾', $item->mmo_user_name);
		$view->set_data('����', $item->mmo_comment);
		$view->set_data('NG���', decode_ngword($item->mmo_ng_word));
		$view->set_data('�ѥȥ���������å�', decode_patrol_check($item->mmo_patrol_check));
		$view->set_data('�ѥȥ���������å���', format_datetime($item->mmo_patrol_date, ''));
		$view->set_data('�ѥȥ���������å���', decode_patrol_name($item->mmo_patrol_id));
		$view->set_data('��̳�ɥ����å�', decode_admin_check($item->mmo_admin_check));
		$view->set_data('��̳�ɥ����å���', format_datetime($item->mmo_admin_date, ''));
		$view->set_data('��̳�ɥ����å���', decode_admin_name($item->mmo_admin_id));
		$view->set_data('����', decode_open_status($item->mmo_open_flag));
		$view->set_data('��Ͽ��', format_datetime($item->mmo_date, ''));
		$view->set_data('�������', number_format($item->pvc_pv_count));
	}
}
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
			return confirm("�᡼��ޥ�������ܾ������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�᡼��ޥ�������ܾ���򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�᡼��ޥ�������ܾ���������ޤ���������Ǥ�����");
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
<input type="hidden" name="melmaga_contents_id" <?=value($data->melmaga_contents_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
