<?
$top = './..';
$inc = "$top/inc";
include("$inc/mn_header2.php");
$img = "$top/image";

$monitor_id = $_SESSION['ss_monitor_id'];
?>
<?monitor_menu()?>
<!--������ƥ�Ĥ��������-->
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�������䤤��碌</SPAN>
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td valign="top"><br>
			<table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="white">
				<tr>
					<td><font color="#2b2b63"><b><br>�ޤ���<a href="<?=$top?>/mypage/faq.php">FAQ</a>�򤴳�ǧ��������������¾�ˤ狼��ʤ������������ޤ����顢��̳�ɤޤǤ����䤯��������</b></font></td>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#c3c2ff">
									<font color="#ffcc00"><b>��</b></font>
									<font size="2"><b>���䤤��碌��᡼�륢�ɥ쥹��<a href="mailto:m-info@kikasete.net">m-info@kikasete.net</a>)</b></font>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ebebeb"><font size="2"><br>�������ơ�net �Ǥϡ��᡼��ˤ�뤪�䤤��碌�б���ʿ���������� �������������δ֤ˤ����ʤäƤ���ޤ������Τ��ᡢ�б����ֳ��ˤ������������䤤��碌�ؤ��ֿ��ˤĤ��ޤ��Ƥϡ�¿�������֤򤤤������Ƥ���ޤ�������������λ��ĺ���ޤ��褦�����ꤤ�פ��ޤ���<br><br></font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<!--������ƥ�Ĥ����ޤǡ�-->
			</TD>
		</TR>
	</TBODY>
</TABLE>
</BODY>
</HTML>
