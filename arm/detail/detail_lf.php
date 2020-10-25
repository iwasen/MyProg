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
	$sql = "SELECT * FROM myhoken." . $table_name . " WHERE user_id =" . sql_number($user_id) ." AND life_insu_id=" . sql_number($insu_no);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		return pg_fetch_object($result, 0);
	}
}

// ������ݸ����̼���
function decode_main_contract_kind($code) {
	switch ($code) {
		case 1:	return "�����ݸ�";
		case 2:	return "�ѳ۽����ݸ�";
		case 3:	return "����ݸ�";
		case 4:	return "����ݸ�(ǯ�⡦��۷�)";
		case 5:	return "���롼���ݸ�";
		case 6:	return "��������ݸ�";
		case 7:	return "��Ϸ�ݸ�";
		case 8:	return "���ΰ���";
		case 9:	return "�����ݸ�";
		case 10:	return "�����ݸ�";
		case 11:	return "�Ŀ�ǯ���ݸ�";
		case 12:	return "�����ݾ��ݸ�";
		case 13:	return "���ν����ݸ�";
		case 14:	return "���̽����ݸ�";
		case 15:	return "���̻��ν����ݸ�";
		case 16:	return "��Ω�����ݸ�";
		case 17:	return "��Ω���̻��ν����ݸ�";
		case 18:	return "�ػ��ݸ�";
		case 19:	return "����ݸ�";
		default:	return " -";
	}
}

// �����Լ���
function decode_main_contractor_kind($code) {
	switch($code) {
		case 1:	return "�ܿͷ���";
		case 2:	return "�۶��Ԥؤ�����";
		default:	return "-";
	}
}

// ������ּ���
function decode_contract_form_kind($code){
	switch($code) {
		case 1:	return "���̷���";
		case 2:	return "���η���";
		case 3:	return "���ΰ�������";
		default:	return " -";
	}
}

// ������ּ���
function decode_duration_policy_kind($code, $data1, $data2) {
	switch($code) {
		case 1:	return '����';
		case 2:	return $data1 . 'ǯ';
		case 3:	return $data2 . '��';
		default:	return " -";
	}
}

// ��˴���ݸ����(�µ���˴)
function sum_amount_de($data1, $data2, $data3) {
	$total = $data1 + $data2 + $data3;
	if ($total > 0)
		return number_format($total);
	else
		return " - ";
}

// ��ʧ����������
function decode_start_prov_kind($code) {
	switch($code) {
		case 1:	return "�����ܤ���";
		case 2:	return "�������ʾ�";
		case 3:	return "�������ʾ�";
		case 4:	return "�������ʾ�";
		default:	return ' - ';
	}
}

// ���մ��ּ���
function decode_medi_main_supply_kind($code, $data) {
	switch ($code) {
		case 1:	return $data . '��';
		break;
		case 2:	return '̵����';
		default:	return ' - ';
	}
}

// ���շ��ּ���
function decode_supply_kind($code) {
	switch($code){
		case 1: return '���';
		case 2: return '��Ψ';
		default:	return ' - ';
	}
}

// ���ճ�/Ψ����
function decode_supply($data1, $data2, $kind) {
	switch ($kind) {
		case 1:	return number_format($data1) . "����";
		case 2:	return $data2 . "��";
		default:	return " - ";
	}
}

if ($coid == 3) {
	$fetch_ma = get_fetch('life_insurance');				// ���ܾ���
	$fetch_de = get_fetch('life_insurance_death');	// ��˴����
	$fetch_me = get_fetch('life_insurance_medical');// ���ž���
	$fetch_ol = get_fetch('life_insurance_oldage');	// Ϸ�����
	$fetch_ip = get_fetch('life_insurance_inposs');	// ������ǽ����
	$fetch_sp = get_fetch('life_special_contract');	// �������
} elseif ($coid == 4) {
	$fetch_ma = get_fetch('recommend_life_plan');			// ����������ܾ���
	$fetch_de = get_fetch('recommend_life_death');		// ���������˴����
	$fetch_me = get_fetch('recommend_life_medical');	// ����������ž���
	$fetch_ol = get_fetch('recommend_life_oldage');		// ��������Ϸ�����
	$fetch_ip = get_fetch('recommend_life_inposs');		// �������ὢ����ǽ����
	$fetch_sp = get_fetch('recommend_spe_contract');	// ���������������
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>��̿�ݸ��ܺ�</title>
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
		<td colspan=2><b>�ݸ�̾�� <?=$fetch_ma->item_name?></b></td>
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
					<td class="left_top"><nobr>&nbsp;<?=$fetch_ma->areas_number != '' ? $fetch_ma->areas_number : ' - '?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=$fetch_ma->insurance_company?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=decode_main_contract_kind($fetch_ma->main_contract_kind)?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=format_ymd($fetch_ma->contract_date)?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=decode_main_contractor_kind($fetch_ma->main_contractor_kind)?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=return_default($fetch_ma->year_conv_amount_a) ?>��</nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=decode_contract_form_kind($fetch_ma->contract_form_kind)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp��˴</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>�� �����</td></tr>
	<tr><td>������� �� <?=decode_duration_policy_kind($fetch_de->duration_policy_kind, $fetch_de->regular_update_year, $fetch_de->dont_update_age)?></td></tr>
	<tr><td>�ݸ���� �� <?=sum_amount_de($fetch_de->death_main_covere_amount, $fetch_de->death_main_educat_amount, $fetch_de->death_main_dim_amount)?>���ߡ��µ���˴��</td></tr>
	<tr><td>�ݸ���� �� <?=return_default($fetch_de->death_main_dis_amount)?>���ߡʺҳ���˴��</td></tr>
	<tr><td><br></td></tr>
	<tr><td>�� ����</td></tr>
	<tr><td>������� �� <?=decode_duration_policy_kind($fetch_sp->death_policy_kind, $fetch_sp->death_poss_regular_year, $fetch_sp->death_imposs_regular_age)?></td></tr>
	<tr><td>�ݸ���� �� <?=sum_amount_de($fetch_sp->death_covere_amount, $fetch_sp->death_educat_amount, $fetch_sp->death_dim_amount)?>���ߡ��µ���˴��</td></tr>
	<tr><td>�ݸ���� �� <?=return_default($fetch_sp->death_dis_amount)?>���ߡʺҳ���˴��</td></tr>
</table>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp����</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>�� �����</td></tr>
	<tr><td>������ �� <?=decode_duration_policy_kind($fetch_me->duration_policy_kind, $fetch_me->regular_update_year, $fetch_me->dont_update_age)?></td></tr>
	<tr><td>������������ �� <?=return_default($fetch_me->hosp_amount)?>�ߡ���</td></tr>
	<tr><td>�ҳ��������� �� <?=return_default($fetch_me->injury_amount)?>�ߡ���</td></tr>
	<tr><td>������������ �� <?=return_default($fetch_me->cancer_amount)?>�ߡ���</td></tr>
	<tr><td>�������������� �� <?=return_default($fetch_me->adult_amount)?>�ߡ���</td></tr>
	<tr><td>��ʧ������ �� <?=decode_start_prov_kind($fetch_me->start_prov_kind)?></td></tr>
	<tr><td>���մ��� �� <?=return_default($fetch_me->medi_main_supply_period)?>��</td></tr>
<!--	<tr><td>���մ��� �� <?=//decode_medi_main_supply_kind($fetch_me->medi_main_supply_kind, $fetch_me->medi_main_supply_period)?></td></tr>-->
	<tr><td>������°���� �� <?=return_default($fetch_me->disease_lump)?>����</td></tr>
	<tr><td>�������� �� <?=return_default($fetch_me->cancer_lump)?>����</td></tr>
	<tr><td><br></td></tr>
	<tr><td>�� ����</td></tr>
	<tr><td>������ �� <?=decode_duration_policy_kind($fetch_sp->medi_policy_kind, $fetch_sp->medi_poss_regular_year, $fetch_sp->medi_imposs_regular_age)?></td></tr>
	<tr><td>������������ �� <?=return_default($fetch_sp->medi_disease_dayamount)?>�ߡ���</td></tr>
	<tr><td>�ҳ��������� �� <?=return_default($fetch_sp->medi_injury_dayamount)?>�ߡ���</td></tr>
	<tr><td>������������ �� <?=return_default($fetch_sp->medi_cancer_dayamount)?>�ߡ���</td></tr>
	<tr><td>�������������� �� <?=return_default($fetch_sp->medi_geriatric_dayamount)?>�ߡ���</td></tr>
	<tr><td>��ʧ������ �� <?=decode_start_prov_kind($fetch_sp->medi_supply_begin_kind)?></td></tr>
	<tr><td>���մ��� �� <?=return_default($fetch_sp->medi_supply_period_day)?>��</td></tr>
<!--	<tr><td>���մ��� �� <?=//decode_medi_main_supply_kind($fetch_sp->medi_supply_period_kind, $fetch_sp->medi_supply_period_day)?></td></tr>-->
	<tr><td>������°���� �� <?=return_default($fetch_sp->medi_disease_lumpamount)?>����</td></tr>
	<tr><td>�������� �� <?=return_default($fetch_sp->medi_cancer_lumpamount)?>����</td></tr>
</table>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbspϷ��</td>
	</tr>
</table>
<?
if ($fetch_ol->user_id) {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>ǯ��� �� <?=return_default($fetch_ol->oldage_pension_amount)?>����</td></tr>
	<tr><td>��ʧ����ǯ�� �� <?=return_default($fetch_ol->oldage_start_prov_age)?>��</td></tr>
	<tr><td>��ʧ���� �� <?=return_default($fetch_ol->oldage_supply_period_year)?>ǯ</td></tr>
	<tr><td>������ �� <?=return_default($fetch_ol->oldage_expiration_amount)?>����</td></tr>
	<tr><td>����ǯ�� �� <?=return_default($fetch_ol->oldage_expiration_age)?>��</td></tr>
</table>
<?
} else {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>ǯ��� �� <?=return_default($fetch_sp->oldage_pension_amount)?>����</td></tr>
	<tr><td>��ʧ����ǯ�� �� <?=return_default($fetch_sp->oldage_supply_begin_age)?>��</td></tr>
	<tr><td>��ʧ���� �� <?=return_default($fetch_sp->oldage_supply_period)?>ǯ</td></tr>
	<tr><td>������ �� <?=return_default($fetch_sp->oldage_expiration_amount)?>����</td></tr>
	<tr><td>����ǯ�� �� <?=return_default($fetch_sp->oldage_expiration_age)?>��</td></tr>
</table>
<?
}
?>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp������ǽ</td>
	</tr>
</table>
<?
if ($fetch_ip->user_id) {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>���շ��� �� <?=decode_supply_kind($fetch_ip->supply_kind)?></td></tr>
	<tr><td>���ճۡ�Ψ �� <?=decode_supply($fetch_ip->fixed_amount, $fetch_ip->fixed_rate, $fetch_ip->supply_kind)?></td></tr>
	<tr><td>�Ƥ�ݴ��� �� <?=return_default($fetch_ip->supply_month)?>����</td></tr>
	<tr><td>���մ��� �� <?=return_default($fetch_ip->immunity_day)?>��</td></tr>
</table>
<?
} else {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>���շ��� �� <?=decode_supply_kind($fetch_sp->imposs_supply_kind)?></td></tr>
	<tr><td>���ճۡ�Ψ �� <?=decode_supply($fetch_sp->imposs_fixed_amount, $fetch_sp->imposs_fixed_rate, $fetch_sp->imposs_supply_kind)?></td></tr>
	<tr><td>�Ƥ�ݴ��� �� <?=return_default($fetch_sp->imposs_tempo_period)?>����</td></tr>
	<tr><td>���մ��� �� <?=return_default($fetch_sp->imposs_immunity_period)?>��</td></tr>
</table>
<?
}
?>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp���κҳ�</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td><br></td></tr>
	<tr><td>�����Ǥ�ݸ���� �� <?=return_default($fetch_sp->ind_liability_amount)?>����</td></tr>
	<tr><td><br></td></tr>
</table>
<hr width=95% size=1><br>
</center>
</body>
</html>