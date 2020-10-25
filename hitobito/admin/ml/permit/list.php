<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ��Ͽ���ɥ쥹�ʳ�����ƴ����ڡ���
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
include("$inc/edit.php");
include("$inc/list.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// �ڡ����إå���������
$header = new header_class('��Ͽ���ɥ쥹�ʳ�����ƴ���', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('�磻�磻���', 'left', 'rom_name', true);
$list->set_search_text('room_name', $_REQUEST['room_name'], 'kanji', 8);

$list->set_header('�����ȥ�', 'left', 'rmp_title');
$list->set_search_text('title', $_REQUEST['title'], 'alpha', 20);

$list->set_header('��ʸ', 'left', 'rmp_content');
$list->set_search_text('content', $_REQUEST['content'], 'kanji', 20);

$list->set_header('from', 'left', 'rmp_mail_addr');
$list->set_search_text('mail_addr', $_REQUEST['mail_addr'], 'kanji', 20);

$list->set_header('�����', 'center', 'rmp_date');
$list->set_search_select('reg_date', select_year_month('���٤�', $_REQUEST['reg_date']));

$list->set_header('���', 'center', 'rmp_status');
$list->set_search_select('status', select_room_public_status('���٤�', $_REQUEST['status'], false));

// �ƥ��ȥǡ�������
$sql = "SELECT rmp_room_id,rmp_seq_no,rom_name,rmp_title,rmp_content,rmp_mail_addr,rmp_date,rmp_status"
	. " FROM t_room_public LEFT JOIN t_room ON rmp_room_id=rom_room_id"
	. $list->get_where()
	. $list->get_order_by(0, 1)
	. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->rom_name, "$top/navi/ml/edit.php?room_id=" . $fetch->rmp_room_id, '�磻�磻��ļ������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->rmp_title);
	$list->set_data($fetch->rmp_content);
	$list->set_data($fetch->rmp_mail_addr);
	$list->set_data(format_datetime($fetch->rmp_date));
	$list->set_html('<select name="status_ary['.$fetch->rmp_room_id.']['.$fetch->rmp_seq_no.']">' . select_room_public_status('', $fetch->rmp_status, true) . '</select>');
}


// ��Ƹ������ơ�����
function select_room_public_status($default, $selected, $delete) {
	if ($default)
		$tag = option_tag('', $selected, $default);

	$tag .= option_tag('1', $selected, '̤���')
			. option_tag('2', $selected, '��ƺ�')
			. option_tag('3', $selected, '��ǧ')
			. option_tag('4', $selected, '���Ǥ�');

	if ($delete)
		$tag .= option_tag('9', $selected, '���');

	return $tag;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function onClick_edit(f) {
	if (confirm("��ƷǺܤε���/����Ĥ򹹿����ޤ���������Ǥ�����")) {
		var f = document.list;
		f.action = "change_status.php";
		f.submit();
	}
}
//-->
</script>
</header>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post" onsubmit="return search_check()">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc"><?php $list->disp_page_line($nrow); ?></td>
		<td width="50%" align="right" colspan=2>
		<td class="lb">
			<input type="button" value="��������" onclick="onClick_edit()">
			<input type="submit" value="�Ƹ���">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>

<?php $list->disp_list(); ?>
</table>
</form>
<br>

<?php $header->page_footer(); ?>

</body>
</html>
