<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �䤤��碌�����ڡ���
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
include("$inc/list.php");
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/csv.php");

// �ڡ����إå���������
$header = new header_class('�䤤��碌����', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// ɽ���Կ�
$limit = $list->get_limit_offset();

// �ꥹ�ȥإå�����
$list->set_header('��礻ID', 'center', 'nvi_inquiry_id');
$list->set_search_text('inquiry_id', $_REQUEST['inquiry_id'], 'number', 10);

$list->set_header('̾��', 'left', 'nvi_name');

$list->set_header('�ʥӥڡ���', 'left', 'nvp_title');
$list->set_search_text('navi_page', $_REQUEST['navi_page'], 'kanji', 20);

$list->set_header('����', 'center', 'nvi_inquiry_type');
$list->set_search_select('inquiry_type', select_inquiry_type('���٤�', $_REQUEST['inquiry_type']));

$list->set_header('��礻��', 'center', 'nvi_date');
$list->set_search_select('inquiry_date', select_year_month('���٤�', $_REQUEST['inquiry_date']));

$list->set_header('���ơ�����', 'center', 'nvi_status');
$list->set_search_select('status', select_inquiry_status('���٤�', $_REQUEST['status']));

$list->set_header('���', 'center', '', false, false);

// �ꥹ�ȥǡ�������
$sql = "SELECT nvi_inquiry_id,nvi_name,nvi_navi_page_id,nvp_title,nvi_inquiry_type,nvi_date,nvi_status"
	. " FROM t_navi_inquiry LEFT JOIN t_navi_page ON nvi_navi_page_id=nvp_navi_page_id"
	. $list->get_where()
	. $list->get_order_by(0, 0)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->nvi_inquiry_id, "check.php?inquiry_id=" . $fetch->nvi_inquiry_id, '�ʥ���礻�����ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->nvi_name);
	$list->set_data(decode_navi_page_name($fetch->nvi_navi_page_id));
	$list->set_data(decode_inquiry_type($fetch->nvi_inquiry_type));
	$list->set_data(format_datetime($fetch->nvi_date));
	$list->set_data(decode_inquiry_status($fetch->nvi_status, '-'));
	$list->set_html('<input type="button" name="delete_btn" value="���" onclick="onClick_delete(' . $fetch->nvi_inquiry_id . ')">');

}

// �䤤��碌��������
function select_inquiry_type($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '�����ˤĤ���')
				. option_tag('2', $selected, '�磻�磻��ĤˤĤ���')
				. option_tag('3', $selected, '�Ż��ΰ���')
				. option_tag('4', $selected, '���ΰ���')
				. option_tag('9', $selected, '����¾');

	return $tag;
}


// �䤤�礻���ơ���������
function select_inquiry_status($default, $selected) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '̤����')
				. option_tag('2', $selected, '������');

	return $tag;
}

// �䤤�礻���ơ������ǥ�����
function decode_inquiry_status($code, $def = '') {
	switch ($code) {
	case '1':
		return '̤����';
	case '2':
		return '������';
	}
	return $def;
}

// �������ݡ��Ƚ���
if (isset($_REQUEST['export'])) {
	$csv = new csv_class("inquiry.csv");
	$csv->export_data($list);
	exit;
}

?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onClick_delete(inquiry_id) {
	var f = document.list;
	if (confirm("��礻����������ޤ���������Ǥ�����")) {
		f.action = "delete.php?inquiry_id=" + inquiry_id;
		f.submit();
	}
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<nobr>
			<input type="submit" name="export" value="�������ݡ���">
			<input type="submit" value="�Ƹ���">
			</nobr>
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</form>

<?php $header->page_footer(); ?>

</body>
</html>
