<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
include("$inc/mail.php");

// ���ϥѥ�᡼������
$mail_addr = get_mail_addr($_POST['mail_addr']);

if ($mail_addr != '') {
	if (!check_mail_addr($mail_addr))
		$err_msg = '�᡼�륢�ɥ쥹�η������������ʤ��褦�Ǥ�';
	else {
		$sql = sprintf("SELECT mn_mail_addr,mn_password FROM t_monitor WHERE mn_mail_addr_low=%s AND mn_status<>9",
				sql_char(strtolower($mail_addr)));
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			get_mail_template('pwd_mn', $subject, $from, $cc, $bcc, $repty_to, $body);
			$body = str_replace('%PASSWORD%', $fetch->mn_password, $body);
			send_mail($subject, $fetch->mn_mail_addr, $from, $body, $cc, $bcc, $reply_to);

			redirect('m_pw_ok.php');
		} else
			$err_msg = '���Ϥ��줿�᡼�륢�ɥ쥹����Ͽ����Ƥ��ʤ��褦�Ǥ�';
	}
}
?>
<HTML>
<HEAD>
<TITLE>�������ʪ�Υ��󥱡��ȥ�˥����å�������̡�</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>

<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD><IMG SRC="images/monitor00_title.gif" WIDTH=720 HEIGHT=106 ALT="�������ʪ�Υ��󥱡��ȥ�˥���"></TD>
	</TR>
	<TR>
	<TD align=center height=320>

<!--���ѥ������礻�ե����ࢧ-->
<form method="post" name="form1" action="m_pw.php">
	<TABLE WIDTH=440 BORDER=0 CELLPADDING=1 CELLSPACING=4 bgcolor=#DDDDDD>
		<TR>
		<TD bgcolor=#FFFFFF>

			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=1 bgcolor=#99CCFF>
			<TR>
			<TD>

				<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 bgcolor=#FFFFFF>
				<TR>
				<TD align="center" valign="center">

					<TABLE WIDTH=383 BORDER=0 CELLPADDING=0 CELLSPACING=7>
					<TR>
					<TD><IMG SRC="images/spacer.gif" WIDTH=102 HEIGHT=1></TD>
					<TD><IMG SRC="images/spacer.gif" WIDTH=260 HEIGHT=1></TD>
					</TR>
					<TR>
					<TD colspan=2><IMG SRC="images/m_pw.gif" WIDTH=200 HEIGHT=36 alt="�ѥ���ɤ���礻"></TD>
					</TR>
					<TR>
					<TD><IMG SRC="images/monitor00_05.gif" WIDTH=102 HEIGHT=22 ALT="�᡼�륢�ɥ쥹"></TD>
					<TD><INPUT size=40 name="mail_addr" <?=value($mail_addr)?>></TD>
					</TR>
<?
if ($err_msg != '') {
?>
					<TR>
					<TD></TD>
					<TD><span class="err">��<?=$err_msg?>��</span></TD>
					</TR>
<?
}
?>
					<TR>
					<TD></TD>
					<TD align="left"><span onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_s0','','images/b_s0_over.gif',1)"><input type="image" SRC="images/b_s0.gif" WIDTH=130 HEIGHT=36 BORDER=0 ALT="��������" name=b_s0></span></TD>
					</TR>
					<TR>
					<TD colspan=2><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=10></TD>
					</TR>
					</TABLE>

				</TD>
				</TR>
				</TABLE>

			</TD>
			</TR>
			</TABLE>

		</TD>
		</TR>
	</TABLE>
</form>
<!--���ѥ������礻�ե����ࢥ-->

	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar.gif" WIDTH=720 HEIGHT=2></TD>
	</TR>
	<TR>
	<TD align=right><IMG SRC="images/copyright.gif" WIDTH=349 HEIGHT=22 ALT="copyright"></TD>
	</TR>
	<TR>
	<TD align=right>
<SPAN class="copyright">���Υۡ���ڡ����˻Ȥ��Ƥ���������̿����������ϡ�����&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a>&nbsp;�˵�°���ޤ���<BR>
̵�Ǥ�ʣ���������Ѥ��뤳�Ȥ�ˡΧ�ˤ��ؤ����Ƥ��ޤ���<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>