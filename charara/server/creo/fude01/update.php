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
	$sql = "SELECT df_name1,df_name2,nd_mail_addr,nd_status,nd_photo_file FROM d_fude01 LEFT JOIN d_fude01_nigaoe ON df_seq_no=fn_seq_no LEFT JOIN t_nigaoe_data ON fn_nigaoe_id=nd_nigaoe_id"
		. " WHERE nd_random_id=" . sql_char($id);
	$result = db_exec($sql);
	if (pg_numrows($result))
		$fetch = pg_fetch_object($result, 0);
	else
		redirect('error.php');
}
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
<font color="#FF0000"><b>����������ǤȤ��������ޤ���</b></font></span></span><br>
<br>
<table width="640" border="0">
	<tr>
		<td><div align="center"><img src="img/step.gif" alt="�ˤ����������Υ��ƥå�" width="604" height="297"></div></td>
	</tr>
</table>
<?
if ($fetch->nd_status <= 2) {
?>
<form enctype="multipart/form-data" action="update_end.php" method="post">
<table width="640" border="0">
	<tr>
		<td width="130" rowspan="4"><br></td>
		<td><span class="large"><b>���̿��ե�����</b></span>
			<span class="small"><font color="#FF33CC"> �ڡֻ��ȡץܥ���򲡤��ơ��̿��ե��������ꤷ�Ʋ�������</font></span></td>
	</tr>

	<!--�ʲ��Υե�����ϥ��ߡ��Ǥ���Ƥ���ޤ�-->
	<tr>
		<td>
			<input name="upload_file" type="file" size=40>
		</td>
	</tr>
	<tr>
		<td><span class="large"><b>���˥å��͡���</b>
			</span><span class="small"><font color="#FF33CC"> �ڼ̿��Υ˥å��͡�������̤����Ϥ��Ʋ�������</font></span></td>
	</tr>
	<tr>
		<td>
			<input name="name_text" type="text" size="50" maxlength=40>
			<input name="sex" type="radio" value="1">��<input name="sex" type="radio" value="2">��
		</td>
	</tr>
</table><br>
<table width="640" border="0">
	<tr>
		<td>
			<div align="right"><img src="img/kakunin.gif" alt="�̿��ϰʲ��ξ����������Ƥ��ޤ�����" width="348" height="203"></div>
		</td>
		<td valign="bottom">
			<input type="image" src="img/btn_ok.gif" alt="�ϣ˥ܥ���" width="107" height="48" border=0><br>
			<font color="#009933" size=2>���̿��ե�����Υ������ˤ�äơ�<br>�������֤������뤳�Ȥ�����ޤ���</font>
		</td>
		<input type="hidden" name="id" <?=value($id)?>>
		<input type="hidden" name="status" <?=value($fetch->nd_status)?>>
		<input type="hidden" name="df_name1" <?=value($fetch->df_name1)?>>
		<input type="hidden" name="df_name2" <?=value($fetch->df_name2)?>>
		<input type="hidden" name="nd_mail_addr" <?=value($fetch->nd_mail_addr)?>>
	</tr>
</table>
</form>
<table width="640" border="0">
	<tr>
		<td colspan="2"><div align="center"><img src="img/bar_ok.gif" width="604" height="50"></div></td>
	</tr>
	<tr>
		<td align="center">
			<table width="540" border="0">
				<tr>
					<td><div align="center"><img src="img/f_normal.jpg" alt="�ϣ˼̿�" width="150" height="150"></div></td>
					<td valign="top"><br>
						<table border=0>
							<tr height=30>
								<td><span class="large">��</span></td>
								<td><span class="large">�餬���̤�����Ƥ���</span></td>
							</tr>
							<tr height=30>
								<td><span class="large">��</span></td>
								<td><span class="large">������������500��500�ʥԥ�����ñ�̡˰̰ʲ��Ǥ���</span></td>
							</tr>
							<tr height=30>
								<td><span class="large">��</span></td>
								<td><span class="large">�餬���̤ˤ��٤����äƤ���</span></td>
							</tr>
							<tr height=30>
								<td><span class="large">��</span></td>
								<td><span class="large">�ʤ�٤�ɽ��Τʤ����</span></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="604" border="0">
	<tr>
		<td colspan="4"><div align="center"><img src="img/bar_ng.gif" width="604" height="50"></div></td>
	</tr>
</table>
<table width="560" border="0">
	<tr>
		<td width="105"><img src="img/f_up.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=15 valign="top">��</td>
					<td width=145><span class="medium">���̤˴餬���꤭�äƤ��ʤ�</span></td>
				</tr>
			</table>
		</td>
		<td width=105><img src="img/f_yoko.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=15 valign="top">��</td>
					<td width=145><span class="medium">�ʤʤ�ʲ���������ˤ�����Ƥ���</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><img src="img/f_light.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=15 valign="top">��</td>
					<td width=145><span class="medium">���������뤹����</span></td>
				</tr>
			</table>
		</td>
		<td><img src="img/f_dark.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=15 valign="top">��</td>
					<td width=145><span class="medium">�������Ť�����</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><img src="img/f_laugh.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=15 valign="top">��</td>
					<td width=145><span class="medium">ɽ�𤬤���</span></td>
				</tr>
			</table>
		</td>
		<td><img src="img/f_two.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=15 valign="top">��</td>
					<td width=145><span class="medium">���Ͱʾ����äƤ���</span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><img src="img/f_far.jpg" width="100" height="100"></td>
		<td width=175>
			<table width=160 border=0>
				<tr>
					<td width=20 valign="top">��</td>
					<td width=140><span class="medium">�餬�󤹤���</span></td>
				</tr>
			</table>
		</td>
		<td colspan="2">�� <span class="medium">�ڥå�������ʪ�ʳ��Ϥ���θ������</span></td>
	</tr>
</table><br><br><br>
<?
} elseif ($fetch->nd_status >= 3 && $fetch->nd_status < 8) {
?>
<table width="640" border="0">
	<tr>
		<td align="center"><font color="red"><b><br><br>���鳨������Ǥ����ڤ��ߤˤ��Ԥ�����������<br><br><br></b></font></td>
	</tr>
</table>
<?
} else {
?>
<table width="640" border="0">
	<tr>
		<td align="center"><font color="red"><b><br><br>���鳨���������ɤǤ��ޤ������Ϥ������᡼���������������<br><br><br></b></font></td>
	</tr>
</table>
<?
}
?>
<table width=640>
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