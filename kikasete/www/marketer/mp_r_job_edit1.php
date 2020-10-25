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

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
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
				<td colspan="2" width="190"><img src="images/fl_l_3_0.gif" alt="" width="190" height="18" border="0"></td>
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
				<td><img src="images/fl_s_2_2.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_0.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>


			<form method="post" name="form1" action="mp_r_job_update1.php">
			<table border=0 cellspacing=2 cellpadding=3 width="90%">
				<tr>
					<td bgcolor="#eff7e8">■実施概要</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="24%" bgcolor="#eeeeee">リクルート期間 </td>
								<td width="76%" bgcolor="#ffeecc">
									<input type="text" name="recruit_start_y" <?=value($myp->recruit_start_y)?> size="5" maxlength="4">年
									<input type="text" name="recruit_start_m" <?=value($myp->recruit_start_m)?> size="3" maxlength="2">月
									<input type="text" name="recruit_start_d" <?=value($myp->recruit_start_d)?> size="3" maxlength="2">日
									<input type="text" name="recruit_start_h" <?=value($myp->recruit_start_h)?> size="3" maxlength="2">時
									〜
									<input type="text" name="recruit_end_y" <?=value($myp->recruit_end_y)?> size="5" maxlength="4">年
									<input type="text" name="recruit_end_m" <?=value($myp->recruit_end_m)?> size="3" maxlength="2">月
									<input type="text" name="recruit_end_d" <?=value($myp->recruit_end_d)?> size="3" maxlength="2">日
									<input type="text" name="recruit_end_h" <?=value($myp->recruit_end_h)?> size="3" maxlength="2">時
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">リクルート条件</td>
									<td bgcolor="#ffeecc"><table>
<?
for ($i = 1; $i <= $myp->recruit_jouken_num; $i++) {
?>
										<tr>
											<td><input type="text" name="recruit_jouken<?=$i?>" size=50 maxlength=100 <?=value($myp->recruit_jouken[$i])?>></td>
										</tr>
<?
}
?>
										<tr>
											<td><input type="submit" value="条件の追加" onclick="document.form1.next_action.value='add_recruit_jouken'"></td>
										</tr>
									</table></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="<?=$myp->recruit_flag & 0x01 ? '　更新　' : '　登録　'?>" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'">
			<input type="hidden" name="next_action">
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
