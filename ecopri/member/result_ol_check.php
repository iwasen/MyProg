<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����μ������ϡ�������
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/result_input.php");
include("$inc/mypage.php");

// ���å�������
session_start();
if (!isset($_SESSION['ss_result_input']))
	redirect('result_gm.php');
$reg = &$_SESSION['ss_result_input'];

// �ե����फ��ǡ��������������å�
for ($i = 0; $i <= $reg->last_day; $i++) {
	$ol_use[$i] = get_number($ol_use[$i]);
	$ol_sum[$i] = get_number($ol_sum[$i]);
	if (($ol_use[$i] && !$ol_sum[$i]) || (!$ol_use[$i] && $ol_sum[$i]))
		$msg[] = $i + 1 . "���λ��������϶�ۤ����Ϥ���Ƥ��ޤ���";
	if ($ol_use[$i] == '')
		$ol_use[$i] = 0;
	if ($ol_sum[$i] == '')
		$ol_sum[$i] = 0;
	if (!is_numeric($ol_use[$i]) || !is_numeric($ol_sum[$i])) {
		$msg[] = $i + 1 . "�������Ϥ˿����ʳ���ʸ�����ޤޤ�Ƥ��ޤ���";
	}
}

$reg->ol_use = implode(",", $ol_use);
$reg->ol_sum = implode(",", $ol_sum);

if (!$msg)
	redirect('result_ol_ok.php');

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
			<img src="img/nyuukai/zou_img/m_error.gif" width="162" height="124" alt="���졩">
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
			<br>
		</td>
		<td><br></td>
	</tr>
	<tr>

		<td width="35" align="left" valign="bottom"> <img src="img/kado_3.gif" width="35" height="35"></td>
		<td align="center" valign="bottom">
		<hr width="95%" size="1">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr align="center">
					<td><input type="image" width="99" height="40" border="0" alt="���ľ��" src="img/nyuukai/button/naosu.gif" onclick="history.back()"></td>
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