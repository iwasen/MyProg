<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��ž��ˡ�������ǽ������饹
'******************************************************/

// ��ž��ˡ�������ǥ��饹
class sim_unten {
	// �����ǡ���
	var $car_cd;				// �֤μ���
	var $engine_vol;		// �ӵ���[cc]
	var $kyori;					// ������Ե�Υ[km]
	var $idling;				// �����ɥ�󥰻���[ʬ]

	// ���ǥǡ���
	var $kyori_e;				// ������Ե�Υ[km]
	var $idling_e;			// �����ɥ�󥰻���[ʬ]

	// ���󥹥ȥ饯��
	function sim_unten() {
		// ���饹�ѿ�������
		$this->car_cd = 1;
		$this->engine_vol[1] = 2;
		$this->engine_vol[2] = 5;
		$this->engine_vol[3] = 3;
		$this->kyori = 100;
		$this->idling = 10;
		$this->kyori_e = 0;
		$this->idling_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_unten;

		// �����ǡ����Ƿ׻�
		$cal->car_cd = $this->car_cd;
		$cal->engine_vol = $this->engine_vol[$this->car_cd];
		$cal->kyori = $this->kyori;
		$cal->idling = $this->idling;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->kyori = $this->kyori * (100 - $this->kyori_e) / 100;
		$cal->idling = $this->idling * (100 - $this->idling_e) / 100;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ���ǥ�å������Խ�
		if ($this->kyori_e)
			$msg_ary[] = "������Ե�Υ��{$this->kyori_e}��";
		if ($this->idling_e)
			$msg_ary[] = "�����Υ����ɥ�󥰻��֤�{$this->idling_e}��";
		if ($msg_ary)
			$msg = join('��<br>', $msg_ary) . '���ʤ����ޤ���';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_UNTENHOUHOU);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->car_cd = 1;
			$this->engine_vol[1] = array_shift($ary);
			$this->engine_vol[2] = array_shift($ary);
			$this->engine_vol[3] = array_shift($ary);
			$this->kyori = array_shift($ary);
			$this->idling = array_shift($ary);
			$this->kyori_e = array_shift($ary);
			$this->idling_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->car_cd;
		$ary[] = $this->engine_vol[1];
		$ary[] = $this->engine_vol[2];
		$ary[] = $this->engine_vol[3];
		$ary[] = $this->kyori;
		$ary[] = $this->idling;
		$ary[] = $this->kyori_e;
		$ary[] = $this->idling_e;

		return join(',', $ary);
	}
}

// ��ž��ˡ�������Ƿ׻����饹
class cal_unten {
	var $car_cd;				// �֤μ���
	var $engine_vol;		// �ӵ���[cc]
	var $kyori;					// ������Ե�Υ[km]
	var $idling;				// �����ɥ�󥰻���[ʬ]

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// ����ǳ��[L]
		$l = $this->kyori * 12 / $this->get_fuel_cost() + 0.14 * $this->idling / 10 * 365;

		// ����꡼�׻�
		switch ($this->car_cd) {
		case 1:		// ��������
		case 3:		// �ϥ��֥�åɼ�
			$calorie = gso_l_mcal($l);
			break;
		case 2:		// �ǥ��������
			$calorie = dsl_l_mcal($l);
			break;
		}

		// ���⡢CO2�׻�
		$cost = cnv_gso_mny($l, $this->car_cd);
		$co2 = cnv_gso_co2($l, $this->car_cd);
	}

	// ǳ�����
	function get_fuel_cost() {
		$sql = "SELECT fp_cost FROM m_fuel_prm WHERE fp_car_type_cd=$this->car_cd AND fp_engine_vol_cd=$this->engine_vol";
		return db_fetch1($sql);
	}
}
?>