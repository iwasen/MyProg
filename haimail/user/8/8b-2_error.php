<?
include('../inc/common.php');
include('../inc/shoukai.php');

session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$shoukai = new CShoukai;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MYͧã�Ҳ𥨥顼</title>
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
		<form name="FormName" action="8b-2.php" method="post">
		<table width="641" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="591" height="36">
					<div align="left">
						<font color="#2f2180"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���<br>
						</b></font><font size="2">
						<hr>
<?
if ($shoukai->err_nickname) {
	Writeln("<br>");
	Writeln("�����ʤ��Τ�̾���ޤ��ϥ˥å��͡��ब���Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("�����ʤ�����Υ�å��������Ȥ狼��褦�ˡ����ʤ��Τ�̾���ޤ��ϥ˥å��͡�������Ϥ��Ƥ���������<br>");
}

if ($shoukai->err_tomodachi_adr) {
	Writeln("<br>");
	Writeln("����ͧã�Υ᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("���᡼����Ϥ����뤿��ˤ���ǧ����������<br>");
}

if ($shoukai->err_message) {
	Writeln("<br>");
	Writeln("����ͧã�������å����������Ϥ��Ƥ���������");
}

if ($shoukai->err_message_len) {
	Writeln("<br>");
	Writeln("����ͧã�������å�����������200ʸ������Ǥ��ꤤ���ޤ���");
}
?>
					</font></div>
				</td>
			</tr>
			<tr height="66">
				<td height="66"></td>
				<td width="591" height="66"><br>
					<br>
					<hr>
					<input type="submit" value="���ᡡ�롡" name="BACK"> </td>
			</tr>
		</table>
		</form>
	</body>

</html>
