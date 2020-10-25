<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/icp_db.php");
include("$inc/icp_func.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

$myp = new my_partner_class;
$myp->read_db($pjt_id);

set_return_page('mp_require_update.php', "pjt_id=$pjt_id");
set_return_page('mp_select_monitor.php', "pjt_id=$pjt_id");
set_return_page('mp_select_update.php', "pjt_id=$pjt_id");
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
				<td colspan="3" width="285"><img src="images/fl_l_2_1.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_1.gif" alt="" width="190" height="18" border="0"></td>
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
				<td><img src="images/fl_s_2_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_1.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			</div>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			

			<br>
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><strong>■<?=htmlspecialchars($myp->room_name)?></strong></td>
				</tr>
			</table>
			<br>

			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr>
					<td bgcolor="#eff7e8" width="30%">■リクルート</td>
					<td bgcolor="#eff7e8" align="right" width="70%">
					<input type="button" value="<?=$myp->status < MPS_REC_SHINSEI ? '設定' : '詳細'?>" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'"></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">ステータス</td>
					<td bgcolor="#ffeecc"><?=decode_recruit_status($myp->status)?></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">期間</td>
					<td bgcolor="#ffeecc"><?=sprintf('%d年%d月%d日 %d時〜%d年%d月%d日 %d時', $myp->recruit_start_y, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_h, $myp->recruit_start_y, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_h)?></td>
				</tr>
<?
if ($myp->real_send == 0) {
?>
				<tr>
					<td bgcolor="#eeeeee">発信数</td>
					<td bgcolor="#ffeecc">−</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">返信数</td>
					<td bgcolor="#ffeecc">−</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">該当数</td>
					<td bgcolor="#ffeecc">−</td>
				</tr>
<?
} else {
	$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE pmn_pjt_id=$pjt_id";
	$reply_count = db_fetch1($sql);
?>
				<tr>
					<td bgcolor="#eeeeee">発信数</td>
					<td bgcolor="#ffeecc">
						<?=number_format($myp->real_send)?>
						<input type="button" value="CSV" onclick="location.href='mp_csv_send.php?enquete_id=<?=$myp->enquete_id?>'">
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">返信数</td>
					<td bgcolor="#ffeecc">
						<?=number_format($reply_count)?>(<?=format_percent($reply_count, $myp->real_send, 1)?>)
<?
	if ($reply_count != 0) {
?>
						<input type="button" value="CSV" onclick="location.href='mp_csv_recv.php?enquete_id=<?=$myp->enquete_id?>'">
<?
	}
?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">該当数</td>
					<td bgcolor="#ffeecc">
<?
	if ($myp->select_sql == '') {
?>
						未設定
<?
	} else {
		$sql = "SELECT COUNT(*) FROM t_mp_monitor WHERE $myp->select_sql";
		$sel_count = db_fetch1($sql);
?>
						<?=number_format($sel_count)?>(<?=format_percent($sel_count, $myp->real_send, 1)?>)
<?
	}
?>
						<input type="button" value="該当者条件の設定" onclick="location.href('mp_require_edit.php?pjt_id=<?=$pjt_id?>')">
						<input type="button" value="選定" onclick="location.href('mp_select_monitor.php?pjt_id=<?=$pjt_id?>')">
<?
	if ($reply_count != 0 && $myp->select_sql != '') {
?>
						<input type="button" value="CSV" onclick="location.href='mp_csv_sel.php?pjt_id=<?=$pjt_id?>'">
<?
	}
?>
					</td>
				</tr>
<?
}
?>
				<tr>
					<td bgcolor="#eff7e8">■実施</td>
					<td bgcolor="#eff7e8" align="right">
<?
if ($myp->icp_job_id) {
?>
						<input type="button" value="詳細" onclick="location.href='mp_pjt_job.php?pjt_id=<?=$pjt_id?>'">
<?
}
?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">ステータス</td>
					<td bgcolor="#ffeecc"><?=decode_room_status($myp->status)?></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">期間</td>
					<td bgcolor="#ffeecc"><?=sprintf('%d年%d月%d日〜%d年%d月%d日', $myp->room_start_y, $myp->room_start_m, $myp->room_start_d, $myp->room_start_y, $myp->room_end_m, $myp->room_end_d)?></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">メンバー数</td>
					<td bgcolor="#ffeecc"><?=$myp->icp_member_num ? "{$myp->icp_member_num}（確定）" : "{$myp->member_num}（目標）"?></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">会議室投稿先メールアドレス</td>
					<td bgcolor="#ffeecc"><?=$myp->icp_job_id ? htmlspecialchars(get_icp_room_addr($myp->icp_job_id)) : '−'?></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">発言集計</td>
					<td bgcolor="#ffeecc">
<? if ($myp->status < MPS_ROOM_JISSHI) { ?>
						−
<? } else { ?>
						<input type="button" value="発言録ダウンロード" onclick="location.href='mp_csv_log.php?job_id=<?=$myp->icp_job_id?>'">
						<input type="button" value="整形マクロダウンロード" onclick="location.href='seikei_v1.xls'">
<? } ?>
					</td>
				</tr>
			</table>
			<br>
			<div align="center">
<?
if ($myp->status < MPS_REC_SHINSEI) {
?>
<script type="text/javascript">
<!--
function delete_job() {
	if (confirm("この会議室を削除しますか？"))
		location.href = "mp_pjt_delete.php?pjt_id=<?=$pjt_id?>";
}
//-->
</script>
				<input type="button" value="　削除　" onclick="delete_job()">
<?
}
?>
				<input type="button" value="　戻る　" onclick="location.href='mp_pjt_list.php'">
			</div>
			</form>
			</div>

			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
