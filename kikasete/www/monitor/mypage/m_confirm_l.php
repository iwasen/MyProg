<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// ����ӥ�̾ɽ��
function decode_conveni($code) {
	if ($code != '') {
		$sql = "SELECT cv_name FROM m_conveni WHERE cv_conveni_cd IN ($code) ORDER BY cv_order";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// �����ѡ�̾ɽ��
function decode_super($code) {
	if ($code != '') {
		$sql = "SELECT sp_name FROM m_super WHERE sp_super_cd IN ($code) ORDER BY sp_order";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// ��̣������̾ɽ��
function decode_genre($code) {
	if ($code != '') {
		$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($code) ORDER BY kg_order";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// �ɲåץ�ե�����̾ɽ��
function decode_ma_profile($profile_id, $code) {
	if ($code != '') {
		$sql = "SELECT pfs_select_text FROM t_profile_sel WHERE pfs_profile_id=$profile_id AND pfs_profile_cd IN ($code) ORDER BY pfs_order";
		$name = db_decode_multi($sql);
	}
	return $name;
}

// �ޥ�������ʣ��̾��ɽ��
function db_decode_multi($sql) {
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$name .= "<nobr>$fetch[0]��</nobr>";
	}
	return $name;
}

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('m_update.php');
$reg = &$_SESSION['ss_regist'];
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�����ץ�ե�����</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<form>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">��</font><font color="white">�������γ�ǧ</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b>�ʲ����̤�˹������ޤ���<br>
									<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3">
												<hr>
											</td>
										</tr>
<?
if ($reg->spouse_flg != '') {
?>
			<tr>
				<td width="119" bgcolor="#c3c2ff"><font size="2">�۶��Ԥ�̵ͭ</font></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_spouse_flg($reg->spouse_flg) . ($reg->spouse_flg == '3' && $reg->spouse_other != '' ? "��$reg->spouse_other" : '')?></SPAN></td>
			</tr>
<?
	if ($reg->spouse_flg == '1') {
		if ($reg->spouse_birthday_y != '' && $reg->spouse_birthday_m != '' && $reg->spouse_birthday_d != '') {
?>
			<tr>
				<td width="119" bgcolor="#c3c2ff"><font size="2">�۶��Ԥ���ǯ����</font></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=$reg->spouse_birthday_y?>ǯ <?=$reg->spouse_birthday_m?>�� <?=$reg->spouse_birthday_d?>��</SPAN></td>
			</tr>
<?
		}

		if ($reg->spouse_shokugyo_cd != '') {
?>
			<tr>
				<td width="119" bgcolor="#c3c2ff"><font size="2">�۶��Ԥο���</font></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_shokugyou($reg->spouse_shokugyo_cd)?></SPAN></td>
			</tr>
<?
		}
	}
}

if ($reg->have_child != '') {
?>
			<tr>
				<td width="119" bgcolor="#c3c2ff"><font size="2">���Ҥ��ޤ�̵ͭ</font></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_have_child($reg->have_child)?></SPAN></td>
			</tr>
<?
	if ($reg->have_child == '1') {
		foreach ($reg->child as $i => &$child) {
?>
			<tr>
				<td width="119" bgcolor="#c3c2ff"><font size="2"><?=$i + 1?>���ܤΤ��Ҥ���</font></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120>
					���̡�<?=decode_child_sex($child->sex)?><br>
					��ǯ������<?=$child->birthday_y?>ǯ <?=$child->birthday_m?>�� <?=$child->birthday_d?>��
				</SPAN></td>
			</tr>
<?
		}
	}
}

if ($reg->senior_flg != '') {
?>
			<tr>
				<td width="119" bgcolor="#c3c2ff"><font size="2">����Ʊ�蘆��Ƥ��륷�˥��ؤ�����̵ͭ</font></td>
				<td colspan="2" valign="top" bgcolor="#FFFFFF"><SPAN class=fs12_lh120><?=decode_senior_flg($reg->senior_flg)?></SPAN></td>
			</tr>
<?
}

if ($reg->housing_form != '') {
?>
										<tr>
											<td width="119" bgcolor="#c3c2ff"><font size="2">�������</font></td>
											<td colspan="2"><font size="2"><?=decode_housing_form($reg->housing_form)?></font></td>
										</tr>
<?
}

if ($reg->have_car != '') {
?>
										<tr>
											<td width="119" bgcolor="#c3c2ff"><font size="2">�֤ν�ͭ</font></td>
											<td colspan="2"><font size="2"><?=decode_have_car($reg->have_car)?></font></td>
										</tr>
<?
}
?>
										<tr>
											<td width="119" bgcolor="#c3c2ff"><font size="2">�褯���Ѥ��륳��ӥ�</font></td>
											<td colspan="2"><font size="2"><?=decode_conveni($reg->conveni)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#c3c2ff"><font size="2">�褯���Ѥ��륹���ѡ�</font></td>
											<td colspan="2"><font size="2"><?=decode_super($reg->super)?></font></td>
										</tr>
<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
										<tr>
											<td width="119" bgcolor="#c3c2ff"><font size="2"><?=htmlspecialchars($profile_name)?></font></td>
											<td colspan="2"><font size="2"><?=decode_ma_profile($profile_id, $reg->ma_profile)?></font></td>
										</tr>
<?
}
?>
										<tr>
											<td width="119" bgcolor="#c3c2ff"><font size="2">��̣������</font></td>
											<td colspan="2"><font size="2"><?=decode_genre($reg->genre)?></font></td>
										</tr>
									</table><font size="2"><br>
									</font></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
<?
$sql = "SELECT rpa_monitor_id FROM t_rp_addr WHERE rpa_monitor_id={$_SESSION['ss_monitor_id']}";
if ($_SESSION['ss_monitor_type'] == 2 || db_fetch1($sql)) {
?>
											<input type="button" value="�ꥵ�����ѡ��ȥʡ�������ѹ�" onclick="location.href='m_update_p.php'">
<?
} else {
?>
											<input type="button" value="����������" onclick="location.href='m_update_end.php?p=0'">
<?
}
?>
											<input type="button" value="���ᡡ�롡" onclick="location.href='m_update_l.php'">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</body>
</html>
