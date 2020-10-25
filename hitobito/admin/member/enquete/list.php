<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���С����󥱡��Ȱ����ڡ���
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
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/list.php");

// �إå���������
$header = new header_class('���󥱡��Ȱ���', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// ɽ���Կ�
$limit = $list->get_limit_offset();

// �ꥹ�ȥإå�����
$list->set_header('SEQ', 'center', 'ren_enquete_id');
$list->set_search_text('seq_no', $_REQUEST['seq_no'], 'number', 8);

$list->set_header('�����̾', 'left', 'ren_job_name');
$list->set_search_text('job_name', $_REQUEST['job_name'], 'kanji', 20);

$list->set_header('���̾', 'left', 'rom_name');
$list->set_search_text('room_name', $_REQUEST['room_name'], 'kanji', 20);

$list->set_header('����', 'center', 'ren_target');
$list->set_search_select('target', select_target('���٤�', $_REQUEST['target']));

$list->set_header('�����Կ�', 'right', 'rec_count');
$list->set_search_select('ans_count', select_over_count('���٤�', $_REQUEST['ans_count']));

$list->set_header('��������', 'center', 'ren_start_date');
$list->set_search_select('start_date', select_year_month('���٤�', $_REQUEST['start_date']));

$list->set_header('���ơ�����', 'center', 'ren_status');
$list->set_search_select('status', select_member_enq_status('���٤�', $_REQUEST['status']));

// �ꥹ�ȥǡ�������
$sql = "SELECT ren_enquete_id, ren_job_name, rom_name, ren_target, rec_count, ren_start_date, ren_end_date, ren_status"
	. " FROM t_room_enquete LEFT JOIN t_room ON rom_room_id = ren_room_id LEFT JOIN c_room_enquete ON rec_enquete_id = ren_enquete_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->ren_enquete_id);
	$list->set_data($fetch->ren_job_name, "edit.php?enquete_id=" . $fetch->ren_enquete_id, '���󥱡��Ⱦ����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->rom_name);
	$list->set_data(decode_member_enq_target($fetch->ren_target));
	$list->set_html(get_link_text($fetch->ren_enquete_id, $fetch->rec_count));
	$list->set_data(format_date($fetch->ren_start_date) . '��' . format_date($fetch->ren_end_date));
	$list->set_data(decode_member_enq_status($fetch->ren_status));
}

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

// �оݼ�
function select_target($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '����')
				. option_tag('2', $selected, '��¸')
				. option_tag('3', $selected, 'ξ��');

	return $tag;
}

// ���С����󥱡��Ȼ���
function decode_member_enq_target($code, $def='') {
	switch($code) {
	case '1':
		return '����';
	case '2':
		return '��¸';
	case '3':
		return 'ξ��';
	}
	return $def;
}

// ���С����󥱡��ȥ��ơ�����
function decode_member_enq_status($code, $def='') {
	switch($code) {
	case '1':
		return '̤�»�';
	case '2':
		return '�»���';
	case '3':
		return '������';
	case '9':
		return '��λ';
	}
	return $def;
}

// CSV�������
function get_link_text($enquete_id, $count) {
	if (!$count)
		return 0;

	$link = '<a href="export.php?enquete_id=' . $enquete_id . '">' . number_format($count) . '</a>';
	return $link;
}
?>

<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>
<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td class="lc" align="right">
		<nobr>
		<input type="submit" value="�Ƹ���" onclick="document.list.next_action.value=''">
		<input type="button" value="������Ͽ" onclick="location.href='edit.php'">
		</nobr>
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
<input type="hidden" name="enquete_id">
<input type="hidden" name="next_action">
</form>
  <br>
<?php $header->page_footer(); ?>
</body>
</html>
