<?
$top = './..';
$inc = "$top/../inc";
include("$inc/check.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_error.php");

if ($web_url == '')
	$err_msg[] = '���ʤ��Υۡ���ڡ���URL�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_url($web_url))
	$err_msg[] = '���ʤ��Υۡ���ڡ���URL��ɽ�����������ʤ��褦�Ǥ���';

if ($introduction == '')
	$err_msg[] = '�ۡ���ڡ����δ�ñ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
?>
<? monitor_menu() ?>
<?
if (!$err_msg) {
?>
<form method="post" action="introduction3.php">
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
								<td><font size="2"><b><br>
									</b></font><font size="2"><br>
									��</font><font size="2" color="#ff0009">��</font><font size="2">�� �Τ�����ܤ�ɬ�ܹ��ܤȤʤ�ޤ��Τǡ�ɬ������������������<br>
									</font><table border="0" cellpadding="3" cellspacing="2" width="100%">
										<tr>
											<td colspan="3"><font size="2">
												<hr>
												</font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> ���ʤ��Υۡ���ڡ���URL</font></td>
											<td colspan="2"><font size="2"><?=htmlspecialchars($web_url)?></font></td>
										</tr>
										<tr>
											<td width="119" bgcolor="#dedede"><font size="2" color="#ff0009">��</font><font size="2"> �ۡ���ڡ����δ�ñ������</font></td>
											<td colspan="2"><font size="2"><pre><?=htmlspecialchars($introduction)?></pre></font></td>
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
<input type="hidden" name="web_url" <?=value($web_url)?>>
<input type="hidden" name="introduction" <?=value($introduction)?>>
</form>
<?
} else
	error_msg($err_msg);
?>
