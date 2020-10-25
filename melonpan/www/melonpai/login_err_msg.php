<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>エラーメッセージ</TITLE>
<link rel="stylesheet" href="../css/melonpan.css">
</head>
<?php draw_head2(".."); ?>

<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">
<br>
<TABLE width="750" border="0" cellspacing="0" cellpadding="0">
<TR>
<TD valign="top" colspan="3" align="center">
<TABLE width="513" border="0" cellspacing="0" cellpadding="0" bgcolor="#6AB05D">
<TR>
<TD>
<TABLE width="513" border="0" cellspacing="1" cellpadding="5">
<TR bgcolor="#FFFFFF">
<TD><FONT color="#006600">■</font><font color="#003399"><b>メルマガナビ　ログイン</b></font></p>  </FONT>
<p align="reft"><br>
<? melx_login_err($login_err); ?> </p>
<p align="center"> <form>
<input type="button" name="button" value="戻る" onClick="history.back()">
</form>
</p>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
<table>
<br>
<br>
</table>
</TD>
</TR>
<?php draw_footer2(".."); ?>
</TABLE>
</body>
</html>

