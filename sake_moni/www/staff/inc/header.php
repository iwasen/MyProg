<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥��������å��ѥڡ���
' Content:�����åեڡ����ѥإå����եå�
'******************************************************/

// �إå�
function staff_header($title, $body_option='') {
?>
<HTML>
<HEAD>
<TITLE>�������ʪ�Υ��󥱡��ȥ�˥�����<?=$title?>��</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<link rel="stylesheet" href="kikasete.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0 <?=$body_option?>>

<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD><IMG SRC="images/staff00_title.gif" WIDTH=720 HEIGHT=106 ALT="�������ʪ�Υ��󥱡��ȥ�˥���"></TD>
	</TR>
	<TR>
	<TD align=center height=320>
<?
}

// ���̥եå�
function staff_footer() {
?>
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
<?
}
?>