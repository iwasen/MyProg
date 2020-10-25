<?
/******************************************************
* system  : xxx リスク診断
* title   : 基本情報管理クラス
* content : リスク診断の基本情報を管理するクラス
* version : 1.0
* author  : aizawa
*******************************************************/

// ▼基本情報管理クラス
class basic_info_class {
	var $m_user_id;
	var $m_company_id;
	var $m_basic_info;
	var $m_update_date;

	// ■コンストラクタ
	function basic_info_class($user_id) {
		$this->m_user_id = $user_id;

		// 企業IDを取得
		$sql = "SELECT company_id FROM myhoken.member WHERE user_id=" . sql_number($this->m_user_id);
		$this->m_company_id = db_fetch1($sql);
	}

	// ■DOMからの読み込み
	function read_from_dom($node) {
		$this->m_basic_info = array();
		dom_to_array($node, $this->m_basic_info);
	}

	// ■DBから読み込み
	function read_from_db() {
		// 基本情報読み込み
		$sql = "SELECT * FROM myhoken.risk_diagnosis WHERE user_id=" . sql_number($this->m_user_id);
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$s = &$this->m_basic_info;
			$s['作成ステータス'] = $fetch->make_status;																					// 作成ステータス
			$s['あなた']['本人']['性別'] = $fetch->user_sex;																		// 基本）性別
			$s['あなた']['本人']['結婚'] = $fetch->marriage_kind;																// 基本）結婚
			$s['あなた']['本人']['生年月日'] = ymd_to_date($fetch->birth_date);									// 基本）本人生年月日
			$s['あなた']['配偶者']['生年月日'] = ymd_to_date($fetch->consort_birth_date);				// 基本）配偶者生年月日
			$s['あなた']['本人']['年収'] = $fetch->annual_income;																// 基本）本人年収金額
			$s['あなた']['配偶者']['年収'] = $fetch->consort_annual_income;											// 基本）配偶者年収金額
			$s['あなた']['本人']['勤続年数'] = $fetch->length_of_service;												// 基本）本人勤続年数
			$s['あなた']['配偶者']['勤続年数'] = $fetch->consort_length_of_service;							// 基本）配偶者勤続年数
			$s['あなた']['本人']['喫煙'] = $fetch->smoking_kind;																// 基本）本人喫煙種別
			$s['あなた']['配偶者']['喫煙'] = $fetch->consort_smoking_kind;											// 基本）配偶者喫煙種別
			$s['あなた']['本人']['健康'] = $fetch->health_kind;																	// 基本）本人健康種別
			$s['あなた']['配偶者']['健康'] = $fetch->consort_health_kind;												// 基本）配偶者健康種別
			$s['あなた']['本人']['運転'] = $fetch->driving_kind;																// 基本）本人車運転種別
			$s['あなた']['配偶者']['運転'] = $fetch->consort_driving_kind;											// 基本）配偶者車運転種別
			$s['あなた']['貯蓄']['現在'] = $fetch->now_savings;																	// 基本）現在貯蓄金額
			$s['あなた']['貯蓄']['年間'] = $fetch->year_savings;																// 基本）年間貯蓄金額
			$s['あなた']['貯蓄']['想定'] = $fetch->sixty_years_savings;													// 基本）60歳想定貯蓄金額
			$s['住宅']['住まい'] = $fetch->dwelling_kind;																				// 基本）住まい種別
			$s['あなた']['本人']['子供'] = $fetch->education_child_count;												// 教育）子供人数選択
			$s['住宅']['賃貸']['形態'] = $fetch->life_house_form;																// 生活）賃貸住宅の形態種別
			$s['住宅']['賃貸']['家賃'] = $fetch->life_lease_house_amount;												// 生活）賃貸家賃金額
			$s['住宅']['持家']['形態'] = $fetch->life_house_form;																// 生活）持家住宅の形態種別
			$s['住宅']['持家']['建物面積'] = $fetch->life_own_house_floor;											// 生活）持家床面積
			$s['住宅']['持家']['購入価格'] = $fetch->life_own_house_price;											// 生活）持家購入価格
			$s['住宅']['持家']['年間維持費'] = $fetch->life_own_house_mainte;										// 生活）持家年間維持費
			$s['住宅']['ローン']['住宅ローン数'] = $fetch->life_housing_loan_count;							// 生活）持家住宅ローン数
			$s['住宅']['ローン']['その他ローン数'] = $fetch->life_other_loan_count;							// 生活）持家その他ローン数
			$s['老後']['本人']['退職予定年齢'] = $fetch->oldage_retire_age;											// 老後）本人リタイア予定年齢
			$s['老後']['本人']['想定退職金'] = $fetch->oldage_assump_retire;										// 老後）本人想定退職金額
			$s['老後']['本人']['退職時想定年収'] = $fetch->oldage_assump_retire_income;					// 老後）本人定年時想定年収金額
			$s['老後']['本人']['退職後年間収入'] = $fetch->oldage_retire_income;								// 老後）本人定年以降年収金額
			$s['老後']['本人']['老後生活費'] = $fetch->oldage_old_living_cost;									// 老後）老後月生活費金額
			$s['老後']['本人']['実質月生活費'] = $fetch->now_life_amount;												// 老後）実質月生活費
			$s['老後']['配偶者']['退職予定年齢'] = $fetch->oldage_cnt_retire_age;								// 老後）配偶者リタイア予定年齢
			$s['老後']['配偶者']['想定退職金'] = $fetch->oldage_cnt_assump_retire;							// 老後）配偶者想定退職金額
			$s['老後']['配偶者']['退職時想定年収'] = $fetch->oldage_cnt_assump_retire_income;		// 老後）配偶者定年時想定年収金額
			$s['老後']['配偶者']['退職後年間収入'] = $fetch->oldage_cnt_retire_income;					// 老後）配偶者定年以降年収金額
			$s['万一']['本人']['死亡退職金'] = $fetch->egency_death_retire;											// 万一）本人死亡退職金額
			$s['万一']['本人']['死亡時賃貸住宅方針'] = $fetch->egency_death_leath;							// 万一）本人死亡時の賃貸住宅方針
			$s['万一']['本人']['死亡時生活費'] = $fetch->egency_death_living_cost;							// 万一）本人死亡月生活費金額
			$s['万一']['本人']['葬儀費用'] = $fetch->egency_funeral_cost;												// 万一）本人葬儀費用種別
			$s['万一']['本人']['入院スタイル'] = $fetch->egency_hospital_style;									// 万一）本人入院スタイル
			$s['万一']['本人']['就業不能時賃貸住宅方針'] = $fetch->egency_dwk_lease_form;				// 万一）本人就業不能賃貸住宅方針種別
			$s['万一']['本人']['就業不能時生活費'] = $fetch->egency_dwk_living_cost;						// 万一）本人就業不能月生活費金額
			$s['万一']['本人']['家事自立年齢'] = $fetch->egency_child_age;											// 万一）本人子供自立年齢
			$s['万一']['本人']['家事代替費用金額'] = $fetch->egency_child_add_amount;						// 万一）本人子供自立追加費金額
			$s['万一']['本人']['家事代替費用'] = $fetch->egency_child_add_amount_kind;					// 万一）本人子供自立月費用種別
			$s['万一']['本人']['保育園費'] = $fetch->egency_care_kind;													// 万一）本人保育園月費用種別
			$s['万一']['配偶者']['死亡退職金'] = $fetch->egency_cnt_death_retire;								// 万一）配偶者死亡退職金額
			$s['万一']['配偶者']['死亡時賃貸住宅方針'] = $fetch->egency_cnt_death_leath;				// 万一）配偶者死亡時の賃貸住宅方針
			$s['万一']['配偶者']['死亡時生活費'] = $fetch->egency_cnt_death_living_cost;				// 万一）配偶者死亡月生活費金額
			$s['万一']['配偶者']['葬儀費用'] = $fetch->egency_cnt_funeral_cost;									// 万一）配偶者葬儀費用種別
			$s['万一']['配偶者']['入院スタイル'] = $fetch->egency_cnt_hospital_style;						// 万一）配偶者入院スタイル
			$s['万一']['配偶者']['就業不能時賃貸住宅方針'] = $fetch->egency_cnt_dwk_lease_form;	// 万一）配偶者就業不能賃貸住宅方針種別
			$s['万一']['配偶者']['就業不能時生活費'] = $fetch->egency_cnt_dwk_living_cost;			// 万一）配偶者就業不能月生活費金額
			$s['万一']['配偶者']['家事自立年齢'] = $fetch->egency_cnt_child_age;								// 万一）配偶者子供自立年齢
			$s['万一']['配偶者']['家事代替費用金額'] = $fetch->egency_cnt_child_add_amount;			// 万一）配偶者子供自立追加費金額
			$s['万一']['配偶者']['家事代替費用'] = $fetch->egency_cnt_child_add_amount_kind;		// 万一）配偶者子供自立月費用種別
			$s['万一']['配偶者']['保育園費'] = $fetch->egency_cnt_care_kind;										// 万一）配偶者保育園月費用種別

			// 更新日取得
			$this->m_update_date = $fetch->update_datetime;

			// 子供詳細情報読み込み
			$sql = "SELECT * FROM myhoken.risk_child_detail WHERE user_id=" . sql_number($this->m_user_id);
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				$n = $fetch->div_id;		// 明細番号

				$s['子供']["子供$n"]['生年月日'] = ymd_to_date($fetch->child_birth_date);	// 子供生年月日
				$s['教育']["子供$n"]['幼稚園'] = $fetch->kindergarten_kind;		// 幼稚園種別
				$s['教育']["子供$n"]['小学'] = $fetch->elementary_kind;				// 小学校種別
				$s['教育']["子供$n"]['中学'] = $fetch->juniorhigh_kind;				// 中学校種別
				$s['教育']["子供$n"]['高校'] = $fetch->high_school_kind;			// 高校種別
				$s['教育']["子供$n"]['大学'] = $fetch->university_kind;				// 大学・専門種別
				$s['教育']["子供$n"]['大学年数'] = $fetch->university_years;	// 大学・専門年数選択
				$s['教育']["子供$n"]['仕送り'] = $fetch->remittance_kind;			// 仕送り・下宿種別
				$s['子供']["子供$n"]['扶養'] = $fetch->dont_support;					// 扶養していない
			}

			// ローン明細情報読み込み
			$housing_loan_no = 0;
			$other_loan_no = 0;
			$sql = "SELECT * FROM myhoken.risk_loan_detail WHERE user_id=" . sql_number($this->m_user_id) . " ORDER BY div_id";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				$n = $fetch->div_id;		// 明細番号

				switch ($fetch->loan_kind) {
				case 1:		// 住宅ローン
					$housing_loan_no++;
					$t = &$s['住宅']['ローン']["住宅$housing_loan_no"];

					$t['年間返済額'] = $fetch->repayment_amount;			// 年間返済金額
					$t['返済終了年度'] = $fetch->repayment_end_year;	// 返済終了年
					$t['金利'] = $fetch->now_rate;										// 現在の金利
					$t['名義'] = $fetch->loan_name_kind;							// ローン名義種別
					$t['団信保険'] = $fetch->group_insurance_kind;		// 団体信用保険種別
					break;
				case 2:		// その他ローン
					$other_loan_no++;
					$t = &$s['住宅']['ローン']["その他$other_loan_no"];

					$t['年間返済額'] = $fetch->repayment_amount;			// 年間返済金額
					$t['返済終了年度'] = $fetch->repayment_end_year;	// 返済終了年
					$t['金利'] = $fetch->now_rate;										// 現在の金利
					$t['名義'] = $fetch->loan_name_kind;							// ローン名義種別
					break;
				}
			}

			$exist = true;
		} else
			$exist = false;

		return $exist;
	}

	// ■DBへの書き出し
	function write_to_db() {
		$s = &$this->m_basic_info;

		// 基本情報書き出し
		$rec['user_sex'] = sql_char($s['あなた']['本人']['性別']);														// 基本）性別
		$rec['marriage_kind'] = sql_char($s['あなた']['本人']['結婚']);												// 基本）結婚
		$rec['birth_date'] = sql_char(date_to_ymd($s['あなた']['本人']['生年月日']));					// 基本）本人生年月日
		$rec['consort_birth_date'] = sql_char(date_to_ymd($s['あなた']['配偶者']['生年月日']));	// 基本）配偶者生年月日
		$rec['annual_income'] = sql_number($s['あなた']['本人']['年収']);											// 基本）本人年収金額
		$rec['consort_annual_income'] = sql_number($s['あなた']['配偶者']['年収']);						// 基本）配偶者年収金額
		$rec['length_of_service'] = sql_number($s['あなた']['本人']['勤続年数']);							// 基本）本人勤続年数
		$rec['consort_length_of_service'] = sql_number($s['あなた']['配偶者']['勤続年数']);		// 基本）配偶者勤続年数
		$rec['smoking_kind'] = sql_char($s['あなた']['本人']['喫煙']);												// 基本）本人喫煙種別
		$rec['consort_smoking_kind'] = sql_char($s['あなた']['配偶者']['喫煙']);							// 基本）配偶者喫煙種別
		$rec['health_kind'] = sql_char($s['あなた']['本人']['健康']);													// 基本）本人健康種別
		$rec['consort_health_kind'] = sql_char($s['あなた']['配偶者']['健康']);								// 基本）配偶者健康種別
		$rec['driving_kind'] = sql_char($s['あなた']['本人']['運転']);												// 基本）本人車運転種別
		$rec['consort_driving_kind'] = sql_char($s['あなた']['配偶者']['運転']);							// 基本）配偶者車運転種別
		$rec['now_savings'] = sql_number($s['あなた']['貯蓄']['現在']);												// 基本）現在貯蓄金額
		$rec['year_savings'] = sql_number($s['あなた']['貯蓄']['年間']);											// 基本）年間貯蓄金額
		$rec['sixty_years_savings'] = sql_number($s['あなた']['貯蓄']['想定']);								// 基本）60歳想定貯蓄金額
		$rec['dwelling_kind'] = sql_char($s['住宅']['住まい']);																// 基本）住まい種別
		$rec['education_child_count'] = sql_number($s['あなた']['本人']['子供']);							// 教育）子供人数選択
		$rec['life_house_form'] = sql_char($s['住宅']['住まい'] == 1 ? $s['住宅']['持家']['形態'] : $s['住宅']['賃貸']['形態']);	// 生活）賃貸住宅の形態種別
		$rec['life_lease_house_amount'] = sql_number($s['住宅']['賃貸']['家賃']);							// 生活）賃貸家賃金額
		$rec['life_own_house_floor'] = sql_number($s['住宅']['持家']['建物面積']);						// 生活）持家床面積
		$rec['life_own_house_price'] = sql_number($s['住宅']['持家']['購入価格']);						// 生活）持家購入価格
		$rec['life_own_house_mainte'] = sql_number($s['住宅']['持家']['年間維持費']);					// 生活）持家年間維持費
		$rec['life_housing_loan_count'] = sql_number($s['住宅']['ローン']['住宅ローン数']);		// 生活）持家住宅ローン数
		$rec['life_other_loan_count'] = sql_number($s['住宅']['ローン']['その他ローン数']);		// 生活）持家その他ローン数
		$rec['oldage_retire_age'] = sql_number($s['老後']['本人']['退職予定年齢']);						// 老後）本人リタイア予定年齢
		$rec['oldage_assump_retire'] = sql_number($s['老後']['本人']['想定退職金']);					// 老後）本人想定退職金額
		$rec['oldage_assump_retire_income'] = sql_number($s['老後']['本人']['退職時想定年収']);		// 老後）本人定年時想定年収金額
		$rec['oldage_retire_income'] = sql_number($s['老後']['本人']['退職後年間収入']);			// 老後）本人定年以降年収金額
		$rec['oldage_cnt_retire_age'] = sql_number($s['老後']['配偶者']['退職予定年齢']);			// 老後）配偶者リタイア予定年齢
		$rec['oldage_cnt_assump_retire'] = sql_number($s['老後']['配偶者']['想定退職金']);		// 老後）配偶者想定退職金額
		$rec['oldage_cnt_assump_retire_income'] = sql_number($s['老後']['配偶者']['退職時想定年収']);		// 老後）配偶者定年時想定年収金額
		$rec['oldage_cnt_retire_income'] = sql_number($s['老後']['配偶者']['退職後年間収入']);	// 老後）配偶者定年以降年収金額
		$rec['oldage_old_living_cost'] = sql_number($s['老後']['本人']['老後生活費']);				// 老後）老後月生活費金額
		$rec['egency_death_retire'] = sql_number($s['万一']['本人']['死亡退職金']);						// 万一）本人死亡退職金額
		$rec['egency_death_leath'] = sql_char($s['万一']['本人']['死亡時賃貸住宅方針']);			// 万一）本人死亡時の賃貸住宅方針
		$rec['egency_death_living_cost'] = sql_number($s['万一']['本人']['死亡時生活費']);		// 万一）本人死亡月生活費金額
		$rec['egency_funeral_cost'] = sql_char($s['万一']['本人']['葬儀費用']);								// 万一）本人葬儀費用種別
		$rec['egency_hospital_style'] = sql_char($s['万一']['本人']['入院スタイル']);					// 万 一）本人入院スタイル
		$rec['egency_dwk_lease_form'] = sql_char($s['万一']['本人']['就業不能時賃貸住宅方針']);	// 万一）本人就業不能賃貸住宅方針種別
		$rec['egency_dwk_living_cost'] = sql_number($s['万一']['本人']['就業不能時生活費']);	// 万一）本人就業不能月生活費金額
		$rec['egency_child_age'] = sql_number($s['万一']['本人']['家事自立年齢']);						// 万一）本人子供自立年齢
		$rec['egency_child_add_amount'] = sql_number($s['万一']['本人']['家事代替費用金額']);	// 万一）本人子供自立追加費金額
		$rec['egency_child_add_amount_kind'] = sql_char($s['万一']['本人']['家事代替費用']);	// 万一）本人子供自立月費用種別
		$rec['egency_care_kind'] = sql_char($s['万一']['本人']['保育園費']);									// 万一）本人保育園月費用種別
		$rec['egency_cnt_death_retire'] = sql_number($s['万一']['配偶者']['死亡退職金']);			// 万一）配偶者死亡退職金額
		$rec['egency_cnt_death_leath'] = sql_char($s['万一']['配偶者']['死亡時賃貸住宅方針']);		// 万一）配偶者死亡時の賃貸住宅方針
		$rec['egency_cnt_death_living_cost'] = sql_number($s['万一']['配偶者']['死亡時生活費']);	// 万一）配偶者死亡月生活費金額
		$rec['egency_cnt_funeral_cost'] = sql_char($s['万一']['配偶者']['葬儀費用']);					// 万一）配偶者葬儀費用種別
		$rec['egency_cnt_hospital_style'] = sql_char($s['万一']['配偶者']['入院スタイル']);		// 万一）配偶者入院スタイル
		$rec['egency_cnt_dwk_lease_form'] = sql_char($s['万一']['配偶者']['就業不能時賃貸住宅方針']);		// 万一）配偶者就業不能賃貸住宅方針種別
		$rec['egency_cnt_dwk_living_cost'] = sql_number($s['万一']['配偶者']['就業不能時生活費']);		// 万一）配偶者就業不能月生活費金額
		$rec['egency_cnt_child_age'] = sql_number($s['万一']['配偶者']['家事自立年齢']);			// 万一）配偶者子供自立年齢
		$rec['egency_cnt_child_add_amount'] = sql_number($s['万一']['配偶者']['家事代替費用金額']);		// 万一）配偶者子供自立追加費金額
		$rec['egency_cnt_child_add_amount_kind'] = sql_char($s['万一']['配偶者']['家事代替費用']);		// 万一）配偶者子供自立月費用種別
		$rec['egency_cnt_care_kind'] = sql_char($s['万一']['配偶者']['保育園費']);						// 万一）配偶者保育園月費用種別
		$rec['now_life_amount'] = sql_number($s['老後']['本人']['実質月生活費']);							// 現在月生活費金額
		$rec['make_status'] = sql_char($s['作成ステータス']);																	// 作成ステータス
		$rec['update_user_id'] = sql_number($this->m_user_id);																// 更新者ID
		$rec['update_datetime'] = 'current_timestamp';																				// 更新日時

		$sql = "SELECT COUNT(*) FROM myhoken.risk_diagnosis WHERE user_id=" . sql_number($this->m_user_id);
		if (db_fetch1($sql) == 0) {
			$rec['user_id'] = sql_number($this->m_user_id);						// ユーザID
			$rec['create_user_id'] = sql_number($this->m_user_id);		// 登録者ID
			$rec['create_datetime'] = 'current_timestamp';						// 登録日時
			db_insert('myhoken.risk_diagnosis', $rec);
		} else {
			db_update('myhoken.risk_diagnosis', $rec, "user_id=" . sql_number($this->m_user_id));
		}

		// 子供詳細情報書き出し
		db_delete('myhoken.risk_child_detail', "user_id=" . sql_number($this->m_user_id));
		$dev_id = 0;
		for ($i = 0; $i < $s['あなた']['本人']['子供']; $i++) {
			$dev_id++;

			$n = $i + 1;

			$rec['user_id'] = sql_number($this->m_user_id);														// ユーザID
			$rec['div_id'] = sql_number($dev_id);																			// 明細番号
			$rec['child_birth_date'] = sql_char(date_to_ymd($s['子供']["子供$n"]['生年月日']));		// 子供生年月日
			$rec['kindergarten_kind'] = sql_char($s['教育']["子供$n"]['幼稚園']);			// 幼稚園種別
			$rec['elementary_kind'] = sql_char($s['教育']["子供$n"]['小学']);					// 小学校種別
			$rec['juniorhigh_kind'] = sql_char($s['教育']["子供$n"]['中学']);					// 中学校種別
			$rec['high_school_kind'] = sql_char($s['教育']["子供$n"]['高校']);				// 高校種別
			$rec['university_kind'] = sql_char($s['教育']["子供$n"]['大学']);					// 大学・専門種別
			$rec['university_years'] = sql_char($s['教育']["子供$n"]['大学年数']);		// 大学・専門年数選択
			$rec['remittance_kind'] = sql_char($s['教育']["子供$n"]['仕送り']);				// 仕送り・下宿種別
			$rec['dont_support'] = sql_char($s['子供']["子供$n"]['扶養']);						// 扶養していない
			$rec['create_user_id'] = sql_number($this->m_user_id);										// 登録者ID
			$rec['create_datetime'] = 'current_timestamp';														// 登録日時
			$rec['update_user_id'] = sql_number($this->m_user_id);										// 更新者ID
			$rec['update_datetime'] = 'current_timestamp';														// 更新日時

			db_insert('myhoken.risk_child_detail', $rec);
		}

		// 住宅ローン明細情報書き出し
		db_delete('myhoken.risk_loan_detail', "user_id=" . sql_number($this->m_user_id));
		$dev_id = 0;
		for ($i = 0; $i < $s['住宅']['ローン']['住宅ローン数']; $i++) {
			$dev_id++;

			$n = $i + 1;
			$t = &$s['住宅']['ローン']["住宅$n"];

			$rec['user_id'] = sql_number($this->m_user_id);								// ユーザID
			$rec['div_id'] = sql_number($dev_id);													// 明細番号
			$rec['loan_kind'] = sql_char('1');														// ローン種別
			$rec['repayment_amount'] = sql_number($t['年間返済額']);			// 年間返済金額
			$rec['repayment_end_year'] = sql_number($t['返済終了年度']);	// 返済終了年
			$rec['now_rate'] = sql_number($t['金利']);										// 現在の金利
			$rec['loan_name_kind'] = sql_char($t['名義']);								// ローン名義種別
			$rec['group_insurance_kind'] = sql_char($t['団信保険']);			// 団体信用保険種別
			$rec['create_user_id'] = sql_number($this->m_user_id);				// 登録者ID
			$rec['create_datetime'] = 'current_timestamp';								// 登録日時
			$rec['update_user_id'] = sql_number($this->m_user_id);				// 更新者ID
			$rec['update_datetime'] = 'current_timestamp';								// 更新日時

			db_insert('myhoken.risk_loan_detail', $rec);
		}

		// その他ローン明細情報書き出し
		for ($i = 0; $i < $s['住宅']['ローン']['その他ローン数']; $i++) {
			$dev_id++;

			$n = $i + 1;
			$t = &$s['住宅']['ローン']["その他$n"];

			$rec['user_id'] = sql_number($this->m_user_id);								// ユーザID
			$rec['div_id'] = sql_number($dev_id);													// 明細番号
			$rec['loan_kind'] = sql_char('2');														// ローン種別
			$rec['repayment_amount'] = sql_number($t['年間返済額']);			// 年間返済金額
			$rec['repayment_end_year'] = sql_number($t['返済終了年度']);	// 返済終了年
			$rec['now_rate'] = sql_number($t['金利']);										// 現在の金利
			$rec['loan_name_kind'] = sql_char($t['名義']);								// ローン名義種別
			$rec['create_user_id'] = sql_number($this->m_user_id);				// 登録者ID
			$rec['create_datetime'] = 'current_timestamp';								// 登録日時
			$rec['update_user_id'] = sql_number($this->m_user_id);				// 更新者ID
			$rec['update_datetime'] = 'current_timestamp';								// 更新日時

			db_insert('myhoken.risk_loan_detail', $rec);
		}
	}

	// ■ステータス処理過程テーブルに追加
	function add_trans_process($status_id) {
		$rec['user_id'] = sql_number($this->m_user_id);						// 対象会員ユーザID
		$rec['throw_user_id'] = sql_number($this->m_user_id);			// サービス発起ユーザID
		$rec['status_id'] = sql_char($status_id);						// システムステータスID
		$rec['service_div_id'] = "(SELECT COALESCE(MAX(service_div_id),0)+1 FROM myhoken.status_trans_process WHERE user_id=$this->m_user_id AND status_id='$status_id')";	// サービス枝番
		$rec['service_detail_id'] = sql_number(1);								// サービス明細枝番
		$rec['company_id'] = sql_number($this->m_company_id);			// 対象企業ID
		$rec['rise_datetime'] = 'current_timestamp';							// 発生日時
		db_insert('myhoken.status_trans_process', $rec);
	}
}
?>