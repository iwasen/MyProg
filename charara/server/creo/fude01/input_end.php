<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ܺپ�����Ͽ��λ
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "../inc";
include("$inc/regist.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('index.php');
$reg = &$_SESSION['ss_regist'];

$reg->save_db();

// ���å�����ѿ����
unset($_SESSION['ss_regist']);
session_unregister('ss_regist');

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�֤ˤ������ץ����ڡ����</title>

<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName = "css/mac.css";
}
else	{
	if(ie)	{
	fName = "css/win_ie.css";
	}
	else if(ns)	{
	fName = "css/win_ns.css";
	}
}

if(fName != null)	{
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>



</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br>

<table>
	<tr>
		<td>
			<table border="0" width="400" cellspacing="10">
				<tr>
					<td colspan="3" height="2" bgcolor="#FFCCCC"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
				<tr>
					<td align="center">��������մ�λ���ޤ�����</td>
				</tr>
				<tr>
					<td colspan="3" height="2" bgcolor="#FFCCCC"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
		</td>
	</tr>
<table><br>
<a href="javascript:window.close()"><img src="img/close.gif" border="0" width="125" height="50" alt="���Υ�����ɥ����Ĥ���"></a>
</div>
</body>
</html>