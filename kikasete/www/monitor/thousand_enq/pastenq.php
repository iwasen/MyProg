<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
?>

<?monitor_menu()?>
<!-- コンテンツ本体のソースはこの間に表記 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" background="../image/title_bg.gif"><img src="../image/title_enq2.gif" width="320" height="39" alt="きかせて１万人アンケート"></td>
	</tr>
</TABLE>

<table width="660" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
		<td align="center">

	<table width="620" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><img src="../image/spacer.gif" width="1" height="15" border="0"></td>
	</tr>
	</table>

						<table width="580" border="1" cellspacing="1" cellpadding="3" bordercolor="#CCCCCC">
							<tr>

								<td colspan="3"><strong><font size="3" class=fs12_lh130 color="#292864">過去に実施したアンケートリスト</font></strong></td>

							</tr> 

							<tr>
								<td width="60%" bgcolor="#DCDCDC"><font size="3" class=fs12_lh130>アンケートタイトル</font></td>
								<td width="30%" bgcolor="#DCDCDC"><font size="3" class=fs12_lh130>調査期間</font></td>
								<td width="10%" bgcolor="#DCDCDC"><font size="3" class=fs12_lh130>参加人数</font></td>
							</tr>

<?
// 1万人アンケート表示
if ($no == '')
	$no = 0;
$sub1 = "SELECT en_enquete_id"
		. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
		. " WHERE en_status=7"
		. " ORDER BY en_enquete_id DESC"
		. " LIMIT 11 OFFSET $no";
$sql = "SELECT en_enquete_id,en_title,en_start_date,en_end_date,em_sum"
		. " FROM t_enquete LEFT JOIN t_enquete_sum ON em_enquete_id=en_enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0"
		. " WHERE en_enquete_id IN ($sub1)"
		. " ORDER BY en_enquete_id DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < 10; $i++) {
	if ($i < $nrow) {
		$fetch = pg_fetch_object($result, $i);
		$title = '<a target="_top" href="pastenqd.php?no=' . ($no + $i) . '">' . htmlspecialchars($fetch->en_title) . '</a>';
		$date = '<nobr>' . format_date($fetch->en_start_date) . '〜' . format_date($fetch->en_end_date) . '</nobr>';
		$count = number_format($fetch->em_sum);
	} else {
		$title = '<br>';
		$date = '<br>';
		$count = '<br>';
	}
?>


							<tr>
								<td ><a href="mk_tw94.php"><font size="3" class=fs12_lh130><?=$title?></font></a></td>
								<td ><font size="3" class=fs12_lh130><?=$date?></font></td>
								<td ><font size="3" class=fs12_lh130><?=$count?></font></td>
							</tr>
<?
}
?>
						</table>
      <TABLE cellSpacing=0 cellPadding=0 border=0 width="580">
        <TBODY>
        <TR>
          <TD align="left" height="30">
<?
if ($no > 0) {
?>
<font size="3" class=fs12_lh130><a href="pastenq.php?no=<?=max($no - 10, 0)?>">＜　前の１０件を見る</a></font>
<?
}
?>
								</td>
		<td align="right" width="50%">
<?
if ($nrow > 10) {
?>
<font size="3" class=fs12_lh130><a href="pastenq.php?no=<?=$no + 10?>">次の１０件を見る　＞</A></font>
<?
}
?>
</td>
	</tr>
	</table>

		</td>
	</tr>
</table>


			<table width="590" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">

				<tr>

					<td>

						<img src="../image/spacer.gif" width="1" height="15" border="0">

					</td>

				</tr>

			</table>
<!-- コンテンツ本体のソースはこの間に表記 -->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
