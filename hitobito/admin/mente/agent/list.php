<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ����������ȥޥ��������ڡ���
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
include("$inc/list.php");

// �ڡ����إå���������
$header = new header_class('����������ȥޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('ID', 'center', 'adm_admin_id');
$list->set_header('�᡼�륢�ɥ쥹', 'left', 'adm_mail_addr');
$list->set_header('̾��', 'left', 'adm_name1,adm_name2');
$list->set_header('����', 'center', 'adm_unit');
$list->set_header('���ơ�����', 'center', 'adm_status');

// �ꥹ�ȥǡ�������
$sql = "SELECT adm_admin_id, adm_mail_addr, adm_name1, adm_name2, adm_type, adm_unit, adm_status"
		. " FROM m_admin"
		. " WHERE adm_type=3"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->adm_admin_id, "edit.php?admin_id=$fetch->adm_admin_id", '����������ȥޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->adm_mail_addr);
	$list->set_data("$fetch->adm_name1 $fetch->adm_name2");
	$list->set_data($fetch->adm_unit);
	$list->set_data(decode_admin_status($fetch->adm_status));
}

// ����������ȥ��ơ�����
function decode_admin_status($code, $def = '') {
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
		<td class="lt">������������ȥޥ�������</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='edit.php'">
			<input type="button" value="����롡" onclick="location.href='<?=$top?>/top.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
