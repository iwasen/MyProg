<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

if ($hyousoku == '')
	$msg[] = '表側が選択されていないようです。';

if ($hyoutou == '')
	$msg[] = '表頭が選択されていないようです。';

if ($hyousoku != '' && $hyousoku == $hyoutou)
	$msg[] = '表側と表頭は異なる設問を選択してください。';

if (!$msg) {
	$enquete = new enquete_class;
	$enquete->read_db($enquete_id);

	$q_hyousoku = &$enquete->question[$hyousoku];
	$q_hyoutou = &$enquete->question[$hyoutou];

	// 回答者数取得
	$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id";
	$ans_count = db_fetch1($sql, 0);

	$sql = "SELECT s.as_sel_no AS s_sel_no,t.as_sel_no AS t_sel_no,COUNT(*) as sel_count"
			. " FROM t_ans_select s"
			. " JOIN t_ans_select t ON t.as_monitor_id=s.as_monitor_id"
			. " WHERE s.as_enquete_id=$enquete_id AND s.as_question_no=$hyousoku AND t.as_enquete_id=$enquete_id AND t.as_question_no=$hyoutou"
			. " GROUP BY t.as_sel_no,s.as_sel_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$count_tbl[$fetch->s_sel_no][$fetch->t_sel_no] = $fetch->sel_count;
	}
}
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
<?
if (!$msg) {
?>
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
			<form method="post" name="form1">
			<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td>
						<table border=1 cellspacing=2 cellpadding=3 width=768>
							<tr bgcolor="#eff7e8">
								<td align="center">&nbsp;</td>
<?
foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
								<td align="center">Q<?=$hyoutou?>=<?=$tno?></td>
<?
}
?>
							</tr>
<?
foreach ($q_hyousoku->sel_text as $sno => $sel_text) {
?>
							<tr>
								<td align="center" bgcolor="#ffeecc">Q<?=$hyousoku?>=<?=$sno?></td>
<?
	foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
								<td align="center"><?=number_format($count_tbl[$sno][$tno])?></td>
<?
	}
?>
							</tr>
<?
}
?>
						</table>
						<br><br>
						<table border=1 cellspacing=2 cellpadding=3 width=768>
							<tr bgcolor="#eff7e8">
								<td align="center">&nbsp;</td>
<?
foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
								<td align="center">Q<?=$hyoutou?>=<?=$tno?></td>
<?
}
?>
							</tr>
<?
foreach ($q_hyousoku->sel_text as $sno => $sel_text) {
?>
							<tr>
								<td align="center" bgcolor="#ffeecc">Q<?=$hyousoku?>=<?=$sno?></td>
<?
	foreach ($q_hyoutou->sel_text as $tno => $sel_text) {
?>
								<td align="center"><?=format_percent($count_tbl[$sno][$tno], $ans_count, 1)?></td>
<?
	}
?>
							</tr>
<?
}
?>
						</table>
						<br>
						<div align="center">
						<input type="button" value="　戻る　" onclick="history.back()">
						<input type="button" value="　CSV　" onclick="location.href='mp_csv_crs.php?enquete_id=<?=$enquete_id?>&hyousoku=<?=$hyousoku?>&hyoutou=<?=$hyoutou?>'">
						</div>
					</td>
				</tr>
			</table>
			</form>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
<? } else{?>
	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer () ?>
