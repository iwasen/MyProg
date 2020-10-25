<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥڡ�����Blog����Ͽ�����Խ��ڡ���
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
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�ʥӥڡ�����Blog�˴���', BACK_TOP);

// ���ڡ���
if (isset($_REQUEST['ret']))
	$ret = $_REQUEST['ret'];
else
	$ret = $_SERVER['HTTP_REFERER'];

// �ǡ����������饹����
$data = new ml_data_class();

// DB����ǡ����ɤ߹���
if (isset($_REQUEST['blog_id']))
	$data->read_db($_REQUEST['blog_id']);

if (isset($_REQUEST['channel_id']))
	$data->channel_id = $_REQUEST['channel_id'];

// ���ܾ����Խ��������饹����
$edit = new edit_class('���ܾ���');

$edit->set_item('BlogID', false);
$edit->set_string($data->blog_id);

$edit->set_item('����ͥ�', false);
$edit->set_select('channel_id', select_channel('̤����', $data->channel_id), '', 1, '', "chg_channel()");

$edit->set_item('���֥���ͥ�', true);
$edit->set_select('sub_channel_id',$data->chg_select_subchannel('̤����', $data->sub_channel_id, $data->channel_id));

$edit->set_item('�ʥӥڡ���̾', true);
$edit->set_text('navi_page_title', $data->navi_page_title, 'kanji', 50, 100);

$edit->set_item('�ʥ�', true);
$edit->set_select('navi_id', select_navi_name('̤����', $data->navi_id));

$edit->set_item('��������', false);
$edit->set_select('open_date_y', select_year('2005', '', $data->open_date['y']));
$edit->set_select('open_date_m', select_month('', $data->open_date['m']));
$edit->set_select('open_date_d', select_day('', $data->open_date['d']));

$edit->set_item('���ơ�����', true);
$edit->set_radio('open_flag', 0, $data->open_flag, '̤����');
$edit->set_radio('open_flag', 1, $data->open_flag, '������');
$edit->set_radio('open_flag', 2, $data->open_flag, '������');
$edit->set_radio('open_flag', 9, $data->open_flag, '�ĺ���');

$edit->set_item('�Хʡ�����URL', false);
$edit->set_text('banner_image_url', $data->banner_image_url, 'alpha', 70, 100);
$edit->set_button('ɽ��', 'disp_banner_image()');

$edit->set_item('�Хʡ������URL', false);
$edit->set_text('banner_link_url', $data->banner_link_url, 'alpha', 70, 100);
$edit->set_button('ɽ��', 'disp_banner_link()');

$edit->set_item('��Ͽ����', false);
$edit->set_string(format_datetime($data->regist_date));

$edit->set_item('�ǿ���������', false);
$edit->set_string(format_datetime($data->udpate_date));

// ���׽������饹����
$edit2 = new edit_class('');

$edit2->set_item('BlogUU(�ǿ�����)', false);
$edit2->set_string(number_format($data->uu_num) . ' (' . format_datetime($data->last_date, '-') . ')');

$edit2->set_item('BlogPV(�ǿ�����)', false);
$edit2->set_string(number_format($data->pv_num) . ' (' . format_datetime($data->last_date, '-') . ')');

$edit2->set_item('�Хʡ�����å���(�ǿ�����)', false);
$edit2->set_string(number_format($data->banner_click_num) . ' (' . format_datetime($data->banner_click_last_date, '-') . ')');

$edit2->set_item('�ʥӵ�����(�ǿ�����)', false);
$edit2->set_string(number_format($data->article_num) . ' (' . format_datetime($data->article_last_date, '-') . ')');

$edit2->set_item('�ȥ�å��Хå���(�ǿ�����)', false);
$edit2->set_string(number_format($data->trackback_num) . ' (' . format_datetime($data->trackback_last_date, '-') . ')');

$edit2->set_item('�����ȿ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->comment_num) . ' (' . format_datetime($data->comment_last_date, '-') . ')');

$edit2->set_item('PICKUP(�ǿ�����)', false);
$edit2->set_string(number_format($data->pickup_num) . ' (' . format_datetime($data->pickup_last_date, '-') . ')');

$edit2->set_item('�ʥӥ��쥯�ȿ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->navi_select_num) . ' (' . format_datetime($data->navi_select_last_date, '-') . ')');

$edit2->set_item('���󥱡��ȿ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->enquete_num) . ' (' . format_datetime($data->enquete_last_date, '-') . ')');

$edit2->set_item('���󥱡��Ȳ�����(�ǿ�����)', false);
$edit2->set_string(number_format($data->ans_num) . ' (' . format_datetime($data->ans_last_date, '-') . ')');

$edit2->set_item('���ޥ��ۿ���(�ǿ�����)', false);
$edit2->set_string(number_format($data->melmaga_num) . ' (' . format_datetime($data->melmaga_last_date, '-') . ')');

$edit2->set_item('���ޥ��ɼԿ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->reader_num) . ' (' . format_datetime($data->reader_last_date, '-') . ')');

// ��������WEB���ƥ�������������饹����
$edit3 = new edit_class('');

$edit3->set_item('��������WEB���ƥ����ʣ������ġ�', false);
$edit3->set_html("<iframe name='kcom_category' src='".$top."/common/kcom_category.php?navi_page_id=$data->navi_page_id' height=300 width=450></iframe>");

// �ʥӥڡ���ID,���С�ID����
$sql = "SELECT nvp_navi_page_id,nav_member_id"
	. " FROM t_navi_page"
	. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
	. " WHERE nvp_blog_id=$data->blog_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$navi_page_id = $fetch->nvp_navi_page_id;
	$member_id = $fetch->nav_member_id;

	// �ʥӥڡ���URL
	$user_page_url = get_const('user_page_url');
	$navi_page_url = "{$user_page_url}index.php?module=Navi&action=ShowNaviPage&navipage_id=$navi_page_id";
	$navi_edit_url = "{$user_page_url}index.php?module=Navi&action=ShowNaviEdit&navipage_id=$navi_page_id";
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<?php $edit->js_input_check(); ?>
<script type="text/javascript">
<!--
function chg_channel() {
	var f = document.edit;
	f.action="edit.php";
	f.submit();
}

function onSubmit_edit(f) {
	get_kcom_category();
	switch (f.next_action.value) {
	case "new":
		if (input_check(f))
			return confirm("Blog��Ͽ�������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("Blog��Ͽ����򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("Blog��Ͽ����������ޤ���������Ǥ�����");
	}
	return false;
}

function get_kcom_category() {
	var f = document.edit;
	var f2 = document.kcom_category.form1;
	f.kcom_category_id.value = "";
	if (f2.kcom_category) {
		for (var i = 0; i < f2.kcom_category.length; i++) {
			if (f2.kcom_category[i].checked)
				f.kcom_category_id.value += f2.kcom_category[i].value + "\t";
		}
	}
}

function disp_banner_image() {
	var f = document.edit;
	if (f.banner_image_url.value != "")
		window.open(f.banner_image_url.value, "_blank");
}
//-->

function disp_banner_link() {
	var f = document.edit;
	if (f.banner_link_url.value != "")
		window.open(f.banner_link_url.value, "_blank");
}
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" action="update.php" onsubmit="return onSubmit_edit(this)">
<table border=0 width="85%">
	<tr>
		<td align="right">
<?php
if ($navi_page_id) {
?>
			<input type="button" value="�ʥӥڡ���(Blog)��" onclick="window.open('<?=$navi_page_url?>', 'navi_page')">
			<input type="button" value="�ʥӥ��ǥ��åȲ��̤إ�����" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$member_id?>&url=<?=urlencode($navi_edit_url)?>', 'navi_edit')">
<?
}
?>
		</td>
	</tr>
</table>
<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<?php $edit3->disp_edit() ?>
<div align="center">
	<input type="hidden" name="blog_id" <?=value($data->blog_id)?>>
	<input type="hidden" name="navi_page_id" <?=value($data->navi_page_id)?>>
	<input type="hidden" name="kcom_category_id">
	<input type="hidden" name="next_action">
	<?php if ($data->new) { ?>
	<input type="submit" value="����Ͽ��" onclick="document.edit.next_action.value='new'">
	<?php } else { ?>
	<input type="submit" value="����Ͽ���󹹿���" onclick="document.edit.next_action.value='update'">
<!--	<input type="submit" value="�������" onclick="document.edit.next_action.value='delete'">	-->
	<?php } ?>
	<input type="button" value="����롡" onclick="location.href='<?=$ret?>'">
	<input type="hidden" name="ret" <?=value($ret)?>>
</div>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
