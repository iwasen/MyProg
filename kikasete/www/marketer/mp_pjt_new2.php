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

// My�ѡ��ȥʡ����饹����
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
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL) ?>
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
			<!-- my�ѡ��ȥʡ� -->
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
					<td bgcolor="#eff7e8" colspan=2>����ļ����ܹ��ܤ�����</td>
				</tr>
				<tr> 
					<td bgcolor="#eeeeee">��ļ�̾</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="room_name" size=50 maxlength=100 <?=value($myp->room_name)?>>
						<br>
						���ͣ��ѡ��ȥʡ������Ѥ���᡼��󥰥ꥹ�Ⱦ�Ǥβ�ļ�̾�Ǥ��� </td>
				</tr>
				<tr> 
					<td bgcolor="#eeeeee">��Ū</td>
					<td bgcolor="#ffeecc">
						<textarea name="purpose" cols=50 rows=5><?=htmlspecialchars($myp->purpose)?></textarea>
						<br>
						���������ơ�net��̳�ɤǡ��������þ��ʤɤȤȤ�˥����å��˻Ȥ��ޤ�����ļ��򳫤���Ū������������ǡ����򤴵������������� </td>
				</tr>
<!--
				<tr>
					<td bgcolor="#eeeeee">���þ��</td>
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
						<input type="submit" value="�����ɲ�" onClick="document.form1.next_action.value='add_sanka_jouken'" name="submit">
					</td>
				</tr>
				<tr>
					<td>��ļ����߷׾���Ȥ�����Ͽ���Ƥ���������<br>
						���ü��罸�᡼��˵��ܤ��������ϲ��Ρ֥ꥯ�롼�Ⱦ��פ˵������Ƥ���������</td>
				</tr>
			</table>
		</td>
		</tr>
-->
				<tr>
					<td bgcolor="#eeeeee">�ꥯ�롼�Ⱦ��</td>
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
								<td><input type="submit" value="�����ɲ�" onclick="document.form1.next_action.value='add_recruit_jouken'"></td>
							</tr>
							<tr>
								<td>���ü��罸�᡼��˵��ܤ������Ǥ���</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">��ļ��ǤΥꥵ�����ѡ��ȥʡ���̾��ɽ��</td>
					<td bgcolor="#ffeecc"><input type="radio" name="policy" <?=value_checked('1', $myp->policy)?>>��̾
									<input type="radio" name="policy" <?=value_checked('2', $myp->policy)?>>�˥å��͡���
									<br><br>���ɤ������������Ǥ�ʲ�ԤΤߤ�ɬ������̾�٤Ǥ��о�ˤʤ�ޤ���
									<br>�ʤ��������̾����ɽ������ޤ����
									
									</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">���С���</td>
					<td bgcolor="#ffeecc"><input type="text" name="member_num" size=5 maxlength=100 <?=value($myp->member_num)?>>��
					<br>���̾�ϣ�̾ñ�̤Ǥγ��Ťˤʤ�ޤ���
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">�ꥯ�롼�ȴ���</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="recruit_start_y" <?=value($myp->recruit_start_y)?> size="5" maxlength="4">ǯ
						<input type="text" name="recruit_start_m" <?=value($myp->recruit_start_m)?> size="3" maxlength="2">��
						<input type="text" name="recruit_start_d" <?=value($myp->recruit_start_d)?> size="3" maxlength="2">��
						<input type="text" name="recruit_start_h" <?=value($myp->recruit_start_h)?> size="3" maxlength="2">��
						<input type="submit" value="�ꥯ�롼�ȳ���������������դ�׻�" onclick="document.form1.next_action.value='calc_date'"><br>
						��
						<input type="text" name="recruit_end_y" <?=value($myp->recruit_end_y)?> size="5" maxlength="4">ǯ
						<input type="text" name="recruit_end_m" <?=value($myp->recruit_end_m)?> size="3" maxlength="2">��
						<input type="text" name="recruit_end_d" <?=value($myp->recruit_end_d)?> size="3" maxlength="2">��
						<input type="text" name="recruit_end_h" <?=value($myp->recruit_end_h)?> size="3" maxlength="2">��
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">������</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="decision_date_y" <?=value($myp->decision_date_y)?> size="5" maxlength="4">ǯ
						<input type="text" name="decision_date_m" <?=value($myp->decision_date_m)?> size="3" maxlength="2">��
						<input type="text" name="decision_date_d" <?=value($myp->decision_date_d)?> size="3" maxlength="2">��
					</td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">��ļ��»ܴ���</td>
					<td bgcolor="#ffeecc">
						<input type="text" name="room_start_y" <?=value($myp->room_start_y)?> size="5" maxlength="4">ǯ
						<input type="text" name="room_start_m" <?=value($myp->room_start_m)?> size="3" maxlength="2">��
						<input type="text" name="room_start_d" <?=value($myp->room_start_d)?> size="3" maxlength="2">��
						��
						<input type="text" name="room_end_y" <?=value($myp->room_end_y)?> size="5" maxlength="4">ǯ
						<input type="text" name="room_end_m" <?=value($myp->room_end_m)?> size="3" maxlength="2">��
						<input type="text" name="room_end_d" <?=value($myp->room_end_d)?> size="3" maxlength="2">��
					</td>
				</tr>
			</table>
			<table>
				<tr align="right">
					<td>
<?
if (!$pjt_id) {
?>
						<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='update'">
<?
} else {
	if ($myp->status == MPS_REC_JUNBI) {
?>
						<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
<?
	}
?>
						<input type="button" value="����롡" onclick="location.href='mp_pjt_list.php'">
<?
}
?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="next_action">
			</form>
			<!-- my�ѡ��ȥʡ� -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
