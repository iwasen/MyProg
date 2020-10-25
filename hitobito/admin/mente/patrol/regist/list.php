<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ�����ޥ��������ڡ���
 *
 *
 * @package
 * @author
 * @version
 */

$top = '../../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/list.php");

// �ڡ����إå���������
$header = new header_class('�ѥȥ�����ޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('ID', 'center', 'ptl_patrol_id');
$list->set_header('�᡼�륢�ɥ쥹', 'left', 'ptl_mail_addr');
$list->set_header('̾��', 'left', 'ptl_name1,ptl_name2');
$list->set_header('���ơ�����', 'center', 'ptl_status');

// �ꥹ�ȥǡ�������
$sql = "SELECT ptl_patrol_id, ptl_mail_addr, ptl_name1, ptl_name2, ptl_status"
		. " FROM m_patrol"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->ptl_patrol_id, "edit.php?patrol_id=$fetch->ptl_patrol_id", '�ѥȥ�����ޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->ptl_mail_addr);
	$list->set_data("$fetch->ptl_name1 $fetch->ptl_name2");
	$list->set_data(decode_patrol_status($fetch->ptl_status));
}

// �ѥȥ����⥹�ơ�����
function decode_patrol_status($code, $def = '') {
	switch ($code) {
	case '1':
		return  'ͭ��';
	case '9':
		return  '̵��';
	}
	return $def;
}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">���ѥȥ�����ޥ�������</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='edit.php'">
			<input type="button" value="����롡" onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
