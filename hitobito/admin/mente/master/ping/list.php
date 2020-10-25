<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * Ping�ޥ��������ڡ���
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
$header = new header_class('Ping�ޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class();

// �ꥹ�ȥإå�����
$list->set_header('������', 'left', 'png_name');
$list->set_header('������URL', 'left', 'png_url');
$list->set_header('���ơ�����', 'center', 'png_status');

// �ꥹ�ȥǡ�������
$sql = "SELECT png_ping_id, png_status, png_name, png_url"
		. " FROM m_ping"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->png_name, "edit.php?ping_id=$fetch->png_ping_id", 'Ping�ޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->png_url);
	$list->set_data(decode_ping_status($fetch->png_status));
}

// Ping���ơ�����
function decode_ping_status($code, $def = '') {
	switch ($code) {
	case 1:
		return  'ͭ��';
	case 9:
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
		<td class="lt">��Ping�ޥ�������</td>
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
