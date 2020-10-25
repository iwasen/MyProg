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
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/ngword.php");
include("$inc/format.php");
include("$inc/edit.php");
include("$inc/view.php");
include("$inc/const.php");
include("$inc/data.php");
include('./data.php');

// �ڡ����إå�������
$header = new header_class('���󥱡��ȴ���', BACK_TOP);

// �ǡ����������饹����
$data = new enquete_data_class();

$user_page_url = get_const('user_page_url');

// DB����ǡ����ɤ߹���
if(isset($_GET['enquete_id'])) {
	$data->read_db($_GET['enquete_id']);
	$data->read_db_comment_all($_GET['enquete_id']);
	$data->read_db_trackback_all($_GET['enquete_id']);
}

// ���ܾ���������饹����
$edit = new edit_class('���󥱡���');

if (!$data->new) {
	$edit->set_item('���󥱡���ID');
	if ($page_kind == 'admin')
		$edit->set_string($data->enquete_id);
	else
		$edit->set_html("<a href='{$user_page_url}index.php?module=Enquete&action=ShowResult&navipage_id=$data->navi_page_id&enquete_id=$data->enquete_id' target='_blank'>$data->enquete_id</a>");
}

$edit->set_item('������');
$edit->set_string(number_format($data->ans_count));

$edit->set_item('���󥱡���̾');
if ($page_kind == 'admin')
	$edit->set_html("<a href='edit.php?enquete_id=$data->enquete_id'>" . htmlspecialchars($data->nen_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Enquete&action=ListEnquete&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->nen_title) . "</a>");

$edit->set_item('�ʥӥڡ���');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->blog_id'>" . htmlspecialchars($data->nvp_title) . "</a>");
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

$checked = ($page_kind == 'patrol' ? $data->nen_patrol_check : $data->nen_admin_check) == 1;
$view->set_group('��̾�� ' . $data->nen_title, "edit_contents.php?enquete_id=$data->enquete_id", $data->nen_class, $checked);
$view->set_data('��̾', $data->nen_title);
$view->set_data('����ʸ', $data->nen_question_text);
$view->set_data('�����', view_enq_option($data->enq_option));
$view->set_data('NG���', decode_ngword($data->nen_ng_word));
$view->set_data('�ѥȥ���������å�', decode_patrol_check($data->nen_patrol_check));
$view->set_data('�ѥȥ���������å���', format_datetime($data->nen_patrol_date, ''));
$view->set_data('�ѥȥ���������å���', decode_patrol_name($data->nen_patrol_id));
$view->set_data('��̳�ɥ����å�', decode_admin_check($data->nen_admin_check));
$view->set_data('��̳�ɥ����å���', format_datetime($data->nen_admin_date, ''));
$view->set_data('��̳�ɥ����å���', decode_admin_name($data->nen_admin_id));
$view->set_data('����', decode_open_status($data->nen_open_flag));
$view->set_data('��Ͽ��', format_datetime($data->nen_date, ''));
$view->set_data('�������', number_format($data->pvc_pv_count));

// �ȥ�å��Хå�
if (is_array($data->fetch_tb)) {
	foreach ($data->fetch_tb as $item) {
		$bar_class = $data->get_bar_class($item->net_patrol_check, $item->net_admin_check, $item->net_ng_word);
		$checked = ($page_kind == 'patrol' ? $item->net_patrol_check : $item->net_admin_check) == 1;
		$view->set_group("�ȥ�å��Хå��� " . mb_strimwidth($item->net_excerpt, 0, 20, '..'), "edit_trackback.php?enquete_id=$data->enquete_id&trackback_no=$item->net_trackback_no", $bar_class, $checked);
		$view->set_data('�����ڡ���̾', $item->net_title);
		$view->set_html('�����URL', '<a href="' . $item->net_url . '" target="_blank">' . $item->net_url . '</a>');
		$view->set_data('����', $item->net_excerpt);
		$view->set_data('NG���', decode_ngword($item->net_ng_word));
		$view->set_data('�ѥȥ���������å�', decode_patrol_check($item->net_patrol_check));
		$view->set_data('�ѥȥ���������å���', format_datetime($item->net_patrol_date, ''));
		$view->set_data('�ѥȥ���������å���', decode_patrol_name($item->net_patrol_id));
		$view->set_data('��̳�ɥ����å�', decode_admin_check($item->net_admin_check));
		$view->set_data('��̳�ɥ����å���', format_datetime($item->net_admin_date, ''));
		$view->set_data('��̳�ɥ����å���', decode_admin_name($item->net_admin_id));
		$view->set_data('����', decode_open_status($item->net_open_flag));
		$view->set_data('��Ͽ��', format_datetime($item->net_date, ''));
		$view->set_data('�������', number_format($item->pvc_pv_count));
	}
}

// ������
if (is_array($data->fetch_cm)) {
	foreach ($data->fetch_cm as $item) {
		$bar_class = $data->get_bar_class($item->neo_patrol_check, $item->neo_admin_check, $item->neo_ng_word);
		$checked = ($page_kind == 'patrol' ? $item->neo_patrol_check : $item->neo_admin_check) == 1;
		$view->set_group("�����ȡ� " . mb_strimwidth($item->neo_comment, 0, 20, '..'), "edit_comment.php?enquete_id=$data->enquete_id&comment_no=$item->neo_comment_no", $bar_class, $checked);
		$view->set_data('��Ƽ�̾', $item->neo_user_name);
		$view->set_data('����', $item->neo_comment);
		$view->set_data('NG���', decode_ngword($item->neo_ng_word));
		$view->set_data('�ѥȥ���������å�', decode_patrol_check($item->neo_patrol_check));
		$view->set_data('�ѥȥ���������å���', format_datetime($item->neo_patrol_date, ''));
		$view->set_data('�ѥȥ���������å���', decode_patrol_name($item->neo_patrol_id));
		$view->set_data('��̳�ɥ����å�', decode_admin_check($item->neo_admin_check));
		$view->set_data('��̳�ɥ����å���', format_datetime($item->neo_admin_date, ''));
		$view->set_data('��̳�ɥ����å���', decode_admin_name($item->neo_admin_id));
		$view->set_data('����', decode_open_status($item->neo_open_flag));
		$view->set_data('��Ͽ��', format_datetime($item->neo_date, ''));
		$view->set_data('�������', number_format($item->pvc_pv_count));
	}
}

// ���󥱡���URL
$enquete_url = get_const('user_page_url') . "index.php?module=Enquete&action=AnswerEnquete&navipage_id=$data->navi_page_id&enquete_id=$data->enquete_id";

// �����ץ�ӥ塼
function view_enq_option($enq_option) {
	foreach ($enq_option as $option_no => $option_text)
		$s .= "�����{$option_no}��$option_text\n";

	return $s;
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
			return confirm("���󥱡��ȴ��ܾ������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("���󥱡��ȴ��ܾ���򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("���󥱡��ȴ��ܾ���������ޤ���������Ǥ�����");
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
<input type="hidden" name="enquete_id" <?=value($data->enquete_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="���󥱡��Ȥ�" onclick="window.open('<?=$enquete_url?>', 'navi_enquete')">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
