<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�������票�����ǽ������饹
'******************************************************/

// �������票�����ǥ��饹
class sim_kansou {
	// �����ǡ���
	var $dryer_type;			// ���絡�μ���
	var $capacity;				// ���絡������[kg]
	var $times;						// ���Ѳ��
	var $method_fair;			// ������ˡ�����������
	var $method_rain;			// ������ˡ�ʱ�������

	// ���ǥǡ���
	var $dryer_type_e;		// ���絡�μ���
	var $method_fair_e;		// ������ˡ�����������
	var $method_rain_e;		// ������ˡ�ʱ�������

	// ���󥹥ȥ饯��
	function sim_kansou() {
		$this->dryer_type = 4;
		$this->capacity = 4;
		$this->times = 0;
		$this->method_fair = 1;
		$this->method_rain = 1;
		$this->dryer_type_e = 4;
		$this->method_fair_e = 1;
		$this->method_rain_e = 1;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_kansou;

		// �����ǡ����Ƿ׻�
		$cal->dryer_type = $this->dryer_type;
		$cal->capacity = $this->capacity;
		$cal->times = $this->times;
		$cal->method_fair = $this->method_fair;
		$cal->method_rain = $this->method_rain;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->dryer_type = $this->dryer_type_e;
		$cal->method_fair = $this->method_fair_e;
		$cal->method_rain = $this->method_rain_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ���ǥ�å���������
		$dryer_type = kansou_shurui_name($this->dryer_type_e);
		$method_fair = kansou_houhou_name($this->method_fair_e);
		$method_rain = kansou_houhou_name($this->method_rain_e);
		$msg = "���絡�ϡ�${dryer_type}�Ȥ�����������ϡ�${method_fair}��<br>�������ϡ�${method_rain}��";
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_KANSOU);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->dryer_type = array_shift($ary);
			$this->capacity = array_shift($ary);
			$this->times = array_shift($ary);
			$this->method_fair = array_shift($ary);
			$this->method_rain = array_shift($ary);
			$this->dryer_type_e = array_shift($ary);
			$this->method_fair_e = array_shift($ary);
			$this->method_rain_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->dryer_type;
		$ary[] = $this->capacity;
		$ary[] = $this->times;
		$ary[] = $this->method_fair;
		$ary[] = $this->method_rain;
		$ary[] = $this->dryer_type_e;
		$ary[] = $this->method_fair_e;
		$ary[] = $this->method_rain_e;

		return join(',', $ary);
	}
}

// ��������׻����饹
class cal_kansou {
	var $dryer_type;			// ���絡�μ���
	var $capacity;				// ���絡������[kg]
	var $times;						// ���Ѳ��
	var $method_fair;			// ������ˡ�����������
	var $method_rain;			// ������ˡ�ʱ�������

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// ���������׻�
		$this->get_calorie_sub($this->method_fair, DAYS_FAIR, $calorie_fair, $cost_fair, $co2_fair);

		// ��������׻�
		$this->get_calorie_sub($this->method_rain, DAYS_RAIN, $calorie_rain, $cost_rain, $co2_rain);

		// ����ȱ���û�
		$calorie = $calorie_fair + $calorie_rain;
		$cost = $cost_fair + $cost_rain;
		$co2 = $co2_fair + $co2_rain;
	}

	// ������ˡ�ˤ�ꥫ��꡼�׻�
	function get_calorie_sub($method, $days, &$calorie, &$cost, &$co2) {
		switch ($method) {
		case 1:		// ���絡�ϻ��Ѥ��ʤ�
			$calorie = 0;
			break;
		case 2:		// ���絡��������
			$calorie = $this->get_power() * $this->get_drying_time() * $this->times * $days * KWH_MCAL;
			break;
		case 3:		// ���絡�Ǥ�䴥�礵�����崳��
			$calorie = $this->get_power() * (20 / 60) * $this->times * $days * KWH_MCAL;
			break;
		case 4:		// �������塢�������Τ�Τϴ��絡��Ȥ�
			$calorie = $this->get_power() * 1 * $this->times * $days * KWH_MCAL;
			break;
		}

		// ���絡�μ���ˤ�������CO2��׻�
		switch ($this->dryer_type) {
		case 1:		// �ŵ������ഥ�絡
		case 3:		// �ŵ����������絡
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 2:		// ���������ഥ�絡
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		}
	}

	// �������ϼ���
	function get_power() {
		$sql = "SELECT dp_power FROM m_dryer_prm WHERE dp_dryer_type=$this->dryer_type AND dp_capacity=$this->capacity";
		return db_fetch1($sql);
	}

	// ������ּ���
	function get_drying_time() {
		switch ($this->dryer_type) {
		case 1:		// �ŵ���(�ɥ��)
			return 2;
		case 2:		// ������
			return 1;
		case 3:		// �ŵ���(�峫��)
			return 4;
		}
	}
}

// �����ߥ������Ƿ׻����饹
class es_namagomi {
	var $weekday_dust;		// ��������(ʿ��)[kg]
	var $holiday_dust;		// ��������(����)[kg]
	var $compost;					// �����߽������䥳��ݥ��Ȥλ���

	// �����̷׻�[kg]
	function get_dust() {
		// �����߽������䥳��ݥ��Ȥλ��Ѿ���
		switch ($this->compost) {
		case 1:		// ���Ѥ��ʤ�
			$dust = $this->weekday_dust * SINK_CORNER * DAYS_WEEKDAY + $this->holiday_dust * SINK_CORNER * DAYS_HOLIDAY;
			break;
		case 2:		// ���Ѥ���
			$dust = 0;
			break;
		}

		return $dust;
	}
}
?>