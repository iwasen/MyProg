<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->staff_id = $_SESSION['ss_staff_id'];
	} else
		$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
}

$enquete = &$myenq->enquete;

// ��®ȯ��������������
get_quickest_date($send_date_y, $send_date_m, $send_date_d, $send_date_h);
?>
<? staff_header('���󥱡�����Ͽ') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form method="post" name="form1" action="myenq_update1.php">
			<table border=0 cellspacing=2 cellpadding=3 width="95%" class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">���»ܳ���</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" bgcolor="#eeeeee">���󥱡��ȥ����ȥ�<br>�ʥơ��ޡ�</td>
								<td width="40%" bgcolor="#ffeecc">
									<input type="text" name="title" size="46" <?=value($enquete->title)?>>
								</td>
								<td width="40%" bgcolor="#ffeecc">���󥱡��ȤΥ����ȥ��20ʸ�����٤Ǥ����꤯��������</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">����ȯ����������</td>
								<td bgcolor="#ffeecc">
									<?=$send_date_y?>ǯ<?=$send_date_m?>��<?=$send_date_d?>����<?=$send_date_h?>��
								</td>
								<td bgcolor="#ffeecc">���������λ���줿�ݤκ���ȯ�����������Ǥ���<br>�������ޤǤ⻲���ͤǤ�����λ������������
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��������</td>
								<td bgcolor="#ffeecc">
									<input type="text" name="start_date_y" <?=value($enquete->start_date_y)?> size="5" maxlength="4">ǯ
									<input type="text" name="start_date_m" <?=value($enquete->start_date_m)?> size="3" maxlength="2">��
									<input type="text" name="start_date_d" <?=value($enquete->start_date_d)?> size="3" maxlength="2">��
									<input type="text" name="start_date_h" <?=value($enquete->start_date_h)?> size="3" maxlength="2">��
								</td>
								<td bgcolor="#ffeecc">����11�������˻�̳�ɤؤο�������λ������ϡ�<br>������곫�Ϥ��������ޤ���</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��λ����</td>
								<td bgcolor="#ffeecc">
									<input type="text" name="end_date_y" <?=value($enquete->end_date_y)?> size="5" maxlength="4">ǯ
									<input type="text" name="end_date_m" <?=value($enquete->end_date_m)?> size="3" maxlength="2">��
									<input type="text" name="end_date_d" <?=value($enquete->end_date_d)?> size="3" maxlength="2">��
									<input type="text" name="end_date_h" <?=value($enquete->end_date_h)?> size="3" maxlength="2">��
								</td>
								<td bgcolor="#ffeecc">���󥱡��ȴ��֤Ϥ���ͳ�ˤ����ꤤ�������ޤ���<br>�ػ��٤�24����ɽ�������Ϥ��Ƥ���������</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�������</td>
								<td bgcolor="#ffeecc">
<?
$ans_cond = explode("\n", $myenq->ans_cond);
for ($i = 0; $i < 5; $i++) {
?>
										<input type="text" name="ans_cond[]" size=50 maxlength=100 <?=value($ans_cond[$i])?>><br>
<?
}
?>
								</td>
								<td bgcolor="#ffeecc">�����������ꤷ�Ƥ����������������Ȥ��ƺ������ޤ�ޤ���</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
			<input type="button" value="����롡" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			<input type="hidden" name="enq_type" value="2">
			<input type="hidden" name="point" <?=value($enquete->point)?>>
			</form>

<? staff_footer () ?>
