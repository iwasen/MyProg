<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ҤȤӤ��ؤ��ۿ�����ְ����ڡ���
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
include("$inc/format.php");
include("$inc/decode.php");
include("$inc/select.php");

// �ڡ����إå���������
$header = new header_class('�ҤȤӤ��ؤ��ۿ�����ִ���', BACK_TOP);

// �ꥹ�Ƚ������饹����
$list = new list_class('small');

// �ꥹ�ȥإå�����
$list->set_header('JOBID', 'center', 'hbl_letter_id');
$list->set_header('JOB̾', 'left', 'hbl_job_name');
$list->set_header('�ۿ���', 'right', 'hbl_send_num');
$list->set_header('�ۿ�����', 'center', 'hbl_send_date');
$list->set_header('��������', 'center', 'hbl_enquete_date');
$list->set_header('������', 'right', 'hlc_answer_count');
$list->set_header('���؎�����', 'right', 'hlc_click_count');
$list->set_header('ȯ������', 'center', 'hbl_status');
$list->set_header('��ǧ', 'center', 'hbl_status');
$list->set_header('�ʎގ����Ŏݎʎގ�', 'center', 'hbl_backnumber');

// �ꥹ�ȥǡ�������
$sql = "SELECT hbl_letter_id, hbl_job_name, hbl_send_num, hbl_send_date, hbl_enquete_date, hlc_answer_count, hlc_click_count, hbl_status, hbl_backnumber, hbl_body"
		. " FROM t_hitobito_letter"
		. " JOIN c_hitobito_letter_count ON hbl_letter_id=hlc_letter_id"
		. $list->get_order_by(0, 1)
		. $list->get_limit_offset();
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_data($fetch->hbl_letter_id);
	$list->set_data($fetch->hbl_job_name, "edit.php?letter_id=$fetch->hbl_letter_id", '�ҤȤӤ��ؤ�����ɽ�����ѹ����ޤ�');
	$list->set_data(number_format($fetch->hbl_send_num));
	$list->set_data(format_datetime($fetch->hbl_send_date));
	$list->set_data(format_datetime($fetch->hbl_enquete_date));
	$list->set_data(number_format($fetch->hlc_answer_count));
	$list->set_data(number_format($fetch->hlc_click_count));
	$list->set_data(decode_letter_status1($fetch->hbl_status));

	if ($fetch->hbl_status == 1 && $fetch->hbl_body != '')
		$list->set_data(decode_letter_status2($fetch->hbl_status), "javascript:change_status1($fetch->hbl_letter_id)", '���Υ���֤�ǧ���ޤ�');
	elseif ($fetch->hbl_status == 2)
		$list->set_data(decode_letter_status2($fetch->hbl_status), "javascript:change_status2($fetch->hbl_letter_id)", '���Υ���֤ξ�ǧ����ä��ޤ�');
	else
		$list->set_data(decode_letter_status2($fetch->hbl_status));

	if ($fetch->hbl_backnumber == 0)
		$list->set_data(decode_backnumber($fetch->hbl_backnumber), "javascript:change_backnumber0($fetch->hbl_letter_id)", '���Υ���֤ΥХå��ʥ�С���Ǻܤ��ޤ�');
	else
		$list->set_data(decode_backnumber($fetch->hbl_backnumber), "javascript:change_backnumber1($fetch->hbl_letter_id)", '���Υ���֤ΥХå��ʥ�С�����Ǻܤˤ��ޤ�');

}
?>
<html lang="ja">
<head>
<?php $header->html_header(); ?>
<script type="text/javascript">
<!--
function change_status1(letter_id) {
	if (confirm("���ꤷ������֤�ǧ���ޤ���������Ǥ�����"))
		location.href = "change_status.php?letter_id=" + letter_id;
}
function change_status2(letter_id) {
	if (confirm("���ꤷ������֤ξ�ǧ����ä��ޤ���������Ǥ�����"))
		location.href = "change_status.php?letter_id=" + letter_id;
}
function change_backnumber0(letter_id) {
	if (confirm("���Υ���֤ΥХå��ʥ�С���Ǻܤ��ޤ���������Ǥ�����"))
		location.href = "change_bn.php?letter_id=" + letter_id;
}
function change_backnumber1(letter_id) {
	if (confirm("���Υ���֤ΥХå��ʥ�С�����Ǻܤˤ��ޤ���������Ǥ�����"))
		location.href = "change_bn.php?letter_id=" + letter_id;
}
//-->
</script>
</head>
<body>

<?php $header->page_header(); ?>

<form name="list" method="post">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lc">
<?php $list->disp_page_line($nrow); ?>
		</td>
		<td class="lb">
			<input type="button" value="������Ͽ" onclick="location.href='edit.php'">
		</td>
	</tr>
</table>
<?php $list->list_form(); ?>
</form>

<?php $list->disp_list(); ?>

<?php $header->page_footer(); ?>

</body>
</html>
