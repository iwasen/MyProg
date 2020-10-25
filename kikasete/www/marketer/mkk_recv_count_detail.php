<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_mye_check.php");

$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
		. " FROM t_enquete_list"
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id AND an_valid_flag"
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
		. " WHERE el_enquete_id=$enquete_id"
		. " GROUP BY mn_sex,ge_age_cd";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$cell[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->recv_count;
}

$sex_s = 1;
$sex_e = 2;
$age_s = 2;
$age_e = 8;
?>
<? marketer_header('受信数詳細', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mkk_enqdet1.gif" width="538" height="20"></td>
				</tr>
			</table>
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						受信数詳細
					</td>
				</tr>
				<tr>
					<td align="right">
						<a href="mkk_real_send_detail.php?enquete_id=<?=$enquete_id?>">発信数詳細へ</a>
					</td>
				</tr>
			</table>
			<br>
			<form method="" action="mkk_myenq_con.php">
			<center>
			<table width="650" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">10代</td>
					<td nowrap width="10%">20代</td>
					<td nowrap width="10%">30代</td>
					<td nowrap width="10%">40代</td>
					<td nowrap width="10%">50代</td>
					<td nowrap width="10%">60代</td>
					<td nowrap width="10%">70代以上</td>
					<td nowrap width="10%">合計</td>
				</tr>
<?
$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center"><?=decode_sex($sex, '性別指定なし')?></td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $cell[$sex][$age];
		$sum_col += $num;
		$sum_row[$age] += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
<?
}
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center">合計</td>
<?
$sum_col = 0;
for ($age = $age_s; $age <= $age_e; $age++) {
	$num = $sum_row[$age];
	$sum_col += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
			</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
									<a href="mkk_enqdet1.php?enquete_id=<?=$enquete_id?>"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
