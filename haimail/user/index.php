<?
include('inc/common.php');
include('inc/database.php');
include('inc/reginfo.php');
include('inc/sel_profile1.php');
include('inc/header.php');

//=== �ᥤ����� ===
session_start();
$reginfo = new CRegInfo;
?>
<HTML>

<HEAD>
<META HTTP-EQUIV="content-type" CONTENT="text/html;charset=EUC-JP">
<META NAME="keywords" CONTENT="���ץȥ���᡼��,�ݥ����,���ߥ�˥��������,���󥱡���,̵��,����,���ʷ�,������,�ǿ�����,����,�Ϥ��᡼��,�Ϥ��᡼�롦net,������������,�ģ�,���,����ô����,�ã����,���󥿥饯�ƥ���,���󥿥饯�����,����ʸ,����,�ȥ�,��᡼��,�Żҥ᡼��,�ѡ��ߥ����ޡ����ƥ���,�ť᡼��ޡ����ƥ���,ʹ��">
<META NAME="description" CONTENT="�ۤ������󤬣ť᡼����Ϥ������äƤ��줷�����ȥݥ���ȡ����äƤ��Τ�������ʸ����Ȥξ���ô���Ԥ���Τ���桢��᡼����Ϥ��ޤ�����κ�ä����ʤǤ������ʤ��δ��ۤ�ʹ�����Ƥ�����������ʬ�ǥ᡼���ȯ���Ǥ��ޤ������󥿥饯�ƥ��֡����ߥ�˥�������󡦥ͥåȥ����������ҡ����������������Ϥ�����ʹ�����ץȥ���">
<TITLE>�Ϥ��᡼�롦����</TITLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
function subWin(wURL,wName,wOption) { //v2.0
	window.open(wURL,wName,wOption);
}
//-->
</SCRIPT>
<style type="text/css">
<!--
.text12 {  font-size: 12px; line-height: 125%}
.oshirase {  font-size: 10px; line-height: 125%; color: #333333}
.loginform{
	font-size: 12px;
}
-->
</style>
</HEAD>


<BODY BGCOLOR="white" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0" TRACINGSRC="�Ϥ��᡼��.psd" TRACINGOPACITY="40" >
<A NAME="top"></A>

<? haimail_header('.'); ?>

<p><a href="http://www.haimail.net/ct/c.asp?8111-188651" target="_blank"><img src="campaign/2004spring/img/top_haru.jpg" width="740" height="74" border="0"><BR>
  ��������ޤǤ���Ͽ�򤹤�ȣ���̾��������֥ץ쥼��ȥե������դ��Ϥ��᡼���̿��פ������ˤ��Ϥ����ޤ���</a></p>
<TABLE WIDTH="598" BORDER="0" CELLSPACING="0" MM_NOCONVERT="TRUE" CELLPADDING="0">
			<TR>
				<TD WIDTH="12" ALIGN="right" VALIGN="top"><IMG HEIGHT="16" WIDTH="12" SRC="img/space.gif"></TD>
				<TD WIDTH="20" ALIGN="left" VALIGN="top"><IMG HEIGHT="16" WIDTH="19" SRC="img/space.gif"></TD>
				<TD COLSPAN="2" ALIGN="left" VALIGN="bottom">
			<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="565">
				<TR HEIGHT="16">
					<TD WIDTH="393" HEIGHT="16" ALIGN="left" VALIGN="middle"></TD>
					<TD HEIGHT="16" VALIGN="middle" width="242" colspan="2"></TD>
				</TR>
			</TABLE>
		</TD>
			</TR>



			<TR ALIGN="left" VALIGN="middle">
				<TD WIDTH="12" ALIGN="right" VALIGN="top"><IMG HEIGHT="16" WIDTH="7" SRC="img/space.gif"></TD>
				<TD WIDTH="20"><IMG HEIGHT="31" WIDTH="20" SRC="img/right1.gif" ALT="right"></TD>
				<TD COLSPAN="2" ALIGN="left" VALIGN="bottom"><IMG HEIGHT="28" WIDTH="479" SRC="img/member_bar.gif" ALT="���С���Ͽ�ޤ�"></TD>
			</TR>
			<TR HEIGHT="22" ALIGN="left" VALIGN="middle">
				<TD HEIGHT="22" ALIGN="right" VALIGN="top" WIDTH="12"></TD>
				<TD WIDTH="20" ROWSPAN="2" VALIGN="top"><IMG HEIGHT="100" WIDTH="20" SRC="img/line.gif"></TD>
				<TD WIDTH="99" HEIGHT="35" VALIGN="top"><IMG VALIGN="top" SRC="img/botton1.gif" WIDTH="94" HEIGHT="17" ALT="1��������Ͽ"><BR></TD>

		<TD WIDTH="445" HEIGHT="35" VALIGN="top"><FONT SIZE="2">���Υڡ�������Ͽ���롡����<A HREF="#step1">STEP1</A>��</FONT>
			<table width="200" border="0" cellspacing="0" cellpadding="0" align="right">
				<tr>
					<td width="67"><A HREF="2/index.html"><IMG HEIGHT="58" WIDTH="57" SRC="img/new.gif" BORDER="0" ALT="�Ϥ���Ƥ�����"></A></td>
					<td width="107">
						<div align="center"><a href="javascript:subWin('cm/index.html','window1','width=650,height=500')"><img src="img/gotoflash.gif" width="97" border="0" height="35" alt="�Ϥ��᡼���CM�Ϥ�����"></a><br>
							<font size="2">��Flash�С�������</font></div>
					</td>
				</tr>
			</table>
		</TD>
			</TR>
			<TR VALIGN="top">
				<TD ALIGN="right" VALIGN="top" WIDTH="12"></TD>
				<TD WIDTH="99" ALIGN="left" VALIGN="top"><IMG SRC="img/botton2.gif" WIDTH="94" HEIGHT="18" ALT="2��ä�����Ͽ"></TD>
				<TD WIDTH="445" ALIGN="left" VALIGN="top"><FONT SIZE="2">
					�᡼�����Ͽ���뤳�Ȥ�Ǥ��ޤ���<BR>
					�᡼�륢�ɥ쥹�����Ϥ���ȡ���Ͽ�ե����बe�᡼����Ϥ��ޤ���<BR>
					���������ֿ����Ƥ���������<BR></FONT>
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
						<TR HEIGHT="22">
							<FORM ACTION="11/index.php" METHOD="post">
							<TD WIDTH="148" ALIGN="left" VALIGN="middle" nowrap HEIGHT="22"><FONT SIZE="2" COLOR="#ff9b2f">��</FONT><FONT SIZE="2" COLOR="black">���ʤ��Υ᡼�륢�ɥ쥹</FONT></TD>
							<TD ALIGN="left" VALIGN="middle" HEIGHT="22"><INPUT TYPE="text" NAME="mail_adr" SIZE="18"></TD>
							<TD ALIGN="left" VALIGN="middle" HEIGHT="22"><INPUT TYPE="submit" VALUE="��������" NAME="��������"></TD>
							</FORM>
						</TR>
					</TABLE></TD>
			</TR>
			<TR>
				<TD WIDTH="12"></TD>
				<TD ALIGN="left" VALIGN="middle" WIDTH="20"><IMG HEIGHT="30" WIDTH="20" SRC="img/ex.gif" ALT="!"></TD>

		<TD COLSPAN="2" VALIGN="bottom"><IMG HEIGHT="25" WIDTH="479" SRC="img/promise_bar.gif" ALT="����«"><FONT SIZE="2"><A NAME="step1"></A></FONT></TD>
			</TR>
			<TR>
				<TD WIDTH="12"></TD>
				<TD WIDTH="20" ALIGN="right" VALIGN="top"><IMG HEIGHT="18" WIDTH="20" SRC="img/line.gif"></TD>
				<TD COLSPAN="2" ALIGN="left" VALIGN="top"><FONT SIZE="2">���С�����Ͽ����Ȥ��ϡ�<A HREF="3/3-1.html" TARGET="_blank">����«</A>���ɤߤ���������<A NAME="step1"></A></FONT></TD>
			</TR>
			<TR HEIGHT="35">
				<TD WIDTH="12" HEIGHT="35">��</TD>
				<TD HEIGHT="35" ALIGN="left" VALIGN="top" WIDTH="20"><IMG HEIGHT="35" WIDTH="20" SRC="img/right2.gif" ALT="right"></TD>

		<TD HEIGHT="35" COLSPAN="2" ALIGN="left" VALIGN="bottom"><IMG HEIGHT="27" WIDTH="479" SRC="img/toroku_bar.gif" ALT="��������Ͽ"></TD>
			</TR>
			<TR>
				<TD WIDTH="12"><IMG HEIGHT="16" WIDTH="10" SRC="img/space.gif"></TD>
				<TD WIDTH="20" ALIGN="right" VALIGN="top"><IMG HEIGHT="210" WIDTH="20" SRC="img/line.gif"></TD>
				<TD COLSPAN="2" ALIGN="left" VALIGN="top"><TABLE BORDER="0" CELLSPACING="0" MM_NOCONVERT="TRUE">
						<TR>

					<TD COLSPAN="9" VALIGN="top"><FONT SIZE="2"><IMG HEIGHT="21" WIDTH="43" SRC="img/1.gif">
						<HR>
						��̣�����뤳�Ȥ䡢�ߤ��������᡼��Ǥ��Ϥ����ޤ���<BR>
						�����ˤۤ���������Ϥ����뤿��ˡ����ʤ��ε������˶ᤤ��̣�٤�����Ǥ���������<BR>
						</FONT><FONT SIZE="2"><BR>
								</FONT><FONT SIZE="2" COLOR="#ff9b2f">��</FONT><FONT SIZE="2">��̣��<BR>
								�������ʤꤢ�롡�������������դĤ��ˤ��롡���������������󤼤�ʤ�����</FONT><FONT SIZE="2"><BR>
								<BR>
								</FONT></TD>
						</TR>
						<TR ALIGN="left" VALIGN="top">
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/eat.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/clithes.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/living.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/work.gif" WIDTH="54" HEIGHT="79" ALT="Ư"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/life.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/relax.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/play.gif" WIDTH="54" HEIGHT="79" ALT="ͷ"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/beauty.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
							<TD WIDTH="62" ALIGN="center" VALIGN="top"><IMG SRC="img/knowridge.gif" WIDTH="54" HEIGHT="79" ALT="��"></TD>
						</TR>
					</TABLE><BR>
				</TD>
			</TR>
		</TABLE><BR>
		<FORM name="form1" ACTION="1/home_check.php" METHOD="post">
		<TABLE CELLSPACING="0" CELLPADDING="0" BORDER="0" WIDTH="605">
			<TR>
				<TD WIDTH="15" VALIGN="top"><IMG HEIGHT="16" WIDTH="20" SRC="img/space.gif"></TD>
				<TD VALIGN="top" WIDTH="342"><FONT SIZE="2"><TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR>
							<TD WIDTH="40" ALIGN="left" VALIGN="top" BGCOLOR="white"></TD>
							<TD WIDTH="200" BGCOLOR="#b2cfef"><FONT SIZE="2">��̣������</FONT></TD>
							<TD WIDTH="30" BGCOLOR="#b2cfef" ALIGN="center" VALIGN="middle">��</TD>
							<TD WIDTH="30" BGCOLOR="#b2cfef" ALIGN="center" VALIGN="middle">��</TD>
							<TD WIDTH="30" BGCOLOR="#b2cfef" ALIGN="center" VALIGN="middle">��</TD>
						</TR>
						<TR BGCOLOR="white" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="13" ALIGN="left" VALIGN="top" BGCOLOR="white"><IMG SRC="img/eat_little.gif" WIDTH="40" HEIGHT="53" ALT="��"></TD>
							<TD WIDTH="200" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR HEIGHT="18">
							<TD WIDTH="40" ROWSPAN="2" BGCOLOR="white"><IMG SRC="img/clothes_little.gif" WIDTH="40" HEIGHT="52" ALT="��"></TD>
							<TD COLSPAN="4" HEIGHT="18"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR BGCOLOR="white" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="6" ALIGN="left" VALIGN="top" BGCOLOR="white"><IMG SRC="img/living_little.gif" WIDTH="40" HEIGHT="52" ALT="��"></TD>
							<TD WIDTH="200" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR BGCOLOR="#ffffcf" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="7" ALIGN="left" VALIGN="top" BGCOLOR="white"><IMG SRC="img/work_little.gif" WIDTH="40" HEIGHT="52" ALT="Ư"></TD>
							<TD COLSPAN="4" BGCOLOR="white" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('Ư', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR BGCOLOR="#ffffcf" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="5" ALIGN="left" VALIGN="top" BGCOLOR="white"><IMG SRC="img/life_little.gif" WIDTH="40" HEIGHT="52" ALT="��"></TD>
							<TD COLSPAN="4" BGCOLOR="white" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="3" ALIGN="left" VALIGN="top"><IMG SRC="img/relax_little.gif" WIDTH="40" HEIGHT="52" ALT="��"></TD>
							<TD COLSPAN="4" BGCOLOR="white" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE></FONT></TD>
				<TD WIDTH="25" VALIGN="top"><IMG HEIGHT="16" WIDTH="25" SRC="img/space.gif"></TD>
				<TD VALIGN="top" WIDTH="342"><FONT SIZE="2"><TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR>
							<TD WIDTH="40" BGCOLOR="white"></TD>
							<TD BGCOLOR="#b2cfef" WIDTH="200"><FONT SIZE="2">��̣������</FONT></TD>
							<TD BGCOLOR="#b2cfef" WIDTH="30" ALIGN="center" VALIGN="middle">��</TD>
							<TD BGCOLOR="#b2cfef" WIDTH="30" ALIGN="center" VALIGN="middle">��</TD>
							<TD BGCOLOR="#b2cfef" WIDTH="30" ALIGN="center" VALIGN="middle">��</TD>
						</TR>
						<TR BGCOLOR="white" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="17" VALIGN="top" BGCOLOR="white"><IMG SRC="img/play_little.gif" WIDTH="40" HEIGHT="52" ALT="ͷ"></TD>
							<TD COLSPAN="4" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('ͷ', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR ALIGN="left" VALIGN="top" BGCOLOR="white" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="4" BGCOLOR="white"><IMG SRC="img/beauty_little.gif" WIDTH="40" HEIGHT="52" ALT="��"></TD>
							<TD WIDTH="200" BGCOLOR="white" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE>
					<HR SIZE="1">
					<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="2">
						<TR ALIGN="left" VALIGN="top" BGCOLOR="white" HEIGHT="16">
							<TD WIDTH="40" ROWSPAN="15" BGCOLOR="white"><IMG SRC="img/knowridge_little.gif" WIDTH="40" HEIGHT="52" ALT="��"></TD>
							<TD WIDTH="200" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
							<TD WIDTH="30" HEIGHT="16"></TD>
						</TR>
<?
WriteKyoumiGenre('��', $reginfo->kyoumi_genre);
?>
					</TABLE></FONT></TD>
			</TR>
		</TABLE><BR>

	<TABLE BORDER="0" CELLSPACING="0" MM_NOCONVERT="TRUE" WIDTH="608">
		<TR HEIGHT="16">
			<TD WIDTH="20" HEIGHT="16" ALIGN="right" VALIGN="top"></TD>
			<TD HEIGHT="16" COLSPAN="2" ALIGN="left" VALIGN="top">
				<HR WIDTH="100%" SIZE="2">
			</TD>
		</TR>
		<TR>
			<TD ALIGN="right" VALIGN="top" WIDTH="20"><IMG HEIGHT="16" WIDTH="20" SRC="img/space.gif"></TD>
			<TD WIDTH="68" ALIGN="left" VALIGN="top"><A HREF="#top"><IMG HEIGHT="72" WIDTH="68" SRC="img/back_to_top.gif" BORDER="0" ALT="������"></A></TD>
			<TD ALIGN="left" VALIGN="top">
				<DIV ALIGN="left"> <FONT SIZE="2">STEP2�ǤϤ��ʤ����ȤΤ��Ȥ䡢�褯�Ԥ���Ź�ˤĤ��Ƥ�ʹ�����ޤ���<BR>
					�����������ʤ����Ȥ����ꤹ��Ŀ;���ˤĤ��ƤϤ�ʹ�����ޤ���Τǡ�<BR>
					�¿�������Ͽ���Ƥ�����������<a href="#" title="SSL�ˤĤ���">SSL�ˤĤ���</a>��<BR>
					</FONT>
					<input type="image" src="img/reg_ssl.gif" alt="SSL����Ͽ" width="106" height="21" border="0" hspace="3" vspace="3" onclick="document.form1.action='<?=https_url('1/home_check.php')?>'">
					<input type="image" src="img/reg.gif" alt="��SSL����Ͽ" width="87" height="21" border="0" hspace="3" vspace="3" onclick="document.form1.action='<?=http_url('1/home_check.php')?>'"></DIV>
			</TD>
		</TR>
		<TR>
			<TD WIDTH="20"></TD>
			<TD COLSPAN="2">
				<TABLE align="right">
					<TR>
<TD width="88" height="31">
<A target="_blank" href="https://www.netmile.co.jp/ctrl?mb=user.NMRegist1"><img src="img/nm-logo.gif" width="88" height="31" border="0" alt="�ͥåȥޥ���"></A>
</TD>
</TR>
<TR>
<TD width="100" align="center"><A target="_blank" href="https://www.netmile.co.jp/ctrl?mb=user.NMRegist1"><Font size="2">�ݥ���ȥץ����<BR>
�ͥåȥޥ���</FONT></A>
</TD>
</TR>
</TABLE>
			</TD>
		</TR>
	</TABLE>

<? haimail_footer('.'); ?>

		</FORM>
	</BODY>

</HTML>
