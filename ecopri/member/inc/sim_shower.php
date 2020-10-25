<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����������ǽ������饹
'******************************************************/

// �����������ǥ��饹
class sim_shower {
	// �����ǡ���
	var $ryuuryou;				// ����ή��[L/ʬ]
	var $yuon_summer;			// �򲹡ʲơ�[��]
	var $yuon_sp_aut;			// �򲹡ʽՎ�����[��]
	var $yuon_winter;			// �򲹡��ߡ�[��]
	var $boiler_type;			// ����勵����

	// ���ǥǡ���
	var $shower_jikan;		// ����û�̻���[ʬ]

	// ���󥹥ȥ饯��
	function sim_shower() {
		// ���饹�ѿ�������
		$this->ryuuryou = 10;
		$this->yuon_summer = 40;
		$this->yuon_sp_aut = 41;
		$this->yuon_winter = 42;
		$this->boiler_type = 1;
		$this->shower_jikan = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$water, &$msg) {
		// �׻����饹����
		$cal = new cal_shower;

		// ���Ǿ��Ƿ׻�
		$cal->ryuuryou = $this->ryuuryou;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->boiler_type = $this->boiler_type;
		$cal->shower_jikan = $this->shower_jikan;
		$cal->calculation($energy, $cost, $co2, $water);

		// ���ǥ�å���������
		if ($this->shower_jikan)
			$msg = "�������ظ����ѻ��֤������� $this->shower_jikan ʬû�����ޤ���";
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_SHOWER);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->ryuuryou = array_shift($ary);
			$this->yuon_summer = array_shift($ary);
			$this->yuon_sp_aut = array_shift($ary);
			$this->yuon_winter = array_shift($ary);
			$this->boiler_type = array_shift($ary);
			$this->shower_jikan = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->ryuuryou;
		$ary[] = $this->yuon_summer;
		$ary[] = $this->yuon_sp_aut;
		$ary[] = $this->yuon_winter;
		$ary[] = $this->boiler_type;
		$ary[] = $this->shower_jikan;

		return join(',', $ary);
	}
}

// �����������Ƿ׻����饹
class cal_shower {
	var $ryuuryou;				// ����ή��[L/ʬ]
	var $yuon_summer;			// �򲹡ʲơ�[��]
	var $yuon_sp_aut;			// �򲹡ʽՎ�����[��]
	var $yuon_winter;			// �򲹡��ߡ�[��]
	var $boiler_type;			// ����勵����
	var $shower_jikan;		// �������ѻ���[ʬ]

	// ����꡼�����⡢CO2�����̷׻�
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// �����Ψ����
		$efficiency = $this->get_efficiency($this->boiler_type);

		// ��
		$calorie_winter = $this->ryuuryou * $this->shower_jikan * DAYS_WINTER * ($this->yuon_winter - TMP_WINTER) / $efficiency;
		$water_winter = $this->ryuuryou * $this->shower_jikan * DAYS_WINTER;

		// ��
		$calorie_summer = $this->ryuuryou * $this->shower_jikan * DAYS_SUMMER * ($this->yuon_summer - TMP_SUMMER) / $efficiency;
		$water_summer = $this->ryuuryou * $this->shower_jikan * DAYS_SUMMER;

		// �ս�
		$calorie_sp_aut = $this->ryuuryou * $this->shower_jikan * DAYS_SP_AUT * ($this->yuon_sp_aut - TMP_SP_AUT) / $efficiency;
		$water_sp_aut = $this->ryuuryou * $this->shower_jikan * DAYS_SP_AUT;

		// ��ס�kcal->Mcal, L->��3)
		$calorie = ($calorie_winter + $calorie_summer + $calorie_sp_aut) / 1000;
		$water = ($water_winter + $water_summer + $water_sp_aut) / 1000;

		// ���⡢CO2�׻�
		switch ($this->boiler_type) {
		case 1:		// ����������
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		case 2:		// �ŵ�������
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 3:		// ����������
			$l = oil_mcal_l($calorie);
			$cost = cnv_oil_mny($l, $this->boiler_type);
			$co2 = cnv_oil_co2($l, $this->boiler_type);
			break;
		}
		$cost += cnv_wtr_mny($water);
		$co2 += cnv_wtr_co2($water);
	}

	// �����Ψ����
	function get_efficiency($boiler_type) {
		switch ($boiler_type) {
		case 1:		// ����������
			return EFF_GAS;
		case 2:		// �ŵ�������
			return EFF_ELE;
		case 3:		// ����������
			return EFF_OIL;
		}
	}
}
?>