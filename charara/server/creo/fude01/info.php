<?
/******************************************************
' System :�֤�����Factory�ץ��쥪�����ڡ���
' Content:���ϲ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// ������ID����
$id = $QUERY_STRING;

// �ᥤ��
if (!$id)
	redirect('error.php');
else {
	$sql = "SELECT nd_status FROM t_nigaoe_data WHERE nd_random_id=" . sql_char($id);
	$status = db_fetch1($sql);
	if (!$status)
		redirect('error.php');
	elseif ($status >= 3)
		redirect('update.php?' . $id);
}

?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>���쥪�ˤ����������ڡ��󡧤���������ǤȤ��������ޤ������ʤ����Ѥˤ����äơ�</title>
<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac') != -1)?true:false;
ie=(navigator.appName.charAt(0) == "M")?true:false;
ns=(navigator.appName.charAt(0) == "N")?true:false;

if(mac)	{
	fName="css/mac.css";
}
else{
	if(ie){
	fName="css/win_ie.css";
	}
	else if(ns){
	fName="css/win_ns.css";
	}
}

if(fName != null){
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
function onclick_btn2() {
	alert("���Υ��ƥåפ˿ʤ�ˤϡ���Ʊ�դ���פ򥯥�å����Ʋ�������");
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<br><br>
<span class="xx-large"><font color="#FF0000"><b>�֤����Ѥˤ����äơ�</b></font></span>
<br><br><br>

<table width="500" border="0">
	<tr>
		<td><span class="large">
		<ul type=square>
			<li>����ˤ������ξ������˴ؤ�����ڤ���Ǥ�ϡ����ѼԤ��ܿͤ����Ƥ���Ǥ���餦���򤴾������������ޤ���
		<br><br>
			<li>�ˤ�����������˴ؤ��Ƥϡ�ɬ�����ܿ͡�����Ρˤξ��������Ƥ���������
		<br><br>
			<li>�ץ饤�Х������ݸ�˴ؤ��ơ������ꤤ���������̿��ʤɤξ�����軰�Ԥ˳���������ϰ��ڤ���ޤ���
		<br><br>
			<li>����饯�����������ȡ���̾�ͤʤɤ�����ϤǤ��ޤ���
			<br><br>
			<li>�������줿�ˤ������Ͼ������Ԥε����ʤ����ޤ��Ŀ�Ū���ϰϤ�ۤ��ơ�ʣ�������䤹��ʤɤ���Ū�˻��Ѥ��뤳�ȤϤǤ��ޤ���
		<br>
		</ul>
		</td>
	</tr>
</table>
<table width="450" border="0">
	<tr>
		<td align="right"><span class="large">�ʡ���</span></td>
	</tr>
</table>
<br>
<br>
<form name="form1" action="update.php" method="post">
<table width="300" border="0">
	<tr>
		<td><div align="center"><input name="btn1" type="submit" value=" Ʊ�դ��� "></div></td>
		<td><div align="center"><input name="btn2" type="button" value="Ʊ�դ��ʤ�" onclick="onclick_btn2()"></div></td>
		<input type="hidden" name="id" <?=value($id)?>>
	</tr>
</table>
</form>
</div>
</body>
</html>