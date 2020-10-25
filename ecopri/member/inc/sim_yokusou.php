<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�������ᥨ�����ǽ������饹
'******************************************************/

// �������ᥨ�����ǥ��饹
class sim_yokusou {
	// �����ǡ���
	var $nyuuyoku_summer;		// ��������̵ͭ�ʲơ�
	var $nyuuyoku_sp_aut; 	// ��������̵ͭ�ʽՎ�����
	var $nyuuyoku_winter;		// ��������̵ͭ���ߡ�
	var $yuon_summer;				// �򲹡ʲơ�[��]
	var $yuon_sp_aut;				// �򲹡ʽՎ�����[��]
	var $yuon_winter;				// �򲹡��ߡ�[��]
	var $yuhari_ryou;				// ��Ϥ���[L]
	var $boiler_type;				// ����勵����

	// ���ǥǡ���
	var $yuhari_ryou_e;			// ��Ϥ��Ѳ���[L]
	var $yuhari_flag_e;			// ��Ϥ�岼�ե饰
	var $yuon_e;						// ��

	// ���󥹥ȥ饯��
	function sim_yokusou() {
		// ���饹�ѿ�������
		$this->nyuuyoku_summer = 0;
		$this->nyuuyoku_sp_aut = 0;
		$this->nyuuyoku_winter = 0;
		$this->yuon_summer = 40;
		$this->yuon_sp_aut = 41;
		$this->yuon_winter = 42;
		$this->yuhari_ryou = 210;
		$this->boiler_type = 1;

		$this->yuhari_ryou_e = 0;
		$this->yuhari_flag_e = 2;
		$this->yuon_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$water, &$msg) {
		// �׻����饹����
		$cal = new cal_yokusou;

		// �����ǡ����Ƿ׻�
		$cal->nyuuyoku_summer = $this->nyuuyoku_summer;
		$cal->nyuuyoku_sp_aut = $this->nyuuyoku_sp_aut;
		$cal->nyuuyoku_winter = $this->nyuuyoku_winter;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->yuhari_ryou = $this->yuhari_ryou;
		$cal->boiler_type = $this->boiler_type;
		$cal->calculation($calorie_c, $cost_c, $co2_c, $water_c);

		// ���Ǿ��Ƿ׻�
		$cal->yuhari_ryou += ($this->yuhari_flag_e == 1) ? $this->yuhari_ryou_e : -$this->yuhari_ryou_e;
		$cal->yuon_summer -= $this->yuon_e;
		$cal->yuon_sp_aut -= $this->yuon_e;
		$cal->yuon_winter -= $this->yuon_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e, $water_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;
		$water = $water_c - $water_e;

		// ���ǥ�å���������
		if ($this->yuhari_ryou_e)
			$msg_ary[] = "�������Ϥ��̤� $this->yuhari_ryou_e L(��åȥ�)" . ($this->yuhari_flag_e == 1 ? '¿��' : '���ʤ�') . '��';
		if ($this->yuon_e)
			$msg_ary[] = "������򲹤� $this->yuon_e ���";
		if ($msg_ary)
			$msg = join('��<br>', $msg_ary) . '�ޤ���';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_YOKUSOU);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->nyuuyoku_summer = array_shift($ary);
			$this->nyuuyoku_sp_aut = array_shift($ary);
			$this->nyuuyoku_winter = array_shift($ary);
			$this->yuon_summer = array_shift($ary);
			$this->yuon_sp_aut = array_shift($ary);
			$this->yuon_winter = array_shift($ary);
			$this->yuhari_ryou = array_shift($ary);
			$this->boiler_type = array_shift($ary);
			$this->yuhari_ryou_e = array_shift($ary);
			$this->yuhari_flag_e = array_shift($ary);
			$this->yuon_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->nyuuyoku_summer;
		$ary[] = $this->nyuuyoku_sp_aut;
		$ary[] = $this->nyuuyoku_winter;
		$ary[] = $this->yuon_summer;
		$ary[] = $this->yuon_sp_aut;
		$ary[] = $this->yuon_winter;
		$ary[] = $this->yuhari_ryou;
		$ary[] = $this->boiler_type;
		$ary[] = $this->yuhari_ryou_e;
		$ary[] = $this->yuhari_flag_e;
		$ary[] = $this->yuon_e;

		return join(',', $ary);
	}
}

// ��������׻����饹
class cal_yokusou {
	var $nyuuyoku_summer;		// ��������̵ͭ�ʲơ�
	var $nyuuyoku_sp_aut;		// ��������̵ͭ�ʽՎ�����
	var $nyuuyoku_winter;		// ��������̵ͭ���ߡ�
	var $yuon_summer;				// �򲹡ʲơ�[��]
	var $yuon_sp_aut;				// �򲹡ʽՎ�����[��]
	var $yuon_winter;				// �򲹡��ߡ�[��]
	var $yuhari_ryou;				// ��Ϥ���[L]
	var $boiler_type;				// ����勵����

	// ����꡼�����⡢CO2�����̷׻�
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// �����Ψ����
		$efficiency = $this->get_efficiency($this->boiler_type);

		// ��
		if ($this->nyuuyoku_winter) {
			$calorie_winter = $this->yuhari_ryou * DAYS_WINTER * ($this->yuon_winter - TMP_WINTER) / $efficiency;
			$water_winter = $this->yuhari_ryou * DAYS_WINTER;
		}

		// ��
		if ($this->nyuuyoku_summer) {
			$calorie_summer = $this->yuhari_ryou * DAYS_SUMMER * ($this->yuon_summer - TMP_SUMMER) / $efficiency;
			$water_summer = $this->yuhari_ryou * DAYS_SUMMER;
		}

		// �ս�
		if ($this->nyuuyoku_sp_aut) {
			$calorie_sp_aut = $this->yuhari_ryou * DAYS_SP_AUT * ($this->yuon_sp_aut - TMP_SP_AUT) / $efficiency;
			$water_sp_aut = $this->yuhari_ryou * DAYS_SP_AUT;
		}

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