<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:LD��˼�������ǽ������饹
'******************************************************/

// LD��˼�������ǥ��饹
class sim_reibou_ld {
	// �����ǡ���
	var $cooler_type;		// ��˼�μ���
	var $temperature;		// ���겹��[��]
	var $hours1;				// ���ѻ����ӡʵ��������
	var $hours2;				// ���ѻ����ӡ����ͼ����
	var $hours3;				// ���ѻ����ӡ�ͼ���ʹߡ�

	// ���ǥǡ���
	var $cooler_type_e;	// ��˼�μ���
	var $temperature_e;	// ���겹��[��]
	var $hours1_e;			// ���ѻ����ӡʵ��������
	var $hours2_e;			// ���ѻ����ӡ����ͼ����
	var $hours3_e;			// ���ѻ����ӡ�ͼ���ʹߡ�

	// ���󥹥ȥ饯��
	function sim_reibou_ld() {
		// ���饹�ѿ�������
		$this->cooler_type = 1;
		$this->temperature = 27;
		$this->hours1 = 0;
		$this->hours2 = 0;
		$this->hours3 = 0;
		$this->cooler_type_e = 1;
		$this->temperature_e = 27;
		$this->hours1_e = 0;
		$this->hours2_e = 0;
		$this->hours3_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_reibou_ld;

		// �����ǡ����Ƿ׻�
		$cal->cooler_type = $this->cooler_type;
		$cal->temperature = $this->temperature;
		$cal->hours1 = $this->hours1;
		$cal->hours2 = $this->hours2;
		$cal->hours3 = $this->hours3;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->cooler_type = $this->cooler_type_e;
		$cal->temperature = $this->temperature_e;
		$cal->hours1 = $this->hours1_e;
		$cal->hours2 = $this->hours2_e;
		$cal->hours3 = $this->hours3_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ��˼�����ѹ������å�
		if ($this->cooler_type != $this->cooler_type_e)
			$msg_ary[] = '��' . reibou_shurui_name($this->cooler_type_e) . '���ؤ�';

		// ���겹���ѹ������å�
		if ($this->temperature > $this->temperature_e)
			$msg_ary[] = '���겹�٤�' . ($this->temperature - $this->temperature_e) . '���㤯��';
		elseif ($this->temperature < $this->temperature_e)
			$msg_ary[] = '���겹�٤�' . ($this->temperature_e - $this->temperature) . '��⤯��';

		// ���ѻ����ѹ������å�
		$hours = $this->hours1 + $this->hours2 + $this->hours3;
		$hours_e = $this->hours1_e + $this->hours2_e + $this->hours3_e;
		if ($hours > $hours_e)
			$msg_ary[] = '���ѻ��֤�' . ($hours - $hours_e) . '����û����';
		if ($hours_e > $hours)
			$msg_ary[] = '���ѻ��֤�' . ($hours_e - $hours) . '����Ĺ����';

		// ���ǥ�å���������
		if ($msg_ary)
			$msg = 'LD�Υ�������' . join('��<br>', $msg_ary) . '�ޤ���';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_REIBOU_LD);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->cooler_type = array_shift($ary);
			$this->temperature = array_shift($ary);
			$this->hours1 = array_shift($ary);
			$this->hours2 = array_shift($ary);
			$this->hours3 = array_shift($ary);
			$this->cooler_type_e = array_shift($ary);
			$this->temperature_e = array_shift($ary);
			$this->hours1_e = array_shift($ary);
			$this->hours2_e = array_shift($ary);
			$this->hours3_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->cooler_type;
		$ary[] = $this->temperature;
		$ary[] = $this->hours1;
		$ary[] = $this->hours2;
		$ary[] = $this->hours3;
		$ary[] = $this->cooler_type_e;
		$ary[] = $this->temperature_e;
		$ary[] = $this->hours1_e;
		$ary[] = $this->hours2_e;
		$ary[] = $this->hours3_e;

		return join(',', $ary);
	}
}

// LD��˼�׻����饹
class cal_reibou_ld {
	var $cooler_type;		// ��˼�μ���
	var $temperature;		// ���겹��[��]
	var $hours1;				// ���ѻ����ӡʵ��������
	var $hours2;				// ���ѻ����ӡ����ͼ����
	var $hours3;				// ���ѻ����ӡ�ͼ���ʹߡ�

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// �����Ӥ��Ȥ�Ǯ��٤���
		$th_load = $this->get_th_load(1, 1, $this->hours1, $this->temperature)
						 + $this->get_th_load(1, 2, $this->hours2, $this->temperature)
						 + $this->get_th_load(1, 3, $this->hours3, $this->temperature);

		// ��˼����������
		$this->get_cooler_info($this->cooler_type, $ave_power, $max_power, $ave_cop);

		// ��˼Ω�夬�걿žʬ
		$startup = $this->get_startup($max_power, $this->hours1, $this->hours2, $this->hours3);

		// ����꡼�׻�
		$calorie = $th_load / $ave_cop + $startup;

		// �����CO2����
		$kwh = ele_mcal_kwh($calorie);
		$cost = cnv_ele_mny($kwh);
		$co2 = cnv_ele_co2($kwh);
	}

	// Ǯ��ټ���
	function get_th_load($room_cd, $time_cd, $hours, $temp) {
		$sql = "SELECT cl_calorie"
				. " FROM m_cooler_load"
				. " WHERE cl_room_cd=$room_cd AND cl_time_cd=$time_cd AND cl_used_hours=$hours AND cl_temperature=$temp";
		return db_fetch1($sql);
	}

	// ��˼����������
	function get_cooler_info($cooler_type, &$ave_power, &$max_power, &$ave_cop) {
		$sql = "SELECT cp_ave_power,cp_max_power,cp_ave_cop"
				. " FROM m_cooler_prm"
				. " WHERE cp_cooler_type=$cooler_type AND cp_room_cd=1";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$ave_power = $fetch->cp_ave_power;
			$max_power = $fetch->cp_max_power;
			$ave_cop = $fetch->cp_ave_cop;
		}
	}

	// Ω�夬�걿žʬ�׻�
	function get_startup($max_power, $hours1, $hours2, $hours3) {
		// ��ž���ּ���
		$startup_time = (($hours1 ? 1 : 0) + ($hours2 ? 1 : 0) + ($hours3 ? 1 : 0)) * 0.25;

		return $max_power * $startup_time * DAYS_REIBOU * KWH_MCAL;
	}
}
?>