<?
/******************************************************
' System :�֤�����Factory�ץ��쥪�����ڡ���
' Content:���ϲ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "../inc";
include("$inc/regist.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	redirect('index.php');
$reg = &$_SESSION['ss_regist'];
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�֤ˤ������ץ����ڡ����</title>

<script language="JavaScript">
<!--
history.go( +1 );

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
					<td colspan="3" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
				<tr>
					<td width=30></td>
					<td width=120>����ֹ�</td>
					<td width=250>��&nbsp;<?=htmlspecialchars($reg->df_creo_id)?></td>
				</tr>
				<tr>
					<td width=30></td>
					<td width=120>��̾��</td>
					<td width=250>��&nbsp;<?=htmlspecialchars($reg->df_name1)?>&nbsp;<?=htmlspecialchars($reg->df_name2)?>&nbsp;��</td>
				</tr>
				<tr>
					<td></td>
					<td>�եꥬ��</td>
					<td>��&nbsp;<?=htmlspecialchars($reg->df_name1_kana)?>&nbsp;<?=htmlspecialchars($reg->df_name2_kana)?></td>
				</tr>
				<tr>
					<td></td>
					<td>�᡼�륢�ɥ쥹</td>
					<td>��&nbsp;<?=htmlspecialchars($reg->df_mail_addr)?></td>
				</tr>
				<tr>
					<td colspan="3" height="2" bgcolor="#CCFF33"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<input type="image" src="img/back.gif" onclick="history.back()" width=106 height=46 alt="���">
			<a href="input_end.php"><img src="img/send.gif" border=0 width=106 height=46 alt="����"></a>
		</td>
	</tr>
<table>
</div>
</body>
</html>