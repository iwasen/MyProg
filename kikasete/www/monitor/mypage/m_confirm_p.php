<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/format.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_partner.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// ���å�������
if (!isset($_SESSION['ss_partner']))
	redirect('m_update.php');
$partner = &$_SESSION['ss_partner'];
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
								<td bgcolor="#525294"><font color="#ffcc00">��</font><font color="white">�ꥵ�����ѡ��ȥʡ�����γ�ǧ</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b>�ʲ����̤�˹������ޤ���<br>
									<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">͹���ֹ�ʼ����</font></td>
											<td colspan="2"><font size="2"><?=$partner->jitaku_zip1?>-<?=$partner->jitaku_zip2?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">����</font></td>
											<td colspan="2"><font size="2"><?=decode_area($partner->jitaku_area)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">����ʻԶ��</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->jitaku_addr1)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">�����Į¼�ʲ���</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->jitaku_addr2)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">����ʥ��ѡ��ȡ��ӥ�̾��</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->jitaku_addr3)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">�����ֹ�</font></td>
											<td colspan="2"><font size="2"><?=format_tel($partner->jitaku_tel1, $partner->jitaku_tel2, $partner->jitaku_tel3)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">FAX�ʤ������ξ���</font></td>
											<td colspan="2"><font size="2"><?=format_tel($partner->jitaku_fax1, $partner->jitaku_fax2, $partner->jitaku_fax3)?></font></td>
										</tr>
<? /* ?>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">����</font></td>
											<td colspan="2"><font size="2"><?=decode_shokugyou($partner->shokugyou_cd)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">�ȼ�</font></td>
											<td colspan="2"><font size="2"><?=decode_gyoushu($partner->gyoushu_cd)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">����</font></td>
											<td colspan="2"><font size="2"><?=decode_shokushu($partner->shokushu_cd)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳��̾</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->kinmu_name)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳��͹���ֹ�</font></td>
											<td colspan="2"><font size="2"><?=$partner->kinmu_zip1?>-<?=$partner->kinmu_zip2?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳�轻�� ����ƻ�ܸ��� </font></td>
											<td colspan="2"><font size="2"><?=decode_area($partner->kinmu_area)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳�轻�� �ʻԶ�� </font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->kinmu_addr1)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳�轻�� ��Į¼�ʲ��� </font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->kinmu_addr2)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳�轻��<br>
												�ʥ��ѡ��ȡ��ӥ�̾�� </font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($partner->kinmu_addr3)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳�������ֹ� </font></td>
											<td colspan="2"><font size="2"><?=format_tel($partner->kinmu_tel1, $partner->kinmu_tel2, $partner->kinmu_tel3)?></font></td>
										</tr>
										<tr>
											<td width="145" bgcolor="#dedede"><font size="2">��̳��FAX�ʤ������ξ��� </font></td>
											<td colspan="2"><font size="2"><?=format_tel($partner->kinmu_fax1, $partner->kinmu_fax2, $partner->kinmu_fax3)?></font></td>
										</tr>
										<tr>
											<td colspan="3"><hr></td>
										</tr>
										<tr>
											<td rowspan="11" bgcolor="#dedede" width="145" valign="top"><font size="2">��²����</font></td>
											<td width="115"><font size="2">1 �۶��Ԥ�̵ͭ </font></td>
											<td width="124"><font size="2"><?=$partner->family[1]->family_rel_cd == '1' ? '����' : '�ʤ�'?></font></td>
										</tr>
										<tr>
											<td width="115"><font size="2">��ǯ��</font></td>
											<td width="124"><font size="2"><?=$partner->family[1]->age?>��</font></td>
										</tr>
<?
for ($i = 2; $i <= 5; $i++) {
	$family = &$partner->family[$i];
?>
										<tr>
											<td width="115"><font size="2"><?=$i?> ³��</font></td>
											<td width="124"><font size="2"><?=decode_family_rel($family->family_rel_cd)?></font></td>
										</tr>
										<tr>
											<td width="115"><font size="2">��ǯ��</font></td>
											<td width="124"><font size="2"><?=$family->age?>��</font></td>
										</tr>
<?
}
?>
<? */ ?>
									</table></font></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="button" value="����������" onclick="location.href='m_update_end.php?p=1'">
											<input type="button" value="���ᡡ�롡" onclick="location.href='m_update_p.php'">
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
