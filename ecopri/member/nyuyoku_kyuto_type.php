<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����Υ��ߥ�졼�����
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
<title>�����μ���ˤ��㤤�ʾܺ١�</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- �迴submit�ǡ��� -->
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_KYUUTOUKI)?>>
	<input type="hidden" name="energy" <?=value(363)?>>
	<input type="hidden" name="cost" <?=value(4900)?>>
	<input type="hidden" name="co2" <?=value(78)?>>
</form>

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<!-- �����ȥ���ʬ -->
			<br>
			<img src="img/shindan/title/kyutoki_01.gif"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<font color="red">������Ǯ���μ���ˤ�äơ�Ʊ��������ˡ�Ǥ⥨�ͥ륮�������̤��ۤʤ�ޤ���</font><br><br>

			���򵡤�Ǯ���ΰ㤤�ˤ�륨�ͥ륮�������̤���ӡ�������Ϥ��
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">����</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">ǯ��<font color="black">����<br>���ͥ륮��</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">ǯ��<font color="black">����</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">ǯ��<br>�����ú���ӽ���</td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td valign="middle" height="40" class="tc1">��ڷ����������</td>
					<td align="center" valign="middle" width="100" height="40">2,597Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">35,060��
					</td>
					<td align="center" valign="middle" width="135" height="40">556 kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td style="padding:4" class="tc1">��Ǯ��������������</td>
					<td align="center" valign="middle" width="100" height="40">2,234Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/gas.gif" align="center" width="44" height="44" border="0">30,160��
					</td>
					<td align="center" valign="middle" width="135" height="40">478 kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td height="40" style="padding:4" class="tc1">��ڷ��������ϲ����</td>
					<td align="center" valign="middle" width="100" height="40">2,078Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0">21,400��
					</td>
					<td align="center" valign="middle" width="135" height="40">445 kg��CO<sub>2</sub></td>
				</tr>
				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td height="40" style="padding:4" class="tc1">�ҡ��ȥݥ�׷��������ϲ����</td>
					<td align="center" valign="middle" width="100" height="40">693Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 7,130��
					</td>
					<td align="center" valign="middle" width="135" height="40">148 kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td height="40" style="padding:4" class="tc1">���������</td>
					<td align="center" valign="middle" width="100" height="40">2,597Mcal</td>
					<td align="center" valign="middle" width="120" height="40">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0">13,760��
					</td>
					<td align="center" valign="middle" width="135" height="40">782 kg��CO<sub>2</sub></td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">���</td>
					<td class="tc0">���ؤȻҤɤ���Ͳ�²�оݡ��������ᡢ����210L��</td>
				</tr>

				<tr>
					<td></td>
					<td class="tc0">��ƻ��ʬ�Ϸ׻��˴ޤߤޤ���</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">����ϡ���������10.3��/Mcal���Իԥ���13.5��/Mcal������5.3��/Mcal�Ƿ׻���</td>
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
								<td align="center" bgcolor="#ffe397" width="260">363</td>
								<td align="center" bgcolor="#ffffc2" width="95">4,900</td>
								<td align="center" bgcolor="#deffff" width="195">78</td>
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
					<td align="center"><b>�ں��ٻȤ��ʤ��Ψ�Τ褤�����򡪡�</b><br><br></b></td>
				</tr>
			</table>

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