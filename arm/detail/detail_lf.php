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
	$sql = "SELECT * FROM myhoken." . $table_name . " WHERE user_id =" . sql_number($user_id) ." AND life_insu_id=" . sql_number($insu_no);
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		return pg_fetch_object($result, 0);
	}
}

// 主契約保険種別取得
function decode_main_contract_kind($code) {
	switch ($code) {
		case 1:	return "終身保険";
		case 2:	return "変額終身保険";
		case 3:	return "定期保険";
		case 4:	return "定期保険(年金・月額型)";
		case 5:	return "グループ保険";
		case 6:	return "逓減定期保険";
		case 7:	return "養老保険";
		case 8:	return "団体医療";
		case 9:	return "医療保険";
		case 10:	return "ガン保険";
		case 11:	return "個人年金保険";
		case 12:	return "所得保障保険";
		case 13:	return "団体傷害保険";
		case 14:	return "普通傷害保険";
		case 15:	return "交通事故傷害保険";
		case 16:	return "積立傷害保険";
		case 17:	return "積立交通事故傷害保険";
		case 18:	return "学資保険";
		case 19:	return "介護保険";
		default:	return " -";
	}
}

// 主契約者取得
function decode_main_contractor_kind($code) {
	switch($code) {
		case 1:	return "本人契約";
		case 2:	return "配偶者への特約";
		default:	return "-";
	}
}

// 契約形態取得
function decode_contract_form_kind($code){
	switch($code) {
		case 1:	return "個別契約";
		case 2:	return "団体契約";
		case 3:	return "団体扱い契約";
		default:	return " -";
	}
}

// 契約期間取得
function decode_duration_policy_kind($code, $data1, $data2) {
	switch($code) {
		case 1:	return '終身';
		case 2:	return $data1 . '年';
		case 3:	return $data2 . '歳';
		default:	return " -";
	}
}

// 死亡：保険金額(病気死亡)
function sum_amount_de($data1, $data2, $data3) {
	$total = $data1 + $data2 + $data3;
	if ($total > 0)
		return number_format($total);
	else
		return " - ";
}

// 支払開始日取得
function decode_start_prov_kind($code) {
	switch($code) {
		case 1:	return "１日目から";
		case 2:	return "４泊５日以上";
		case 3:	return "７泊８日以上";
		case 4:	return "２０日以上";
		default:	return ' - ';
	}
}

// 給付期間取得
function decode_medi_main_supply_kind($code, $data) {
	switch ($code) {
		case 1:	return $data . '日';
		break;
		case 2:	return '無期限';
		default:	return ' - ';
	}
}

// 給付形態取得
function decode_supply_kind($code) {
	switch($code){
		case 1: return '定額';
		case 2: return '定率';
		default:	return ' - ';
	}
}

// 給付額/率取得
function decode_supply($data1, $data2, $kind) {
	switch ($kind) {
		case 1:	return number_format($data1) . "万円";
		case 2:	return $data2 . "％";
		default:	return " - ";
	}
}

if ($coid == 3) {
	$fetch_ma = get_fetch('life_insurance');				// 基本情報
	$fetch_de = get_fetch('life_insurance_death');	// 死亡情報
	$fetch_me = get_fetch('life_insurance_medical');// 医療情報
	$fetch_ol = get_fetch('life_insurance_oldage');	// 老後情報
	$fetch_ip = get_fetch('life_insurance_inposs');	// 就業不能情報
	$fetch_sp = get_fetch('life_special_contract');	// 特約情報
} elseif ($coid == 4) {
	$fetch_ma = get_fetch('recommend_life_plan');			// おすすめ基本情報
	$fetch_de = get_fetch('recommend_life_death');		// おすすめ死亡情報
	$fetch_me = get_fetch('recommend_life_medical');	// おすすめ医療情報
	$fetch_ol = get_fetch('recommend_life_oldage');		// おすすめ老後情報
	$fetch_ip = get_fetch('recommend_life_inposs');		// おすすめ就業不能情報
	$fetch_sp = get_fetch('recommend_spe_contract');	// おすすめ特約情報
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<title>生命保険詳細</title>
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
		<td colspan=2><b>保険名： <?=$fetch_ma->item_name?></b></td>
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
					<td class="left_top"><nobr>&nbsp;<?=$fetch_ma->areas_number != '' ? $fetch_ma->areas_number : ' - '?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=$fetch_ma->insurance_company?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=decode_main_contract_kind($fetch_ma->main_contract_kind)?></nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=format_ymd($fetch_ma->contract_date)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=30%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="right"><nobr>&nbsp;主契約者</nobr></td>
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
					<td class="right"><nobr>&nbsp;年間保険料</nobr></td>
				</tr>
			</table>
		</td>
		<td width=70%>
			<table cellspacing=0 cellpadding=0 border=0 width=100%>
				<tr>
					<td class="left"><nobr>&nbsp;<?=return_default($fetch_ma->year_conv_amount_a) ?>円</nobr></td>
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
					<td class="left"><nobr>&nbsp;<?=decode_contract_form_kind($fetch_ma->contract_form_kind)?></nobr></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp死亡</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>◎ 主契約</td></tr>
	<tr><td>契約期間 ： <?=decode_duration_policy_kind($fetch_de->duration_policy_kind, $fetch_de->regular_update_year, $fetch_de->dont_update_age)?></td></tr>
	<tr><td>保険金額 ： <?=sum_amount_de($fetch_de->death_main_covere_amount, $fetch_de->death_main_educat_amount, $fetch_de->death_main_dim_amount)?>万円（病気死亡）</td></tr>
	<tr><td>保険金額 ： <?=return_default($fetch_de->death_main_dis_amount)?>万円（災害死亡）</td></tr>
	<tr><td><br></td></tr>
	<tr><td>◎ 特約</td></tr>
	<tr><td>契約期間 ： <?=decode_duration_policy_kind($fetch_sp->death_policy_kind, $fetch_sp->death_poss_regular_year, $fetch_sp->death_imposs_regular_age)?></td></tr>
	<tr><td>保険金額 ： <?=sum_amount_de($fetch_sp->death_covere_amount, $fetch_sp->death_educat_amount, $fetch_sp->death_dim_amount)?>万円（病気死亡）</td></tr>
	<tr><td>保険金額 ： <?=return_default($fetch_sp->death_dis_amount)?>万円（災害死亡）</td></tr>
</table>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp医療</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>◎ 主契約</td></tr>
	<tr><td>契約金間 ： <?=decode_duration_policy_kind($fetch_me->duration_policy_kind, $fetch_me->regular_update_year, $fetch_me->dont_update_age)?></td></tr>
	<tr><td>疾病入院日額 ： <?=return_default($fetch_me->hosp_amount)?>円／日</td></tr>
	<tr><td>災害入院日額 ： <?=return_default($fetch_me->injury_amount)?>円／日</td></tr>
	<tr><td>がん入院日額 ： <?=return_default($fetch_me->cancer_amount)?>円／日</td></tr>
	<tr><td>成人病入院日額 ： <?=return_default($fetch_me->adult_amount)?>円／日</td></tr>
	<tr><td>支払開始日 ： <?=decode_start_prov_kind($fetch_me->start_prov_kind)?></td></tr>
	<tr><td>給付期間 ： <?=return_default($fetch_me->medi_main_supply_period)?>日</td></tr>
<!--	<tr><td>給付期間 ： <?=//decode_medi_main_supply_kind($fetch_me->medi_main_supply_kind, $fetch_me->medi_main_supply_period)?></td></tr>-->
	<tr><td>特定疾病一時金 ： <?=return_default($fetch_me->disease_lump)?>万円</td></tr>
	<tr><td>がん一時金 ： <?=return_default($fetch_me->cancer_lump)?>万円</td></tr>
	<tr><td><br></td></tr>
	<tr><td>◎ 特約</td></tr>
	<tr><td>契約金間 ： <?=decode_duration_policy_kind($fetch_sp->medi_policy_kind, $fetch_sp->medi_poss_regular_year, $fetch_sp->medi_imposs_regular_age)?></td></tr>
	<tr><td>疾病入院日額 ： <?=return_default($fetch_sp->medi_disease_dayamount)?>円／日</td></tr>
	<tr><td>災害入院日額 ： <?=return_default($fetch_sp->medi_injury_dayamount)?>円／日</td></tr>
	<tr><td>がん入院日額 ： <?=return_default($fetch_sp->medi_cancer_dayamount)?>円／日</td></tr>
	<tr><td>成人病入院日額 ： <?=return_default($fetch_sp->medi_geriatric_dayamount)?>円／日</td></tr>
	<tr><td>支払開始日 ： <?=decode_start_prov_kind($fetch_sp->medi_supply_begin_kind)?></td></tr>
	<tr><td>給付期間 ： <?=return_default($fetch_sp->medi_supply_period_day)?>日</td></tr>
<!--	<tr><td>給付期間 ： <?=//decode_medi_main_supply_kind($fetch_sp->medi_supply_period_kind, $fetch_sp->medi_supply_period_day)?></td></tr>-->
	<tr><td>特定疾病一時金 ： <?=return_default($fetch_sp->medi_disease_lumpamount)?>万円</td></tr>
	<tr><td>がん一時金 ： <?=return_default($fetch_sp->medi_cancer_lumpamount)?>万円</td></tr>
</table>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp老後</td>
	</tr>
</table>
<?
if ($fetch_ol->user_id) {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>年金額 ： <?=return_default($fetch_ol->oldage_pension_amount)?>万円</td></tr>
	<tr><td>支払開始年齢 ： <?=return_default($fetch_ol->oldage_start_prov_age)?>歳</td></tr>
	<tr><td>支払期間 ： <?=return_default($fetch_ol->oldage_supply_period_year)?>年</td></tr>
	<tr><td>満期金 ： <?=return_default($fetch_ol->oldage_expiration_amount)?>万円</td></tr>
	<tr><td>満期年齢 ： <?=return_default($fetch_ol->oldage_expiration_age)?>歳</td></tr>
</table>
<?
} else {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>年金額 ： <?=return_default($fetch_sp->oldage_pension_amount)?>万円</td></tr>
	<tr><td>支払開始年齢 ： <?=return_default($fetch_sp->oldage_supply_begin_age)?>歳</td></tr>
	<tr><td>支払期間 ： <?=return_default($fetch_sp->oldage_supply_period)?>年</td></tr>
	<tr><td>満期金 ： <?=return_default($fetch_sp->oldage_expiration_amount)?>万円</td></tr>
	<tr><td>満期年齢 ： <?=return_default($fetch_sp->oldage_expiration_age)?>歳</td></tr>
</table>
<?
}
?>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp就業不能</td>
	</tr>
</table>
<?
if ($fetch_ip->user_id) {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>給付形態 ： <?=decode_supply_kind($fetch_ip->supply_kind)?></td></tr>
	<tr><td>給付額／率 ： <?=decode_supply($fetch_ip->fixed_amount, $fetch_ip->fixed_rate, $fetch_ip->supply_kind)?></td></tr>
	<tr><td>てんぽ期間 ： <?=return_default($fetch_ip->supply_month)?>ヶ月</td></tr>
	<tr><td>免責期間 ： <?=return_default($fetch_ip->immunity_day)?>日</td></tr>
</table>
<?
} else {
?>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td>給付形態 ： <?=decode_supply_kind($fetch_sp->imposs_supply_kind)?></td></tr>
	<tr><td>給付額／率 ： <?=decode_supply($fetch_sp->imposs_fixed_amount, $fetch_sp->imposs_fixed_rate, $fetch_sp->imposs_supply_kind)?></td></tr>
	<tr><td>てんぽ期間 ： <?=return_default($fetch_sp->imposs_tempo_period)?>ヶ月</td></tr>
	<tr><td>免責期間 ： <?=return_default($fetch_sp->imposs_immunity_period)?>日</td></tr>
</table>
<?
}
?>
<hr width=95% size=1><br>
<table cellspacing=0 cellpadding=1 border=0 width="95%">
	<tr>
		<td class="line">&nbsp事故災害</td>
	</tr>
</table>
<table cellspacing=0 cellpadding=1 border=0 width="90%">
	<tr><td><br></td></tr>
	<tr><td>賠償責任保険金額 ： <?=return_default($fetch_sp->ind_liability_amount)?>万円</td></tr>
	<tr><td><br></td></tr>
</table>
<hr width=95% size=1><br>
</center>
</body>
</html>