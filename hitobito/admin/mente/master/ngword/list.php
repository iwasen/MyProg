<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * NG��ɥޥ��������ڡ���
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
$header = new header_class('NG��ɥޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class();

// �ꥹ�ȥإå�����
$list->set_header('NG���', 'left', 'ngw_word');
$list->set_header('���ơ�����', 'center', 'ngw_status');

// �ꥹ�ȥǡ�������
$sql = "SELECT ngw_ngword_id, ngw_status, ngw_word"
		. " FROM m_ngword"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->ngw_word, "edit.php?ngword_id=$fetch->ngw_ngword_id", 'NG��ɥޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data(decode_ngword_status($fetch->ngw_status));
}

// NG��ɥ��ơ�����
function decode_ngword_status($code, $def = '') {
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
		<td class="lt">��NG��ɥޥ�������</td>
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
