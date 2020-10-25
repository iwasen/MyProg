<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ޥ���Ͽ�����Խ��ڡ���
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
include("data.php");


// �ʥӥڡ���(Blog)̾(�����ʥӥڡ����ȥ��ޥ��ʤ��Τ�)
function select_nomelmaga_blog_title($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$sql = sprintf("SELECT blg_blog_id, blg_title FROM t_blog LEFT JOIN t_navi_page ON blg_blog_id = nvp_blog_id"
		. " WHERE nvp_status = 1 AND (nvp_navi_page_id = %s OR nvp_melmaga_id IS NULL) ORDER BY blg_blog_id", sql_number($selected));
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$tag .= option_tag($fetch->blg_blog_id, $selected, $fetch->blg_title);
	}

	return $tag;
}

// �ڡ����إå���������
$header = new header_class('�᡼��ޥ��������', BACK_TOP);

// �ǡ����������饹����
$data = new ml_data_class();

// DB����ǡ����ɤ߹���
if (isset($_GET['melmaga_id']))
	$data->read_db($_GET['melmaga_id']);

// ���ܾ����Խ��������饹����
$edit = new edit_class('���ܾ���');

$edit->set_item('���ޥ�ID', false);
$edit->set_string($data->melmaga_id);

$edit->set_item('�᡼��ޥ�����̾', true);
$edit->set_text('melmaga_name', $data->melmaga_name, 'kanji', 50, 100);

$edit->set_item('�ʥӥڡ���̾', true);
$edit->set_select('edit_navi_page_id', select_navi_page_title('̤����', $data->navi_page_id));

$edit->set_item('�ʥ�', true);
$edit->set_string($data->navi_name);


$edit->set_item('���ơ�����', true);
$edit->set_radio('status', 1, $data->status, 'ͭ��');
$edit->set_radio('status', 0, $data->status, '̵��');

$edit->set_item('��Ͽ����', false);
$edit->set_string(format_datetime($data->regist_date));

$edit->set_item('�ǿ���������', false);
$edit->set_string(format_datetime($data->update_date));

// ���ܾ����Խ��������饹����
$edit2 = new edit_class('');

$edit2->set_item('ȯ�Կ�(�ǿ�����)', false);
$edit2->set_string($data->reader_cnt . ' (' . format_datetime($data->last_publish, '̤ȯ��') . ')');

$edit2->set_item('�ɼԿ�(�ǿ�����)', false);
$edit2->set_string($data->publish_cnt . ' (' . format_datetime($data->last_publish, '̤ȯ��') . ')');

// �ʥӥڡ���ID,���С�ID����
$sql = "SELECT nvp_navi_page_id,nav_member_id"
	. " FROM t_navi_page"
	. " JOIN t_navi ON nav_navi_id=nvp_navi_id"
	. " WHERE nvp_melmaga_id=$data->melmaga_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$navi_page_id = $fetch->nvp_navi_page_id;
	$member_id = $fetch->nav_member_id;

	// �ʥӥڡ���URL
	$user_page_url = get_const('user_page_url');
	$melmaga_page_url = "{$user_page_url}index.php?module=MailMag&action=BacknumberList&navipage_id=$navi_page_id";
	$melmaga_edit_url = "{$user_page_url}index.php?module=MailMag&action=ListMailMag&id=1&navipage_id=$navi_page_id";
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
			return confirm("�᡼��ޥ�����������Ͽ���ޤ���������Ǥ�����");
		break;
	case "update":
		if (input_check(f))
			return confirm("�᡼��ޥ�������Ͽ����򹹿����ޤ���������Ǥ�����");
		break;
	case "delete":
		return confirm("�᡼��ޥ�������Ͽ����������ޤ���������Ǥ�����");
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
<?php
if ($navi_page_id) {
?>
			<input type="button" value="���ޥ��ڡ�����" onclick="window.open('<?=$melmaga_page_url?>', 'melmaga_page')">
			<input type="button" value="���ޥ����ǥ��åȲ��̤إ�����" onclick="window.open('<?=$top?>/common/user_login.php?member_id=<?=$member_id?>&url=<?=urlencode($melmaga_edit_url)?>', 'melmaga_edit')">
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
<div align="center">
	<input type="hidden" name="melmaga_id" <?=value($data->melmaga_id)?>>
	<input type="hidden" name="navi_page_id" <?=value($data->navi_page_id)?>>
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
