<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �᡼��ƥ�ץ졼�ȥޥ��������ڡ���
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
$header = new header_class('�᡼��ƥ�ץ졼�ȥޥ�������', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('�ƥ�ץ졼��̾', 'left', 'mtm_template_id');
$list->set_header('��̾', 'left', 'mtm_subject');
$list->set_header('���пͥ��ɥ쥹', 'left', 'mtm_from');

// �ꥹ�ȥǡ�������
$sql = "SELECT mtm_template_id, mtm_guide, mtm_subject, mtm_from"
		. " FROM m_mail_template"
		. $list->get_order_by(0, 0);
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->mtm_guide, "edit.php?template_id=$fetch->mtm_template_id", '�᡼��ƥ�ץ졼�ȥޥ��������ɽ�����ѹ����ޤ�');
	$list->set_data($fetch->mtm_subject);
	$list->set_data($fetch->mtm_from);
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
		<td class="lt">���᡼��ƥ�ץ졼�ȥޥ�������</td>
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
