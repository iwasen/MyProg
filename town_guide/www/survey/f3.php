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
<script type="text/javascript">
<!--
function next() {
	var f = document.form1;
	if (f.f1.selectedIndex == 0) {
		alert("�����ޤ�����ƻ�ܸ������򤵤�Ƥ��ʤ��褦�Ǥ���");
		f.f1.focus();
		return;
	}
	if (!sel_chk(f.f2)) {
		alert("���̤����򤵤�Ƥ��ʤ��褦�Ǥ���");
		f.f2[0].focus();
		return;
	}
	if (f.f3.value == "") {
		alert("ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.f3.focus();
		return;
	}
	if (!num_chk(f.f3.value)) {
		alert("ǯ���Ⱦ�ѿ��������Ϥ��Ƥ���������");
		f.f3.focus();
		return;
	}
	if (!f.f4[0].checked && !f.f4[1].checked) {
		alert("̤���������򤵤�Ƥ��ʤ��褦�Ǥ���");
		f.f4[0].focus();
		return;
	}
	if (!sel_chk(f.f5)) {
		alert("���Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���");
		f.f5[0].focus();
		return;
	}
	if (f.f6.value == "") {
		alert("����Ͽ�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.f6.focus();
		return;
	}
	if (!mail_chk(f.f6.value)) {
		alert("����Ͽ�᡼�륢�ɥ쥹���������ʤ��褦�Ǥ���");
		f.f6.focus();
		return;
	}
	if (f.f7.value == "") {
		alert("�����ѣɣĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���");
		f.f7.focus();
		return;
	}
	f.submit();
}
function num_chk(v) {
	for (var i = 0; i < v.length; i++) {
		if (v.charAt(i) < "0" || v.charAt(i) > "9")
			return false;
	}
	return true;
}
function sel_chk(c) {
	for (var i = 0; i < c.length; i++) {
		if (c[i].checked)
			return true;
	}
	return false;
}
function mail_chk(m) {
	return m.match("^[a-zA-Z0-9._/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$") != null;
}
//-->
</script>
</head>
<body text="#000000" bgcolor="#ffffee" link="#ff0000" vlink="#ff0000">
<form name="form1" method="post" action="ctl.php">
<!-- ���� -->
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td class="font-qa" align="left">�ڤ������顢���ʤ����ȤΤ��ȤˤĤ��Ƥ��Ǥ����ޤ���<br></td>
	</tr>
</TABLE>
<!-- ����� -->
<br>
<table border=0 cellpadding=3 cellspacing=2 width="95%">
	<tr>
		<td width="5%">��</td>
		<td class="font-qa" align="left">
			<table border=0 cellpadding=3 cellspacing=2 width="90%">
				<tr>
					<td valign=top>
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ���<br>�����ޤ�����ƻ�ܸ������Ӥ���������</td></tr>
								<tr><td class="font-qa">
									<select name="f1">
									<option value=0>��������ꤪ���Ӥ���������</option>
									<option value=1>�̳�ƻ</option>
									<option value=2>�Ŀ���</option>
									<option value=3>��긩</option>
									<option value=4>�ܾ븩</option>
									<option value=5>���ĸ�</option>
									<option value=6>������</option>
									<option value=7>ʡ�縩</option>
									<option value=8>��븩</option>
									<option value=9>���ڸ�</option>
									<option value=10>���ϸ�</option>
									<option value=11>��̸�</option>
									<option value=12>���ո�</option>
									<option value=13>�����</option>
									<option value=14>�����</option>
									<option value=15>���㸩</option>
									<option value=16>�ٻ���</option>
									<option value=17>���</option>
									<option value=18>ʡ�温</option>
									<option value=19>������</option>
									<option value=20>Ĺ�</option>
									<option value=21>���츩</option>
									<option value=22>�Ų���</option>
									<option value=23>���θ�</option>
									<option value=24>���Ÿ�</option>
									<option value=25>���츩</option>
									<option value=26>������</option>
									<option value=27>�����</option>
									<option value=28>ʼ�˸�</option>
									<option value=29>���ɸ�</option>
									<option value=30>�²λ���</option>
									<option value=31>Ļ�踩</option>
									<option value=32>�纬��</option>
									<option value=33>������</option>
									<option value=34>���縩</option>
									<option value=35>������</option>
									<option value=36>���縩</option>
									<option value=37>���</option>
									<option value=38>��ɲ��</option>
									<option value=39>���θ�</option>
									<option value=40>ʡ����</option>
									<option value=41>���츩</option>
									<option value=42>Ĺ�긩</option>
									<option value=43>���ܸ�</option>
									<option value=44>��ʬ��</option>
									<option value=45>�ܺ긩</option>
									<option value=46>�����縩</option>
									<option value=47>���츩</option>
									</select>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ�������</td></tr>
								<tr><td class="font-qa">
									<nobr><input type="radio" name="f2" value=1>��</nobr><br>
									<nobr><input type="radio" name="f2" value=2>��</nobr><br>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ���ǯ���Ⱦ�ѿ����ǡ�</td></tr>
								<tr><td class="font-qa">
									<input type="text" name="f3" size=4 maxlength=3>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ���̤����</td></tr>
								<tr><td class="font-qa">
									<nobr><input type="radio" name="f4" value=1>̤��</nobr><br>
									<nobr><input type="radio" name="f4" value=2>����</nobr><br>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ�������</td></tr>
								<tr><td class="font-qa">
									<nobr><input type="radio" name="f5" value=1>��Ұ�</nobr><br>
									<nobr><input type="radio" name="f5" value=2>��ȼ��ء�����</nobr><br>
									<nobr><input type="radio" name="f5" value=3>����</nobr><br>
									<nobr><input type="radio" name="f5" value=4>�ѡ��ȡ�����Х���</nobr><br>
									<nobr><input type="radio" name="f5" value=5>���Ķ�</nobr><br>
									<nobr><input type="radio" name="f5" value=6>��̳��</nobr><br>
									<nobr><input type="radio" name="f5" value=7>���翦��SOHO���ǥ����ʡ������ꥨ����������</nobr><br>
									<nobr><input type="radio" name="f5" value=8>���翦���۸�Ρ���ա���׻�����</nobr><br>
									<nobr><input type="radio" name="f5" value=9>��ҷбġ����</nobr><br>
									<nobr><input type="radio" name="f5" value=10>����</nobr><br>
									<nobr><input type="radio" name="f5" value=11>̵��</nobr><br>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ�������Ͽ�᡼�륢�ɥ쥹</td></tr>
								<tr><td class="font-qa">
									<input type="text" name="f6" size=50>
								</td></tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<table border=0 cellpadding=3 cellspacing=2 width="90%">
								<tr><td class="font-qa">���ƣ��������ѣɣġʤ�����᡼��˵��ܤ���Ƥ���ޤ���</td></tr>
								<tr><td class="font-qa">
									<input type="text" name="f7" size=50>
								</td></tr>
						</table>
					</td>
				</tr>
			</table>
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
