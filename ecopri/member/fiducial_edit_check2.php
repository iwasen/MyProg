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

// �ե����फ��Υǡ�������
$reg->edit_el_use = get_number($edit_el_use);
$reg->edit_el_sum = get_number($edit_el_sum);
$reg->edit_gs_use = get_number($edit_gs_use);
$reg->edit_gs_sum = get_number($edit_gs_sum);
$reg->edit_wt_use = get_number($edit_wt_use);
$reg->edit_wt_sum = get_number($edit_wt_sum);
$reg->edit_gm_use = get_number($edit_gm_use);
$reg->edit_ol_use = get_number($edit_ol_use);
$reg->edit_ol_sum = get_number($edit_ol_sum);
$reg->edit_gl_use = get_number($edit_gl_use);
$reg->edit_gl_sum = get_number($edit_gl_sum);

$reg->reason = get_number($reason);

$eng = &$reg->eng[$edit_no];
$ym = $reg->bd_y . "/" . sprintf("%02d", $reg->bd_m);

// �������ܥ����å�

if (trim($reg->edit_el_use) == '' || trim($reg->edit_el_sum) == '')
	$msg[] = "�ŵ��Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
elseif (!check_num($reg->edit_el_use) || !check_num($reg->edit_el_use))
	$msg[] = "�ŵ��Υǡ����˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
elseif (($reg->edit_el_use <= 0 && $reg->edit_el_sum > 0) || ($reg->edit_el_use > 0 && $reg->edit_el_sum <= 0))
	$msg[] = "�ŵ��Υǡ������������ʤ��褦�Ǥ���";

if (trim($reg->edit_gs_use) == '' || trim($reg->edit_gs_sum) == '')
	$msg[] = "�����Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
elseif (!check_num($reg->edit_gs_use) || !check_num($reg->edit_gs_use))
	$msg[] = "�����Υǡ����˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
elseif (($reg->edit_gs_use <= 0 && $reg->edit_gs_sum > 0) || ($reg->edit_gs_use > 0 && $reg->edit_gs_sum <= 0))
	$msg[] = "�����Υǡ������������ʤ��褦�Ǥ���";

if (trim($reg->edit_wt_use) == '' || trim($reg->edit_wt_sum) == '')
	$msg[] = "��ƻ�Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
elseif (!check_num($reg->edit_wt_use) || !check_num($reg->edit_wt_use))
	$msg[] = "��ƻ�Υǡ����˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
elseif (($reg->edit_wt_use <= 0 && $reg->edit_wt_sum > 0) || ($reg->edit_wt_use > 0 && $reg->edit_wt_sum <= 0))
	$msg[] = "��ƻ�Υǡ������������ʤ��褦�Ǥ���";

if (trim($reg->edit_gm_use) == '')
	$msg[] = "���ߤΥǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
elseif (!check_num($reg->edit_gm_use))
	$msg[] = "���ߤΥǡ����˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";

if (trim($reg->edit_ol_use) == '' || trim($reg->edit_ol_sum) == '')
	$msg[] = "�����Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
elseif (!check_num($reg->edit_ol_use) || !check_num($reg->edit_ol_use))
	$msg[] = "�����Υǡ����˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
elseif (($reg->edit_ol_use <= 0 && $reg->edit_ol_sum > 0) || ($reg->edit_ol_use > 0 && $reg->edit_ol_sum <= 0))
	$msg[] = "�����Υǡ������������ʤ��褦�Ǥ���";

if (trim($reg->edit_gl_use) == '' || trim($reg->edit_gl_sum) == '')
	$msg[] = "�������Υǡ��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
elseif (!check_num($reg->edit_gl_use) || !check_num($reg->edit_gl_use))
	$msg[] = "�������Υǡ����˿��Ͱʳ���ʸ�����ޤޤ�Ƥ���褦�Ǥ���";
elseif (($reg->edit_gl_use <= 0 && $reg->edit_gl_sum > 0) || ($reg->edit_gl_use > 0 && $reg->edit_gl_sum <= 0))
	$msg[] = "�������Υǡ������������ʤ��褦�Ǥ���";

if (trim($reg->reason) == '')
	$msg[] = "������ͳ�����Ϥ��Ƥ���������";

if (!$msg)
	redirect('fiducial_edit_end2.php');

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
			<table border="0" width="350" cellspacing="10">
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