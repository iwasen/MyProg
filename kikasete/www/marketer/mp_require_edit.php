<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
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

$sql = "SELECT psl_question_no,psl_sel_no,psl_and_or FROM t_mp_select WHERE psl_pjt_id=$pjt_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$qno = $fetch->psl_question_no;
	$and_or[$qno] = $fetch->psl_and_or;
	$sel_no[$qno] = explode(',', $fetch->psl_sel_no);
}

if ($myp->and_or == '')
	$myp->and_or = 'A';
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<script LANGUAGE="JavaScript">
<!--
function onclick_search() {
	window.open("", "search", "width=300,height=160");
	document.form1.target = "search";
	document.form1.next_action.value = "search";
}
// -->
</script>
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
			</table>
			<!-- myパートナー -->

			<div align="center">
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_0.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_0.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_1.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_0.gif" alt="" width="95" height="18" border="0"></td>
			</tr>
			<tr>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
			</tr>
			<tr>
				<td><img src="images/fl_s_1_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_2.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_0.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>


			<form method="post" name="form1" action="mp_require_update.php">
			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td><strong>■<?=htmlspecialchars($myp->room_name)?><br>
						<div align="right">設問間結合条件
						<input name="and_or1" type="radio" <?=value_checked('A', $myp->and_or)?>>AND
						<input name="and_or1" type="radio" <?=value_checked('O', $myp->and_or)?>>OR
						</div>
					</strong></td>
				</tr>
			</table>

			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box" width="99%">
<?
$n = 1;
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		if ($question->question_type != 3) {
			$and_or_flag = $and_or[$qno] != '' ? $and_or[$qno] : 'O';
?>
				<tr bgcolor="<?=$n % 2 ? '#ffeecc' : '#eeeeee'?>">
					<td align="center">Q<?=$qno?></td>
					<td align="center">
						<input type="radio" name="and_or2[<?=$qno?>]" <?=value_checked('O', $and_or_flag)?>>OR&nbsp;&nbsp;
						<input type="radio" name="and_or2[<?=$qno?>]" <?=value_checked('A', $and_or_flag)?>>AND
					</td>
					<td>
<?
			if ($question->question_type == 1 || $question->question_type == 2) {
				if (is_array($question->sel_text)) {
					foreach ($question->sel_text as $sno => $sel_text) {
?>
						<nobr><input type="checkbox" name="sel_no[<?=$qno?>][]" value="<?=$sno?>" <?=checked(is_array($sel_no[$qno]) && in_array($sno, $sel_no[$qno]))?>><?=$sno?>:<?=htmlspecialchars($sel_text)?>&nbsp;&nbsp;</nobr>
<?
					}
				}
			} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
						<table width="100%" border="1" cellspacing="1" cellpadding="2">
							<tr>
								<td></td>
<?
				foreach ($question->hyoutou as $hyoutou) {
?>
								<td align="center"><?=htmlspecialchars($hyoutou)?></td>
<?
				}
?>
							</tr>
<?
				foreach ($question->hyousoku as $sno => $hyousoku) {
?>
							<tr>
								<td><?=htmlspecialchars($hyousoku)?></td>
<?
					foreach ($question->hyoutou as $tno => $hyoutou) {
?>
								<td align="center"><input type="checkbox" name="sel_no[<?=$qno?>][]" value="<?="$sno:$tno"?>" <?=checked(is_array($sel_no[$qno]) && in_array("$sno:$tno", $sel_no[$qno]))?>></td>
<?
					}
?>
							</tr>
<?
				}
?>
						</table>
<?
			}
?>
					</td>
				</tr>
<?
			$n++;
		}
	}
}
?>
			</table>
			<br>
			<div align="center">
			<input type="submit" value="該当者人数検索" onclick="onclick_search()">
<?
	if ($myp->status <= MPS_MEM_SENTEI) {
?>
			<input type="submit" value="　更新　" onclick="document.form1.target='';document.form1.next_action.value='update'">
			<input type="reset" value="元に戻す">
<?
}
?>
			<input type="reset" value="　戻る　" onclick="history.back()">
			<input type="hidden" name="pjt_id" <?=value($pjt_id)?>>
			<input type="hidden" name="next_action"></div>
			</form>
			
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
