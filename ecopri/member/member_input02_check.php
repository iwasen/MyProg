<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist2.php");

/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����Ծ�������
'******************************************************/

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_input_exp.php');
$reg = &$_SESSION['ss_regist2'];

// �ե����फ��ǡ�������
$reg->name1_kana = get_name_kana($name1_kana);
$reg->name2_kana = get_name_kana($name2_kana);
$reg->name1 = get_name($name1);
$reg->name2 = get_name($name2);
$reg->sex = $sex;
$reg->birthday_y = get_number($birthday_y);
$reg->birthday_m = get_number($birthday_m);
$reg->birthday_d = get_number($birthday_d);
$reg->zip1 = get_number($zip1);
$reg->zip2 = get_number($zip2);
$reg->area_cd = $area_cd;
$reg->address = $address;


if ($reg->name1 == '')
	$msg[] = '��̾���ʴ����ˤ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->name2 == '')
	$msg[] = '��̾���ʴ����ˤ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->name1_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_kana($reg->name1_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ����ϥ������ʤ����Ϥ��Ƥ���������';

if ($reg->name2_kana == '')
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_kana($reg->name2_kana))
	$msg[] = '��̾���ʥեꥬ�ʡˤ�̾�ϥ������ʤ����Ϥ��Ƥ���������';

if ($reg->sex == '')
	$msg[] = '���̤����򤵤�Ƥ��ʤ��褦�Ǥ���';

$birthday_ok = true;
if ($reg->birthday_y == '') {
	$msg[] = '��ǯ������ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_y, 1900, date('Y'))) {
	$msg[] = '��ǯ������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($reg->birthday_m == '') {
	$msg[] = '��ǯ�����η���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_m, 1, 12)) {
	$msg[] = '��ǯ�����η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($reg->birthday_d == ''){
	$msg[] = '��ǯ�������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
} elseif (!check_num($reg->birthday_d, 1, 31)) {
	$msg[] = '��ǯ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$birthday_ok = false;
}

if ($birthday_ok && !checkdate($reg->birthday_m, $reg->birthday_d, $reg->birthday_y))
	$msg[] = '��ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->zip1 == '' || $reg->zip2 == '')
	$msg[] = '͹���ֹ椬���Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_zip($reg->zip1, $reg->zip2))
	$msg[] = '͹���ֹ椬���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
else
	$zip_ok = true;

if ($reg->area_cd == '')
	$msg[] = '��ƻ�ܸ������򤵤�Ƥ��ʤ��褦�Ǥ���';
else
	$area_ok = true;

if ($reg->address == '')
	$msg[] = '���꤬���Ϥ���Ƥ��ʤ��褦�Ǥ���';
else
	$addr_ok = true;

if ($zip_ok && $area_ok && $addr_ok && !check_zip_area($reg->zip1 . $reg->zip2, $reg->area_cd))
	$msg[] = "͹���ֹ�Ƚ��꤬���פ��Ƥ��ʤ��褦�Ǥ���";

if (!$msg) {
	if ($reg->back_page)
		redirect("$reg->back_page");
	else
		redirect('member_input03.php');
}
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
		<td width="35" align="left" valign="top" bgcolor="#FFFFFF">
		<img src="img/kado_1.gif" width="35" height="35"></td>
		<td align="center" valign="bottom"><br>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><img src="img/nyuukai/title/m_input02_01.gif" width="262" height="37" alt="�椬�Ȥβ�²����������1"></td>
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