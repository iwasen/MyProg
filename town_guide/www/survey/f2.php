<?
// ctl.php����include���줿�ΤǤʤ���н�λ�ڡ��������Ф�
if (!isset($enquete)) {
	header("location: end.html");
	exit;
}

$qno = mb_convert_kana($question_no, 'N');
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
.font-qa {FONT-SIZE: 12pt; COLOR: black}
</style>
<title>���󥱡���</title>
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	var chk = false;
	for (var i = 0; i < f.sa1.length; i++) {
		if (f.sa1[i].checked) {
			chk = true;
			break;
		}
	}
	if (!chk) {
		alert("��<?=$qno?>���椫��ҤȤĤ����Ӥ���������");
		f.sa1[0].focus();
		return;
	}
	f.submit();
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name=form1 method=post action="ctl.php">
<!-- ���� -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">����<?=$qno?>��<br>
&nbsp;&nbsp;&nbsp;<a href="images/tg-160.JPG" target="_blank">������</a>�򥯥�å����ơ������������������ʤ�������ĺ���Ƥ�������ϡڥ����󥬥��ɡۤ�ɽ�楤�᡼���Ǥ���<br>
&nbsp;&nbsp;&nbsp;�����ޤ����ϰ�ˤ�ꡢɽ�椬�ۤʤ���⤢��ޤ��Τǡ�����դ���������<br><br>
&nbsp;&nbsp;&nbsp;���ʤ��ϡ������󥬥��ɤ�ɤΤ��餤�����٤ǻ��Ѥ��ޤ�����<br>
&nbsp;&nbsp;&nbsp;���ƤϤޤ��Τ�ҤȤĤ��������Ӥ���������<br></td>
	</tr>
</TABLE>
<!-- ����� -->
<br>

<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">��</td>
		<td class="font-qa" align="left">
			<nobr><input type="radio" name="sa1" value=1>������ʾ�</nobr><br>
			<nobr><input type="radio" name="sa1" value=2>�����֤ˣ���ʾ�</nobr><br>
			<nobr><input type="radio" name="sa1" value=3>������ˣ���ʾ�</nobr><br>
			<nobr><input type="radio" name="sa1" value=4>������ˣ���ʾ�</nobr><br>
			<nobr><input type="radio" name="sa1" value=5>Ⱦǯ�ˣ���ʾ�</nobr><br>
			<nobr><input type="radio" name="sa1" value=6>��ǯ�ˣ���ʾ�</nobr><br>
			<nobr><input type="radio" name="sa1" value=7>��ǯ�ˣ���̤��</nobr><br>
			<nobr><input type="radio" name="sa1" value=8>�����󥬥��ɤ��Τ�ʤ�</nobr><br>
		</td>
	</tr>
</table>

<center>
	<input type="button" value="���μ���" onclick="next()">
</center>

<input type="hidden" name="question_no" value="<?=$question_no?>">
</form>
</body>
</html>
