<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���顦���᤭�Υ��ߥ�졼�����
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
<title>���顦���᤭�ʾܺ١�</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- �迴submit�ǡ��� -->
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_SENGAN)?>>
	<input type="hidden" name="energy" <?=value(1341)?>>
	<input type="hidden" name="cost" <?=value(22880)?>>
	<input type="hidden" name="co2" <?=value(311)?>>
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
			<img src="img/shindan/title/sengan_01.gif"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<font color="red">���顦���᤭�Ǥ����Ѳ��١���λȤ����ˤ�äƥ��ͥ륮�������̤��ۤʤ�ޤ���</font><br><br>

			������Υ������ظ����Ѿ��ˤ�륨�ͥ륮�������̤����
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff" colspan="2"><font color="#000010">��(��)</font></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">������ˡ</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">ǯ��<font color="black">����<br>���ͥ륮��</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">ǯ��<font color="black">����</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">ǯ��<br>�����ú���ӽ���</div></td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3">
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td align="center" valign="middle" class="tc1">��</td>
					<td align="center" valign="middle" bgcolor="#ffc99a" class="tc1">38��</td>
					<td rowspan="3" style="padding:4" class="tc1">�ͿͲ�²����īͼ�ΰ���������顦���̡�</td>
					<td align="center" valign="middle" width="100" rowspan="3">1,924Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 25,980��
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 7,490��
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">449kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">��/��</td>
					<td align="center" valign="middle" bgcolor="#6bff84" class="tc1">38��</td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">��</td>
					<td align="center" valign="middle" bgcolor="#82fffe" class="tc1">38��</td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3">
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td align="center" valign="middle" class="tc1">��</td>
					<td align="center" valign="middle" bgcolor="#ffc99a" class="tc1">24��</td>
					<td rowspan="3" style="padding:4" class="tc1">�ͿͲ�²����īͼ�ΰ���������顦���̡�<br><br>��������̥ܥ����ί��ơ����᤭�ϥ��åפ���Ѥ��롣</td>
					<td align="center" valign="middle" width="100" rowspan="3">583Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 7,870��
						<img src="img/shindan/energy/suidou.gif" align="middle" width="44" height="44" border="0"> 2,720��
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">138kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">��/��</td>
					<td align="center" valign="middle" bgcolor="#6bff84" class="tc1">35��</td>
				</tr>

				<tr>
					<td align="center" valign="middle" class="tc1">��</td>
					<td align="center" valign="middle" bgcolor="#82fffe" class="tc1">38��</td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">���</td>
					<td class="tc0">���ؤȻҤɤ���Ͳ�²�оݡ�</font class="tc0">���򵡤��Իԥ�����ڷ���</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">����ϡ��Իԥ���13.5��/Mcal����ƻ116.6��/m<sup>3</sup>�Ƿ׻���</td>
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
								<td align="center" bgcolor="#ffe397" width="260">1,341</td>
								<td align="center" bgcolor="#ffffc2" width="95">22,880</td>
								<td align="center" bgcolor="#deffff" width="195">311</td>
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
					<td align="center"><b>�����̻��᤭�ώ�ή�������򤷤ʤ���</b><br><br></b></td>
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