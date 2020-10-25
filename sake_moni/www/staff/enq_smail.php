<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/mye_check.php");
?>
<? staff_header('送信者プロファイル') ?>

<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="680" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪送信者プロファイル≫</font></td>
	</tr>
</table>
			<table width="680" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="500" rowspan="2">
						<font size="3" class="honbun1">
						アンケートを発信したモニターのプロファイルです。<br>
						また、データをダウンロードすることもできます。
						</font>
					</td>
					<td width="200" align="right">
						<form method="post" action="enq_dl1.php">
						<font size="3" class="honbun1">
						<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
						<input type="hidden" name="type" value="send">
						<input type="submit" value="CSVダウンロード">
						</font>
						</form>
					</td>
				</tr>
				<tr>
					<td width="200" align="right">
						<a href="javascript:history.back()"><font size="3" class="honbun1">アンケート詳細表示に戻る</font></a>
					</td>
				</tr>
			</table>
			<table width="680" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr bgcolor="#eeeeee">
					<td nowrap><font size="2" class="honbun2">番号</font></td>
					<td nowrap><font size="2" class="honbun2">送信日</font></td>
					<td nowrap><font size="2" class="honbun2">性別</font></td>
					<td nowrap><font size="2" class="honbun2">年齢</font></td>
					<td nowrap><font size="2" class="honbun2">未既婚</font></td>
					<td nowrap><font size="2" class="honbun2">子供の有無</font></td>
					<td nowrap><font size="2" class="honbun2">居住地域</font></td>
					<td nowrap><font size="2" class="honbun2">職業</font></td>
				</tr>
<?
// アンケート開始日時取得
$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
$start_date = "'" . format_date(db_fetch1($sql)) . "'";

$sql = "SELECT el_send_date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_child,ar_area_name AS jitaku_area_name,sg_shokugyou_name"
		. " FROM t_enquete_list JOIN t_monitor ON mn_monitor_id=el_monitor_id"
		. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " WHERE el_enquete_id=$enquete_id"
		. " ORDER BY el_monitor_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr bgcolor="#ffffff">
					<td nowrap><font size="2" class="honbun2"><?=$i + 1?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=format_date($fetch->el_send_date)?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=decode_sex($fetch->mn_sex)?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=$fetch->mn_age?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=decode_mikikon($fetch->mn_mikikon)?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=decode_child($fetch->mn_child)?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=htmlspecialchars($fetch->jitaku_area_name)?></font></td>
					<td nowrap><font size="2" class="honbun2"><?=htmlspecialchars($fetch->sg_shokugyou_name)?></font></td>
				</tr>
<?
}
?>
			</table>

<? staff_footer() ?>
