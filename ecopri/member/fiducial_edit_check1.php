<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:ID,Password����
'******************************************************/
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/fiducial1.php");
include("$inc/mypage.php");

// ���å�������
if (!isset($_SESSION['ss_fiducial1']))
	redirect('main.php');
$reg = &$_SESSION['ss_fiducial1'];

$reg->en = $en;

for ($i = 0; $i < 12; $i++) {
	$eng = &$reg->eng[$i];
	$ym = $eng->bd_y . "/" . sprintf("%02d", $eng->bd_m);

	// �ե����फ��Υǡ�������
	$eng->edit_use = get_number($edit_use[$i]);
	$eng->edit_sum = get_number($edit_sum[$i]);

	if ($reg->en != 'gm') {
		if (trim($eng->edit_use) == '' || trim($eng->edit_sum) == '')
			$msg[] = "$ym �Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
		elseif (!check_num($eng->edit_use))
			$msg[] = "$ym �λ����̤˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
		elseif (!check_num($eng->edit_sum))
			$msg[] = "$ym �ζ�ۤ˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
		elseif ($eng->edit_use <= 0 && $eng->edit_sum > 0)
			$msg[] = "$ym �λ����̤��������ʤ��褦�Ǥ���";
		elseif ($eng->edit_use > 0 && $eng->edit_sum <= 0)
			$msg[] = "$ym �ζ�ۤ��������ʤ��褦�Ǥ���";
	} else {
		if (trim($eng->edit_use) == '')
			$msg[] = "$ym �Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
		elseif (!check_num($eng->edit_use))
			$msg[] = "$ym �λ����̤˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
	}
}
if (!$msg)
	redirect('fiducial_edit_end1.php');

?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>������xxx��������Club������</title>
</head>

<body bgcolor="#FF0000" leftmargin="8" topmargin="8">
<div align="center">
<table width="714" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="90%">
	<tr>
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF"> <img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="top"> 
			<table border="0" cellspacing="0" cellpadding="0">
				<tr> 
					<td height=35></td>
				</tr>
			</table>
			<hr width="95%" size="1">
		</td>
		<td width="35" align="right" valign="top">
			<img src="img/kado_2.gif" width="35" height="35"></td>
	</tr>
	<tr>
		<td><br></td>
		<td align="center" valign="top">
			<br><br><br><br>
			<img src="img/nyuukai/zou_img/m_error.gif" width="162" height="124" alt="����Ǥ��äƤ롩">
			<br>
			<table border="0" width="400" cellspacing="10">
				<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
				<td>
					<font size="2"><br><?=disp_msg($msg, '��', '<br><br>')?><br></font>
				</td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#00CCFF"><img src="img/spacer.gif" width="300" height="2"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><input type="image" width="99" height="40" border="0" alt="���ľ��" src="img/nyuukai/button/back.gif" onclick="history.back();"></td>
				</tr>
				<tr>
					<td height="7" colspan="3"><img src="img/spacer.gif" width="208" height="7"></td>
				</tr>
			</table> 
		</td>
		<td width="35" height="35" align="right" valign="bottom">
			<img src="img/kado_4.gif" width="35" height="35"></td>
	</tr>
</table>

</div>
</body>
</html>