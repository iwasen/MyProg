<?
//=== �ᥤ����� ===
include('../inc/common.php');
include('../inc/haimail_letter.php');

session_start();
$h_letter = new CHaimailLetter;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>�ӥ��ͥ������ѥ��顼</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table cellspacing="0" cellpadding="0" border="0" height="58" width="691">
	<tr height="48">

		<td width="691" bgcolor="white" align="left" valign="top" height="49"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">

		<td width="691" height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form name="FormName" action="index.php" method="post">
		<br>
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td height="36" align="right" valign="top" width="50"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="550" height="36">
					<div align="left">
						<font color="#2f2180"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���<br>
						</b></font><font size="2">
						<hr>
<?
if ($h_letter->err_mail_adr) {
	Writeln("<br>");
	Writeln("���᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("���᡼����Ϥ����뤿��ˡ�����ǧ����������<br>");
}

if ($h_letter->err_name) {
	Writeln("<br>");
	Writeln("����̾�������������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
}

if ($h_letter->err_company) {
	Writeln("<br>");
	Writeln("�����̾�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
}
?>
					</font></div>
				</td>
			</tr>
			<tr>
				<td width="50">��</td>
				<td width="550"><br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<hr>
					<input type="submit" value="���ᡡ�롡" name="BACK"></td>
			</tr>
		</table>��
		</form>
	</body>

</html>
