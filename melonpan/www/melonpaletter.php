<?php
	include("melonpa/inc/jimu_mag_num.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>�֥С����塼��ס֤���Ѥ�쥿���פι��ɲ�����ƹ��� </TITLE>
<link rel="stylesheet" href="css/melonpan.css">
</head>
<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600">
<TABLE width="750" border="0" cellspacing="0" cellpadding="0">
<TR>
<TD valign="top" colspan="3" align="center">
<TABLE width="513" border="0" cellspacing="0" cellpadding="0" bgcolor="#6AB05D">
<TR>
<TD>
<TABLE width="513" border="0" cellspacing="1" cellpadding="5">
<TR bgcolor="#FFFFFF">
<TD>
<p><b><font color="#336600">���֥С����塼��ס֤���Ѥ�쥿���פι��ɲ�����ƹ��� </font></b><br>
��<br>
<br>
<span class="text01">���֥С����塼��ס֤���Ѥ�쥿���פ��ɼԤγ����������ˤ��Ϥ����Ƥ��ޤ���<br>���ɤβ�����ƹ��ɤϤ��Υڡ�������Ԥ����Ȥ��Ǥ��ޤ���<br><br>
�����Υܥå�������˥᡼�륢�ɥ쥹�����Ϥ��ơ����ɲ�����ƹ��ɤΤɤ��餫�����ӡ��Ǹ��[����]�ܥ���򲡤��Ƥ���������(Enter�����ǤϤʤ���[�����ܥ���]�򥯥�å����Ƥ�������)</span></p>
<form action="melonpa/mel_reg.php" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#336600">
<td colspan="2"><b><font color="#FFFFCC">�֥С����塼��׹��ɲ�����ƹ���</font></b></td>
</tr>
<tr>
<td>
<input type="text" name="email" size="40">
</td>
<td>
<?php
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"of$bakyun_id\">\n";
	print "���ɲ�� �� \n";
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"on$bakyun_id\">\n";
	print "�ƹ��ɡ� \n";
?>
<input type="submit" value="����">
</td>
</tr>
</table>
</form>
<form action="melonpa/mel_reg.php" method="post">
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#336600">
<td colspan="2"><b><font color="#FFFFCC">�֤���Ѥ�쥿���׹��ɲ�����ƹ���</font></b></td>
</tr>
<tr>
<td>
<input type="text" name="email" size="40">
</td>
<td>
<?php
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"of$letter_id\">\n";
	print "���ɲ�� �� \n";
	print "<input type=\"radio\" name=\"read_chk[]\" value=\"on$letter_id\">\n";
	print "�ƹ��ɡ� \n";
?>
<input type="submit" value="����">
</td>
</tr>
</table>
</form>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
<TR>
<TD valign="top" colspan="3" align="right">
<HR size="1" width="750">
</TD>
</TR>
<TR>
<TD valign="top" width="200"> <IMG src="img/shim.gif" width="200" height="1">
</TD>
<TD valign="middle" align="center" width="350">
<div align="center"><FONT size="2"><IMG src="img/shim.gif" width="350" height="1"><BR>
Copyright &copy; 2000 xxxxxxx, Co.,Ltd. All rights reserved. <br>
<a href="http://www.xxxxxxx.co.jp/" target="mm_window"><img src="img/logo_do.gif" width="50" height="45" border="0"></a>
</FONT></div>
</TD>
<TD valign="top" width="200"><IMG src="img/shim.gif" width="200" height="1"><a href="./index.html" target="_top"><IMG src="img/b_home.gif" width="46" height="20" alt="HOME" hspace="5" border="0" align="absmiddle"></a></TD>
</TR>
</TABLE>
</body>
</html>
