<?
include('../inc/common.php');
include('../inc/reginfo.php');

session_start();
$reginfo = new CRegInfo;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>�Ŀ;��󡦤褯�Ԥ���Ź����Ͽ���顼</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table>
		<form name="FormName" action="1-2.php" method="post">
		<br>
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">
				<td width="50" height="36" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="561" height="36">
					<div align="left">
						<font color="#2f2180" size="2"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���<br>
						</b></font><font size="2">
						<hr>
<?
if ($reginfo->err_mail_adr) {
	Writeln("<br>");
	Writeln("���᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("���᡼����Ϥ����뤿��ˡ�����ǧ����������<br>");
}

if ($reginfo->err_already_regist) {
	Writeln("<br>");
	Writeln("�����Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ���Ͽ�ѤߤǤ���<br>");
}

if ($reginfo->err_password) {
	Writeln("<br>");
	Writeln("���ѥ���ɤ����������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($reginfo->err_seibetsu) {
	Writeln("<br>");
	Writeln("�����ʤ������̤򶵤��Ƥ��������� <br>");
}

if ($reginfo->err_mikikon) {
	Writeln("<br>");
	Writeln("�����ʤ��Ϥ��뺧����Ƥ��ޤ�����̤�����ι��ܤǶ����Ƥ��������� <br>");
}

if ($reginfo->err_seinengappi) {
	Writeln("<br>");
	Writeln("����ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}


if ($reginfo->err_zip_kyojuu) {
	Writeln("<br>");
	Writeln("���ｻ��͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($reginfo->err_zip_kinmu) {
	Writeln("<br>");
	Writeln("����̳��͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($reginfo->err_shokugyou) {
	Writeln("<br>");
	Writeln("�����ʤ��ο��ȤˤĤ��ƶ����Ƥ��������� <br>");
}

if ($reginfo->err_html_mail) {
	Writeln("<br>");
	Writeln("��HTML�᡼��������˾���ޤ����������Ƥ���������<br>");
}

if ($reginfo->err_oshirase_mail) {
	Writeln("<br>");
	Writeln("����ȯ����������ԡפ���礹���᡼��򤪼������ˤʤ�ޤ�����<br>");
	Writeln("�������Ƥ���������");
}
?>
					</font></div>
				</td>
			</tr>
			<tr height="36">
				<td width="50" height="36">��</td>
				<td width="561" height="36"><br>
					<br>
					<hr>
					<input type="submit" value="���ᡡ�롡" name="BACK"></td>
			</tr>
		</table>
		</form>
	</body>

</html>
