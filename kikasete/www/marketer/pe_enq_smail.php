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

// アンケートID.開始日時取得
$sql = "SELECT en_enquete_id,en_start_date"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
		. " WHERE mep_marketer_id={$_SESSION['ss_owner_id']} AND mep_pro_enq_no=$pro_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$enquete_id = $fetch->en_enquete_id;
	$start_date = sql_date(format_date($fetch->en_start_date));
} else
	redirect('pe_index.php');
?>
<? marketer_header('送信者プロファイル', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top">≪送信者プロファイル≫</td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="768" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
				<tr>
					<td width="500" rowspan="2">
						
						アンケートを発信したモニターのプロファイルです。<br>
						また、データをダウンロードすることもできます。
						
					</td>
					<td width="200" align="right">
						<form method="post" action="pe_enq_dl1.php">
						
						<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
						<input type="hidden" name="type" value="send">
						<input type="image" src="images/mkk_bt/dl_csv.gif" alt="CSVのダウンロード">
						
						</form>
					</td>
				</tr>
				<tr>
					<td width="200" align="right">
						<a href="javascript:history.back()">アンケート詳細表示に戻る</a>
					</td>
				</tr>
			</table>
			<table bgcolor="#ffffff" width="768" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr bgcolor="#eeeeee">
					<td nowrap>番号</td>
					<td nowrap>送信日</td>
					<td nowrap>性別</td>
					<td nowrap>年齢</td>
					<td nowrap>未既婚</td>
					<td nowrap>居住地域</td>
					<td nowrap>職業</td>
					<td nowrap>業種</td>
					<td nowrap>職種</td>
					<td nowrap>勤務先地域</td>
				</tr>
<?
$sql = "SELECT el_send_date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
		. " FROM (((((t_enquete_list JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id)"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area)"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area)"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd)"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd)"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " WHERE el_enquete_id=$enquete_id"
		. " ORDER BY el_monitor_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr bgcolor="#ffffff">
					<td nowrap><?=$i + 1?></td>
					<td nowrap><?=format_date($fetch->el_send_date)?></td>
					<td nowrap><?=decode_sex($fetch->mn_sex)?></td>
					<td nowrap><?=$fetch->mn_age?></td>
					<td nowrap><?=decode_mikikon($fetch->mn_mikikon)?></td>
					<td nowrap><?=htmlspecialchars($fetch->jitaku_area_name)?></td>
					<td nowrap><?=htmlspecialchars($fetch->sg_shokugyou_name)?></td>
					<td nowrap><?=htmlspecialchars($fetch->gs_gyoushu_name)?></td>
					<td nowrap><?=htmlspecialchars($fetch->ss_shokushu_name)?></td>
					<td nowrap><?=htmlspecialchars($fetch->kinmu_area_name)?></td>
				</tr>
<?
}
?>
			<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
