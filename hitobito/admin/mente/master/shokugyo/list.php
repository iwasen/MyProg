<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ȥޥ��������ڡ���
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
$header = new header_class('���ȥޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class();

// �ꥹ�ȥإå�����
$list->set_header('ɽ�����', 'center', 'skg_order');
$list->set_header('����̾', 'left', 'skg_shokugyo_name');

// �ꥹ�ȥǡ�������
$sql = "SELECT skg_shokugyo_id, skg_shokugyo_name, skg_order"
		. " FROM m_shokugyo"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->skg_order, "edit.php?shokugyo_id=$fetch->skg_shokugyo_id", '���ȥޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->skg_shokugyo_name);
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
		<td class="lt">�����ȥޥ�������</td>
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
