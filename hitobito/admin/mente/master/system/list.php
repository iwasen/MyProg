<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ƥ�ޥ��������ڡ���
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
$header = new header_class('�����ƥ�ޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class();

// �ꥹ�ȥإå�����
$list->set_header('����', 'left', 'sys_caption');
$list->set_header('������', 'left', 'sys_value');

// �ꥹ�ȥǡ�������
$sql = "SELECT sys_key, sys_value, sys_caption"
		. " FROM m_system"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->sys_caption, "edit.php?key=$fetch->sys_key", '�����ƥ�ޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->sys_value);
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
		<td class="lt">�������ƥ�ޥ�������</td>
		<td class="lb">
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
