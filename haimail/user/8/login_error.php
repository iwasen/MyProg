<?
include('../inc/common.php');
include('../inc/member.php');

session_start();
$member = new CMember;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY�����󥨥顼</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="58">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
		<form name="FormName" action="../index.php" method="post">
		<table width="641" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="591" height="36">
					<div align="left">
						<font color="#2f2180"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���<br>
						</b></font><font size="2">
						<hr>
<?
if ($member->err_mail_adr) {
	Writeln("<br>");
	Writeln("���᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("����Ͽ���Ƥ����������᡼�륢�ɥ쥹�򤴳�ǧ����������<br>");
}

if ($member->err_password) {
	Writeln("<br>");
	Writeln("���ѥ���ɤ����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("�����ѥ���ɤ�˺������ϡ֥ѥ���ɤ�˺��Ƥ��ޤä����Ϥ�����פ���<br>");
	Writeln("�������ꤷ���ѥ���ɤ򤴳�ǧ����������<br>");
}
?>
						</font></div>
				</td>
			</tr>
			<tr height="66">
				<td height="66"></td>
				<td width="591" height="66"><br>
					<hr>
					<input type="submit" value="���ᡡ�롡" name="BACK"> </td>
			</tr>
		</table>
		</form>
	</body>

</html>
