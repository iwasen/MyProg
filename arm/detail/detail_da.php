<?
include('../inc/database.php');

//GET変数
$user_id = get_user_id($_GET["sid"]);
$coid = $_GET["coid"];
$insu_no = $_GET["id"];

// ■ユーザIDを取得
function get_user_id($sid) {
	$user_id = 0;

	// セッションID取得
	$session_id = $sid;

	// セッションからユーザIDを取得
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

// 年月日フォーマット
function format_ymd($ymd){
	if ($ymd != '')
		return substr($ymd, 0, 4)."/".substr($ymd, 4, 2)."/".substr($ymd, 6, 2);
	else
		return ' - ';
}

// デフォルト取得
function return_default($data) {
	if ($data)
		return number_format($data);
	else
		return " - ";
}

// データ取得SQL
function get_fetch ($table_name) {
	global $user_id, $insu_no;
	$sql = "SELECT * FROM myhoken." . $table_name . " WHERE user_id =" . sql_number($user_id) ." AND dama_insu_id=" . sql_number($insu_no);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		return pg_fetch_object($result, 0);
	}
}

// 保険種目取得
function decode_item_kind($code) {
	switch($code) {
		case 1:	return '火災';
		case 2:	return '自動車';
		case 3:	return '賠償責任';
		case 4:	return 'その他';
		default:	return ' - ';
	}
}

// 主契約保険種類
function decode_main_item_kind($code) {
	switch($code) {
		case 1:	return '住宅火災保険';
		case 2:	return '住宅総合保険';
		case 3:	return '団地保険';
		case 4:	return '自動車保険';
		case 5:	return '個人賠償責任保険';
		case 6:	return 'ゴルファー';
		case 7:	return 'スキー・スケート';
		case 8:	return 'その他';
		default:	return ' - ';
	}
}

// 契約形態取得
function decode_contract_form_kind($code) {
	switch($code) {
		case 1: return '個別契約';
		case 2: return '団体契約';
		case 31: return '団体扱い契約';
		default:	return ' - ';
	}
}

// 家族限定条件取得
function decode_car_family_limit_kind($code) {
	switch($code) {
		case 1: return '本人限定';
		case 2: return '家族限定';
		case 3: return 'なし';
		default: return ' - ';
	}
}

// 自動車用途
function decode_car_usage_kind($code) {
	switch($code) {
		case 1: return 'レジャー';
		case 2: return '通勤';
		case 3: return 'その他';
		default: return ' - ';
	}
}

// 自動車用途
function decode_car_insurance_kind($code) {
	switch($code) {
		case 1: return 'エコノミー';
		case 2: return '一般';
		case 3: return 'その他';
		default: return ' - ';
	}
}

// 損保情報
if ($coid == 3)
	$fetch = get_fetch('damage_insurance');
elseif ($coid == 4)
	$fetch = get_fetch('recommend_dama_plan');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>損害保険詳細</title>
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
		<td colspan=2><b>保険名： <?=$fetch->brand_name?></b></td>
	</tr>
</table>
<table cellspacing=0 cellpadding=0 width="95%">
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right_top"><nobr>&nbsp;証券番号</nobr></td>
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
					<td class="right"><nobr>&nbsp;保険会社</nobr></td>
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
					<td class="right"><nobr>&nbsp;保険種目</nobr></td>
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
					<td class="right"><nobr>&nbsp;主契約保険種類</nobr></td>
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
					<td class="right"><nobr>&nbsp;契約日</nobr></td>
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
					<td class="right"><nobr>&nbsp;満期日</nobr></td>
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
					<td class="right"><nobr>&nbsp;年間保険料</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=return_default($fetch->insurance_amount)?>円</nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;契約形態</nobr></td>
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
		<td class="line">&nbsp火災</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>火災保険金額（建物） ： <?=return_default($fetch->dam_fire_ins_amount)?>万円</td></tr>
	<tr><td>火災保険金額（家財） ： <?=return_default($fetch->dam_fire_hold_amount)?>万円</td></tr>
	<tr><td>借家人賠償責任保険金額 ： <?=return_default($fetch->dam_rent_comp_amount)?>万円</td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp地震</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>地震保険金額（建物） ： <?=return_default($fetch->dam_eart_ins_amount)?>万円</td></tr>
	<tr><td>地震保険金額（家財） ： <?=return_default($fetch->dam_eart_hold_amount)?>万円</td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp自動車</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>対物賠償責任保険金額 ： <?=return_default($fetch->dam_real_comp_amount)?>万円</td></tr>
	<tr><td>対人賠償責任保険金額 ： <?=return_default($fetch->dam_person_comp_amount)?>万円</td></tr>
	<tr><td>搭乗者損害保険金額 ： <?=return_default($fetch->dam_passenger_amount)?>万円</td></tr>
	<tr><td>自損事故保険金額 ： <?=return_default($fetch->dam_disadvantage_amount)?>万円</td></tr>
	<tr><td>人身障害保険金額 ： <?=return_default($fetch->dam_personal_amount)?>万円</td></tr>
	<tr><td>車両保険金額 ： <?=return_default($fetch->dam_vehicle_amount)?>万円</td></tr>
	<tr><td>運転年齢条件 ： <?=return_default($fetch->car_driving_age)?>歳</td></tr>
	<tr><td>家族限定条件 ： <?=decode_car_family_limit_kind($fetch->car_family_limit_kind)?></td></tr>
	<tr><td>自動車用途 ： <?=decode_car_usage_kind($fetch->car_usage_kind)?></td></tr>
	<tr><td>等級 ： <?=return_default($fetch->car_grade)?>等級</td></tr>
	<tr><td>車両保険種類 ： <?=decode_car_insurance_kind($fetch->car_insurance_kind)?></td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp損害賠償</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>個人賠償責任保険金額 ： <?=return_default($fetch->dam_individual_amount)?>万円</td></tr>
</table>
<hr width=95% size=1>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbspその他</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td><?=$fetch->dam_other1 != '' ? $fetch->dam_other1 : '-' ?> ： <?=return_default($fetch->dam_other1_amount)?>万円</td></tr>
	<tr><td><?=$fetch->dam_other2 != '' ? $fetch->dam_other2 : '-'?> ： <?=return_default($fetch->dam_other2_amount)?>万円</td></tr>
</table>
<hr width=95% size=1><br>
</center>
</body>
</html>