<?
/******************************************************
' System :�֤�����Factory�ץ��쥪�����ڡ���
' Content:���ϲ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");

if (!$id)
	$id = $QUERY_STRING;

if ($id) {
	$sql = "SELECT df_name1,df_name2,nd_mail_addr,nd_status,nd_photo_file,nd_nigaoe_file,nd_name_text"
		. " FROM d_fude01 LEFT JOIN d_fude01_nigaoe ON df_seq_no=fn_seq_no"
		. " LEFT JOIN t_nigaoe_data ON fn_nigaoe_id=nd_nigaoe_id"
		. " WHERE (nd_status=8 OR nd_status=9) AND nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	if (pg_numrows($result))
		$fetch = pg_fetch_object($result, 0);
	else
		redirect('error.php');
}

// ��������������񤭹���
if ($fetch->nd_status == 8) {
	$sql = "UPDATE t_nigaoe_data SET nd_status=9,nd_download_date='now' WHERE nd_random_id=" . sql_char($id);
	db_exec($sql);
}

// �̿�����URL����
$url_photo = get_system_info('url_photo');

// ���鳨����URL����
$url_nigaoe = get_system_info('url_nigaoe');
?>
<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>���쥪�ˤ����������ڡ��󡧤���������ǤȤ��������ޤ�����</title>
<script language="JavaScript">
<!--
mac=(navigator.appVersion.indexOf('Mac')!=-1)?true:false;
ie=(navigator.appName.charAt(0)=="M")?true:false;
ns=(navigator.appName.charAt(0)=="N")?true:false;

if(mac){
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

if(fName!=null){
	document.write("<link rel=stylesheet href='"+fName+"' type='text/css'>");
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<span class="xx-large"><span class="gyokan125">
<br>
<br>
<b><?=$fetch->df_name1 . " " . $fetch->df_name2?></b>����<br>
<font color="#FF0000"><b>���鳨���������ɤǤ��ޤ���</b></font></span></span><br>
<br>
<table width="640" border="0">
	<tr>
		<td><div align="center"><img src="img/step.gif" alt="�ˤ����������Υ��ƥå�" width="604" height="297"></div></td>
	</tr>
</table>
<br><br>
<hr width=500>
<table width="500" border="0" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=2 align="center"><b>�˥å��͡���</b>�� <?=$fetch->nd_name_text?> ����<br><br></td>
	</tr>
	<tr>
		<td align="center" width=50%>�ڼ̿���</td>
		<td align="center" width=50%>�ڤˤ�������</td>
	</td>
	<tr>
		<td align="center" width=50%><img src="<?=$url_photo?><?=$fetch->nd_photo_file?>" width=180 border=1 alt="��̿�"></td>
		<td align="center" width=50%><img src="<?=$url_nigaoe?><?=$fetch->nd_nigaoe_file?>" width=180 height=180 border=1 alt="���鳨"></td>
	</tr>
	<tr><td><br></td></tr>
</table>
<hr width=500>
<br><br>
<table width="500" border="1" cellpadding=3 cellspacing=1 bordercolordark="#3399ff" bordercolor="#99ccff">
	<tr>
		<td align="center">
			<table width=97% height=90%>
				<tr>
					<td colspan=2><span class="medium">���� ��������ɤλ��� ��</span></td>
				</tr>
				<tr>
					<td valign="top"><span class="medium">��1. </span></td>
					<td><span class="medium">�ڤˤ������ۤβ�����ǥޥ����α��ܥ���򥯥�å�����ȡ��ݥåץ��åץ�˥塼��<br>�Ǥޤ���</span></td>
				</tr>
				<tr>
					<td valign="top"><span class="medium">��2. </span></td>
					<td><span class="medium">��˥塼����Ρ�̾����Ĥ��Ʋ�������¸�פ����ӡ���������̾����Ĥ�����¸����<br>��������</span></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br>
<table width=500>
	<tr>
		<td><div align="center">
			<span class="medium"><font color="#009933">
			���������������������������������������������������������������� </font><br>
			���鳨�����ڡ����̳��<br>
			E-mail : <a href="mailto:creo_camp@chrr.jp">creo_camp@chrr.jp</a>
			<br>
			�ĶȻ��� 10:00-12:00, 13:00-17:00�������ˡ��˺���������� <br>
			<font color="#009933">���������������������������������������������������������������� </font><br><br></span></span></div>
		</td>
	</tr>
</table>

</div>
</body>
</html>