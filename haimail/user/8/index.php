<?
include('../inc/common.php');
include('../inc/database.php');
include('../inc/member.php');
include('../inc/point.php');
include('../inc/header.php');

session_start();
$member = New CMember;

if ($mail_adr == '') {
	if (!$_SESSION['login'])
		redirect('../index.php');
} else {
	$mail_adr = ToHankaku(trim($mail_adr));
	if (!MailAdrCheck($mail_adr)) {
		$member->err_mail_adr = true;
		$member->SaveData();
		redirect('login_error.php');
	}

	if (!$member->Login($mail_adr, $password)) {
		$member->err_password = true;
		$member->SaveData();
		redirect('login_error.php');
	}
	$member->SaveData();
	$_SESSION['login'] = true;
}
?>
<HTML>

	<HEAD>
		<META HTTP-EQUIV="content-type" CONTENT="text/html;charset=EUC-JP">
		<TITLE>MY PAGE</TITLE>
	</HEAD>

	<BODY BGCOLOR="white" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

<? haimail_header('..'); ?>

		<BR>
		<BR>
		<TABLE BORDER="0" CELLSPACING="0" WIDTH="634" CELLPADDING="0">
			<TR>

		<TD WIDTH="50"><IMG HEIGHT="1" WIDTH="50" SRC="../img/space.gif"></TD>
				<TD><FONT COLOR="#2f2180"><B>���ʤ��θ��ߤΥݥ���Ȥ� <?=GetTotalPoint($member->member_id)?> �ݥ���ȤǤ���</B></FONT>
			<TABLE WIDTH="550" BORDER="0" CELLSPACING="2" CELLPADDING="2">
				<TR HEIGHT="16">
					<TD COLSPAN="2" HEIGHT="16"></TD>
				</TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">���ʤ�����Ͽ����</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">���ʤ��ˤԤä���ξ�����Ϥ����뤿��ˡ�<BR>
							���ʤ��ζ�̣�١����ꡢ�褯�Ԥ���Ź�ʤɤ��Ѥ�ä���<BR>
							���ҹ������Ƥ���������</FONT><BR>
							��<BR>
						</BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8a-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/toroku_b.gif" ALT="��Ͽ�ѹ�" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
        <TR HEIGHT="18">
          <TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">�᡼��ǾҲ�</TD>
        </TR>
        <TR>
          <TD WIDTH="451">
            <BLOCKQUOTE> <FONT SIZE="2">���ʤ��Τ�ͧã��Ҳ𤷤ޤ��󤫡�<BR>
              �Ҳ𤷤���ͧã�� �Ϥ��᡼�롦net ����Ͽ������<BR>
              ��<FONT COLOR="#2f2180">���ʤ�</FONT>�פȡ�<FONT COLOR="#2f2180">��ͧã</FONT>�פˤ��줾��</FONT><FONT SIZE="2" COLOR="#ff2f29">100</FONT><FONT
										SIZE="2">�ݥ��������ޤ���<br>
              </FONT></BLOCKQUOTE>
          </TD>
          <TD>
            <CENTER>
              <A HREF="8b-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/shokai_b.gif" ALT="�Ҳ𤹤�" BORDER="0"></A>
            </CENTER>
          </TD>
        </TR>
        <TR HEIGHT="18">
          <TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">�Хʡ��ǾҲ�</TD>
        </TR>
        <TR>
          <TD>
<BLOCKQUOTE>
              <P><FONT SIZE="2">���ʤ��ΣȣФǡ֤Ϥ��᡼�롦�����פ�Ҳ𤷤Ƥ��������ޤ��󤫡�
                <BR>
                �ȣФΥХʡ�������С���Ͽ���Ƥ���������������ͤˤĤ� <BR>
                ����Ȥ��Ƥ��ʤ��ˣ������ݥ���Ⱥ����夲�ޤ��� </FONT><BR>
              </P>
            </BLOCKQUOTE>
          </TD>
          <TD HEIGHT="18"><A HREF="8f-2.html"><IMG HEIGHT="17" WIDTH="80" SRC="img/shokai_b.gif" ALT="�Ҳ𤹤�" BORDER="0"></A></TD>
        </TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">�ݥ���ȸ�</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">���ʤ��ϲ��ݥ���Ȥ��ޤ�ޤ�������<BR>
							���ޤä��ݥ���ȤϤ�����ʤ�Τ˸򴹤Ǥ��ޤ���<BR>
							��</FONT><BR>
						</BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8c-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/ichiran_b.gif" ALT="�����򸫤�" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">�������ݴ��</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">���ޤǤ��Ϥ�����Ȥ���Υ᡼���<BR>
							�����������ݤ�������Ȥ�����Ǥ��ޤ���<BR>
							��</FONT><BR>
						</BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8d-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/settei_b.gif" ALT="���ꤹ��" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
				<TR HEIGHT="18">
					<TD BGCOLOR="#b2cce4" HEIGHT="18" COLSPAN="2">���</TD>
				</TR>
				<TR>
					<TD WIDTH="451">
						<BLOCKQUOTE> <FONT SIZE="2">�ȤƤ��ǰ�Ǥ�������񤷤������Ϥ����餫������Ǥ��ޤ���<BR>
							��<BR>
							</FONT></BLOCKQUOTE>
					</TD>
					<TD>
						<CENTER>
							<A HREF="8e-2.php"><IMG HEIGHT="17" WIDTH="80" SRC="img/taikai_b.gif" ALT="��񤹤�" BORDER="0"></A>
						</CENTER>
					</TD>
				</TR>
			</TABLE>
			<BR>
				</TD>
			</TR>
			<TR>
				<TD WIDTH="50"></TD>
				<TD><TABLE BORDER="0" CELLSPACING="0" MM_NOCONVERT="TRUE">
						<TR HEIGHT="16">
							<TD HEIGHT="16" ALIGN="left" VALIGN="top" WIDTH="550"><FONT SIZE="2">
								<HR>
								���䤤��碌��Ϥ����餫�顡��<A HREF="mailto:okotae@haimail.net">okotae@haimail.net</A></FONT></TD>
						</TR>
						<TR>
							<TD ALIGN="left" VALIGN="top" WIDTH="550"><FONT SIZE="2"><BR>
								<BR>
								<BR>
								</FONT>
								<HR>
								<A HREF="../index.php"><IMG HEIGHT="17" WIDTH="80" SRC="../img/home_b.gif" BORDER="0" ALT="�ۡ�������"></A></TD>
						</TR>
						</TD>
						</TR>
					</TABLE></TD>
			</TR>
		</TABLE>
		<br>

<? haimail_footer('..'); ?>

	</BODY>

</HTML>
