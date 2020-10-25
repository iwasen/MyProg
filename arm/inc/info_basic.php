<?
/******************************************************
* system  : xxx �ꥹ������
* title   : ���ܾ���������饹
* content : �ꥹ�����Ǥδ��ܾ����������륯�饹
* version : 1.0
* author  : aizawa
*******************************************************/

// �����ܾ���������饹
class basic_info_class {
	var $m_user_id;
	var $m_company_id;
	var $m_basic_info;
	var $m_update_date;

	// �����󥹥ȥ饯��
	function basic_info_class($user_id) {
		$this->m_user_id = $user_id;

		// ���ID�����
		$sql = "SELECT company_id FROM myhoken.member WHERE user_id=" . sql_number($this->m_user_id);
		$this->m_company_id = db_fetch1($sql);
	}

	// ��DOM������ɤ߹���
	function read_from_dom($node) {
		$this->m_basic_info = array();
		dom_to_array($node, $this->m_basic_info);
	}

	// ��DB�����ɤ߹���
	function read_from_db() {
		// ���ܾ����ɤ߹���
		$sql = "SELECT * FROM myhoken.risk_diagnosis WHERE user_id=" . sql_number($this->m_user_id);
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$s = &$this->m_basic_info;
			$s['�������ơ�����'] = $fetch->make_status;																					// �������ơ�����
			$s['���ʤ�']['�ܿ�']['����'] = $fetch->user_sex;																		// ���ܡ�����
			$s['���ʤ�']['�ܿ�']['�뺧'] = $fetch->marriage_kind;																// ���ܡ˷뺧
			$s['���ʤ�']['�ܿ�']['��ǯ����'] = ymd_to_date($fetch->birth_date);									// ���ܡ��ܿ���ǯ����
			$s['���ʤ�']['�۶���']['��ǯ����'] = ymd_to_date($fetch->consort_birth_date);				// ���ܡ��۶�����ǯ����
			$s['���ʤ�']['�ܿ�']['ǯ��'] = $fetch->annual_income;																// ���ܡ��ܿ�ǯ�����
			$s['���ʤ�']['�۶���']['ǯ��'] = $fetch->consort_annual_income;											// ���ܡ��۶���ǯ�����
			$s['���ʤ�']['�ܿ�']['��³ǯ��'] = $fetch->length_of_service;												// ���ܡ��ܿͶ�³ǯ��
			$s['���ʤ�']['�۶���']['��³ǯ��'] = $fetch->consort_length_of_service;							// ���ܡ��۶��Զ�³ǯ��
			$s['���ʤ�']['�ܿ�']['�ʱ�'] = $fetch->smoking_kind;																// ���ܡ��ܿ͵ʱ����
			$s['���ʤ�']['�۶���']['�ʱ�'] = $fetch->consort_smoking_kind;											// ���ܡ��۶��Եʱ����
			$s['���ʤ�']['�ܿ�']['��'] = $fetch->health_kind;																	// ���ܡ��ܿͷ򹯼���
			$s['���ʤ�']['�۶���']['��'] = $fetch->consort_health_kind;												// ���ܡ��۶��Է򹯼���
			$s['���ʤ�']['�ܿ�']['��ž'] = $fetch->driving_kind;																// ���ܡ��ܿͼֱ�ž����
			$s['���ʤ�']['�۶���']['��ž'] = $fetch->consort_driving_kind;											// ���ܡ��۶��Լֱ�ž����
			$s['���ʤ�']['����']['����'] = $fetch->now_savings;																	// ���ܡ˸������߶��
			$s['���ʤ�']['����']['ǯ��'] = $fetch->year_savings;																// ���ܡ�ǯ�����߶��
			$s['���ʤ�']['����']['����'] = $fetch->sixty_years_savings;													// ���ܡ�60���������߶��
			$s['����']['���ޤ�'] = $fetch->dwelling_kind;																				// ���ܡ˽��ޤ�����
			$s['���ʤ�']['�ܿ�']['�Ҷ�'] = $fetch->education_child_count;												// ����˻Ҷ��Ϳ�����
			$s['����']['����']['����'] = $fetch->life_house_form;																// ��������߽���η��ּ���
			$s['����']['����']['����'] = $fetch->life_lease_house_amount;												// ��������߲��¶��
			$s['����']['����']['����'] = $fetch->life_house_form;																// ����˻��Ƚ���η��ּ���
			$s['����']['����']['��ʪ����'] = $fetch->life_own_house_floor;											// ����˻��Ⱦ�����
			$s['����']['����']['��������'] = $fetch->life_own_house_price;											// ����˻��ȹ�������
			$s['����']['����']['ǯ�ְݻ���'] = $fetch->life_own_house_mainte;										// ����˻���ǯ�ְݻ���
			$s['����']['����']['��������'] = $fetch->life_housing_loan_count;							// ����˻��Ƚ�������
			$s['����']['����']['����¾�����'] = $fetch->life_other_loan_count;							// ����˻��Ȥ���¾�����
			$s['Ϸ��']['�ܿ�']['�࿦ͽ��ǯ��'] = $fetch->oldage_retire_age;											// Ϸ����ܿͥ꥿����ͽ��ǯ��
			$s['Ϸ��']['�ܿ�']['�����࿦��'] = $fetch->oldage_assump_retire;										// Ϸ����ܿ������࿦���
			$s['Ϸ��']['�ܿ�']['�࿦������ǯ��'] = $fetch->oldage_assump_retire_income;					// Ϸ����ܿ���ǯ������ǯ�����
			$s['Ϸ��']['�ܿ�']['�࿦��ǯ�ּ���'] = $fetch->oldage_retire_income;								// Ϸ����ܿ���ǯ�ʹ�ǯ�����
			$s['Ϸ��']['�ܿ�']['Ϸ��������'] = $fetch->oldage_old_living_cost;									// Ϸ���Ϸ�����������
			$s['Ϸ��']['�ܿ�']['�¼���������'] = $fetch->now_life_amount;												// Ϸ��˼¼���������
			$s['Ϸ��']['�۶���']['�࿦ͽ��ǯ��'] = $fetch->oldage_cnt_retire_age;								// Ϸ����۶��ԥ꥿����ͽ��ǯ��
			$s['Ϸ��']['�۶���']['�����࿦��'] = $fetch->oldage_cnt_assump_retire;							// Ϸ����۶��������࿦���
			$s['Ϸ��']['�۶���']['�࿦������ǯ��'] = $fetch->oldage_cnt_assump_retire_income;		// Ϸ����۶�����ǯ������ǯ�����
			$s['Ϸ��']['�۶���']['�࿦��ǯ�ּ���'] = $fetch->oldage_cnt_retire_income;					// Ϸ����۶�����ǯ�ʹ�ǯ�����
			$s['����']['�ܿ�']['��˴�࿦��'] = $fetch->egency_death_retire;											// ������ܿͻ�˴�࿦���
			$s['����']['�ܿ�']['��˴�����߽�������'] = $fetch->egency_death_leath;							// ������ܿͻ�˴�������߽�������
			$s['����']['�ܿ�']['��˴��������'] = $fetch->egency_death_living_cost;							// ������ܿͻ�˴����������
			$s['����']['�ܿ�']['������'] = $fetch->egency_funeral_cost;												// ������ܿ������Ѽ���
			$s['����']['�ܿ�']['������������'] = $fetch->egency_hospital_style;									// ������ܿ�������������
			$s['����']['�ܿ�']['������ǽ�����߽�������'] = $fetch->egency_dwk_lease_form;				// ������ܿͽ�����ǽ���߽������˼���
			$s['����']['�ܿ�']['������ǽ��������'] = $fetch->egency_dwk_living_cost;						// ������ܿͽ�����ǽ����������
			$s['����']['�ܿ�']['�Ȼ���Ωǯ��'] = $fetch->egency_child_age;											// ������ܿͻҶ���Ωǯ��
			$s['����']['�ܿ�']['�Ȼ��������Ѷ��'] = $fetch->egency_child_add_amount;						// ������ܿͻҶ���Ω�ɲ�����
			$s['����']['�ܿ�']['�Ȼ���������'] = $fetch->egency_child_add_amount_kind;					// ������ܿͻҶ���Ω�����Ѽ���
			$s['����']['�ܿ�']['�ݰ����'] = $fetch->egency_care_kind;													// ������ܿ��ݰ������Ѽ���
			$s['����']['�۶���']['��˴�࿦��'] = $fetch->egency_cnt_death_retire;								// ������۶��Ի�˴�࿦���
			$s['����']['�۶���']['��˴�����߽�������'] = $fetch->egency_cnt_death_leath;				// ������۶��Ի�˴�������߽�������
			$s['����']['�۶���']['��˴��������'] = $fetch->egency_cnt_death_living_cost;				// ������۶��Ի�˴����������
			$s['����']['�۶���']['������'] = $fetch->egency_cnt_funeral_cost;									// ������۶��������Ѽ���
			$s['����']['�۶���']['������������'] = $fetch->egency_cnt_hospital_style;						// ������۶���������������
			$s['����']['�۶���']['������ǽ�����߽�������'] = $fetch->egency_cnt_dwk_lease_form;	// ������۶��Խ�����ǽ���߽������˼���
			$s['����']['�۶���']['������ǽ��������'] = $fetch->egency_cnt_dwk_living_cost;			// ������۶��Խ�����ǽ����������
			$s['����']['�۶���']['�Ȼ���Ωǯ��'] = $fetch->egency_cnt_child_age;								// ������۶��ԻҶ���Ωǯ��
			$s['����']['�۶���']['�Ȼ��������Ѷ��'] = $fetch->egency_cnt_child_add_amount;			// ������۶��ԻҶ���Ω�ɲ�����
			$s['����']['�۶���']['�Ȼ���������'] = $fetch->egency_cnt_child_add_amount_kind;		// ������۶��ԻҶ���Ω�����Ѽ���
			$s['����']['�۶���']['�ݰ����'] = $fetch->egency_cnt_care_kind;										// ������۶����ݰ������Ѽ���

			// ����������
			$this->m_update_date = $fetch->update_datetime;

			// �Ҷ��ܺپ����ɤ߹���
			$sql = "SELECT * FROM myhoken.risk_child_detail WHERE user_id=" . sql_number($this->m_user_id);
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				$n = $fetch->div_id;		// �����ֹ�

				$s['�Ҷ�']["�Ҷ�$n"]['��ǯ����'] = ymd_to_date($fetch->child_birth_date);	// �Ҷ���ǯ����
				$s['����']["�Ҷ�$n"]['���ձ�'] = $fetch->kindergarten_kind;		// ���ձ����
				$s['����']["�Ҷ�$n"]['����'] = $fetch->elementary_kind;				// ���ع�����
				$s['����']["�Ҷ�$n"]['���'] = $fetch->juniorhigh_kind;				// ��ع�����
				$s['����']["�Ҷ�$n"]['�⹻'] = $fetch->high_school_kind;			// �⹻����
				$s['����']["�Ҷ�$n"]['���'] = $fetch->university_kind;				// ��ء��������
				$s['����']["�Ҷ�$n"]['���ǯ��'] = $fetch->university_years;	// ��ء�����ǯ������
				$s['����']["�Ҷ�$n"]['������'] = $fetch->remittance_kind;			// �����ꡦ���ɼ���
				$s['�Ҷ�']["�Ҷ�$n"]['����'] = $fetch->dont_support;					// ���ܤ��Ƥ��ʤ�
			}

			// �������پ����ɤ߹���
			$housing_loan_no = 0;
			$other_loan_no = 0;
			$sql = "SELECT * FROM myhoken.risk_loan_detail WHERE user_id=" . sql_number($this->m_user_id) . " ORDER BY div_id";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				$n = $fetch->div_id;		// �����ֹ�

				switch ($fetch->loan_kind) {
				case 1:		// �������
					$housing_loan_no++;
					$t = &$s['����']['����']["����$housing_loan_no"];

					$t['ǯ���ֺѳ�'] = $fetch->repayment_amount;			// ǯ���ֺѶ��
					$t['�ֺѽ�λǯ��'] = $fetch->repayment_end_year;	// �ֺѽ�λǯ
					$t['����'] = $fetch->now_rate;										// ���ߤζ���
					$t['̾��'] = $fetch->loan_name_kind;							// ����̾������
					$t['�Ŀ��ݸ�'] = $fetch->group_insurance_kind;		// ���ο����ݸ�����
					break;
				case 2:		// ����¾����
					$other_loan_no++;
					$t = &$s['����']['����']["����¾$other_loan_no"];

					$t['ǯ���ֺѳ�'] = $fetch->repayment_amount;			// ǯ���ֺѶ��
					$t['�ֺѽ�λǯ��'] = $fetch->repayment_end_year;	// �ֺѽ�λǯ
					$t['����'] = $fetch->now_rate;										// ���ߤζ���
					$t['̾��'] = $fetch->loan_name_kind;							// ����̾������
					break;
				}
			}

			$exist = true;
		} else
			$exist = false;

		return $exist;
	}

	// ��DB�ؤν񤭽Ф�
	function write_to_db() {
		$s = &$this->m_basic_info;

		// ���ܾ���񤭽Ф�
		$rec['user_sex'] = sql_char($s['���ʤ�']['�ܿ�']['����']);														// ���ܡ�����
		$rec['marriage_kind'] = sql_char($s['���ʤ�']['�ܿ�']['�뺧']);												// ���ܡ˷뺧
		$rec['birth_date'] = sql_char(date_to_ymd($s['���ʤ�']['�ܿ�']['��ǯ����']));					// ���ܡ��ܿ���ǯ����
		$rec['consort_birth_date'] = sql_char(date_to_ymd($s['���ʤ�']['�۶���']['��ǯ����']));	// ���ܡ��۶�����ǯ����
		$rec['annual_income'] = sql_number($s['���ʤ�']['�ܿ�']['ǯ��']);											// ���ܡ��ܿ�ǯ�����
		$rec['consort_annual_income'] = sql_number($s['���ʤ�']['�۶���']['ǯ��']);						// ���ܡ��۶���ǯ�����
		$rec['length_of_service'] = sql_number($s['���ʤ�']['�ܿ�']['��³ǯ��']);							// ���ܡ��ܿͶ�³ǯ��
		$rec['consort_length_of_service'] = sql_number($s['���ʤ�']['�۶���']['��³ǯ��']);		// ���ܡ��۶��Զ�³ǯ��
		$rec['smoking_kind'] = sql_char($s['���ʤ�']['�ܿ�']['�ʱ�']);												// ���ܡ��ܿ͵ʱ����
		$rec['consort_smoking_kind'] = sql_char($s['���ʤ�']['�۶���']['�ʱ�']);							// ���ܡ��۶��Եʱ����
		$rec['health_kind'] = sql_char($s['���ʤ�']['�ܿ�']['��']);													// ���ܡ��ܿͷ򹯼���
		$rec['consort_health_kind'] = sql_char($s['���ʤ�']['�۶���']['��']);								// ���ܡ��۶��Է򹯼���
		$rec['driving_kind'] = sql_char($s['���ʤ�']['�ܿ�']['��ž']);												// ���ܡ��ܿͼֱ�ž����
		$rec['consort_driving_kind'] = sql_char($s['���ʤ�']['�۶���']['��ž']);							// ���ܡ��۶��Լֱ�ž����
		$rec['now_savings'] = sql_number($s['���ʤ�']['����']['����']);												// ���ܡ˸������߶��
		$rec['year_savings'] = sql_number($s['���ʤ�']['����']['ǯ��']);											// ���ܡ�ǯ�����߶��
		$rec['sixty_years_savings'] = sql_number($s['���ʤ�']['����']['����']);								// ���ܡ�60���������߶��
		$rec['dwelling_kind'] = sql_char($s['����']['���ޤ�']);																// ���ܡ˽��ޤ�����
		$rec['education_child_count'] = sql_number($s['���ʤ�']['�ܿ�']['�Ҷ�']);							// ����˻Ҷ��Ϳ�����
		$rec['life_house_form'] = sql_char($s['����']['���ޤ�'] == 1 ? $s['����']['����']['����'] : $s['����']['����']['����']);	// ��������߽���η��ּ���
		$rec['life_lease_house_amount'] = sql_number($s['����']['����']['����']);							// ��������߲��¶��
		$rec['life_own_house_floor'] = sql_number($s['����']['����']['��ʪ����']);						// ����˻��Ⱦ�����
		$rec['life_own_house_price'] = sql_number($s['����']['����']['��������']);						// ����˻��ȹ�������
		$rec['life_own_house_mainte'] = sql_number($s['����']['����']['ǯ�ְݻ���']);					// ����˻���ǯ�ְݻ���
		$rec['life_housing_loan_count'] = sql_number($s['����']['����']['��������']);		// ����˻��Ƚ�������
		$rec['life_other_loan_count'] = sql_number($s['����']['����']['����¾�����']);		// ����˻��Ȥ���¾�����
		$rec['oldage_retire_age'] = sql_number($s['Ϸ��']['�ܿ�']['�࿦ͽ��ǯ��']);						// Ϸ����ܿͥ꥿����ͽ��ǯ��
		$rec['oldage_assump_retire'] = sql_number($s['Ϸ��']['�ܿ�']['�����࿦��']);					// Ϸ����ܿ������࿦���
		$rec['oldage_assump_retire_income'] = sql_number($s['Ϸ��']['�ܿ�']['�࿦������ǯ��']);		// Ϸ����ܿ���ǯ������ǯ�����
		$rec['oldage_retire_income'] = sql_number($s['Ϸ��']['�ܿ�']['�࿦��ǯ�ּ���']);			// Ϸ����ܿ���ǯ�ʹ�ǯ�����
		$rec['oldage_cnt_retire_age'] = sql_number($s['Ϸ��']['�۶���']['�࿦ͽ��ǯ��']);			// Ϸ����۶��ԥ꥿����ͽ��ǯ��
		$rec['oldage_cnt_assump_retire'] = sql_number($s['Ϸ��']['�۶���']['�����࿦��']);		// Ϸ����۶��������࿦���
		$rec['oldage_cnt_assump_retire_income'] = sql_number($s['Ϸ��']['�۶���']['�࿦������ǯ��']);		// Ϸ����۶�����ǯ������ǯ�����
		$rec['oldage_cnt_retire_income'] = sql_number($s['Ϸ��']['�۶���']['�࿦��ǯ�ּ���']);	// Ϸ����۶�����ǯ�ʹ�ǯ�����
		$rec['oldage_old_living_cost'] = sql_number($s['Ϸ��']['�ܿ�']['Ϸ��������']);				// Ϸ���Ϸ�����������
		$rec['egency_death_retire'] = sql_number($s['����']['�ܿ�']['��˴�࿦��']);						// ������ܿͻ�˴�࿦���
		$rec['egency_death_leath'] = sql_char($s['����']['�ܿ�']['��˴�����߽�������']);			// ������ܿͻ�˴�������߽�������
		$rec['egency_death_living_cost'] = sql_number($s['����']['�ܿ�']['��˴��������']);		// ������ܿͻ�˴����������
		$rec['egency_funeral_cost'] = sql_char($s['����']['�ܿ�']['������']);								// ������ܿ������Ѽ���
		$rec['egency_hospital_style'] = sql_char($s['����']['�ܿ�']['������������']);					// �� ����ܿ�������������
		$rec['egency_dwk_lease_form'] = sql_char($s['����']['�ܿ�']['������ǽ�����߽�������']);	// ������ܿͽ�����ǽ���߽������˼���
		$rec['egency_dwk_living_cost'] = sql_number($s['����']['�ܿ�']['������ǽ��������']);	// ������ܿͽ�����ǽ����������
		$rec['egency_child_age'] = sql_number($s['����']['�ܿ�']['�Ȼ���Ωǯ��']);						// ������ܿͻҶ���Ωǯ��
		$rec['egency_child_add_amount'] = sql_number($s['����']['�ܿ�']['�Ȼ��������Ѷ��']);	// ������ܿͻҶ���Ω�ɲ�����
		$rec['egency_child_add_amount_kind'] = sql_char($s['����']['�ܿ�']['�Ȼ���������']);	// ������ܿͻҶ���Ω�����Ѽ���
		$rec['egency_care_kind'] = sql_char($s['����']['�ܿ�']['�ݰ����']);									// ������ܿ��ݰ������Ѽ���
		$rec['egency_cnt_death_retire'] = sql_number($s['����']['�۶���']['��˴�࿦��']);			// ������۶��Ի�˴�࿦���
		$rec['egency_cnt_death_leath'] = sql_char($s['����']['�۶���']['��˴�����߽�������']);		// ������۶��Ի�˴�������߽�������
		$rec['egency_cnt_death_living_cost'] = sql_number($s['����']['�۶���']['��˴��������']);	// ������۶��Ի�˴����������
		$rec['egency_cnt_funeral_cost'] = sql_char($s['����']['�۶���']['������']);					// ������۶��������Ѽ���
		$rec['egency_cnt_hospital_style'] = sql_char($s['����']['�۶���']['������������']);		// ������۶���������������
		$rec['egency_cnt_dwk_lease_form'] = sql_char($s['����']['�۶���']['������ǽ�����߽�������']);		// ������۶��Խ�����ǽ���߽������˼���
		$rec['egency_cnt_dwk_living_cost'] = sql_number($s['����']['�۶���']['������ǽ��������']);		// ������۶��Խ�����ǽ����������
		$rec['egency_cnt_child_age'] = sql_number($s['����']['�۶���']['�Ȼ���Ωǯ��']);			// ������۶��ԻҶ���Ωǯ��
		$rec['egency_cnt_child_add_amount'] = sql_number($s['����']['�۶���']['�Ȼ��������Ѷ��']);		// ������۶��ԻҶ���Ω�ɲ�����
		$rec['egency_cnt_child_add_amount_kind'] = sql_char($s['����']['�۶���']['�Ȼ���������']);		// ������۶��ԻҶ���Ω�����Ѽ���
		$rec['egency_cnt_care_kind'] = sql_char($s['����']['�۶���']['�ݰ����']);						// ������۶����ݰ������Ѽ���
		$rec['now_life_amount'] = sql_number($s['Ϸ��']['�ܿ�']['�¼���������']);							// ���߷���������
		$rec['make_status'] = sql_char($s['�������ơ�����']);																	// �������ơ�����
		$rec['update_user_id'] = sql_number($this->m_user_id);																// ������ID
		$rec['update_datetime'] = 'current_timestamp';																				// ��������

		$sql = "SELECT COUNT(*) FROM myhoken.risk_diagnosis WHERE user_id=" . sql_number($this->m_user_id);
		if (db_fetch1($sql) == 0) {
			$rec['user_id'] = sql_number($this->m_user_id);						// �桼��ID
			$rec['create_user_id'] = sql_number($this->m_user_id);		// ��Ͽ��ID
			$rec['create_datetime'] = 'current_timestamp';						// ��Ͽ����
			db_insert('myhoken.risk_diagnosis', $rec);
		} else {
			db_update('myhoken.risk_diagnosis', $rec, "user_id=" . sql_number($this->m_user_id));
		}

		// �Ҷ��ܺپ���񤭽Ф�
		db_delete('myhoken.risk_child_detail', "user_id=" . sql_number($this->m_user_id));
		$dev_id = 0;
		for ($i = 0; $i < $s['���ʤ�']['�ܿ�']['�Ҷ�']; $i++) {
			$dev_id++;

			$n = $i + 1;

			$rec['user_id'] = sql_number($this->m_user_id);														// �桼��ID
			$rec['div_id'] = sql_number($dev_id);																			// �����ֹ�
			$rec['child_birth_date'] = sql_char(date_to_ymd($s['�Ҷ�']["�Ҷ�$n"]['��ǯ����']));		// �Ҷ���ǯ����
			$rec['kindergarten_kind'] = sql_char($s['����']["�Ҷ�$n"]['���ձ�']);			// ���ձ����
			$rec['elementary_kind'] = sql_char($s['����']["�Ҷ�$n"]['����']);					// ���ع�����
			$rec['juniorhigh_kind'] = sql_char($s['����']["�Ҷ�$n"]['���']);					// ��ع�����
			$rec['high_school_kind'] = sql_char($s['����']["�Ҷ�$n"]['�⹻']);				// �⹻����
			$rec['university_kind'] = sql_char($s['����']["�Ҷ�$n"]['���']);					// ��ء��������
			$rec['university_years'] = sql_char($s['����']["�Ҷ�$n"]['���ǯ��']);		// ��ء�����ǯ������
			$rec['remittance_kind'] = sql_char($s['����']["�Ҷ�$n"]['������']);				// �����ꡦ���ɼ���
			$rec['dont_support'] = sql_char($s['�Ҷ�']["�Ҷ�$n"]['����']);						// ���ܤ��Ƥ��ʤ�
			$rec['create_user_id'] = sql_number($this->m_user_id);										// ��Ͽ��ID
			$rec['create_datetime'] = 'current_timestamp';														// ��Ͽ����
			$rec['update_user_id'] = sql_number($this->m_user_id);										// ������ID
			$rec['update_datetime'] = 'current_timestamp';														// ��������

			db_insert('myhoken.risk_child_detail', $rec);
		}

		// ����������پ���񤭽Ф�
		db_delete('myhoken.risk_loan_detail', "user_id=" . sql_number($this->m_user_id));
		$dev_id = 0;
		for ($i = 0; $i < $s['����']['����']['��������']; $i++) {
			$dev_id++;

			$n = $i + 1;
			$t = &$s['����']['����']["����$n"];

			$rec['user_id'] = sql_number($this->m_user_id);								// �桼��ID
			$rec['div_id'] = sql_number($dev_id);													// �����ֹ�
			$rec['loan_kind'] = sql_char('1');														// �������
			$rec['repayment_amount'] = sql_number($t['ǯ���ֺѳ�']);			// ǯ���ֺѶ��
			$rec['repayment_end_year'] = sql_number($t['�ֺѽ�λǯ��']);	// �ֺѽ�λǯ
			$rec['now_rate'] = sql_number($t['����']);										// ���ߤζ���
			$rec['loan_name_kind'] = sql_char($t['̾��']);								// ����̾������
			$rec['group_insurance_kind'] = sql_char($t['�Ŀ��ݸ�']);			// ���ο����ݸ�����
			$rec['create_user_id'] = sql_number($this->m_user_id);				// ��Ͽ��ID
			$rec['create_datetime'] = 'current_timestamp';								// ��Ͽ����
			$rec['update_user_id'] = sql_number($this->m_user_id);				// ������ID
			$rec['update_datetime'] = 'current_timestamp';								// ��������

			db_insert('myhoken.risk_loan_detail', $rec);
		}

		// ����¾�������پ���񤭽Ф�
		for ($i = 0; $i < $s['����']['����']['����¾�����']; $i++) {
			$dev_id++;

			$n = $i + 1;
			$t = &$s['����']['����']["����¾$n"];

			$rec['user_id'] = sql_number($this->m_user_id);								// �桼��ID
			$rec['div_id'] = sql_number($dev_id);													// �����ֹ�
			$rec['loan_kind'] = sql_char('2');														// �������
			$rec['repayment_amount'] = sql_number($t['ǯ���ֺѳ�']);			// ǯ���ֺѶ��
			$rec['repayment_end_year'] = sql_number($t['�ֺѽ�λǯ��']);	// �ֺѽ�λǯ
			$rec['now_rate'] = sql_number($t['����']);										// ���ߤζ���
			$rec['loan_name_kind'] = sql_char($t['̾��']);								// ����̾������
			$rec['create_user_id'] = sql_number($this->m_user_id);				// ��Ͽ��ID
			$rec['create_datetime'] = 'current_timestamp';								// ��Ͽ����
			$rec['update_user_id'] = sql_number($this->m_user_id);				// ������ID
			$rec['update_datetime'] = 'current_timestamp';								// ��������

			db_insert('myhoken.risk_loan_detail', $rec);
		}
	}

	// �����ơ��������������ơ��֥���ɲ�
	function add_trans_process($status_id) {
		$rec['user_id'] = sql_number($this->m_user_id);						// �оݲ���桼��ID
		$rec['throw_user_id'] = sql_number($this->m_user_id);			// �����ӥ�ȯ���桼��ID
		$rec['status_id'] = sql_char($status_id);						// �����ƥॹ�ơ�����ID
		$rec['service_div_id'] = "(SELECT COALESCE(MAX(service_div_id),0)+1 FROM myhoken.status_trans_process WHERE user_id=$this->m_user_id AND status_id='$status_id')";	// �����ӥ�����
		$rec['service_detail_id'] = sql_number(1);								// �����ӥ����ٻ���
		$rec['company_id'] = sql_number($this->m_company_id);			// �оݴ��ID
		$rec['rise_datetime'] = 'current_timestamp';							// ȯ������
		db_insert('myhoken.status_trans_process', $rec);
	}
}
?>