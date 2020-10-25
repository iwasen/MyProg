#!/usr/local/bin/php4
<?
/******************************************************
' System :flette�䤤��碌�ڡ���
' Content:���ϥ����å�����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/inquiry.php");
include("$inc/check.php");
include("$inc/decode.php");

// ���å���󤫤��䤤��碌�������
session_start();
if ($inquiry == null)
	redirect('timeout.php');

// ���顼�ꥻ�å�
$inquiry->error = null;

// �ե����फ��������
$inquiry->name = $name;
$inquiry->mail_addr = $mail_addr;
$inquiry->mail_addr2 = $mail_addr2;
$inquiry->subject = $subject;
$inquiry->content = $content;

// ̾�����ϥ����å�
if ($inquiry->name == '')
	$inquiry->error['name'] = '�㤪̾�������Ϥ��Ƥ���������';

// �᡼�륢�ɥ쥹���ϥ����å�
if ($inquiry->mail_addr == '')
	$inquiry->error['mail_addr'] = '��᡼�륢�ɥ쥹�����Ϥ��Ƥ���������';
elseif ($inquiry->mail_addr2 == '')
	$inquiry->error['mail_addr'] = '��᡼�륢�ɥ쥹�ʳ�ǧ�ѡˤ����Ϥ��Ƥ���������';
elseif ($inquiry->mail_addr != $inquiry->mail_addr2)
	$inquiry->error['mail_addr'] = '��᡼�륢�ɥ쥹�����פ��Ƥ��ʤ��褦�Ǥ���';
elseif (!check_mail_addr($inquiry->mail_addr))
	$inquiry->error['mail_addr'] = '��᡼�륢�ɥ쥹���������ʤ��褦�Ǥ���';

// �䤤��碌�������ϥ����å�
if ($inquiry->subject == '')
	$inquiry->error['inquiry'] = '�㤪�䤤��碌���Ƥ����򤷤Ƥ���������';
elseif ($inquiry->content == '')
	$inquiry->error['inquiry'] = '�㤪�䤤��碌���Ƥ����Ϥ��Ƥ���������';

// ���顼����������
if ($inquiry->error)
	redirect('info_form.php');
?>
<HTML>
<HEAD>
<TITLE>FLETTE / ���䤤��碌</TITLE>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" href="../../css/flette.css" type="text/css">
<script language="JavaScript" src="../../js/fjs.js" type="text/JavaScript"></script>
</head>

<body BGCOLOR="#ffffff" TEXT="#333333" MARGINWIDTH="0" MARGINHEIGHT="0" TOPMARGIN="0" LEFTMARGIN="0">

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 >
<TR>
<TD align="left" background="../../images/header_bg.gif"><img src="../../images/header2.gif" width="599" height="30" border="0"></TD>
</TR>
</TABLE>

<!--main start-->

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD valign="left">

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="10" height="1"></TD>
<TD ><img src="../../images/info_title.gif" width="510" height="20"></TD>
<TD ><A HREF="javascript:window.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0"></A></TD>
</TR>
</TABLE>

<TABLE WIDTH=74 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="10"></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="20" height="1"></TD>
<TD ><img src="../../images/info_subtit.gif" width="570" height="18"></TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>

<TABLE WIDTH=599 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="52" height="1" border="0" ></TD>
<TD><span class="txt-m">ɬ�׻�������ϤΤ����������ץܥ���򥯥�å����Ƥ���������</font></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="51" height="1"></TD>
<TD bgcolor="#FFFFFF" align="center">

<TABLE WIDTH=539 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor="#595959">
<TABLE WIDTH=100% BORDER=0 CELLPADDING=4 CELLSPACING=1>
<form>
<!--�Ŀ;���-->
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">��̾��<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD width="431" colspan="3" bgcolor="#FFFFFF"><span class="txt-m"><?=htmlspecialchars($inquiry->name)?>&nbsp;&nbsp;��</span></TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">�᡼�륢�ɥ쥹<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD width="431" colspan="3" bgcolor="#FFFFFF">
<span class="txt-m"><?=htmlspecialchars($inquiry->mail_addr)?></span>
</TD>
</TR>
<TR>
<TD width="105" bgcolor="#DCDCD7"><span class="txt-m">���䤤��碌����<BR><font color="#FD6000">��ɬ��</font></span></TD>
<TD width="431" colspan="3" bgcolor="#FFFFFF">

<span class="txt-m"><?=decode_inquiry_subject($inquiry->subject)?><BR>
<BR>
<?=nl2br(htmlspecialchars($inquiry->content))?>
</span>

</TD>
</TR>
</table>
</TD>
</TR>
</TABLE>

</TD>
</TR>
</table>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD ><img src="../../images/spacer.gif" width="1" height="15"></TD>
</TR>
</table>

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD><img src="../../images/spacer.gif" width="51" height="30" border="0" ></TD>
<TD><a href="info_thanks.php"><img src="../../images/btn_soushin.gif" width="100" height="30" border="0" alt="����"></TD>
<TD><img src="../../images/spacer.gif" width="15" height="30" border="0" ></TD>
<TD><a HREF="info_form.php"><img src="../../images/btn_back.gif" width="100" height="30" border="0" alt="���"></a></TD>
</TR>
</form>
</TABLE>

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD height="30" valign="top" align="left">

	<TABLE WIDTH=590 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TD align="right"><A HREF="javascript:window.close();"><img src="../../images/btn_close.gif" width="70" height="20" border="0" alt="������ɥ����Ĥ���"></A></TD>
	</TR>
	</table>

</TD>
</TR>
<TR>
<td bgcolor="#595959" align="right" class="txt-s"><font color="#FFFFFF"><?=COPYRIGHT?> &nbsp;&nbsp;&nbsp;&nbsp;</font></td>
</TR>
</TABLE>

</BODY>
</HTML>
