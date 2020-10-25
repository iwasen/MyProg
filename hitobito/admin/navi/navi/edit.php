<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥ���Ͽ�����Խ��ڡ���
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
include("$inc/decode.php");
include("$inc/list.php");
include("$inc/edit.php");
include("$inc/data.php");
include("data.php");

// �ڡ����إå���������
$header = new header_class('�ʥ���Ͽ�����Խ�', BACK_TOP);

// �ǡ����������饹����
$data = new navi_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['navi_id']))
	$data->read_db($_GET['navi_id']);

// ���ܾ����Խ��������饹����
$edit = new edit_class('���ܾ���');

if (!$data->new) {
	$edit->set_item('ID', false);
	$edit->set_string($data->navi_id);
}

$edit->set_item('�᡼�륢�ɥ쥹', true);
$edit->set_text('mail_addr', $data->mail_addr, 'alpha', 50, 100);
$edit->set_note('��Ⱦ��100ʸ���ޤǡ�');

$edit->set_item('�ѥ����', false);
$edit->set_text('password', '', 'alpha', 25, 20);
$edit->set_note('���ѹ�������Ͽ������ѥ���ɤ����ϡ��ѹ����ʤ����϶���Τޤޤˤ��Ƥ���������');

$edit->set_item('�ʥӥ��ɥ쥹', true);
$edit->set_text('forward_id', $data->forward_id, 'alpha', 25, 20);
$edit->set_string('@navi.hitobito.net');

$edit->set_item('�᡼��ž��', false);
$edit->set_radio('forward_flag', 1, $data->forward_flag, 'ž�������Ѥ���');
$edit->set_radio('forward_flag', 0, $data->forward_flag, 'ž�������Ѥ��ʤ�');

$edit->set_item('̾��', true);
$edit->set_string('��');
$edit->set_text('name1', $data->name1, 'kanji', 10, 20);
$edit->set_string('̾');
$edit->set_text('name2', $data->name2, 'kanji', 10, 20);

$edit->set_item('�եꥬ��', true);
$edit->set_string('����');
$edit->set_text('name1_kana', $data->name1_kana, 'kanji', 10, 20);
$edit->set_string('�Ҏ�');
$edit->set_text('name2_kana', $data->name2_kana, 'kanji', 10, 20);

$edit->set_item('����', true);
$edit->set_radio('sex_cd', 1, $data->sex_cd, '����');
$edit->set_radio('sex_cd', 2, $data->sex_cd, '����');

$edit->set_item('������', true);
$edit->set_select('birthday_y', select_year('1900', '', $data->birthday['y']));
$edit->set_select('birthday_m', select_month('', $data->birthday['m']));
$edit->set_select('birthday_d', select_day('', $data->birthday['d']));

$edit->set_item('͹���ֹ�', true);
$edit->set_text('zip1', substr($data->zip_cd, 0, 3), 'number', 3, 3);
$edit->set_string('-');
$edit->set_text('zip2', substr($data->zip_cd, 3, 4), 'number', 4, 4);

$edit->set_item('�����ֹ�', true);
$edit->set_text('jitaku_tel', $data->jitaku_tel, 'alpha', 20, 20);
$edit->set_note('��Ⱦ��20ʸ���ޤǡ�');

$edit->set_item('��ƻ�ܸ�', true);
$edit->set_select('address1', select_todofuken('��ƻ�ܸ�', $data->address1));

$edit->set_item('����', true);
$edit->set_text('address2', $data->address2, 'kanji', 50, 100);

$edit->set_item('�ޥ󥷥��/���ѡ���', false);
$edit->set_text('address3', $data->address3, 'kanji', 50, 100);

$edit->set_item('����', true);
$edit->set_select('shokugyo_id', select_shokugyo('���򤷤ʤ�', $data->shokugyo_id));


// ML���󡧥ꥹ�Ƚ������饹����
$list = new list_class(small);

// �ꥹ�ȥإå�����
$list->set_header_group('�ʥӥڡ���', 3);
$list->set_header('̾��', 'left');
$list->set_header('������', 'center');
$list->set_header('���ơ�����', 'center');
$list->set_header_group('�磻�磻��ļ�', 3);
$list->set_header('̾��', 'left');
$list->set_header('������', 'center');
$list->set_header('���ơ�����', 'center');

// �ꥹ�ȥǡ�������
$sql = "SELECT blg_blog_id, nvp_title, nvp_open_date, blg_status, rom_room_id, rom_status, rom_open_date, rom_name, nvp_open_flag"
		. " FROM t_navi_page"
		. " LEFT JOIN t_room ON nvp_room_id = rom_room_id"
		. " LEFT JOIN t_blog ON nvp_blog_id = blg_blog_id"
		. " WHERE nvp_navi_id = " . sql_number($data->navi_id)
		. " ORDER BY rom_open_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nvp_title, "$top/navi/blog/edit.php?blog_id=$fetch->blg_blog_id", 'Blog�����ɽ�����ѹ����ޤ�');
	$list->set_data(format_datetime($fetch->nvp_open_date, ''));
	$list->set_data(decode_navi_open_flag($fetch->nvp_open_flag, ''));
	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=$fetch->rom_room_id", 'ML�����ɽ�����ѹ����ޤ�');
	$list->set_data(format_date($fetch->rom_open_date, ''));
	$list->set_data(decode_room_status($fetch->rom_status, ''));
}

// ML�����Խ��������饹����
$edit2 = new edit_class('ML����');
$edit2->set_item($list);


// ����¾�����Խ��������饹����
$edit4 = new edit_class('����¾����');

//$edit4->set_item('���ơ�����', false);
//$edit4->set_select('status', select_navi_status($data->status));

if (!$data->new) {
	$edit4->set_item('��Ͽ����', false);
	$edit4->set_string(format_datetime($data->regist_date));

	$edit4->set_item('�ǿ���������', false);
	$edit4->set_string(format_datetime($data->update_date, '-'));
}

// �ʥӥڡ���ID,���С�ID����
$sql = "SELECT nvp_navi_page_id,nav_member_id"
	. " FROM t_navi_page"
	. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
	. " WHERE nvp_navi_id=$data->navi_id AND nvp_status=1";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$navi_page_id = $fetch->nvp_navi_page_id;
	$member_id = $fetch->nav_member_id;

	// �ʥӥڡ���URL
	$user_page_url = get_const('user_page_url');
	$profile_page_url = "{$user_page_url}index.php?module=Navi&action=ShowProfile&navipage_id=$navi_page_id";
	$regist_edit_url = "{$user_page_url}index.php?module=Member&action=ModifyProfile";
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
		if (input_check(f))
			return confirm("�ʥӾ������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�ʥ���Ͽ����򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�ʥ���Ͽ����������ޤ���������Ǥ�����");
	}
	return false;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 width="85%">
	<tr>
		<td align="right">
			<input type="button" value="���ʾҲ�ڡ�����" onclick="window.open('<?=$profile_page_url?>', 'profile_page')">
			<input type="button" value="��Ͽ���󥨥ǥ��åȲ��̤إ�����" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$member_id?>&url=<?=urlencode($regist_edit_url)?>', 'regist_edit')">
		</td>
	</tr>
</table>
<?php $edit->disp_edit() ?>
<br>
<?php $edit2->disp_edit() ?>
<br>
<?php $edit4->disp_edit() ?>

<div align="center">
	<input type="hidden" name="navi_id" <?=value($data->navi_id)?>>
	<input type="hidden" name="member_id" <?=value($data->member_id)?>>
	<input type="hidden" name="next_action">
	<?php if ($data->new) { ?>
	<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='new'">
	<?php } else { ?>
	<input type="submit" value="����Ͽ���󹹿���" onclick="document.form1.next_action.value='update'">
<!--	<input type="submit" value="�������" onclick="document.form1.next_action.value='delete'">	-->
	<?php } ?>
	<input type="button" value="����롡" onclick="history.back()">
</div>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
