<?
// ctl.php����include���줿�ΤǤʤ���н�λ�ڡ��������Ф�
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}

// ���å������
session_destroy();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {FONT-SIZE: 12pt; COLOR: black}
</style>
<title>���󥱡���</title>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<br>
<br>
<center>
	<input type="button" VALUE=" ��λ " onClick="javascript:parent.window.close()">
<br><br>
</center>
<!-- ��å�����ɽ�� -->
<table border=0 cellpadding=3 cellspacing=2 width="100%">
	<tr>
		<td align=center>
			<font color=red>��������������ޤ������������Ϥ��꤬�Ȥ��������ޤ���</font>
		</td>
	</tr>
</table>

</form>
</body>
</html>
