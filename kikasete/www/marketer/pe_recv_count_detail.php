<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/pro_enq_target.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// アンケートID.開始日時取得
$sql = "SELECT mep_enquete2_id,en_start_date"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
		. " WHERE mep_marketer_id={$_SESSION['ss_owner_id']} AND mep_pro_enq_no=$pro_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$enquete_id = $fetch->mep_enquete2_id;
} else
	redirect('pe_index.php');

// 利用ネットワークの取得	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;

if ($target_flg == 't') {
	$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
			. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
			. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_sex,ge_age_cd";
} else {
	$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_sex,ge_age_cd";
}
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
<? marketer_header('本調査回答数詳細', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
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
						本アンケート回答数詳細
					</td>
					<td align="right">
						<form method="post" action="pe_recv_csv.php">
						<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
						<input type="image" src="images/mkk_bt/dl_csv.gif" alt="CSVのダウンロード">
						</form>
					</td>
				</tr>
				<tr>
					<td align="right" colspan=2>
						<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>">アンケート詳細表示に戻る</a>
					</td>
				</tr>
			</table>
			<br>
			<form>
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
								<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>

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
