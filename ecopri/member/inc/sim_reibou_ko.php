<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�ļ���˼�������ǽ������饹
'******************************************************/

// �ļ���˼�������ǥ��饹
class sim_reibou_ko {
	// �����ǡ���
	var $cooler_type_ary;		// ��˼�μ���
	var $temperature_ary;		// ���겹��[��]
	var $hours_ary;					// ���ѻ����ӡʽ������

	// ���ǥǡ���
	var $cooler_type_ary_e;	// ��˼�μ���
	var $temperature_ary_e;	// ���겹��[��]
	var $hours_ary_e;				// ���ѻ����ӡʽ������

	// ���󥹥ȥ饯��
	function sim_reibou_ko() {
		// ���饹�ѿ�������
		for ($i = 1; $i <= 3; $i++) {
			$this->cooler_type_ary[$i] = 1;
			$this->temperature_ary[$i] = 27;
			$this->hours_ary[$i] = 0;

			$this->cooler_type_ary_e[$i] = 1;
			$this->temperature_ary_e[$i] = 27;
			$this->hours_ary_e[$i] = 0;
		}
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_reibou_ko;

		// �����ǡ����Ƿ׻�
		for ($i = 1; $i <= 3; $i++) {
			$cal->cooler_type_ary[$i] = $this->cooler_type_ary[$i];
			$cal->temperature_ary[$i] = $this->temperature_ary[$i];
			$cal->hours_ary[$i] = $this->hours_ary[$i];
		}
		$cal->calculation($calorie_c, $cost_c, $co2_c, $calorie_ko_c);

		// ���Ǿ��Ƿ׻�
		for ($i = 1; $i <= 3; $i++) {
			$cal->cooler_type_ary[$i] = $this->cooler_type_ary_e[$i];
			$cal->temperature_ary[$i] = $this->temperature_ary_e[$i];
			$cal->hours_ary[$i] = $this->hours_ary_e[$i];
		}
		$cal->calculation($calorie_e, $cost_e, $co2_e, $calorie_ko_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ����̾�Υ��å�
		$room_name[1] = '�翲��';
		$room_name[2] = '�Ҷ���(���)';
		$room_name[3] = '�Ҷ���(��¦)';

		// ���ǥ�å������Խ�
		for ($i = 1; $i <= 3; $i++) {
			// ���ͥ륮�������ä���ΤˤĤ��ƤΤߥ�å���������
			if ($calorie_ko_c[$i] > $calorie_ko_e[$i]) {
				$msg_ary = array();

				// ����̾��

				// ��˼�����ѹ������å�
				if ($this->cooler_type_ary[$i] != $this->cooler_type_ary_e[$i])
					$msg_ary[] = '��' . reibou_shurui_name($this->cooler_type_ary_e[$i]) . '���ؤ�';

				// ���겹���ѹ������å�
				if ($this->temperature_ary[$i] > $this->temperature_ary_e[$i])
					$msg_ary[] = '���겹�٤�' . ($this->temperature_ary[$i] - $this->temperature_ary_e[$i]) . '���㤯��';
				elseif ($this->temperature_ary[$i] < $this->temperature_ary_e[$i])
					$msg_ary[] = '���겹�٤�' . ($this->temperature_ary_e[$i] - $this->temperature_ary[$i]) . '��⤯��';

				// ���ѻ����ѹ������å�
				if ($this->hours_ary[$i] > $this->hours_ary_e[$i])
					$msg_ary[] = '���ѻ��֤�' . ($this->hours_ary[$i] - $this->hours_ary_e[$i]) . '����û����';
				if ($this->hours_ary_e[$i] > $this->hours_ary[$i])
					$msg_ary[] = '���ѻ��֤�' . ($this->hours_ary_e[$i] - $this->hours_ary[$i]) . '����Ĺ����';

				// ���ǥ�å���������
				if ($msg_ary)
					$msg .= "{$room_name[$i]}�Υ�������" . join('��', $msg_ary) . '�ޤ���<br>';
			}
		}
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_REIBOU_KO);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			for ($i = 1; $i <= 3; $i++) {
				$this->cooler_type_ary[$i] = array_shift($ary);
				$this->temperature_ary[$i] = array_shift($ary);
				$this->hours_ary[$i] = array_shift($ary);
				$this->cooler_type_ary_e[$i] = array_shift($ary);
				$this->temperature_ary_e[$i] = array_shift($ary);
				$this->hours_ary_e[$i] = array_shift($ary);
			}
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		for ($i = 1; $i <= 3; $i++) {
			$ary[] = $this->cooler_type_ary[$i];
			$ary[] = $this->temperature_ary[$i];
			$ary[] = $this->hours_ary[$i];
			$ary[] = $this->cooler_type_ary_e[$i];
			$ary[] = $this->temperature_ary_e[$i];
			$ary[] = $this->hours_ary_e[$i];
		}

		return join(',', $ary);
	}
}

// �ļ���˼�׻����饹
class cal_reibou_ko {
	var $cooler_type_ary;		// ��˼�μ���
	var $temperature_ary;		// ���겹��[��]
	var $hours_ary;					// ���ѻ����ӡʽ������

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2, &$calorie_ko) {
		$calorie = 0;
		for ($i = 1; $i <= 3; $i++) {
			if ($this->hours_ary[$i]) {
				// ����������
				$room_cd = $i + 1;

				// �����Ӥ��Ȥ�Ǯ��٤���
				$th_load = $this->get_th_load($room_cd, 4, $this->hours_ary[$i], $this->temperature_ary[$i]);

				// ��˼����������
				$this->get_cooler_info($this->cooler_type_ary[$i], $ave_power, $max_power, $ave_cop);

				// ��˼Ω�夬�걿žʬ
				$startup = 0.25;

				// ��˼���ͥ륮���̷׻�
				$calorie_ko[$i] = $th_load / $ave_cop + $startup;
				$calorie += $calorie_ko[$i];
			}
		}

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