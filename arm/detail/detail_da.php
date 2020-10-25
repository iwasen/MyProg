<?
include('../inc/database.php');

//GET�ѿ�
$user_id = get_user_id($_GET["sid"]);
$coid = $_GET["coid"];
$insu_no = $_GET["id"];

// ���桼��ID�����
function get_user_id($sid) {
	$user_id = 0;

	// ���å����ID����
	$session_id = $sid;

	// ���å���󤫤�桼��ID�����
	if ($session_id != '') {
		if ($session_id{0} == '#')
			$user_id = substr($session_id, 1);
		else {
			session_id($session_id);
			session_start();
			if (isset($_SESSION['USER_ID']))
				$user_id = $_SESSION['USER_ID'];
		}
	}

	return $user_id;
}

// ǯ�����ե����ޥå�
function format_ymd($ymd){
	if ($ymd != '')
		return substr($ymd, 0, 4)."/".substr($ymd, 4, 2)."/".substr($ymd, 6, 2);
	else
		return ' - ';
}

// �ǥե���ȼ���
function return_default($data) {
	if ($data)
		return number_format($data);
	else
		return " - ";
}

// �ǡ�������SQL
function get_fetch ($table_name) {
	global $user_id, $insu_no;
	$sql = "SELECT * FROM myhoken." . $table_name . " WHERE user_id =" . sql_number($user_id) ." AND dama_insu_id=" . sql_number($insu_no);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		return pg_fetch_object($result, 0);
	}
}

// �ݸ����ܼ���
function decode_item_kind($code) {
	switch($code) {
		case 1:	return '�к�';
		case 2:	return '��ư��';
		case 3:	return '�����Ǥ';
		case 4:	return '����¾';
		default:	return ' - ';
	}
}

// ������ݸ�����
function decode_main_item_kind($code) {
	switch($code) {
		case 1:	return '����к��ݸ�';
		case 2:	return '��������ݸ�';
		case 3:	return '�����ݸ�';
		case 4:	return '��ư���ݸ�';
		case 5:	return '�Ŀ������Ǥ�ݸ�';
		case 6:	return '����ե���';
		case 7:	return '����������������';
		case 8:	return '����¾';
		default:	return ' - ';
	}
}

// ������ּ���
function decode_contract_form_kind($code) {
	switch($code) {
		case 1: return '���̷���';
		case 2: return '���η���';
		case 31: return '���ΰ�������';
		default:	return ' - ';
	}
}

// ��²���������
function decode_car_family_limit_kind($code) {
	switch($code) {
		case 1: return '�ܿ͸���';
		case 2: return '��²����';
		case 3: return '�ʤ�';
		default: return ' - ';
	}
}

// ��ư������
function decode_car_usage_kind($code) {
	switch($code) {
		case 1: return '�쥸�㡼';
		case 2: return '�̶�';
		case 3: return '����¾';
		default: return ' - ';
	}
}

// ��ư������
function decode_car_insurance_kind($code) {
	switch($code) {
		case 1: return '�����Υߡ�';
		case 2: return '����';
		case 3: return '����¾';
		default: return ' - ';
	}
}

// »�ݾ���
if ($coid == 3)
	$fetch = get_fetch('damage_insurance');
elseif ($coid == 4)
	$fetch = get_fetch('recommend_dama_plan');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>»���ݸ��ܺ�</title>
<style type="text/css">
<!--
td {font-size:14px;}
td.right_top
{
	border-top		: 1px solid gray;
	border-right	: 1px solid gray;
	border-bottom	: 1px solid gray;
}
td.right
{
	border-right	: 1px solid gray;
	border-bottom	: 1px solid gray;
}
td.left_top
{
	border-top		: 1px solid gray;
	border-bottom	: 1px solid gray;
}
td.left
{
	border-bottom	: 1px solid gray;
}
td.line
{
	border-top		: 1px solid gray;
	border-bottom	: 1px solid gray;
}
-->
</style>
</head>
<body>
<center>
<table cellspacing=0 cellpadding=0 width="95%">
	<tr>
		<td colspan=2><b>�ݸ�̾�� <?=$fetch->brand_name?></b></td>
	</tr>
</table>
<table cellspacing=0 cellpadding=0 width="95%">
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right_top"><nobr>&nbsp;�ڷ��ֹ�</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left_top"><nobr>&nbsp;<?=$fetch->areas_number != '' ? $fetch->areas_number : ' - '?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;�ݸ����</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=$fetch->ins_company_name?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;�ݸ�����</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=decode_item_kind($fetch->item_kind)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;������ݸ�����</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=decode_main_item_kind($fetch->main_item_kind)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;������</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=format_ymd($fetch->contract_start_date)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;������</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=format_ymd($fetch->contract_period_date)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;ǯ���ݸ���</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=return_default($fetch->insurance_amount)?>��</nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;�������</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=decode_contract_form_kind($fetch->contract_form_kind)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp�к�</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>�к��ݸ���ۡʷ�ʪ�� �� <?=return_default($fetch->dam_fire_ins_amount)?>����</td></tr>
	<tr><td>�к��ݸ���ۡʲȺ�� �� <?=return_default($fetch->dam_fire_hold_amount)?>����</td></tr>
	<tr><td>�ڲȿ������Ǥ�ݸ���� �� <?=return_default($fetch->dam_rent_comp_amount)?>����</td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp�Ͽ�</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>�Ͽ��ݸ���ۡʷ�ʪ�� �� <?=return_default($fetch->dam_eart_ins_amount)?>����</td></tr>
	<tr><td>�Ͽ��ݸ���ۡʲȺ�� �� <?=return_default($fetch->dam_eart_hold_amount)?>����</td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp��ư��</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>��ʪ�����Ǥ�ݸ���� �� <?=return_default($fetch->dam_real_comp_amount)?>����</td></tr>
	<tr><td>�п������Ǥ�ݸ���� �� <?=return_default($fetch->dam_person_comp_amount)?>����</td></tr>
	<tr><td>����»���ݸ���� �� <?=return_default($fetch->dam_passenger_amount)?>����</td></tr>
	<tr><td>��»�����ݸ���� �� <?=return_default($fetch->dam_disadvantage_amount)?>����</td></tr>
	<tr><td>�ͿȾ㳲�ݸ���� �� <?=return_default($fetch->dam_personal_amount)?>����</td></tr>
	<tr><td>��ξ�ݸ���� �� <?=return_default($fetch->dam_vehicle_amount)?>����</td></tr>
	<tr><td>��žǯ���� �� <?=return_default($fetch->car_driving_age)?>��</td></tr>
	<tr><td>��²������ �� <?=decode_car_family_limit_kind($fetch->car_family_limit_kind)?></td></tr>
	<tr><td>��ư������ �� <?=decode_car_usage_kind($fetch->car_usage_kind)?></td></tr>
	<tr><td>���� �� <?=return_default($fetch->car_grade)?>����</td></tr>
	<tr><td>��ξ�ݸ����� �� <?=decode_car_insurance_kind($fetch->car_insurance_kind)?></td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp»�����</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>�Ŀ������Ǥ�ݸ���� �� <?=return_default($fetch->dam_individual_amount)?>����</td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp����¾</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td><?=$fetch->dam_other1 != '' ? $fetch->dam_other1 : '-' ?> �� <?=return_default($fetch->dam_other1_amount)?>����</td></tr>
	<tr><td><?=$fetch->dam_other2 != '' ? $fetch->dam_other2 : '-'?> �� <?=return_default($fetch->dam_other2_amount)?>����</td></tr>
</table>
<hr width=95% size=1><br>
</center>
</body>
</html>