<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/mn_partner.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// ���å�������
$_SESSION['ss_partner'] = new m_partner;
$partner = &$_SESSION['ss_partner'];
$partner->read_db($_SESSION['ss_monitor_id']);
?>
<? monitor_header('onload="onload_body()"') ?>
<? monitor_menu() ?>

<script type="text/javascript">
<!--
function onchange_shokugyou() {
	kinmu_control();
}
function onload_body() {
	kinmu_control();
}
function kinmu_control() {
	var f = document.form1;
	if (f.shokugyou_cd.selectedIndex == 0 || f.shokugyou_cd.selectedIndex == 5 || f.shokugyou_cd.selectedIndex == 10 || f.shokugyou_cd.selectedIndex == 11) {
		set_visibility("gyoushu_cd", false);
		set_visibility("shokushu_cd", false);
		set_visibility("kinmu_name", false);
		set_visibility("kinmu_zip", false);
		set_visibility("kinmu_area", false);
	} else {
		set_visibility("gyoushu_cd", true);
		set_visibility("shokushu_cd", true);
		set_visibility("kinmu_name", true);
		set_visibility("kinmu_zip", true);
		set_visibility("kinmu_area", true);
	}
}
function set_visibility(id, visible) {
	if (visible) {
		if (document.all)
			document.all(id).visibility  = "visible";
		if (document.getElementById)
			document.getElementById(id).style.visibility = "visible";
	} else {
		if (document.all)
			document.all(id).visibility  = "hidden";
		if (document.getElementById)
			document.getElementById(id).style.visibility = "hidden";
	}
}
//-->
</script>

<form name="form1" method="post" action="m_update_p_check.php">
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15"><img src="images/spacer.gif" width="15" height="1"></td>
		<td width="22"><img src="images/title_r.gif" width="22" height="21"></td>
		<td width="366" bgcolor="#8584d2"><img src="images/update_title.gif" width="113" height="21"></td>
		<td width="93"><img src="images/tilte_l.gif" width="93" height="21"></td>
	</tr>
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">����</font><font color="white">���ܾ������Ͽ</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b></font><font size="2"><br>
									��</font><font size="2" color="#ff0009">��</font><font size="2">�� �Τ�����ܤ�ɬ�ܹ��ܤȤʤ�ޤ��Τǡ�ɬ������������������<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2"><hr></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">͹���ֹ�ʼ����</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_zip1" size="3" <?=value($partner->jitaku_zip1)?>> - <input type="text" name="jitaku_zip2" size="4" <?=value($partner->jitaku_zip2)?>> ��Ⱦ�ѿ�����</font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">����</font></td>
											<td colspan="2"><font size="2"><select name="jitaku_area" size="1"><? select_area('�����Ӳ���������������', $partner->jitaku_area) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">���� <br>�ʻԶ��</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_addr1" size="39" <?=value($partner->jitaku_addr1)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">���� <br>��Į¼�ʲ���</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_addr2" size="39" <?=value($partner->jitaku_addr2)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">����<br>�ʥ��ѡ��ȡ��ӥ�̾��</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_addr3" size="39" <?=value($partner->jitaku_addr3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">�����ֹ�</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_tel1" size="5" maxlength="5" <?=value($partner->jitaku_tel1)?>> - <input type="text" name="jitaku_tel2" size="5" maxlength="5" <?=value($partner->jitaku_tel2)?>> - <input type="text" name="jitaku_tel3" size="5" maxlength="4" <?=value($partner->jitaku_tel3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">FAX<br>�ʤ������ξ���</font></td>
											<td colspan="2"><font size="2"><input type="text" name="jitaku_fax1" size="5" maxlength="5" <?=value($partner->jitaku_fax1)?>> - <input type="text" name="jitaku_fax2" size="5" maxlength="5" <?=value($partner->jitaku_fax2)?>> - <input type="text" name="jitaku_fax3" size="5" maxlength="4" <?=value($partner->jitaku_fax3)?>></font></td>
										</tr>
										<tr>
											<td colspan="3"><font size="2"><hr><br>
											���Ȥ���</font><font size="2" color="#ff0009">����</font><font size="2">�ס�</font><font size="2" color="#ff0009">��ȼ��ء�����</font><font size="2">�ס�</font><font size="2" color="#ff0009">̵��</font><font size="2">�פ����ϡ�</font><font size="2" color="#0000ff">�ȼ�</font><font size="2">��</font><font size="2" color="#0000ff">����</font><font size="2">�ι��ܤǤ�<br>
											��</font><font size="2" color="#ff0009">����¾</font><font size="2">�פ����Ӥ���������</font></td></tr><br>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">����</font></td>
											<td colspan="2"><font size="2"><select name="shokugyou_cd" size="1" onchange="onchange_shokugyou()"><? select_shokugyou('�����Ӳ���������������', $partner->shokugyou_cd) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="gyoushu_cd" size="2" color="#ff0009">��</font><font size="2">�ȼ�</font></td>
											<td colspan="2"><font size="2"><select name="gyoushu_cd" size="1"><? select_gyoushu('�����Ӳ���������������', $partner->gyoushu_cd, 1) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="shokushu_cd" size="2" color="#ff0009">��</font><font size="2">����</font></td>
											<td colspan="2"><font size="2"><select name="shokushu_cd" size="1"><? select_shokushu('�����Ӳ���������������', $partner->shokushu_cd) ?></select></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="kinmu_name" size="2" color="#ff0009">��</font><font size="2">��̳��̾</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_name" size="39" <?=value($partner->kinmu_name)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="kinmu_zip" size="2" color="#ff0009">��</font><font size="2">͹���ֹ�ʶ�̳���</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_zip1" size="3" <?=value($partner->kinmu_zip1)?>> - <input type="text" name="kinmu_zip2" size="4" <?=value($partner->kinmu_zip2)?>> ��Ⱦ�ѿ�����</font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font id="kinmu_area" size="2" color="#ff0009">��</font><font size="2">�̶��轻��<br>����ƻ�ܸ���</font></td>
											<td colspan="2"><font size="2"><select name="kinmu_area" size="1"><? select_area('�����Ӳ���������������', $partner->kinmu_area) ?></select></font></td>
										</tr>
<?
/*
?>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">�̶��轻��<br>�ʻԶ��</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_addr1" size="39" <?=value($partner->kinmu_addr1)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">�̶��轻��<br>��Į¼�ʲ���</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_addr2" size="39" <?=value($partner->kinmu_addr2)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">�̶��轻��<br>�ʥ��ѡ��ȡ��ӥ�̾��</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_addr3" size="39" <?=value($partner->kinmu_addr3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">�̶��������ֹ�</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_tel1" size="5" maxlength="5" <?=value($partner->kinmu_tel1)?>> - <input type="text" name="kinmu_tel2" size="5" maxlength="5" <?=value($partner->kinmu_tel2)?>> - <input type="text" name="kinmu_tel3" size="5" maxlength="4" <?=value($partner->kinmu_tel3)?>></font></td>
										</tr>
										<tr>
											<td width="114" bgcolor="#dedede"><font size="2"><font size="2" color="#ff0009">��</font><font size="2">�̶���FAX<br>�ʤ������ξ���</font></td>
											<td colspan="2"><font size="2"><input type="text" name="kinmu_fax1" size="5" <?=value($partner->kinmu_fax1)?>> - <input type="text" name="kinmu_fax2" size="5" <?=value($partner->kinmu_fax2)?>> - <input type="text" name="kinmu_fax3" size="5" <?=value($partner->kinmu_fax3)?>></font></td>
										</tr>
										<tr>
											<td colspan="3"><font size="2"><hr></font></td></tr>
										<tr>
											<td rowspan="2" bgcolor="#dedede" width="114"><font size="2">��²����<br>��Ʊ����۶��ԡ�</font></td>
											<td width="124"><font size="2">1.���۶��Ԥ�̵ͭ</font></td>
											<td width="146"><font size="2"><input type="radio" name="family_rel_cd[]" value="1" <?=checked($partner->family[1]->family_rel_cd == '1')?>> ���� <input type="radio" name="family_rel_cd[]" value="" <?=checked($partner->family[1]->family_rel_cd != '1')?>> �ʤ�</font></td>
										</tr>
										<tr>
											<td width="124"><font size="2">����ǯ��</font></td>
											<td width="146"><font size="2"><input type="text" name="family_age[]" size="3" <?=value($partner->family[1]->age)?>> ��</font></td>
										</tr>
<?
for ($i = 2; $i <= 5; $i++) {
	$family = &$partner->family[$i];
?>
										<tr>
											<td rowspan="2" bgcolor="#dedede" width="114"><font size="2">��²����<br>��Ʊ���²��</font></td>
											<td width="124"><font size="2"><?=$i?>.��³��</font></td>
											<td width="146"><font size="2"><select name="family_rel_cd[]" size="1"><? select_family_rel('�����Ӳ���������������', $family->family_rel_cd) ?></select></font></td>
										</tr>
										<tr>
											<td width="124"><font size="2">����ǯ��</font></td>
											<td width="146"><font size="2"><input type="text" name="family_age[]" size="3" <?=value($family->age)?>> ��</font></td>
										</tr>
<?
}
*/
?>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="����������">
											<input type="reset" value="�������᤹��">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</form>

<? monitor_footer() ?>
