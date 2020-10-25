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

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq_no == '') {
		$pro_enq->marketer_id = $_SESSION['ss_owner_id'];
		$pro_enq->creator_id = $_SESSION['ss_marketer_id'];

		// リサーチ開始希望日のデフォルト値
		get_quickest_date_pro($send_date_y, $send_date_m, $send_date_d, $send_date_h);
		$pro_enq->enquete->start_date_y = $send_date_y;
		$pro_enq->enquete->start_date_m = $send_date_m;
		$pro_enq->enquete->start_date_d = $send_date_d;
		$pro_enq->enquete->start_date_h = 18;

		// リサーチ終了希望日のデフォルト値
		$end_date = next_working_day2(mktime($send_date_h, 0, 0, $send_date_m, $send_date_d, $send_date_y), 2);
		$pro_enq->enquete->end_date_y = date('Y', $end_date);
		$pro_enq->enquete->end_date_m = date('m', $end_date);
		$pro_enq->enquete->end_date_d = date('d', $end_date);
		$pro_enq->enquete->end_date_h = 9;
	} else
		$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
}

$enquete = &$pro_enq->enquete;

// ファインディング有無の初期値
if ($pro_enq->finding_flag == '')
	$pro_enq->finding_flag = DBFALSE;
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

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
		text = "ファインディングサンプル条件";
	else
		text = "サンプル条件";
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
					<td bgcolor="#eff7e8">■実施概要</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" bgcolor="#eeeeee">アンケートタイトル</td>
								<td width="40%" bgcolor="#ffeecc">
									<input type="text" name="title" size="36" <?=value($enquete->title)?>>
									<span class="footer_text">　についてのアンケート</span></td>
								<td width="40%" bgcolor="#ffeecc">&nbsp;</td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#eeeeee">アンケート形式</td>
								<td width="40%" bgcolor="#ffeecc">
<?
if ($pro_enq_no == '') {
?>
									<input type="radio" name="finding" <?=value_checked(DBFALSE, $pro_enq->finding_flag)?> onclick="set_answer_cond(value)">ファインディングなし<br>
									<input type="radio" name="finding" <?=value_checked(DBTRUE, $pro_enq->finding_flag)?> onclick="set_answer_cond(value)">ファインディングあり
<?
} else {
?>
									<?=$pro_enq->finding_flag == DBTRUE ? 'ファインディングあり' : 'ファインディングなし'?>
<?
}
?>
									<input type="hidden" name="finding_flag" <?=value($pro_enq->finding_flag)?>>
								</td>
								<td width="40%" bgcolor="#ffeecc">ファインディング（事前調査）の有無を選んでください。<br />
							    <br />
						      <span class="footer_text">※ファインディングあり・なしは一度設定すると変更が出来ません。変更する場合は、新しくアンケートを作成してください。</span></td>
							</tr>
<? /*
							<tr>
								<td bgcolor="#eeeeee">最早発信開始日時</td>
								<td bgcolor="#ffeecc">
									<?=$send_date_y?>年<?=$send_date_m?>月<?=$send_date_d?>日　<?=$send_date_h?>時
								</td>
								<td bgcolor="#ffeecc">現在設定を完了された際の最早発信開始日時です。<br><br><span class="footer_text">※あくまでも参考値です。申し訳ありませんが、
																			<span style="color:navy;"><b>土日・祝日には発信ができません</b></span>ので、
																			あらかじめご了承ください。</span>
								</td>
							</tr>
*/ ?>
							<tr>
								<td bgcolor="#eeeeee">リサーチ開始希望日</td>
								<td bgcolor="#ffeecc">
									<select name="start_date_y"><? select_year(min($enquete->start_date_y, date('Y')), '', $enquete->start_date_y, date('Y') + 1) ?></select>年
									<select name="start_date_m"><? select_month('', $enquete->start_date_m) ?></select>月
									<select name="start_date_d"><? select_day('', $enquete->start_date_d) ?></select>日
									<input type="hidden" name="start_date_h" <?=value($enquete->start_date_h)?>>
								</td>
							  <td bgcolor="#ffeecc">15時以前に事務局への申請が完了する場合は、<br>
								  翌営業日18時より開始いただけます。<br>
								  <br>
																		  <span class="footer_text"><span style="color:navy;"><b>15時を過ぎての申請の場合、翌営業日中に発信できないこともございます</b></span>
																		  ので、エージェントまでご相談ください。</span>								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">リサーチ終了希望日</td>
								<td bgcolor="#ffeecc">
									<select name="end_date_y"><? select_year(min($enquete->end_date_y, date('Y')), '', $enquete->end_date_y, date('Y') + 1) ?></select>年
									<select name="end_date_m"><? select_month('', $enquete->end_date_m) ?></select>月
									<select name="end_date_d"><? select_day('', $enquete->end_date_d) ?></select>日
									<input type="hidden" name="end_date_h" <?=value($enquete->end_date_h)?>>
									<?=$enquete->end_date_h?>時
								</td>
							  <td bgcolor="#ffeecc">
						      <span style="color:navy;"><strong>開始日の翌日より3日後（営業日以外は含まず）以降</strong></span>で設定してください。<br /></td>
							</tr>
<!-- 
							<tr>
								<td bgcolor="#eeeeee"><span id="id_answer_cond">回答条件</span></td>
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
										<input type="submit" value="回答条件の追加" onclick="document.form1.next_action.value='add_cond'">
<?
}
?>
								</td>
								<td bgcolor="#ffeecc">回答条件を設定してください。モニターへの案内メールの「回答条件」欄に表示されます。</td>
							</tr>
-->
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
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
