<?
include('../inc/common.php');
include('../inc/member.php');

session_start();
if (!$_SESSION['login'])
	redirect('../index.php');

$member = new CMember;
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title>MY��Ͽ���󥨥顼</title>
	</head>

	<body bgcolor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table cellspacing="0" cellpadding="0" border="0" height="52">
			<tr height="48">
				<td bgcolor="white" align="left" valign="top" height="48"><img height="48" width="150" src="../img/logo1.gif" alt="�Ϥ��᡼�롦net"><img height="48" width="482" src="../img/logo2.gif" alt="interactive communication network"></td>
			</tr>
			<tr height="16">
				<td height="16" align="left" valign="top"><img height="3" width="642" src="../img/logo_line.gif"></td>
			</tr>
		</table><br>
		<form name="FormName" action="8a-2.php" method="post">
		<table width="604" border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr>
				<td width="50" align="right" valign="top"><img height="16" width="50" src="../img/space.gif"></td>
				<td width="561" align="left" valign="top"><font color="#2f2180"><b>���������Ϥ���Ƥ��ʤ����ܤ�����褦�Ǥ���
					<hr>
					</b></font><font size="2">
<?
if ($member->err_kyoumi_genre) {
	Writeln("<br>");
	Writeln("���Ϥ��᡼�롦net�Ǥϡ����ʤ��ζ�̣�٤ˤ��ä��᡼����Ϥ����ޤ���<br>");
	Writeln("�����ʤ��Τ��긵�˥᡼����Ϥ����뤿��ˡ���̣������򣱤İʾ�����å����Ƥ���������<br>");
}

if ($member->err_mail_adr) {
	Writeln("<br>");
	Writeln("���᡼�륢�ɥ쥹�����������Ϥ���Ƥ��ʤ��褦�Ǥ���<br>");
	Writeln("���᡼����Ϥ����뤿��ˡ�����ǧ����������<br>");
}

if ($member->err_mail_adr2) {
	Writeln("<br>");
	Writeln("�����Ϥ��줿�᡼�륢�ɥ쥹�Ϥ��Ǥ˻Ȥ��Ƥ��ޤ���<br>");
	Writeln("���ۤ��Υ᡼�륢�ɥ쥹�򤪻Ȥ�����������<br>");
}

if ($member->err_password) {
	Writeln("<br>");
	Writeln("���ѥ���ɤ����������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($member->err_seibetsu) {
	Writeln("<br>");
	Writeln("�����ʤ������̤򶵤��Ƥ��������� <br>");
}

if ($member->err_mikikon) {
	Writeln("<br>");
	Writeln("�����ʤ��Ϥ��뺧����Ƥ��ޤ�����̤�����ι��ܤǶ����Ƥ��������� <br>");
}

if ($member->err_seinengappi) {
	Writeln("<br>");
	Writeln("����ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($member->err_zip_kyojuu) {
	Writeln("<br>");
	Writeln("���ｻ��͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($member->err_zip_kinmu) {
	Writeln("<br>");
	Writeln("����̳��͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ�������ǧ���������� <br>");
}

if ($member->err_shokugyou) {
	Writeln("<br>");
	Writeln("�����ʤ��ο��ȤˤĤ��ƶ����Ƥ��������� <br>");
}

if ($member->err_html_mail) {
	Writeln("<br>");
	Writeln("��HTML�᡼��������˾���ޤ����������Ƥ���������<br>");
}

if ($member->err_oshirase_mail) {
	Writeln("<br>");
	Writeln("����ȯ����������ԡפ���礹���᡼��򤪼������ˤʤ�ޤ�����<br>");
	Writeln("�������Ƥ���������");
}
?>
				</font></td>
			</tr>
			<tr>
				<td width="50"></td>
				<td width="561"><br>
					<br>
					<hr>
					<input type="submit" value="���ᡡ�롡" name="BACK"> </td>
			</tr>
		</table>
		</form>
	</body>

</html>
