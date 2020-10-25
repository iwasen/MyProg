<?
/******************************************************
* system  : xxx リスク診断
* title   : 保険情報管理クラス
* content : リスク診断の保険情報を管理するクラス
* version : 1.0
* author  : aizawa
*******************************************************/

// ▼保険情報管理クラス
class hoken_info_class {
	var $m_user_id;
	var $m_seiho_data;
	var $m_sonpo_data;
	var $m_update_date;
	var $m_type;
	var $m_exist_flag;
	var $_life_insu_id;

	// ■コンストラクタ
	function hoken_info_class($user_id) {
		$this->m_user_id = $user_id;
		$this->m_seiho_data = array();
		$this->m_sonpo_data = array();
		$this->m_type = 'hoken';
		$this->m_exist_flag = false;
		$this->_life_insu_id = '';
	}

	// ■DBから読み込み
	function read_from_db() {
		$this->_read_basic();
		if ($this->_life_insu_id != '') {
			$this->_read_death();
			$this->_read_medical();
			$this->_read_oldage();
			$this->_read_inposs();
//#			$this->_read_nursing();
			$this->_read_special();
		}
		$this->_read_sonpo();
	}

	// ■生命保険基本情報テーブルから読み込み
	function _read_basic() {
		$life_insu_id = array();

		$sql = "SELECT"
					. " life_insu_id"
					. ",areas_number"
					. ",insurant_kind"
					. ",item_name"
					. ",insurance_company"
					. ",main_contract_kind"
					. ",contract_date"
					. ",main_contractor_kind"
					. ",death_continue_kind"
					. ",insurance_paym_kind"
					. ",insurance_amount"
					. ",life_invari_amount_b"
					. ",contract_form_kind"
					. ",update_datetime"
				. " FROM myhoken.life_insurance"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND make_status='1'"
				. " ORDER BY life_insu_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['基本'] = $fetch;

			$life_insu_id[] = $fetch->life_insu_id;

			if ($this->m_update_date < $fetch->update_datetime)
				$this->m_update_date = $fetch->update_datetime;
		}

		if ($nrow > 0)
			$this->m_exist_flag = true;

		$this->_life_insu_id = join(',', $life_insu_id);
	}

	// ■生命保険死亡情報テーブルから読み込み
	function _read_death() {
		$sql = "SELECT"
					. " life_insu_id"
					. ",duration_policy_kind AS duration_policy_kind"
					. ",regular_update_year AS regular_update_year"
					. ",dont_update_age AS dont_update_age"
					. ",expiration_age AS expiration_age"
					. ",unsmoking_discount_kind"
					. ",health_discount_kind"
					. ",death_main_covere_amount"
					. ",death_main_educat_amount"
					. ",death_main_period_year"
					. ",death_main_dim_amount"
					. ",death_main_dis_amount"
					. ",execute_insurance_kind"
				. " FROM myhoken.life_insurance_death"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND life_insu_id IN ($this->_life_insu_id)";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['死亡'] = $fetch;
		}
	}

	// ■生命保険医療情報テーブルから読み込み
	function _read_medical() {
		$sql = "SELECT"
					. " life_insu_id"
					. ",hosp_amount"
					. ",injury_amount"
					. ",adult_amount"
					. ",cancer_amount"
					. ",disease_lump"
					. ",cancer_lump"
					. ",start_prov_kind"
					. ",before_begin_kind"
					. ",medi_main_supply_period"
					. ",duration_policy_kind AS duration_policy_kind"
					. ",regular_update_year AS regular_update_year"
					. ",dont_update_age AS dont_update_age"
					. ",expiration_age AS expiration_age"
					. ",execute_insurance_kind"
				. " FROM myhoken.life_insurance_medical"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND life_insu_id IN ($this->_life_insu_id)";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['医療'] = $fetch;
		}
	}

	// ■生命保険老後情報テーブルから読み込み
	function _read_oldage() {
		$sql = "SELECT"
					. " life_insu_id"
					. ",oldage_pension_amount"
					. ",oldage_start_prov_age"
					. ",oldage_supply_period_year"
					. ",oldage_all_reg_kind"
					. ",oldage_expiration_age"
					. ",oldage_expiration_amount"
				. " FROM myhoken.life_insurance_oldage"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND life_insu_id IN ($this->_life_insu_id)";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['老後'] = $fetch;
		}
	}

	// ■生命保険就業不能情報テーブルから読み込み
	function _read_inposs() {
		$sql = "SELECT"
					. " life_insu_id"
					. ",supply_kind"
					. ",fixed_amount"
					. ",fixed_rate"
					. ",expiration_60_kind"
					. ",regular_60_year"
					. ",immunity_day"
				. " FROM myhoken.life_insurance_inposs"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND life_insu_id IN ($this->_life_insu_id)";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['就業不能'] = $fetch;
		}
	}

	// ■生命保険介護情報テーブルから読み込み
	function _read_nursing() {
		$sql = "SELECT"
					. " life_insu_id"
					. ",amount"
					. ",supply_period"
				. " FROM myhoken.life_insurance_nursing"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND life_insu_id IN ($this->_life_insu_id)";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['介護'] = $fetch;
		}
	}

	// ■生命保険特約情報テーブルから読み込み
	function _read_special() {
		$sql = "SELECT"
					. " life_insu_id"
					. ",special_kind"
					. ",death_select_kind"
					. ",death_covere_amount"
					. ",death_educat_amount"
					. ",death_period_year"
					. ",death_dim_amount"
					. ",death_dis_amount"
					. ",death_policy_kind"
					. ",death_poss_regular_year"
					. ",death_imposs_regular_age"
					. ",death_unsmoking_discount_kind"
					. ",death_health_discount_kind"
					. ",death_execute_insurance_kind"
					. ",medi_select_kind"
					. ",medi_disease_dayamount"
					. ",medi_injury_dayamount"
					. ",medi_geriatric_dayamount"
					. ",medi_cancer_dayamount"
					. ",medi_disease_lumpamount"
					. ",medi_cancer_lumpamount"
					. ",medi_supply_begin_kind"
					. ",medi_for_period_kind"
					. ",medi_supply_period_day"
					. ",medi_policy_kind"
					. ",medi_poss_regular_year"
					. ",medi_imposs_regular_age"
					. ",medi_duration_policy_age"
					. ",medi_execute_insurance_kind"
					. ",oldage_select_kind"
					. ",oldage_pension_amount"
					. ",oldage_supply_begin_age"
					. ",oldage_supply_period"
					. ",oldage_life_regular_kind"
					. ",oldage_expiration_age"
					. ",oldage_expiration_amount"
					. ",imposs_select_kind"
					. ",imposs_supply_kind"
					. ",imposs_fixed_amount"
					. ",imposs_fixed_rate"
					. ",imposs_60expiration_kind"
					. ",imposs_regular_year"
					. ",imposs_immunity_period"
					. ",ind_liability_kind"
					. ",ind_liability_amount"
				. " FROM myhoken.life_special_contract"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND life_insu_id IN ($this->_life_insu_id)";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_seiho_data[$fetch->life_insu_id]['特約'] = $fetch;
		}
	}

	// ■損保情報テーブルから読み込み
	function _read_sonpo() {
		$sql = "SELECT"
					. " dama_insu_id"
					. ",areas_number"
					. ",insurant_kind"
					. ",brand_name"
					. ",ins_company_name"
					. ",contract_start_date"
					. ",period_year"
					. ",contract_period_date"
					. ",item_kind"
					. ",main_item_kind"
					. ",dam_fire_ins_amount"
					. ",dam_eart_ins_amount"
					. ",dam_fire_hold_amount"
					. ",dam_eart_hold_amount"
					. ",dam_rent_comp_amount"
					. ",dam_real_comp_amount"
					. ",dam_person_comp_amount"
					. ",dam_passenger_amount"
					. ",dam_disadvantage_amount"
					. ",dam_personal_amount"
					. ",dam_vehicle_amount"
					. ",dam_individual_amount"
					. ",dam_other1"
					. ",dam_other1_amount"
					. ",dam_other2"
					. ",dam_other2_amount"
					. ",curr_pension_amount"
					. ",curr_start_prov_age"
					. ",curr_paym_period"
					. ",curr_all_reg_kind"
					. ",curr_expiration_age"
					. ",curr_expiration_amount"
					. ",cons_pension_amount"
					. ",cons_start_prov_age"
					. ",cons_paym_period"
					. ",cons_all_reg_kind"
					. ",cons_expiration_age"
					. ",cons_expiration_amount"
					. ",eval_fire_ins_amount"
					. ",eval_eart_ins_amount"
					. ",eval_fire_hold_amount"
					. ",eval_eart_hold_amount"
//#					. ",car_driving_age"
//#					. ",car_family_limit_kind"
//#					. ",car_usage_kind"
//#					. ",car_grade"
//#					. ",car_insurance_kind"
//#					. ",eval_newvalue_presence"
//#					. ",oth_special1"
//#					. ",oth_special2"
					. ",insurance_paym_kind"
					. ",insurance_amount"
					. ",join_details_kind"
					. ",update_datetime"
				. " FROM myhoken.damage_insurance"
				. " WHERE user_id=" . sql_number($this->m_user_id) . " AND make_status='1'"
				. " ORDER BY dama_insu_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_sonpo_data[$fetch->dama_insu_id] = $fetch;

			if ($this->m_update_date < $fetch->update_datetime)
				$this->m_update_date = $fetch->update_datetime;
		}

		if ($nrow > 0)
			$this->m_exist_flag = true;
	}

	// ■年間保険料取得
	function get_yearly_insurance() {
		$amount0 = 0;
		$amount1 = 0;

		// 生保
		foreach ($this->m_seiho_data as $seiho_data) {
			$fetch = &$seiho_data['基本'];

			$amount = $this->_get_yearly_insurance($fetch->insurance_paym_kind, $fetch->insurance_amount);

			if ($fetch->insurant_kind == '0')
				$amount0 += $amount;
			elseif ($fetch->insurant_kind == '1')
				$amount1 += $amount;
		}

		// 損保
		foreach ($this->m_sonpo_data as $fetch) {
			$amount = $this->_get_yearly_insurance($fetch->insurance_paym_kind, $fetch->insurance_amount);

			$amount0 += $amount;
		}

		return array($amount0, $amount1);
	}

	// ■年間保険料算出
	function _get_yearly_insurance($insurance_paym_kind, $insurance_amount) {
		switch ($insurance_paym_kind) {
		case '1':		// 月払い
			$amount = $insurance_amount * 12;
			break;
		case '2':		// 半年払い
			$amount = $insurance_amount * 2;
			break;
		case '3':		// 年払い
			$amount = $insurance_amount;
			break;
		default:
			$amount = 0;
			break;
		}

		return $amount;
	}
}
?>