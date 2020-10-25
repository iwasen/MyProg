<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/icp_db.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

$sql = "SELECT ppj_room_name,ppj_purpose,ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);
$icp_job_id = $fetch->ppj_icp_job_id;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<script type="text/javascript">
<!--
function theme_detail(remark_id) {
	window.open("mp_theme_detail.php?remark_id=" + remark_id, "theme_detail", "resizable=yes,scrollbars=yes");
}
function to_room() {
	window.open("mp_room.php?pjt_id=<?=$pjt_id?>", "room");
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
				<td colspan="2" width="190"><img src="images/fl_l_3_0.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_1.gif" alt="" width="95" height="18" border="0"></td>
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
				<td><img src="images/fl_s_5_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_2.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>

			<br>
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><strong>■<?=htmlspecialchars($fetch->ppj_room_name)?></strong></td>
				</tr>
			</table>

			<div align="center">
			<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td bgcolor="#eff7e8" colspan=3>■目的</td>
				</tr>
				<tr>
					<td bgcolor="#ffeecc" colspan=3><?=nl2br(htmlspecialchars($fetch->ppj_purpose))?></td>
				</tr>
				<tr>
					<td bgcolor="#eff7e8" colspan=5>■話題の履歴</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" width="15%" align="center">SEQ</td>
					<td bgcolor="#eeeeee" width="25%" align="center">テーマ</td>
					<td bgcolor="#eeeeee" width="20%" align="center">投げかけ日</td>
				</tr>
<?
$sql = "SELECT rm_remark_id,rm_seq_no,rm_date,rm_subject"
		. " FROM l_remark"
		. " WHERE rm_job_id=$icp_job_id AND rm_disp_type=1 AND rm_status=1 AND rm_marking_id IS NOT NULL"
		. " ORDER BY rm_seq_no";
$result = icp_db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr>
					<td bgcolor="#eeeeee" width="20%" align="center"><?=$i + 1?></td>
					<td bgcolor="#ffeecc" width="60%"><a href="javascript:theme_detail(<?=$fetch->rm_remark_id?>)"><?=htmlspecialchars($fetch->rm_subject)?></a></td>
					<td bgcolor="#ffeecc" width="20%" align="center"><?=format_date($fetch->rm_date)?></td>
				</tr>
<?
}
?>
			</table>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td bgcolor="#eff7e8" colspan=3>■発言集計</td>
					<td bgcolor="#eff7e8" align="right"><input type="button" value="ダウンロード" onclick="location.href='mp_csv_count.php?job_id=<?=$icp_job_id?>'"></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee" width="25%" align="center">ID</td>
					<td bgcolor="#eeeeee" width="25%" align="center">ニックネーム</td>
					<td bgcolor="#eeeeee" width="25%" align="center">総発言</td>
					<td bgcolor="#eeeeee" width="25%" align="center">日別カウント数</td>
				</tr>
<?
$sb1 = "SELECT rm_member_id,COUNT(*) AS s_count1,COUNT(DISTINCT(date_trunc('day', rm_date))) AS s_count2"
		. " FROM l_remark"
		. " WHERE rm_job_id=$icp_job_id AND rm_remark_type=3 AND rm_status=1"
		. " GROUP BY rm_member_id";
$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,s_count1,s_count2"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " LEFT JOIN ($sb1) sb1 ON rm_member_id=jm_member_id"
		. " WHERE jm_job_id=$icp_job_id AND jm_status=1 AND pm_operator_flag=0"
		. " ORDER BY pm_member_id";
$result = icp_db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
				<tr>
					<td bgcolor="#ffeecc" align="center"><?=$fetch->pm_member_id?></td>
					<td bgcolor="#ffeecc" align="center"><?=htmlspecialchars($fetch->pm_nickname)?></td>
					<td bgcolor="#ffeecc" align="center"><?=number_format($fetch->s_count1)?></td>
					<td bgcolor="#ffeecc" align="center"><?=number_format($fetch->s_count2)?></td>
				</tr>
<?
}
?>
			</table>
			</form>
			</div>
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr align="right">
					<td>
<?
if ($icp_job_id) {
?>
						<input type="button" value="閲覧者の登録" onclick="location.href='mp_regist_rom.php?pjt_id=<?=$pjt_id?>'">
						<input type="button" value="会議室へ" onclick="to_room()">
<?
}
?>
					</td>
				</tr>
			</table>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
