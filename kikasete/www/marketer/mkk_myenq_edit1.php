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

// セッション処理
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->marketer_id = $_SESSION['ss_owner_id'];
		$myenq->creator_id = $_SESSION['ss_marketer_id'];

		// リサーチ開始希望日のデフォルト値
		get_quickest_date($send_date_y, $send_date_m, $send_date_d, $send_date_h);
		$myenq->enquete->start_date_y = $send_date_y;
		$myenq->enquete->start_date_m = $send_date_m;
		$myenq->enquete->start_date_d = $send_date_d;
		$myenq->enquete->start_date_h = 18;

		// リサーチ終了希望日のデフォルト値
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
<? marketer_header('Myアンケート', PG_NULL) ?>

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
					<td bgcolor="#eff7e8">■実施概要</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" bgcolor="#eeeeee">myアンケートタイトル</td>
								<td width="40%" bgcolor="#ffeecc">
									<input type="text" name="title" size="30" <?=value($enquete->title)?>>　
									についてのアンケート</td>
								<td width="40%" bgcolor="#ffeecc">&nbsp;</td>
							</tr>
<? /*
							<tr>
								<td bgcolor="#eeeeee">最早発信開始日時</td>
								<td bgcolor="#ffeecc">
									<?=$send_date_y?>年<?=$send_date_m?>月<?=$send_date_d?>日　<?=$send_date_h?>時
								</td>
								<td bgcolor="#ffeecc">現在設定を完了された際の最早発信開始日時です。<br><br>※あくまでも参考値です。申し訳ありませんが、
																			<span style="color:navy;"><b>土日・祝日には発信ができません</b></span>ので、
																			あらかじめご了承ください。
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
							  <td bgcolor="#ffeecc">午前11時以前に事務局への申請が完了する場合は、<br>本日より開始いただけます。</td>
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
							  <td bgcolor="#ffeecc">標準24時間後（2営業日）の9時になります。</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">回答条件</td>
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
										<input type="submit" value="回答条件の追加" onclick="document.form1.next_action.value='add_cond'">
<?
}
?>
								</td>
								<td bgcolor="#ffeecc">回答条件を設定してください。<br /><span class="footer_text">
							    モニターへの案内メールの「回答条件」欄に表示されます。</span></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			<input type="hidden" name="enq_type" value="2">
			<input type="hidden" name="point" <?=value($enquete->point)?>>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer () ?>
