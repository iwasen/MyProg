<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ƥ�ӡʵ���ˤΥ��ߥ�졼�����
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
<title>�ƥ��_����ξ��ʾܺ١�</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- �迴submit�ǡ��� -->
<form method="post" name="form1" action="kesshin.php">
	<input type="hidden" name="ganbalist_id" <?=value(GANBA_TV_KIKI)?>>
	<input type="hidden" name="energy" <?=value(77)?>>
	<input type="hidden" name="cost" <?=value(2210)?>>
	<input type="hidden" name="co2" <?=value(39)?>>
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
			<img src="img/shindan/title/tv_01.gif"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<font color="red">�ƥ�Ӥμ���ˤ�äơ����ͥ륮�������̤��ۤʤ�ޤ���</font><br><br>

			�ƥ�Ӥμ���ΰ㤤�ˤ���ŵ��Υ��ͥ륮�������̤����
			<table border="1">
				<tr>
					<td><img src="img/spacer.gif"></td>
					<td align="center" valign="middle" bgcolor="#8ac3ff"><font color="black">������ˡ</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="100">ǯ��<font color="black">����<br>���ͥ륮��</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="120">ǯ��<font color="black">����</font></td>
					<td align="center" valign="middle" bgcolor="#ffff9c" width="135">ǯ��<br>�����ú���ӽ���</td>
				</tr>

				<tr>
					<td>
						<img src="img/shindan/seika/hyojun.gif" width="65" height="67" border="0">
					</td>
					<td style="padding:4" class="tc1">��ӥ󥰤Ǥϡ��֥饦��ɥƥ�ӡ�28���磻�ɡ�</td>
					<td align="center" valign="middle" width="100">229Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 6,560��
					</td>
					<td align="center" valign="middle" width="135">116 kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td>
						<img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0">
					</td>
					<td style="padding:4" class="tc1">��ӥ󥰤Ǥϡ��վ��ƥ�ӡ�28����</td>
					<td align="center" valign="middle" width="100">152Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 4,350��
					</td>
					<td align="center" valign="middle" width="135">77 kg��CO<sub>2</sub></td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">���</font></td>
					<td class="tc0">���ؤȻҤɤ���Ͳ�²�оݡ��ƥ�Ӥϡ���İ���ְʳ����Ե����Ϥ�ޤࡣ</td>
				</tr>

				<tr>
					<td></td>
					<td class="tc0">�ʤ����İ�ʤ����翲���Υƥ�Ӥ�21���֥饦��ɥƥ�ӡ�</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">����ϡ��ŵ�28.6��/Mcal�Ƿ׻���</td>
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
								<td align="center" bgcolor="#ffe397" width="260">77</td>
								<td align="center" bgcolor="#ffffc2" width="95">2,210</td>
								<td align="center" bgcolor="#deffff" width="195">39</td>
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
					<td align="center"><b>�ں����㤦�ʤ�֥饦���TV���վ�TV����</b><br><br></b></td>
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