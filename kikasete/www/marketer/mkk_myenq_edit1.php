<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->marketer_id = $_SESSION['ss_owner_id'];
		$myenq->creator_id = $_SESSION['ss_marketer_id'];

		// �ꥵ�������ϴ�˾���Υǥե������
		get_quickest_date($send_date_y, $send_date_m, $send_date_d, $send_date_h);
		$myenq->enquete->start_date_y = $send_date_y;
		$myenq->enquete->start_date_m = $send_date_m;
		$myenq->enquete->start_date_d = $send_date_d;
		$myenq->enquete->start_date_h = 18;

		// �ꥵ������λ��˾���Υǥե������
		$end_date = next_working_day2(mktime($send_date_h, 0, 0, $send_date_m, $send_date_d, $send_date_y), 2);
		$myenq->enquete->end_date_y = date('Y', $end_date);
		$myenq->enquete->end_date_m = date('m', $end_date);
		$myenq->enquete->end_date_d = date('d', $end_date);
		$myenq->enquete->end_date_h = 9;
	} else
		$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);
}

$enquete = &$myenq->enquete;
?>
<? marketer_header('My���󥱡���', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>

			<form method="post" name="form1" action="mkk_myenq_update1.php">
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td bgcolor="#eff7e8">���»ܳ���</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" bgcolor="#eeeeee">my���󥱡��ȥ����ȥ�</td>
								<td width="40%" bgcolor="#ffeecc">
									<input type="text" name="title" size="30" <?=value($enquete->title)?>>��
									�ˤĤ��ƤΥ��󥱡���</td>
								<td width="40%" bgcolor="#ffeecc">&nbsp;</td>
							</tr>
<? /*
							<tr>
								<td bgcolor="#eeeeee">����ȯ����������</td>
								<td bgcolor="#ffeecc">
									<?=$send_date_y?>ǯ<?=$send_date_m?>��<?=$send_date_d?>����<?=$send_date_h?>��
								</td>
								<td bgcolor="#ffeecc">���������λ���줿�ݤκ���ȯ�����������Ǥ���<br><br>�������ޤǤ⻲���ͤǤ�������������ޤ��󤬡�
																			<span style="color:navy;"><b>�����������ˤ�ȯ�����Ǥ��ޤ���</b></span>�Τǡ�
																			���餫���ᤴλ������������
								</td>
							</tr>
*/ ?>
							<tr>
								<td bgcolor="#eeeeee">�ꥵ�������ϴ�˾��</td>
								<td bgcolor="#ffeecc">
									<select name="start_date_y"><? select_year(min($enquete->start_date_y, date('Y')), '', $enquete->start_date_y, date('Y') + 1) ?></select>ǯ
									<select name="start_date_m"><? select_month('', $enquete->start_date_m) ?></select>��
									<select name="start_date_d"><? select_day('', $enquete->start_date_d) ?></select>��
									<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
								</td>
							  <td bgcolor="#ffeecc">����11�������˻�̳�ɤؤο�������λ������ϡ�<br>������곫�Ϥ��������ޤ���</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�ꥵ������λ��˾��</td>
								<td bgcolor="#ffeecc">
									<select name="end_date_y"><? select_year(min($enquete->end_date_y, date('Y')), '', $enquete->end_date_y, date('Y') + 1) ?></select>ǯ
									<select name="end_date_m"><? select_month('', $enquete->end_date_m) ?></select>��
									<select name="end_date_d"><? select_day('', $enquete->end_date_d) ?></select>��
									<input type="hidden" name="end_date_h" <?=value($enquete->end_date_h)?>>
									<?=$enquete->end_date_h?>��
								</td>
							  <td bgcolor="#ffeecc">ɸ��24���ָ��2�Ķ����ˤ�9���ˤʤ�ޤ���</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�������</td>
								<td bgcolor="#ffeecc">
<?
$ans_cond_ary = explode("\n", $myenq->ans_cond);
for ($i = 0; $i < $myenq->ans_cond_count; $i++) {
?>
										<input type="text" name="ans_cond[]" size=50 maxlength=100 <?=value($ans_cond_ary[$i])?>><br>
<?
}
if ($myenq->ans_cond_count < 5) {
?>
										<input type="submit" value="���������ɲ�" onclick="document.form1.next_action.value='add_cond'">
<?
}
?>
								</td>
								<td bgcolor="#ffeecc">�����������ꤷ�Ƥ���������<br /><span class="footer_text">
							    ��˥����ؤΰ���᡼��Ρֲ����������ɽ������ޤ���</span></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
			<input type="button" value="����롡" onclick="location.href='mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			<input type="hidden" name="enq_type" value="2">
			<input type="hidden" name="point" <?=value($enquete->point)?>>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer () ?>
