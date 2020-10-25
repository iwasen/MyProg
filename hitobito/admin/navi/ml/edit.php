<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥ�ML��Ͽ�����Խ��ڡ���
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
include("$inc/decode.php");
include("$inc/data.php");
include("$inc/csv.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�磻�磻��ļ���ML�˴���', BACK_TOP);

// ���ڡ���
if (isset($_REQUEST['ret']))
	$ret = $_REQUEST['ret'];
else
	$ret = $_SERVER['HTTP_REFERER'];

// �ǡ����������饹����
$data = new ml_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['room_id']))
	$data->read_db($_GET['room_id']);

// ���ܾ����Խ��������饹����
$edit = new edit_class('���ܾ���');

$edit->set_item('MLID', false);
$edit->set_string($data->room_id);

$edit->set_item('��ļ�̾', true);
$edit->set_text('name', $data->name, 'kanji', 50, 100);

$edit->set_item('ML-NAME', true);
$edit->set_text('room_code', $data->room_code, 'alpha', 20);

$edit->set_item('��������', false);
$edit->set_select('open_date_y', select_year('2005', '', $data->open_date['y']));
$edit->set_select('open_date_m', select_month('', $data->open_date['m']));
$edit->set_select('open_date_d', select_day('', $data->open_date['d']));

$edit->set_item('���ơ�����', true);
$edit->set_radio('status', 1, $data->status, '����');
$edit->set_radio('status', 2, $data->status, '�����');
$edit->set_radio('status', 9, $data->status, '�ĺ�');

$edit->set_item('�Хʡ�����URL', false);
$edit->set_text('banner_image_url', $data->banner_image_url, 'alpha', 70, 100);
$edit->set_button('ɽ��', 'disp_banner_image()');

$edit->set_item('�Хʡ������URL', false);
$edit->set_text('banner_link_url', $data->banner_link_url, 'alpha', 70, 100);
$edit->set_button('ɽ��', 'disp_banner_link()');

$edit->set_item('��Ͽ����', false);
$edit->set_string(format_datetime($data->regist_date));

$edit->set_item('�ǿ���������', false);
$edit->set_string(format_datetime($data->update_date));

$edit->set_item('ɬ���ɲ�°��', false);
$edit->set_checkbox("required_item[]", '1', in_array('1', $data->required_item), '��̾��');
$edit->set_checkbox('required_item[]', '2', in_array('2', $data->required_item), '�դ꤬�ʡ�');
$edit->set_checkbox('required_item[]', '3', in_array('3', $data->required_item), '���̡�');
$edit->set_checkbox('required_item[]', '4', in_array('4', $data->required_item), '��ǯ������');
$edit->set_checkbox('required_item[]', '5', in_array('5', $data->required_item), '͹���ֹ桡');
$edit->set_checkbox('required_item[]', '6', in_array('6', $data->required_item), '��ƻ�ܸ���');
$edit->set_checkbox('required_item[]', '7', in_array('7', $data->required_item), '����ʻ�Į¼�ʲ��ι��ܡˡ�');
$edit->set_checkbox('required_item[]', '8', in_array('8', $data->required_item), '�����ֹ桡');
$edit->set_checkbox('required_item[]', '9', in_array('9', $data->required_item), '���ʾҲ�');

// ���ܾ����Խ��������饹����
$edit2 = new edit_class('');

$edit2->set_item('���UU(�ǿ�����)', false);
$edit2->set_string(number_format($data->uu_num) . ' (' . format_datetime($data->last_date, '-') . ')');

$edit2->set_item('���PV(�ǿ�����)', false);
$edit2->set_string(number_format($data->pv_num) . ' (' . format_datetime($data->last_date, '-') . ')');

$edit2->set_item('�Хʡ�����å���(�ǿ�����)', false);
$edit2->set_string(number_format($data->click_num) . ' (' . format_datetime($data->click_last_date, '-') . ')');

$edit2->set_item('���С���(�ǿ�����)', false);
$edit2->set_string(number_format($data->member_num) . ' (' . format_datetime($data->member_last_date, '-') . ')');

$edit2->set_item('����Կ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->regist_num) . ' (' . format_datetime($data->regist_last_date, '-') . ')');

$edit2->set_item('���Կ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->resign_num) . ' (' . format_datetime($data->resign_last_date, '-') . ')');

$edit2->set_item('ȯ���Կ�(�ǿ�����)', false);
$edit2->set_string(number_format($data->remark_member_num) . ' (' . format_datetime($data->remark_member_last_date, '-') . ')');

$edit2->set_item('ȯ����(�ǿ�����)', false);
$edit2->set_string(number_format($data->remark_num) . ' (' . format_datetime($data->remark_last_date, '-') . ')');

// �ʥӥڡ���ID,���С�ID����
$sql = "SELECT nvp_navi_page_id,nav_member_id"
	. " FROM t_navi_page"
	. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
	. " WHERE nvp_room_id=$data->room_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$navi_page_id = $fetch->nvp_navi_page_id;
	$member_id = $fetch->nav_member_id;

	// �ʥӥڡ���URL
	$user_page_url = get_const('user_page_url');
	$waiwai_browse_url = "{$user_page_url}index.php?module=Waiwai&action=Browse&navipage_id=$navi_page_id";
	$waiwai_edit_url = "{$user_page_url}index.php?module=Waiwai&action=EditMenu&navipage_id=$navi_page_id";
}

// �������ݡ��Ƚ���
if ($_REQUEST['next_action'] == 'export') {
	$room_id = $_REQUEST['room_id'];

	$list = new list_class('');
	$csv = new csv_class("room_member_info.csv");

	$list->set_header('���С�ID');
	$list->set_header('�᡼�륢�ɥ쥹');
	$list->set_header('�˥å��͡���');
	$list->set_header('�ҤȤӤȡ�net��Ͽ����');
	$list->set_header('�磻�磻�����Ͽ����');

	$list->set_header('���ơ�����');

	$list->set_header('��̾�ʴ�����');
	$list->set_header('��̾�ʤ��ʡ�');
	$list->set_header('����');
	$list->set_header('��ǯ����');
	$list->set_header('͹���ֹ�');
	$list->set_header('��ƻ�ܸ�');
	$list->set_header('����ʻ�Į¼�ʲ��ι��ܡ�');
	$list->set_header('�����ֹ�');
	$list->set_header('���ʾҲ�');

	$sql = "SELECT * FROM t_room_member LEFT JOIN t_member ON mbr_member_id=rmm_member_id WHERE rmm_room_id=$room_id ORDER BY mbr_member_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$list->set_data($fetch->mbr_member_id);
		$list->set_data($fetch->mbr_mail_addr);
		$list->set_data($fetch->rmm_nickname);
		$list->set_data($fetch->mbr_regist_date);
		$list->set_data($fetch->rmm_regist_date);

		$list->set_data(decode_member_ml_status($fetch->rmm_status));
		$list->set_data($fetch->mbr_name);
		$list->set_data($fetch->mbr_name_kana);
		$list->set_data(decode_sex_cd($fetch->mbr_sex_cd));
		$list->set_data($fetch->mbr_birthday);
		$list->set_data(decode_zip_cd($fetch->mbr_zip_cd));
		$list->set_data(decode_address1($fetch->mbr_address1));
		$list->set_data($fetch->mbr_address2);
		$list->set_data($fetch->mbr_tel);
		$list->set_data($fetch->mbr_self_introduction);
	}

	$csv->export_data($list);
	exit;
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
		if (input_check(f)) {
			f.action = "update.php";
			return confirm("�磻�磻��ļ��������Ͽ���ޤ���������Ǥ�����");
		}
		break;
	case "update":
		if (input_check(f)) {
			f.action = "update.php";
			return confirm("�磻�磻��ļ���Ͽ����򹹿����ޤ���������Ǥ�����");
		}
		break;
	case "delete":
		f.action = "update.php";
		return confirm("�磻�磻��ļ���Ͽ����������ޤ���������Ǥ�����");
	case "export":
		f.action = "";
		f.submit();
	}
	return false;
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
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="edit" onsubmit="return onSubmit_edit(this)">
<table border=0 width="85%">
	<tr>
		<td align="right">
<?php
if ($navi_page_id) {
?>
			<input type="button" value="�磻�磻��ļ���" onclick="window.open('<?=$waiwai_browse_url?>', 'waiwai_browse')">
			<input type="button" value="ML���ǥ��åȲ��̤إ�����" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$member_id?>&url=<?=urlencode($waiwai_edit_url)?>', 'waiwai_edit')">
<?
}
?>
		</td>
	</tr>
</table>
<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<div align="center">
	<input type="hidden" name="room_id" <?=value($data->room_id)?>>
	<input type="hidden" name="banner_id" <?=value($data->banner_id)?>>
	<input type="hidden" name="next_action">
	<input type="submit" name="export" value="���С�����CSV�������ݡ���" onclick="document.edit.next_action.value='export'">
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
