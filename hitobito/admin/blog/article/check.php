<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * Blog�ʥʥӵ����˴����ڡ���
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
$header = new header_class('Blog(�ʥӵ���)����', BACK_TOP);

// �ǡ����������饹����
$data = new blog_data_class();

// DB����ǡ����ɤ߹���
if(isset($_GET['article_id'])) {
	$data->read_db($_GET['article_id']);
}
// ���ܾ���������饹����
$edit = new edit_class('���ܾ���');

$user_page_url = get_const('user_page_url');

if (!$data->new) {
	$edit->set_item('����ID');
	if ($page_kind == 'admin')
		$edit->set_string($data->article_id);
	else
		$edit->set_html("<a href='{$user_page_url}index.php?module=Blog&action=ViewStory&blog_story_id=$data->article_id' target='_blank'>$data->article_id</a>");
}

$edit->set_item('�ʥӥڡ���');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/blog/edit.php?blog_id=$data->blog_id'>" . htmlspecialchars($data->navi_page_title) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowNaviPage&navipage_id=$data->navi_page_id' target='_blank'>" . htmlspecialchars($data->navi_page_title) . "</a>");

$edit->set_item('�ʥ�');
if ($page_kind == 'admin')
	$edit->set_html("<a href='$top/navi/navi/edit.php?navi_id=$data->navi_id'>" . decode_navi_name($data->navi_id) . "</a>");
else
	$edit->set_html("<a href='{$user_page_url}index.php?module=Navi&action=ShowProfile&navipage_id=$data->navi_page_id' target='_blank'>" . decode_navi_name($data->navi_id) . "</a>");

$edit->set_item('pickup');
$edit->set_select('pickup', select_pickup('----', $data->pickup));

$edit->set_item('�ʥӥ��쥯��');
$edit->set_select('navi_select', select_naviselect('---', $data->navi_select));

$edit->set_item('Ϣ����');
$edit->set_textarea('communication_msg', 70, 4, $data->communication_msg);

// �ܺپ���ɽ�����饹����
$view = new view_class('�ܺپ���');

$checked = ($page_kind == 'patrol' ? $data->bla_patrol_check : $data->bla_admin_check) == 1;
$view->set_group('�ʥӵ����� ' . $data->bla_title, "edit_article.php?article_id=$data->article_id", $data->bla_class, $checked);
$view->set_data('��̾', $data->bla_title);
$view->set_data('����', $data->bla_article_text);
$view->set_data('NG���', decode_ngword($data->bla_ng_word));
$view->set_data('�ѥȥ���������å�', decode_patrol_check($data->bla_patrol_check));
$view->set_data('�ѥȥ���������å���', format_datetime($data->bla_patrol_date, ''));
$view->set_data('�ѥȥ���������å���', decode_patrol_name($data->bla_patrol_id));
$view->set_data('��̳�ɥ����å�', decode_admin_check($data->bla_admin_check));
$view->set_data('��̳�ɥ����å���', format_datetime($data->bla_admin_date, ''));
$view->set_data('��̳�ɥ����å���', decode_admin_name($data->bla_admin_id));
$view->set_data('����', decode_open_status($data->bla_open_flag));
$view->set_data('��Ͽ��', format_datetime($data->bla_date, ''));
$view->set_data('�������', number_format($data->pvc_pv_count));

if (is_array($data->fetch_tb)) {
	foreach ($data->fetch_tb as $tb) {
		$blt_class = $data->get_bar_class($tb->blt_patrol_check, $tb->blt_admin_check, $tb->blt_ng_word);
		$checked = ($page_kind == 'patrol' ? $tb->blt_patrol_check : $tb->blt_admin_check) == 1;
		$view->set_group("�ȥ�å��Хå��� $tb->blt_title", "edit_trackback.php?article_id=$data->article_id&trackback_no=$tb->blt_trackback_no", $blt_class, $checked);
		$view->set_data('�����ڡ���̾', $tb->blt_title);
		$view->set_html('�����URL', '<a href="' . $tb->blt_url . '" target="_blank">' . $tb->blt_url . '</a>');
		$view->set_data('����', $tb->blt_excerpt);
		$view->set_data('NG���', decode_ngword($tb->blt_ng_word));
		$view->set_data('�ѥȥ���������å�', decode_patrol_check($tb->blt_patrol_check));
		$view->set_data('�ѥȥ���������å���', format_datetime($tb->blt_patrol_date, ''));
		$view->set_data('�ѥȥ���������å���', decode_patrol_name($tb->blt_patrol_id));
		$view->set_data('��̳�ɥ����å�', decode_admin_check($tb->blt_admin_check));
		$view->set_data('��̳�ɥ����å���', format_datetime($tb->blt_admin_date, ''));
		$view->set_data('��̳�ɥ����å���', decode_admin_name($tb->blt_admin_id));
		$view->set_data('����', decode_open_status($tb->blt_open_flag));
		$view->set_data('��Ͽ��', format_datetime($tb->blt_date, ''));
		$view->set_data('�������', number_format($tb->pvc_pv_count));
	}
}
if (is_array($data->fetch_cm)) {
	foreach ($data->fetch_cm as $cm) {
		$blo_class = $data->get_bar_class($cm->blo_patrol_check, $cm->blo_admin_check, $cm->blo_ng_word);
		$checked = ($page_kind == 'patrol' ? $cm->blo_patrol_check : $cm->blo_admin_check) == 1;
		$view->set_group("�����ȡ� " . mb_strimwidth($cm->blo_comment, 0, 20, '..'), "edit_comment.php?article_id=$data->article_id&comment_no=$cm->blo_comment_no", $blo_class, $checked);
		$view->set_data('��Ƽ�', $cm->blo_user_name);
		$view->set_data('����', $cm->blo_comment);
		$view->set_data('NG���', decode_ngword($cm->blo_ng_word));
		$view->set_data('�ѥȥ���������å�', decode_patrol_check($cm->blo_patrol_check));
		$view->set_data('�ѥȥ���������å���', format_datetime($cm->blo_patrol_date, ''));
		$view->set_data('�ѥȥ���������å���', decode_patrol_name($cm->blo_patrol_id));
		$view->set_data('��̳�ɥ����å�', decode_admin_check($cm->blo_admin_check));
		$view->set_data('��̳�ɥ����å���', format_datetime($cm->blo_admin_date, ''));
		$view->set_data('��̳�ɥ����å���', decode_admin_name($cm->blo_admin_id));
		$view->set_data('����', decode_open_status($cm->blo_open_flag));
		$view->set_data('��Ͽ��', format_datetime($cm->blo_date, ''));
		$view->set_data('�������', number_format($cm->pvc_pv_count));
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
			return confirm("�ʥӵ����������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�ʥӵ�������򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�ʥӵ�������������ޤ���������Ǥ�����");
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
<input type="hidden" name="article_id" <?=value($data->article_id)?>>
<input type="hidden" name="edit_kind" value='bs'>
<input type="hidden" name="next_action">
<input type="submit" value="��������" onclick="document.edit.next_action.value='update'">
<input type="button" value="����롡" onClick='location.href="list.php"'>
</form>
<?php $header->page_footer(); ?>
</body>
</html>
