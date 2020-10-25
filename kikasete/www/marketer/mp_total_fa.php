<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
$enquete_id = $myp->enquete_id;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
					<tr>
					<td width="10" colspan="2"><img src="images/common/spacer.gif" width="10" border="0" height="20"></td>
				</tr>				
			</table>
			<!-- myパートナー -->

			<p>
			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td>■<?=htmlspecialchars($myp->room_name)?>（<?=$myp->pjt_id?>）</td>
				</tr>
			</table>
			</p>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						
						<a href="javascript:window.close()">ウィンドウを閉じる</a>
					</td>
				</tr>
			</table>
<?
$question = $enquete->question[$question_no];
$qno = mb_convert_kana($question_no, 'N');
$sno = mb_convert_kana($sel_no, 'N');
?>
			<br><br>
			<table border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box" width="99%">
				<tr>
					<td align="center" colspan=2>
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								Ｑ<?=$qno?>(設問文)　：　
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
								</td>
							</tr>
<?
if ($sel_no != 0) {
?>
							<tr>
								<td width="100" align="right" valign="top">
								(選択肢)　：　
								</td>
								<td width="550">
								<?=$sno?>．<?=htmlspecialchars($question->sel_text[$sel_no])?>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td width="6%" align="center"><nobr>回答受<wbr>付連番</nobr></td>
					<td align="center"><nobr>回答</nobr></td>
				</tr>
<?
$sql = "SELECT as_free_answer"
		. " FROM t_enquete_list"
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
		. " LEFT JOIN t_ans_select ON as_enquete_id=an_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=$question_no AND as_sel_no=$sel_no"
		. " WHERE el_enquete_id=$enquete_id"
		. " ORDER BY an_date";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->as_free_answer != '') {
?>
			<tr>
				<td align="center"><?=$i + 1?></td>
				<td><?=nl2br(htmlspecialchars(trim($fetch->as_free_answer)))?></td>
			</tr>
<?
	}
}
?>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
