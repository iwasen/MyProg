<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ɤ��椭�������ǽ������饹
'******************************************************/

// �ɤ��椭�������ǥ��饹
class sim_oidaki {
	// �����ǡ���
	var $ninzuu_summer;			// ����Ϳ��ʲơ�
	var $ninzuu_sp_aut;			// ����Ϳ��ʽՎ�����
	var $ninzuu_winter;			// ����Ϳ����ߡ�
	var $yuon_summer;				// �򲹡ʲơ�[��]
	var $yuon_sp_aut;				// �򲹡ʽՎ�����[��]
	var $yuon_winter;				// �򲹡��ߡ�[��]
	var $nyuuyoku_kankaku;	// ����ֳ�[h]
	var $yokusou_futa;			// ����γ�
	var $yuhari_ryou;				// ��Ϥ���[L]
	var $boiler_type;				// ����勵����

	// ���ǥǡ���
	var $nyuuyoku_kankaku_e;	// ����ֳ�[h]
	var $yokusou_futa_e;			// ����γ�

	// ���󥹥ȥ饯��
	function sim_oidaki() {
		// ���饹�ѿ�������
		$this->ninzuu_summer = 1;
		$this->ninzuu_sp_aut = 1;
		$this->ninzuu_winter = 1;
		$this->yuon_summer = 40;
		$this->yuon_sp_aut = 41;
		$this->yuon_winter = 42;
		$this->nyuuyoku_kankaku = 0;
		$this->yokusou_futa = 2;
		$this->yuhari_ryou = 210;
		$this->boiler_type = 1;

		$this->nyuuyoku_kankaku_e = 0;
		$this->yokusou_futa_e = 2;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_oidaki;

		// �����ǡ����Ƿ׻�
		$cal->ninzuu_summer = $this->ninzuu_summer;
		$cal->ninzuu_sp_aut = $this->ninzuu_sp_aut;
		$cal->ninzuu_winter = $this->ninzuu_winter;
		$cal->yuon_summer = $this->yuon_summer;
		$cal->yuon_sp_aut = $this->yuon_sp_aut;
		$cal->yuon_winter = $this->yuon_winter;
		$cal->nyuuyoku_kankaku = $this->nyuuyoku_kankaku;
		$cal->yokusou_futa = $this->yokusou_futa;
		$cal->yuhari_ryou = $this->yuhari_ryou;
		$cal->boiler_type = $this->boiler_type;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->nyuuyoku_kankaku = $this->nyuuyoku_kankaku_e;
		$cal->yokusou_futa = $this->yokusou_futa_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ���ǥ�å���������
		if ($this->nyuuyoku_kankaku > $this->nyuuyoku_kankaku_e)
			$msg_ary[] = '��²������ֳ֤� ' . ($this->nyuuyoku_kankaku - $this->nyuuyoku_kankaku_e) . ' ʬ����ˤ�';

		if ($this->yokusou_futa == 2 && $this->yokusou_futa_e == 1)
			$msg_ary[] = '������ʳ�������˳���';

		if ($msg_ary)
			$msg = join('��<br>', $msg_ary) . '�ޤ���';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_OIDAKI);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->ninzuu_summer = array_shift($ary);
			$this->ninzuu_sp_aut = array_shift($ary);
			$this->ninzuu_winter = array_shift($ary);
			$this->yuon_summer = array_shift($ary);
			$this->yuon_sp_aut = array_shift($ary);
			$this->yuon_winter = array_shift($ary);
			$this->nyuuyoku_kankaku = array_shift($ary);
			$this->yokusou_futa = array_shift($ary);
			$this->yuhari_ryou = array_shift($ary);
			$this->boiler_type = array_shift($ary);
			$this->nyuuyoku_kankaku_e = array_shift($ary);
			$this->yokusou_futa_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->ninzuu_summer;
		$ary[] = $this->ninzuu_sp_aut;
		$ary[] = $this->ninzuu_winter;
		$ary[] = $this->yuon_summer;
		$ary[] = $this->yuon_sp_aut;
		$ary[] = $this->yuon_winter;
		$ary[] = $this->nyuuyoku_kankaku;
		$ary[] = $this->yokusou_futa;
		$ary[] = $this->yuhari_ryou;
		$ary[] = $this->boiler_type;
		$ary[] = $this->nyuuyoku_kankaku_e;
		$ary[] = $this->yokusou_futa_e;

		return join(',', $ary);
	}
}

// �ɤ��椭�������Ƿ׻����饹
class cal_oidaki {
	var $ninzuu_summer;			// ����Ϳ��ʲơ�
	var $ninzuu_sp_aut;			// ����Ϳ��ʽՎ�����
	var $ninzuu_winter;			// ����Ϳ����ߡ�
	var $yuon_summer;				// �򲹡ʲơ�[��]
	var $yuon_sp_aut;				// �򲹡ʽՎ�����[��]
	var $yuon_winter;				// �򲹡��ߡ�[��]
	var $nyuuyoku_kankaku;	// ����ֳ�[h]
	var $yokusou_futa;			// ����γ�
	var $yuhari_ryou;				// ��Ϥ���[L]
	var $boiler_type;				// ����勵����

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// ���㲼Ψ����
		switch ($this->yokusou_futa) {
		case 1:		// ������
			$teika_ritsu = TEIKA_FUTAARI;
			break;
		case 2:		// ���ʤ�
			$teika_ritsu = TEIKA_FUTANASHI;
			break;
		}

		// �����Ψ����
		$efficiency = $this->get_efficiency($this->boiler_type);

		// ���ᤴ�Ȥ˷׻�
		$winter = $this->get_calorie($this->ninzuu_winter, $this->yuon_winter, DAYS_WINTER, $teika_ritsu, $efficiency);
		$summer = $this->get_calorie($this->ninzuu_summer, $this->yuon_summer, DAYS_SUMMER, $teika_ritsu, $efficiency);
		$sp_aut = $this->get_calorie($this->ninzuu_sp_aut, $this->yuon_sp_aut, DAYS_SP_AUT, $teika_ritsu, $efficiency);

		// ��ס�kcal->Mcal)
		$calorie = ($winter + $summer + $sp_aut) / 1000;

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
	}

	// ���ᤴ�ȤΥ���꡼�׻�
	function get_calorie($ninzuu, $yuon, $days, $teika_ritsu, $efficiency) {
		// ���㲼ʬ
		$yuon_teika = $ninzuu * (NYUUYOKU_JIKAN * TEIKA_FUTANASHI + $this->nyuuyoku_kankaku * $teika_ritsu) / 60;

		// ����꡼�׻�
		return $this->yuhari_ryou * $days * $yuon_teika / $efficiency;
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