<?php 
include ( "../sub/head.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<TITLE>���顼��å�����</TITLE>
<link rel="stylesheet" href="../css/melonpan.css">
</head>
<?php	draw_head( ".." );	?>
<BODY bgcolor="#FFFFFF" text="#666666" link="#336600" vlink="#666600" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">
<TABLE width="750" border="0" cellspacing="0" cellpadding="0">
<TR>
<TD valign="top" colspan="3" align="center">
<TABLE width="513" border="0" cellspacing="0" cellpadding="0" bgcolor="#6AB05D">
<TR>
<TD>
<TABLE width="513" border="0" cellspacing="1" cellpadding="5">
<TR bgcolor="#FFFFFF">
<TD><FONT color="#006600"><B>��<? echo $p_titl; ?> </B> </FONT>
<p align="reft"><br>
<br>
<? echo $print_msg; ?> </p>
<p align="center"> <form>
<input type="button" name="button" value="���" onClick="history.back()">
</form>
</p>
</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</TD>
</TR>
<?php draw_footer( ".." ); ?>
</TABLE>
</body>
</html>
