<?
include('../inc/common.php');
include('../inc/database.php');

$member_id = $id;
if ($member_id != '') {
	$sql = "SELECT MM_zip_kyojuu FROM T_MEMBER WHERE MM_member_id='member_id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if (!$ds->EOF())
		$zip_kyojuu = $ds->GetValue('MM_zip_kyojuu');
}
?>
<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
		<title></title>
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
		<br>
		<table border="0" cellspacing="0" mm_noconvert="TRUE" cellpadding="0">
			<tr height="36">

		<td width="50" height="36"><img src="http://www.info-cash.com/cgi-bin/ictrap/trapchk.cgi?UID=045D582307&PID=<?=$id?>" height="1" width="1"><img height="16" width="50" src="../img/space.gif"></td>
									 <img src="https://admin.ad4commerce.com/e/wfon.pl?m=40&amount=1&count=1&name=<?=$zip_kyojuu?>&email=<?=$member_id?>&option=" width="1" height="1">
				<td width="550" height="36" valign="top"><font color="#2f2180"><b>�Ϥ��᡼�롦net�ؤΤ���Ͽ�����꤬�Ȥ��������ޤ�����<br>
					�������Ͽ�Ϥ��٤ƴ�λ�Ǥ���
					<hr>
					</b></font><font size="2"><br>
					���Ȥϡ֤Ϥ��᡼��פ��Ϥ��Τ�ڤ��ߤˤ��Ԥ�����������<br>
					<br>
					�ޤ����ͣ�P����ʤ��ʤ����ѤΥڡ����ˤǤϡ���Ͽ������ѹ���<br>
					��ͧã�˾Ҳ�᡼�������ʤɤε�ǽ�򤤤����Ѱդ��Ƥ���ޤ���<br>
					���Ҥ����Ѥ���������<br>
					<br>
					�ͣ��У���ؤϡ��ԣϣФ���᡼�륢�ɥ쥹�ȥѥ���ɤ����Ϥ���ȥ�����Ǥ��ޤ���<br>
					<br>
					</font><center><a href="../index.php"><img height="17" width="80" src="../img/home_b.gif" border="0" alt="�ۡ�������"></a><br></center>

			<p><br>
				<br>
				<br>
				<b><br>
				<font size="2">�Ϥ��᡼�롦net�Ǥϡ����ߡ�<font color="#333399">�Хʡ��ǾҲ�</font>�ץ����ڡ����»ܤ��Ƥ��ޤ���<br>
				���������ߤϣͣ��У���ˤƼ����դ��桪</font></b><br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br><img src=http://tool.cafesta.com/gsp_page_end.cgi?c_code=gsp_0015  border=0  width=1  height=1>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			</p>
			</td></tr>
			<tr height="36">
				<td width="50" height="36"></td>

		<td colspan="2" align="center" valign="top"><font size="1">
			<hr width="100%" size="1">
			�֤Ϥ��᡼�롦net�פϳ�����ҡ����������������Ĥ��Ƥ��ޤ���<br>
			Copyright (C) 2002-2002 xx xxxxx, Inc. All rights reserved. <br>
			���Ķ��ϡ�������ҥ���ץ쥹<br>
			<br>
			<a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"target="top"> </a></font>
			<table width="200" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100">
						<div align="center"><a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html"><img src="../img/xxxxxxx_logo_i.gif" width="43" height="46" border="0" alt="xx xxxxx"></a></div>
					</td>
					<td width="100">
						<div align="center"><a href="http://www.impress.co.jp/"><img src="../img/impress_logo_i.gif" width="53" height="39" border="0" alt="impress"></a></div>
					</td>
				</tr>
			</table>
			<font size="1"><br>
			<br>
			</font></td>
			</tr>
		</table>
	</body>

</html>
