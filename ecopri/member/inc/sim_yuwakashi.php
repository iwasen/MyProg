<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ʨ���������ǽ������饹
'******************************************************/

// ��ʨ���������ǥ��饹
class sim_yuwakashi {
	// �����ǡ���
	var $yuwakashi_houhou;	// ��ʨ������ˡ
	var $yuwakashi_kaisuu;	// ��ʨ�����
	var $yuwakashi_ryou;		// ��ʨ���̥�����
	var $hoon_houhou;				// �ݲ�����ˡ
	var $hoon_jikan;				// �ݲ�����[h]

	// ���ǥǡ���
	var $yuwakashi_houhou_e;	// ��ʨ������ˡ
	var $hoon_houhou_e;				// �ݲ�����ˡ
	var $hoon_jikan_e;				// �ݲ�����[h]

	// ���󥹥ȥ饯��
	function sim_yuwakashi() {
		// ���饹�ѿ�������
		$this->yuwakashi_houhou = 1;
		$this->yuwakashi_kaisuu = 1;
		$this->yuwakashi_ryou = 80;
		$this->hoon_houhou = 1;
		$this->hoon_jikan = 0;
		$this->yuwakashi_houhou_e = 1;
		$this->hoon_houhou_e = 1;
		$this->hoon_jikan_e = 24;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_yuwakashi;

		// �����ǡ����Ƿ׻�
		$cal->yuwakashi_houhou = $this->yuwakashi_houhou;
		$cal->yuwakashi_kaisuu = $this->yuwakashi_kaisuu;
		$cal->yuwakashi_ryou = $this->yuwakashi_ryou;
		$cal->hoon_houhou = $this->hoon_houhou;
		$cal->hoon_jikan = $this->hoon_jikan;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->yuwakashi_houhou = $this->yuwakashi_houhou_e;
		$cal->hoon_houhou = $this->hoon_houhou_e;
		$cal->hoon_jikan = $this->hoon_jikan_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ���ǥ�å������Խ�
		$yuwakashi_houhou = yuwakashi_houhou_name($this->yuwakashi_houhou_e);
		$hoon_houhou = hoon_houhou_name($this->hoon_houhou_e);
		$hoon_jikan = hoon_jikan_name($this->hoon_jikan_e);
		$msg = "��ʨ������ˡ�ϡ�${yuwakashi_houhou}�ˤ���${hoon_houhou}�ǡ�${hoon_jikan}�ݲ����롣";
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_YUWAKASHI);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->yuwakashi_houhou = array_shift($ary);
			$this->yuwakashi_kaisuu = array_shift($ary);
			$this->yuwakashi_ryou = array_shift($ary);
			$this->hoon_houhou = array_shift($ary);
			$this->hoon_jikan = array_shift($ary);
			$this->yuwakashi_houhou_e = array_shift($ary);
			$this->hoon_houhou_e = array_shift($ary);
			$this->hoon_jikan_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->yuwakashi_houhou;
		$ary[] = $this->yuwakashi_kaisuu;
		$ary[] = $this->yuwakashi_ryou;
		$ary[] = $this->hoon_houhou;
		$ary[] = $this->hoon_jikan;
		$ary[] = $this->yuwakashi_houhou_e;
		$ary[] = $this->hoon_houhou_e;
		$ary[] = $this->hoon_jikan_e;

		return join(',', $ary);
	}
}

// ��ʨ���׻����饹
class cal_yuwakashi {
	var $yuwakashi_houhou;	// ��ʨ������ˡ
	var $yuwakashi_kaisuu;	// ��ʨ�����
	var $yuwakashi_ryou;		// ��ʨ����[L]
	var $hoon_houhou;				// �ݲ�����ˡ
	var $hoon_jikan;				// �ݲ�����[h]

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// ��ʨ���̼���
		$ryou = $this->yuwakashi_ryou / 100;

		// ��ʨ����ˡ�ˤ�륫��꡼�׻�
		switch ($this->yuwakashi_houhou) {
		case 1:		// �ŵ��ݥå�
			$yuwakashi_calorie = 13.2 / 60 * $ryou * 1 * $this->yuwakashi_kaisuu * 365 * KWH_MCAL;

			$kwh = ele_mcal_kwh($yuwakashi_calorie);
			$yuwakashi_cost = cnv_ele_mny($kwh);
			$yuwakashi_co2 = cnv_ele_co2($kwh);
			break;
		case 2:		// �䤫��ʥ�����
			$winter = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_WINTER) * DAYS_WINTER / 0.5 / 1000;
			$summer = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SUMMER) * DAYS_SUMMER / 0.5 / 1000;
			$sp_aut = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SP_AUT) * DAYS_SP_AUT / 0.5 / 1000;
			$yuwakashi_calorie = $winter + $summer + $sp_aut;

			$m3 = gas_mcal_m3($yuwakashi_calorie);
			$yuwakashi_cost = cnv_gas_mny($m3);
			$yuwakashi_co2 = cnv_gas_co2($m3);
			break;
		case 3:		// �䤫��ʣɣȡ�
			$winter = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_WINTER) * DAYS_WINTER / 0.9 / 1000;
			$summer = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SUMMER) * DAYS_SUMMER / 0.9 / 1000;
			$sp_aut = 2 * $ryou * $this->yuwakashi_kaisuu * (100 - TMP_SP_AUT) * DAYS_SP_AUT / 0.9 / 1000;
			$yuwakashi_calorie = $winter + $summer + $sp_aut;

			$kwh = ele_mcal_kwh($yuwakashi_calorie);
			$yuwakashi_cost = cnv_gas_mny($kwh);
			$yuwakashi_co2 = cnv_gas_co2($kwh);
			break;
		}

		// �ݲ���ˡ�ˤ�륫��꡼�׻�
		switch ($this->hoon_houhou) {
		case 1:		// �ŵ��ݥå�
			$hoon_calorie = 0.029 * $this->hoon_jikan * 365 * KWH_MCAL;
			$kwh = ele_mcal_kwh($hoon_calorie);
			$hoon_cost = cnv_ele_mny($kwh);
			$hoon_co2 = cnv_ele_co2($kwh);
			break;
		case 2:		// ��ˡ��
			$hoon_calorie = 0;
			$hoon_cost = 0;
			$hoon_co2 = 0;
			break;
		}

		// ��ʨ�����ݲ�����
		$calorie = $yuwakashi_calorie + $hoon_calorie;
		$cost = $yuwakashi_cost + $hoon_cost;
		$co2 = $yuwakashi_co2 + $hoon_co2;
	}
}
?>