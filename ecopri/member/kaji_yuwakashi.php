<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ʨ���ξܺ�
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// �ѥ�᡼���ǥ��ͥ륮��ʬ��������ä��饻�å�������¸
if ($eng_bunrui)
	$_SESSION['s_eng_bunrui'] = $eng_bunrui;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>��ʨ���ξ��ʾܺ١�</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

<body bgcolor="#FF0000" text="#000000" link="#66ccff" vlink="#66ccff" leftmargin="8" topmargin="8" alink="#66ccff">
<div align="center">

<!-- ��ݳѥơ��֥� -->
<table border="0" cellpadding="0" cellspacing="0" width="714" height="90%" bgcolor="#FFFFFF">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
			<img src="img/kado_1.gif" width="35" height="35">
		</td>
		<td align="center" valign="top">
			<br>
			<img src="img/shindan/sim_syosai/hinto.gif"><br>
			<!-- �����ȥ���ʬ -->
			<br>
			<img src="img/shindan/title/yuwakashi_01.gif"><br>
			<!-- �����ȥ���ʬ��λ -->

			<!-- ���������� -->
			<font color="red">��ʨ������ˡ�ˤ�äơ����ͥ륮�������̤��ۤʤ�ޤ���</font><br><br>


			��ʨ������ˡ�ΰ㤤�ˤ�륬�����ŵ��Υ��ͥ륮�������̤����
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
					<td style="padding:4" class="tc1">��������ʨ�������塢������ʽ�����ʳ��ˤϡ��ŵ��ݥåȤ��ݲ���</td>
					<td align="center" valign="middle" width="100">193Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 1,610��
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 2,130��
					</td>
					<td align="center" valign="middle" width="135">63 kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle"><img src="img/shindan/seika/suisyou.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">��������ʨ�������塢��ˡ�Ӥ��ݲ�������᤿ʬ�򥬥���ʨ�����ʤ�����</td>
					<td align="center" valign="middle" width="100">141Mcal</td>
					<td align="center" valign="middle" width="120">
						<img src="img/shindan/energy/gas.gif" align="middle" width="44" height="44" border="0"> 1,900��
					</td>
					<td align="center" valign="middle" width="135">30 kg��CO<sub>2</sub></td>
				</tr>

				<tr>
					<td align="center" valign="middle" rowspan="3"><img src="img/shindan/seika/sankou.gif" width="65" height="67" border="0"></td>
					<td style="padding:4" class="tc1">�ŵ��ݥåȤǿ夫��ʨƭ�����ä��ݲ���</td>
					<td align="center" valign="middle" width="100" rowspan="3">143Mcal</td>
					<td align="center" valign="middle" width="120" rowspan="3">
						<img src="img/shindan/energy/denki.gif" align="middle" width="44" height="44" border="0"> 4,100��
					</td>
					<td align="center" valign="middle" width="135" rowspan="3">73 kg��CO<sub>2</sub></td>
				</tr>
			</table>

			<table border="0">
				<tr>
					<td class="tc0">���</td>
					<td class="tc0">���ؤȻҤɤ���Ͳ�²�оݡ�2L/�����ѡ�</td>
				</tr>

				<tr height="20">
					<td height="20"></td>
					<td height="20" class="tc0">����ϡ��ŵ�28.6��/Mcal���Իԥ���13.5��/Mcal�Ƿ׻���</td>
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

			<table>
				<tr>
					<td><a href="javascript:history.back()"><img src="img/shindan/button/back3.gif" border="0" width="117" height="36"></a></td>
					<td><a href="sim_yuwakashi.php" title="���ߥ�졼�����"><img src="img/shindan/sim_syosai/button_ecoshindan.gif" border="0" width="145" height="36"></a></td>
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