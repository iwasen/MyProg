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
$inc = "../inc";
include("$inc/regist.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist']))
	$_SESSION['ss_regist'] = new reg_fude01;
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
<table width="640" border="0">
	<tr>
		<td colspan="6"><div align="center"><img src="img/title2004.gif" alt="�����ܴ��ե����ڡ���" width="294" height="109"></div></td>
	</tr>
	<tr>
		<td colspan="6">
			<div align="center"><br>
			<span class="gyokan105"><span class="large">���Ӥ��Ԥ������䡢���쥪�Υ��եȤǴ�ñ�ˡʤϤ����䥷����ˡ˥ץ��ȥ����ȤǤ��ޤ��� <br>
			������ˤ�ä��б��Ǥ��ʤ���Τ⤴�����ޤ� </span></span><br>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6"><div align="center"><img src="img/picture1.gif" alt="�ˤ���������ץ�1" width="602" height="148"></div></td>
	</tr>
	<tr>
		<td colspan="6"><div align="center"><img src="img/picture2.gif" alt="�ˤ���������ץ�2" width="492" height="310"></div></td>
	</tr>
	<tr>
		<td colspan=6 align="center"><span class="medium">�ʥ���ץ�ϡ����٤ơ�ɮ�ޤ�Ver.14�פ���Ѥ��Ƥ��ޤ�����</span></td>
	</tr>
</table>
<br><br>
<table width="640" border="0">
	<tr>
		<td colspan="6"><div align="center"><img src="img/mousikomi.gif" alt="������������ˡ" width="423" height="25"></div></td>
	</tr>

	<form name="form1" method="post" action="input_check.php">
	<tr>
		<td colspan="6"><div align="center"><span class="gyokan105"><span class="medium">
			<font color="#FF0000"><b>�����쥪��������åײ���ͤآ�</b></font><br>
			���쥪��������åײ���ֹ�򤪻����Τ����ͤʤ�ɤʤ��Ǥ⤪�������ߤ��������ޤ��� <br>
		</td>
	</tr>
	<tr>
		<td align="center"colspan=6>
			<table border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" bgcolor="#f5ffff" width=350>
				<tr>
					<td align="center" colspan=6><span class="medium"><font color="#444444">
						<font color="#0033cc">���ޤ����쥪��������åײ���Ǥʤ���</font><br>
						������������Ͽ����ǽ�Ǥ�������Ͽ��ǯ�����̵����<br>
						<a href="https://e-shop.fudemame.net/creo/cust/menu.asp" target="_blank">̵�������Ͽ</a><br>
						</font></span>
					</td>
				</tr>
			</table><br>
		</td>
	</tr>
	<tr>
		<td colspan="6"><div align="center"><span class="gyokan105"><span class="medium">
			<font color="#0000FF"><b>�����売����̾�ͤ�������ޤ��� </b></font><br>
			�����Ԥ˥᡼��ǡ֤ˤ������գң̡פ��Ϥ����ޤ��� <br>
			�Ϥ����գң̤򥯥�å����Ƥ�����������Windows�Τ�IE5.0�ʾ��б��� <br><br>
			</span></span></div>
		</td>
	</tr>
	<tr>
		<td width="213"><div align="right"><span class="medium">����ֹ��Ⱦ�ѡ�</span></div></td>
		<td width="27"><div align="right"><span class="medium"></span></div></td>
		<td><input name="df_creo_id" type="text" size=20 maxlength=12 <?=value($reg->df_creo_id)?>></td>
		<td colspan=2><font color="green"><span class="medium">������ֹ��˺�줿���Ϣ�<a href="https://e-shop.fudemame.net/creo/cust/askpass.asp" target="_blank">������</a></spna></font></td>
	</tr>
	<tr>
		<td width="213"><div align="right"><span class="medium">��̾���ʴ�����</span></div></td>
		<td width="27"><div align="right"><span class="medium">(��)</span></div></td>
		<td width="120"><input name="df_name1" type="text" size=20 maxlength=20 <?=value($reg->df_name1)?>></td>
		<td width="36"><div align="center"><span class="medium">(̾)</span></div></td>
		<td width="222"><input name="df_name2" type="text" size=20 maxlength=20 <?=value($reg->df_name2)?>></td>
	</tr>
	<tr>
		<td><div align="right"><span class="medium">��̾���ʥեꥬ�ʡ�</span></div></td>
		<td><div align="right"><span class="medium">(��)</span></div></td>
		<td><input name="df_name1_kana" type="text" size=20 maxlength=20 <?=value($reg->df_name1_kana)?>></td>
		<td><div align="center"><span class="medium">(̾)</span></div></td>
		<td><input name="df_name2_kana" type="text" size=20 maxlength=20 <?=value($reg->df_name2_kana)?>></td>
	</tr>
	<tr>
		<td><div align="right"><span class="large">E-mail���ɥ쥹</span></div></td>
		<td><span class="large"><br></span></td>
		<td colspan=3><input name="df_mail_addr" type="text" size=40 maxlength=100 <?=value($reg->df_mail_addr)?>></td>
	</tr>
	<tr>
		<td colspan="6">
			<div align="center">
			<input type="image" src="img/btn1.gif" border=0 alt="���ϴ�λ" width="107" height="47">
<!--
			<img src="img/spacer.gif" width="50" height="2">
			<input type="image" src="img/btn2.gif" border="0" width="107" height="47" onClick="location.reload()">
-->
			<br>
			<br>
			</div>
		</td>
	</tr>
	</form>
	<tr>
<!--
		<td colspan="6"><div align="center"><img src="img/toousen.gif" alt="���������顪��" width="421" height="23"></div></td>
	</tr>

	<tr>
		<span class="gyokan105"><span class="large">
			<font color="#FF0000">���ǥ�����������Ѱդ��ޤ��礦��</font><br>
			�ǥ����ᡦ�������äǻ��Ƥ�����̿����Ѱդ��������ʺ��硧500��500�ˡ� <br>
			���������ϡ�<font color="#FF0000">JPG/BMP/PNG/GIF</font>�ʤ�ɤ�Ǥ�ϣˢ� <br><br>
			<font color="#0000FF">���������������ߴ��¤Ϣ�</font><br>
			�����᡼�뤬�Ϥ��Ƥ��顢�����ְ���ˤ��������ߤ��������� <br>
			�᡼����λ���գң̤򥯥�å����ơ��ˤ��������������ɤ��Ƥ��������� <br>
			<br><br><br><br>
			���ʤ��μ̿����ɤ�ʴ�ˡ��ܺ٤Ϥ����颭 <br>
			</span>
-->
		<td colspan="6"><div align="center">
			<span class="medium">
			���������������������������������������������������������������� <br>
			���鳨�����ڡ����̳��<br>
			E-mail : <a href="mailto:creo_camp@chrr.jp">creo_camp@chrr.jp</a>
			<br>
			�ĶȻ��� 10:00-12:00, 13:00-17:00�������ˡ��˺���������� <br>
			���������������������������������������������������������������� <br><br></span></span></div>
		</td>
	</tr>
</table>

</div>
</body>
</html>