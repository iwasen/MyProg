<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ȥ���Υ��ߥ�졼�����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/sim_db.php");

// �ѥ�᡼���ǥ��ͥ륮��ʬ��������ä��饻�å�������¸
if ($eng_bunrui)
	$_SESSION['s_eng_bunrui'] = $eng_bunrui;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>�ȥ���ξ��ʾܺ١�</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- �迴submit�ǡ��� -->
<?
if ($f == 1) {
?>
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_TOILET_TSUUDEN)?>>
	<input type="hidden" name="energy" <?=value(196)?>>
	<input type="hidden" name="cost" <?=value(5720)?>>
	<input type="hidden" name="co2" <?=value(43)?>>
</form>
<?
} else {
?>
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_TOILET_SESSUI)?>>
	<input type="hidden" name="energy" <?=value(27)?>>
	<input type="hidden" name="cost" <?=value(800)?>>
	<input type="hidden" name="co2" <?=value(6)?>>
</form>
<?
}
?>

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- �����ȥ���ʬ -->
			<br>
			<img src="img/shindan/title/toilet_01.gif"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<font color="red">�ȥ���Ǥβ��������غ����Ѥβ�������֤ǡ����ͥ륮�������̤��ۤʤ�ޤ���</font><br><br>

			�ȥ���Ǥβ��������غ����Ѿ��ˤ�륨�ͥ륮�������̤����
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">������ˡ</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">ǯ��<font color="black">����<br>���ͥ륮��</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">ǯ��<font color="black">����</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">ǯ��<br>�����ú���ӽ���</td>
				</tr>

				<tr>
					<td align="center" valign="middle"><img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">����������ѡ�</td>
					<td align="center" valign="middle" width="100">261Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 7,450��
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0">��200��
					</td>
					<td align="center" valign="middle" width="135">57kg��CO<sub>2</sub></td>
				</tr>
<?
if ($f == 1) {
?>
				<tr>
					<td align="center" valign="middle"><img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">���������ѡ�<br>�������������Τ����š�</td>
					<td align="center" valign="middle" width="100">65Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 1,860��
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0">����70��
					</td>
					<td align="center" valign="middle" width="135">14kg��CO<sub>2</sub></td>
				</tr>
<?
} else {
?>
				<tr>
					<td align="center" valign="middle"><img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">��巿�غ¡�<br>���������ѡ�<br>�������������Τ����š�</td>
					<td align="center" valign="middle" width="100">38Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 1,100��
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0">����30��
					</td>
					<td align="center" valign="middle" width="135">8kg��CO<sub>2</sub></td>
				</tr>
<?
}
?>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">���</td>
					<td class="tc0">���ؤȻҤɤ���Ͳ�²�оݡ�</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">����ϡ��ŵ�28.6��/Mcal����ƻ116.6��/m<sup>3</sup>�Ƿ׻���</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">���񥨥ͥ륮���ϡ��������ȯ��������ʬ�򵭺ܡ�</td>
				</tr>

				<tr>
					<td></td>
					<td class="tc0">�����ú�Ǥ��ӽз����ϡ��Ķ��ȷ���Ǻ��Ѥ���Ƥ����ͤ���ѡ�</td>
				</tr>

				<tr>
					<td colspan="2" class="tc0">����ˡ����λ�Ϥ����ޤǤ⡢����Ĵ���������줿��̤˴𤤤Ƥ��ꡢ<br>����ˡ�ˤ��ƶ����������Ȥ������Ȥ�λ�򤯤�������</td>
				</tr>
			</table>
			<br><br>
<?
if ($f == 1) {
?>
			<table border=0 cellspacing=2 cellpadding=0>
				<tr>
					<td align="center"><font color="#ff6600"><b>��������Хꥹ�Ȥ���Ͽ����ݤ��ܰ¡��ʾ嵭��ɸ���͡ݿ侩�͢�︺���̡ˡ���</b></font></td>
				</tr>
				<tr>
					<td align="center" class="tc0"><font color="orange">�ʤ��Υ��ɥХ������ܤˤϸ��ߡ����̿��ǵ�ǽ���ʤ����ᡢ�ʲ��γ����ͤǥ���Хꥹ�Ȥ���Ͽ����ޤ���</font></td>
				</tr>
				<tr>
					<td align="center">
						<table border="1" bordercolor="#cccccc" cellpadding="0" cellspacing="2" width="550">
							<tr>
								<td bgcolor="#ff8846" align="center" width="260">���ͥ륮��(Mcal/ǯ��</td>
								<td align="center" bgcolor="#ffff67" width="95">����ʡ�/ǯ��</td>
								<td bgcolor="#99ccff" align="center" width="195">�����ú���ӽ���(kg/ǯ)</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ffe397" width="260">196</td>
								<td align="center" bgcolor="#ffffc2" width="95">5,720</td>
								<td align="center" bgcolor="#deffff" width="195">43</td>
							</tr>
						</table><br>
					</td>
				</tr>
				<tr>
					<td align="center">����Хꥹ�Ȥ��������</td>
				</tr>
				<tr>
					<td align="center">��</td>
				</tr>
				<tr>
					<td align="center"><b>�ڲ��������غ¤����Ż��֤򸺤餽������</b><br><br></b></td>
				</tr>
			</table>
<?
} else {
?>
			<table border=0 cellspacing=2 cellpadding=0>
				<tr>
					<td align="center"><font color="#ff6600"><b>��������Хꥹ�Ȥ���Ͽ����ݤ��ܰ¡��ʾ嵭��ɸ���͡ݿ侩�͢�︺���̡ˡ���</b></font></td>
				</tr>
				<tr>
					<td align="center" class="tc0"><font color="orange">�ʤ��Υ��ɥХ������ܤˤϸ��ߡ����̿��ǵ�ǽ���ʤ����ᡢ�ʲ��γ����ͤǥ���Хꥹ�Ȥ���Ͽ����ޤ���</font></td>
				</tr>
				<tr>
					<td align="center">
						<table border="1" bordercolor="#cccccc" cellpadding="0" cellspacing="2" width="550">
							<tr>
								<td bgcolor="#ff8846" align="center" width="260">���ͥ륮��(Mcal/ǯ��</td>
								<td align="center" bgcolor="#ffff67" width="95">����ʡ�/ǯ��</td>
								<td bgcolor="#99ccff" align="center" width="195">�����ú���ӽ���(kg/ǯ)</td>
							</tr>
							<tr>
								<td align="center" bgcolor="#ffe397" width="260">27</td>
								<td align="center" bgcolor="#ffffc2" width="95">800</td>
								<td align="center" bgcolor="#deffff" width="195">6</td>
							</tr>
						</table><br>
					</td>
				</tr>
				<tr>
					<td align="center">����Хꥹ�Ȥ��������</td>
				</tr>
				<tr>
					<td align="center">��</td>
				</tr>
				<tr>
					<td align="center"><b>�ں��ٻȤ��ʤ��ش����ڷ�������巿�ˡ���</b><br><br></b></td>
				</tr>
			</table>
<?
}
?>
			<table>
				<tr>
					<td><a href="javascript:history.back()"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a></td>
					<td><a href="javascript:document.form1.submit()"><img src="img/shindan/sim_res/button_ganbalist.gif" border="0" width="170" height="36"></td>
				</tr>
			</table>
			<!-- �����ޤ� -->
		</td>
		<td width="35" align="right" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_2.gif" width="35" height="35">
		</td>
	</tr>

	<tr>
		<td width="35" valign="bottom"><img src="img/kado_3.gif" width="35" height="35"></td>
		<td><br></td>
		<td width="35" valign="bottom"><img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>
<!-- ��ݳѥơ��֥뽪λ -->

</div>
</body>
</html>