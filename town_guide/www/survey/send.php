<?
// ctl.php����include���줿�ΤǤʤ���н�λ�ڡ��������Ф�
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {FONT-SIZE: 12pt; COLOR: black}
</style>
<title>���󥱡���</title>
<script type="text/javascript"></script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- ���� -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">������ͤǤ�����[����]�ܥ���򲡤��ơ������ǡ������������Ʋ�������<br>�ɤ��⤢�꤬�Ȥ��������ޤ�����<br><br></td>
	</tr>
</table>
<!-- ����� -->
<br>
<br>
<center>
	<input type="submit" value=" ���� ">
</center>

<input type="hidden" name="question_no" value="<?=$question_no?>">
</form>
</body>
</html>
