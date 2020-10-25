<?
/******************************************************
* system  : xxx リスク診断
* title   : 会社情報情報管理クラス
* content : リスク診断の会社情報を管理するクラス
* version : 1.0
* author  : aizawa
*******************************************************/

// ▼会社情報情報管理クラス
class company_info_class {
	var $m_user_id;
	var $m_company_id;
	var $m_guarantee;
	var $m_retire_rate;

	// ■コンストラクタ
	function company_info_class($user_id, $company_id) {
		$this->m_user_id = $user_id;
		$this->m_company_id = $company_id;
		$this->m_retire_rate = array();
	}

	// ■DBから読み込み
	function read_from_db() {
		$this->_read_guarantee();
		$this->_read_retire_rate();
	}

	// ■企業保証情報テーブル読み込み
	function _read_guarantee() {
		$sql = "SELECT"
					. " pension_start_age"
					. ",paidholiday_monthly"
					. ",sick_extension_monthly"
					. ",addition_benefit_rate"
					. ",individual_benefit_rate"
					. ",regular_self_amount"
					. ",regular_family_amount"
					. ",group_selfhosp_amount"
					. ",group_familyhosp_amount"
					. ",group_selfattend_amount"
					. ",group_familyattend_amount"
					. ",impossible_benefit_form"
					. ",impossible_benefit_amount"
					. ",impossible_benefit_rate"
					. ",expire60_whether"
					. ",regular_year"
					. ",exemption_days"
					. ",mentaldisorder_whether"
					. ",publicdeduction_whether"
					. ",retire_institution"
					. ",retire_income_base"
					. ",retire_income_update_rate"
					. ",retire_income_max"
					. ",retire_income_min"
					. ",death_institution"
					. ",death_income_base"
					. ",death_income_update_rate"
					. ",death_income_max"
					. ",death_income_min"
					. ",old_institution"
					. ",old_income_calc"
					. ",old_income_manual"
					. ",old_income_base"
					. ",old_income_update_rate"
					. ",old_income_max"
					. ",old_income_min"
					. ",bereaved_institution"
					. ",bereaved_income_calc"
					. ",bereaved_income_manual"
					. ",bereaved_income_base"
					. ",bereaved_income_update_rate"
					. ",bereaved_income_max"
					. ",bereaved_income_min"
					. ",inposs_institution"
					. ",inposs_income_base"
					. ",inposs_income_update_rate"
					. ",inposs_income_max"
					. ",inposs_income_min"
				. " FROM myhoken.company_guarantee"
				. " WHERE company_id=" . sql_number($this->m_company_id);
		$result = db_exec($sql);
		if (pg_num_rows($result))
			$this->m_guarantee = pg_fetch_object($result, 0);
	}

	// ■企業退職金乗率情報テーブル読み込み
	function _read_retire_rate() {
		$sql = "SELECT continuous_of_year,retirement_rate,death_rate,old_pension_rate,survivor_pension_rate,extension_rate"
				. " FROM myhoken.company_retire_rate"
				. " WHERE company_id=" . sql_number($this->m_company_id);
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$this->m_retire_rate[$fetch->continuous_of_year] = $fetch;
		}
	}
}
?>