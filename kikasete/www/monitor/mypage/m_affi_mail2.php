<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

if ($intro_num == '')
	$err_msg[] = '���Ҳ��ͽ��Ϳ������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_num($intro_num, 0))
	$err_msg[] = '���Ҳ��ͽ��Ϳ����������ʤ��褦�Ǥ���';
?>
<? monitor_menu() ?>

<?
if (!$err_msg) {
?>
<form method="post" action="m_affi_mail3.php">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#c0e2fa>
				<TD>
					<IMG height=29 alt=���ե��ꥨ���� src="<?=$img?>/my_affiliate.gif" width=45 align=absMiddle>
					<SPAN class=mysttl>���ե��ꥨ���� </SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="481" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">����</font><font color="white">�������ơ�net�Ҳ𡡱���ե�����</font></td>
							</tr>
							<tr>
								<td><font size="2"><br>�������Ƥ򤴳�ǧ����������</font>
									<table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2">���Ҳ�ͽ��Ϳ�</font></td>
											<td colspan="2"><font size="2"><?=number_format($intro_num)?>��</font></td>
										</tr>
									</table></td>
							</tr>
							<tr>
								<td>
									<center>
										<hr>
										<font size="2">
											<input type="submit" value="�����礹�롡">
											<input type="button" value="����롡" onclick="history.back()">
										</font></center>
								</td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
<input type="hidden" name="affiliate_mgr_id" <?=value($affiliate_mgr_id)?>>
<input type="hidden" name="intro_num" <?=value($intro_num)?>>
</form>
<?
} else
	error_msg($err_msg);
?>
