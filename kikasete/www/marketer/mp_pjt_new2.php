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

// Myパートナークラス取得
if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	if ($pjt_id)
		$myp->read_db($pjt_id);
	else {
		$myp->marketer_id = $_SESSION['ss_owner_id'];
		$myp->save_enquete = true;
		$myp->save_search = true;

		$fetch = get_system_info('sy_myp_max_send');
		$myp->send_num = $fetch->sy_myp_max_send;
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
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_1.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_0.gif" alt="" width="285" height="18" border="0"></td>
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
				<td><img src="images/fl_s_1_2.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_0.gif" alt="" width="95" height="50" border="0"></td>
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
			
			
			<form method="post" name="form1" action="mp_pjt_update.php">
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr> 
					<td bgcolor="#eff7e8" colspan=2>■会議室基本項目の設定</td>
				</tr>
				<tr> 
					<td bgcolor="#eeeeee">会議室名</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="room_name" size=50 maxlength=100 <?=value($myp->room_name)?>>
						<br>
						※Ｍｙパートナーで利用するメーリングリスト上での会議室名です。 </td>
				</tr>
				<tr> 
					<td bgcolor="#eeeeee">目的</td>
					<td bgcolor="#ffeecc">
						<textarea name="purpose" cols=50 rows=5><?=htmlspecialchars($myp->purpose)?></textarea>
						<br>
						※きかせて・net事務局で、下記参加条件などとともにチェックに使います。会議室を開く目的や収集したいデータをご記入ください。 </td>
				</tr>
<!--
				<tr>
					<td bgcolor="#eeeeee">参加条件</td>
					<td bgcolor="#ffeecc">
						<table>
<?
if ($myp->sanka_jouken_num < 3) $myp->sanka_jouken_num = 3; for ($i = 1; $i <= $myp->sanka_jouken_num; $i++)
	 { ?>
				<tr>
					<td>
						<input type="text" name="sanka_jouken<?=$i?>" size=50 maxlength=100 <?=value($myp->sanka_jouken[$i])?>>
					</td>
				</tr>
<? }?>
				<tr>
					<td>
						<input type="submit" value="条件の追加" onClick="document.form1.next_action.value='add_sanka_jouken'" name="submit">
					</td>
				</tr>
				<tr>
					<td>会議室の設計情報として登録してください。<br>
						参加者募集メールに記載したい条件は下の「リクルート条件」に記入してください。</td>
				</tr>
			</table>
		</td>
		</tr>
-->
				<tr>
					<td bgcolor="#eeeeee">リクルート条件</td>
					<td bgcolor="#ffeecc">
						<table>
<?
if ($myp->recruit_jouken_num < 3)
	$myp->recruit_jouken_num = 3;

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
							<tr>
								<td>参加者募集メールに記載される条件です。</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">会議室でのリサーチパートナーの名前表示</td>
					<td bgcolor="#ffeecc"><input type="radio" name="policy" <?=value_checked('1', $myp->policy)?>>本名
									<input type="radio" name="policy" <?=value_checked('2', $myp->policy)?>>ニックネーム
									<br><br>※どちらを選んだ場合でも司会者のみは必ず『本名』での登場になります。
									<br>（ただし企業名等は表示されません）
									
									</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">メンバー数</td>
					<td bgcolor="#ffeecc"><input type="text" name="member_num" size=5 maxlength=100 <?=value($myp->member_num)?>>人
					<br>※通常は６名単位での開催になります。
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">リクルート期間</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="recruit_start_y" <?=value($myp->recruit_start_y)?> size="5" maxlength="4">年
						<input type="text" name="recruit_start_m" <?=value($myp->recruit_start_m)?> size="3" maxlength="2">月
						<input type="text" name="recruit_start_d" <?=value($myp->recruit_start_d)?> size="3" maxlength="2">日
						<input type="text" name="recruit_start_h" <?=value($myp->recruit_start_h)?> size="3" maxlength="2">時
						<input type="submit" value="リクルート開始日時から各日付を計算" onclick="document.form1.next_action.value='calc_date'"><br>
						〜
						<input type="text" name="recruit_end_y" <?=value($myp->recruit_end_y)?> size="5" maxlength="4">年
						<input type="text" name="recruit_end_m" <?=value($myp->recruit_end_m)?> size="3" maxlength="2">月
						<input type="text" name="recruit_end_d" <?=value($myp->recruit_end_d)?> size="3" maxlength="2">日
						<input type="text" name="recruit_end_h" <?=value($myp->recruit_end_h)?> size="3" maxlength="2">時
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">決定日</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="decision_date_y" <?=value($myp->decision_date_y)?> size="5" maxlength="4">年
						<input type="text" name="decision_date_m" <?=value($myp->decision_date_m)?> size="3" maxlength="2">月
						<input type="text" name="decision_date_d" <?=value($myp->decision_date_d)?> size="3" maxlength="2">日
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">会議室実施期間</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="room_start_y" <?=value($myp->room_start_y)?> size="5" maxlength="4">年
						<input type="text" name="room_start_m" <?=value($myp->room_start_m)?> size="3" maxlength="2">月
						<input type="text" name="room_start_d" <?=value($myp->room_start_d)?> size="3" maxlength="2">日
						〜
						<input type="text" name="room_end_y" <?=value($myp->room_end_y)?> size="5" maxlength="4">年
						<input type="text" name="room_end_m" <?=value($myp->room_end_m)?> size="3" maxlength="2">月
						<input type="text" name="room_end_d" <?=value($myp->room_end_d)?> size="3" maxlength="2">日
					</td>
				</tr>
			</table>
			<table>
				<tr align="right">
					<td>
<?
if (!$pjt_id) {
?>
						<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
<?
} else {
	if ($myp->status == MPS_REC_JUNBI) {
?>
						<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<?
	}
?>
						<input type="button" value="　戻る　" onclick="location.href='mp_pjt_list.php'">
<?
}
?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="next_action">
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
