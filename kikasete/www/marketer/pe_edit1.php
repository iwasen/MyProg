<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq_no == '') {
		$pro_enq->marketer_id = $_SESSION['ss_owner_id'];
		$pro_enq->creator_id = $_SESSION['ss_marketer_id'];

		// �ꥵ�������ϴ�˾���Υǥե������
		get_quickest_date_pro($send_date_y, $send_date_m, $send_date_d, $send_date_h);
		$pro_enq->enquete->start_date_y = $send_date_y;
		$pro_enq->enquete->start_date_m = $send_date_m;
		$pro_enq->enquete->start_date_d = $send_date_d;
		$pro_enq->enquete->start_date_h = 18;

		// �ꥵ������λ��˾���Υǥե������
		$end_date = next_working_day2(mktime($send_date_h, 0, 0, $send_date_m, $send_date_d, $send_date_y), 2);
		$pro_enq->enquete->end_date_y = date('Y', $end_date);
		$pro_enq->enquete->end_date_m = date('m', $end_date);
		$pro_enq->enquete->end_date_d = date('d', $end_date);
		$pro_enq->enquete->end_date_h = 9;
	} else
		$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
}

$enquete = &$pro_enq->enquete;

// �ե�����ǥ���̵ͭ�ν����
if ($pro_enq->finding_flag == '')
	$pro_enq->finding_flag = DBFALSE;
?>
<? marketer_header('�У�異�󥱡���', PG_NULL) ?>

<script type="text/javascript">
<!--
function set_answer_cond(flag) {
	document.form1.finding_flag.value = flag;
	disp_answer_cond();
}
function disp_answer_cond() {
	var f = document.form1;
	var text;
	if (f.finding_flag.value == 't')
		text = "�ե�����ǥ��󥰥���ץ���";
	else
		text = "����ץ���";
	document.getElementById("id_answer_cond").innerText = text;
}
window.onload = function() {
 disp_answer_cond();
}
//-->
</script>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>

			<form method="post" name="form1" action="pe_update1.php">
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td bgcolor="#eff7e8">���»ܳ���</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" bgcolor="#eeeeee">���󥱡��ȥ����ȥ�</td>
								<td width="40%" bgcolor="#ffeecc">
									<input type="text" name="title" size="36" <?=value($enquete->title)?>>
									<span class="footer_text">���ˤĤ��ƤΥ��󥱡���</span></td>
								<td width="40%" bgcolor="#ffeecc">&nbsp;</td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#eeeeee">���󥱡��ȷ���</td>
								<td width="40%" bgcolor="#ffeecc">
<?
if ($pro_enq_no == '') {
?>
									<input type="radio" name="finding" <?=value_checked(DBFALSE, $pro_enq->finding_flag)?> onclick="set_answer_cond(value)">�ե�����ǥ��󥰤ʤ�<br>
									<input type="radio" name="finding" <?=value_checked(DBTRUE, $pro_enq->finding_flag)?> onclick="set_answer_cond(value)">�ե�����ǥ��󥰤���
<?
} else {
?>
									<?=$pro_enq->finding_flag == DBTRUE ? '�ե�����ǥ��󥰤���' : '�ե�����ǥ��󥰤ʤ�'?>
<?
}
?>
									<input type="hidden" name="finding_flag" <?=value($pro_enq->finding_flag)?>>
								</td>
								<td width="40%" bgcolor="#ffeecc">�ե�����ǥ��󥰡ʻ���Ĵ���ˤ�̵ͭ������Ǥ���������<br />
							    <br />
						      <span class="footer_text">���ե�����ǥ��󥰤��ꡦ�ʤ��ϰ������ꤹ����ѹ�������ޤ����ѹ�������ϡ����������󥱡��Ȥ�������Ƥ���������</span></td>
							</tr>
<? /*
							<tr>
								<td bgcolor="#eeeeee">����ȯ����������</td>
								<td bgcolor="#ffeecc">
									<?=$send_date_y?>ǯ<?=$send_date_m?>��<?=$send_date_d?>����<?=$send_date_h?>��
								</td>
								<td bgcolor="#ffeecc">���������λ���줿�ݤκ���ȯ�����������Ǥ���<br><br><span class="footer_text">�������ޤǤ⻲���ͤǤ�������������ޤ��󤬡�
																			<span style="color:navy;"><b>�����������ˤ�ȯ�����Ǥ��ޤ���</b></span>�Τǡ�
																			���餫���ᤴλ������������</span>
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
							  <td bgcolor="#ffeecc">15�������˻�̳�ɤؤο�������λ������ϡ�<br>
								  ��Ķ���18����곫�Ϥ��������ޤ���<br>
								  <br>
																		  <span class="footer_text"><span style="color:navy;"><b>15����᤮�Ƥο����ξ�硢��Ķ������ȯ���Ǥ��ʤ����Ȥ⤴�����ޤ�</b></span>
																		  �Τǡ�����������ȤޤǤ����̤���������</span>								</td>
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
							  <td bgcolor="#ffeecc">
						      <span style="color:navy;"><strong>���������������3����ʱĶ����ʳ��ϴޤޤ��˰ʹ�</strong></span>�����ꤷ�Ƥ���������<br /></td>
							</tr>
<!-- 
							<tr>
								<td bgcolor="#eeeeee"><span id="id_answer_cond">�������</span></td>
								<td bgcolor="#ffeecc">
<?
$ans_cond_ary = explode("\n", $pro_enq->ans_cond);
for ($i = 0; $i < $pro_enq->ans_cond_count; $i++) {
?>
										<input type="text" name="ans_cond[]" size=50 maxlength=100 <?=value($ans_cond_ary[$i])?>><br>
<?
}
if ($pro_enq->ans_cond_count < 5) {
?>
										<input type="submit" value="���������ɲ�" onclick="document.form1.next_action.value='add_cond'">
<?
}
?>
								</td>
								<td bgcolor="#ffeecc">�����������ꤷ�Ƥ�����������˥����ؤΰ���᡼��Ρֲ����������ɽ������ޤ���</td>
							</tr>
-->
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
			<input type="button" value="����롡" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			<input type="hidden" name="enq_type" value="2">
			<input type="hidden" name="point" <?=value($enquete->point)?>>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
