<?

/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��������ѹ���
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/regist2.php");

// �ֽ�ͭnull��0
function null_to_0($num) {
	if ($num == '')
		return 0;
	else
		return $num;
}

// ���å�������
session_start();
if (!isset($_SESSION['ss_regist2']))
	redirect('member_change01.php');
$reg = &$_SESSION['ss_regist2'];

// �ե����फ��ǡ�������
$reg->keitai_cd = $keitai_cd;
//$reg->keitai_etc = $keitai_etc;
$reg->sozai_cd = $sozai_cd;		// 1:�Ϥ���2��������
$reg->hebel_flag = $hebel_flag;		//��1���Ϥ���0��������
$reg->chikunen_cd = $chikunen_cd;
$reg->room_cd = $room_cd;
$reg->space_cd = $space_cd;
$reg->boiler_3cd = $boiler_3cd;	// 3:��������1:�ŵ�����5��������
$reg->boil_gas_flag = $boil_gas_flag;	// 1���Ϥ���2����������3���狼��ʤ�
$reg->boil_ele_flag = $boil_ele_flag;
$reg->boiler_etc = $_etc;		//���ѻ�
$reg->gas_kind_cd = $gas_kind_cd;
$reg->gas_type_cd = $gas_type_cd;
$reg->water_month = $water_month;
$reg->car_flag = $car_flag;	//1:���ꡡ0���ʤ�
$reg->gcar_num = null_to_0(get_number($gcar_num));
$reg->dcar_num = null_to_0(get_number($dcar_num));
$reg->bike_num = null_to_0(get_number($bike_num));
$reg->ol_flag = $ol_flag;

if ($reg->gas_kind_cd != '1')
	$reg->gas_type_cd = '';
/*
if ($reg->gas_type_cd == '99')
	$reg->gas_type_cd = '13';
*/

if ($reg->boiler_3cd == '1') {
	switch ($reg->boil_ele_flag) {
	case 1:
		$reg->boiler_cd = 2;
		break;
	case 2:
		$reg->boiler_cd = 1;
		break;
	case 3:
		$reg->boiler_cd = 6;
		break;
	}
	$reg->boil_gas_flag = '';
} elseif ($reg->boiler_3cd == '3') {
	switch ($reg->boil_gas_flag) {
	case 1:
		$reg->boiler_cd = 4;
		break;
	case 2:
		$reg->boiler_cd = 3;
		break;
	case 3:
		$reg->boiler_cd = 7;
		break;
	}
	$reg->boil_ele_flag = '';
} elseif ($reg->boiler_3cd == '5') {
	$reg->boiler_cd = 5;
	$reg->boil_ele_flag = '';
	$reg->boil_gas_flag = '';
}

if ($reg->keitai_cd == '')
	$msg[] = '������֤����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->sozai_cd == '')
	$msg[] = '��¤�ʤϤ�/�������ˤ����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->hebel_flag == '')
	$msg[] = '�͎��͎ގَʎ����ʤϤ�/�������ˤ����򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->room_cd == '')
	$msg[] = '�ּ�꤬���򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->boiler_cd == '')
	$msg[] = '����勵���פ����򤵤�Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->boiler_3cd == '3' && $reg->boil_gas_flag == '')
	$msg[] = '��Ǯ�����(�Ϥ�/������/�狼��ʤ�)�����򤵤��<br>�����ʤ��褦�Ǥ���';
elseif ($reg->boiler_3cd == '1' && $reg->boil_ele_flag == '')
	$msg[] = '�ҡ��ȥݥ�׼�(�Ϥ�/������/�狼��ʤ�)�����򤵤��<br>�����ʤ��褦�Ǥ���';

if ($reg->boiler_3cd == '3' && $reg->gas_kind_cd == '3')
	$msg[] = '���������򤴻��Ѥ����ϡ�����������򤷤Ʋ�������';
elseif ($reg->gas_kind_cd == '')
	$msg[] = '�����郎���򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->gas_kind_cd == '1' && $reg->gas_type_cd == '')
	$msg[] = '�Իԥ��������ϥ��������פ����򤷤Ʋ�������<br>���ʤ狼��ʤ����ϡ��ָ�����Ϥ���פ����򤷤Ʋ���������';

if ($reg->water_month == '')
	$msg[] = '��ƻ�������򤵤�Ƥ��ʤ��褦�Ǥ���';

if ($reg->car_flag == '')
	$msg[] = '�ֽ�ͭ(�Ϥ�/������)�����򤵤�Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->car_flag == '1' && ($reg->gcar_num <=0 && $reg->dcar_num <= 0 && $reg->bike_num <= 0))
	$msg[] = '�֤ν�ͭ��������Ϥ���Ƥ��ʤ��褦�Ǥ���';

if ($reg->ol_flag == '')
	$msg[] = '��������(����/�ʤ�)�����򤵤�Ƥ��ʤ��褦�Ǥ���';
elseif ($reg->ol_flag == '0' && $reg->boiler_cd == '5')
	$msg[] = '���������򤴻��Ѥξ��ϡ��������ѡ֤���פ�����<br>�����Ʋ�������';

if (!$msg)
	redirect('member_change05.php');

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
					<td align="center"><img src="img/nyuukai/title/m_input04_01.gif" width="246" height="37" alt="�椬�Ȥν���˴ؤ���"></td>
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
				<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
				<tr>
				<td>
					<font size="2"><br><?=disp_msg($msg, '��', '<br><br>')?><br></font>
				</td>
				</tr>
				<tr>
					<td colspan="2" height="2" bgcolor="#FFCC66"><img src="img/spacer.gif" width="300" height="2"></td>
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