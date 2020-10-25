<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:��˼�������ǽ������饹
'******************************************************/

// ��˼�������ǥ��饹
class sim_danbou {
	// �����ǡ���
	var $main_heater;		// ����˼����
	var $sub_heater;		// �����˼����
	var $temperature;		// ���겹��[��]
	var $main_hours1;		// ����˼���ѻ��֡ʵ��������[h]
	var $main_hours2;		// ����˼���ѻ��֡����ͼ����[h]
	var $main_hours3;		// ����˼���ѻ��֡�ͼ���ʹߡ�[h]
	var $sub_hours1;		// �����˼���ѻ��֡ʵ��������[h]
	var $sub_hours2;		// �����˼���ѻ��֡����ͼ����[h]
	var $sub_hours3;		// �����˼���ѻ��֡�ͼ���ʹߡ�[h]

	// ���ǥǡ���
	var $main_heater_e;	// ����˼����
	var $sub_heater_e;	// �����˼����
	var $temperature_e;	// ���겹��[��]
	var $main_hours1_e;	// ����˼���ѻ��֡ʵ��������[h]
	var $main_hours2_e;	// ����˼���ѻ��֡����ͼ����[h]
	var $main_hours3_e;	// ����˼���ѻ��֡�ͼ���ʹߡ�[h]
	var $sub_hours1_e;	// �����˼���ѻ��֡ʵ��������[h]
	var $sub_hours2_e;	// �����˼���ѻ��֡����ͼ����[h]
	var $sub_hours3_e;	// �����˼���ѻ��֡�ͼ���ʹߡ�[h]

	// ���󥹥ȥ饯��
	function sim_danbou() {
		// ���饹�ѿ�������
		$this->main_heater = 1;
		$this->sub_heater = 8;
		$this->temperature = 18;
		$this->main_hours1 = 0;
		$this->main_hours2 = 0;
		$this->main_hours3 = 0;
		$this->sub_hours1 = 0;
		$this->sub_hours2 = 0;
		$this->sub_hours3 = 0;

		$this->main_heater_e = 1;
		$this->sub_heater_e = 8;
		$this->temperature_e = 18;
		$this->main_hours1_e = 0;
		$this->main_hours2_e = 0;
		$this->main_hours3_e = 0;
		$this->sub_hours1_e = 0;
		$this->sub_hours2_e = 0;
		$this->sub_hours3_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_danbou;

		// �����ǡ����Ƿ׻�
		$cal->main_heater = $this->main_heater;
		$cal->sub_heater = $this->sub_heater;
		$cal->temperature = $this->temperature;
		$cal->main_hours1 = $this->main_hours1;
		$cal->main_hours2 = $this->main_hours2;
		$cal->main_hours3 = $this->main_hours3;
		$cal->sub_hours1 = $this->sub_hours1;
		$cal->sub_hours2 = $this->sub_hours2;
		$cal->sub_hours3 = $this->sub_hours3;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->main_heater = $this->main_heater_e;
		$cal->sub_heater = $this->sub_heater_e;
		$cal->temperature = $this->temperature_e;
		$cal->main_hours1 = $this->main_hours1_e;
		$cal->main_hours2 = $this->main_hours2_e;
		$cal->main_hours3 = $this->main_hours3_e;
		$cal->sub_hours1 = $this->sub_hours1_e;
		$cal->sub_hours2 = $this->sub_hours2_e;
		$cal->sub_hours3 = $this->sub_hours3_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ����˼�����ѹ������å�
		if ($this->main_heater != $this->main_heater_e)
			$msg_ary[] = '����˼��' . danbou_shurui_name($this->main_heater_e) . '���ؤ�';

		// ����˼���ѻ����ѹ������å�
		$hours = $this->main_hours1 + $this->main_hours2 + $this->main_hours3;
		$hours_e = $this->main_hours1_e + $this->main_hours2_e + $this->main_hours3_e;
		if ($hours > $hours_e)
			$msg_ary[] = '����˼�λ��ѻ��֤�' . ($hours - $hours_e) . '����û����';
		if ($hours_e > $hours)
			$msg_ary[] = '����˼�λ��ѻ��֤�' . ($hours_e - $hours) . '����Ĺ����';

		// �����˼�����ѹ������å�
		if ($this->sub_heater != $this->sub_heater_e)
			$msg_ary[] = '�����˼��' . danbou_shurui_name($this->sub_heater_e) . '���ؤ�';

		// �����˼���ѻ����ѹ������å�
		$hours = $this->sub_hours1 + $this->sub_hours2 + $this->sub_hours3;
		$hours_e = $this->sub_hours1_e + $this->sub_hours2_e + $this->sub_hours3_e;
		if ($hours > $hours_e)
			$msg_ary[] = '�����˼�λ��ѻ��֤�' . ($hours - $hours_e) . '����û����';
		if ($hours_e > $hours)
			$msg_ary[] = '�����˼�λ��ѻ��֤�' . ($hours_e - $hours) . '����Ĺ����';

		// ���겹���ѹ������å�
		if ($this->temperature > $this->temperature_e)
			$msg_ary[] = '��˼�����겹�٤�' . ($this->temperature - $this->temperature_e) . '���㤯��';
		elseif ($this->temperature < $this->temperature_e)
			$msg_ary[] = '��˼�����겹�٤�' . ($this->temperature_e - $this->temperature) . '��⤯��';

		// ���ǥ�å���������
		if ($msg_ary)
			$msg = join('��<br>', $msg_ary) . '�ޤ���';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_DANBOU_LD);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->main_heater = array_shift($ary);
			$this->sub_heater = array_shift($ary);
			$this->temperature = array_shift($ary);
			$this->main_hours1 = array_shift($ary);
			$this->main_hours2 = array_shift($ary);
			$this->main_hours3 = array_shift($ary);
			$this->sub_hours1 = array_shift($ary);
			$this->sub_hours2 = array_shift($ary);
			$this->sub_hours3 = array_shift($ary);
			$this->main_heater_e = array_shift($ary);
			$this->sub_heater_e = array_shift($ary);
			$this->temperature_e = array_shift($ary);
			$this->main_hours1_e = array_shift($ary);
			$this->main_hours2_e = array_shift($ary);
			$this->main_hours3_e = array_shift($ary);
			$this->sub_hours1_e = array_shift($ary);
			$this->sub_hours2_e = array_shift($ary);
			$this->sub_hours3_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->main_heater;
		$ary[] = $this->sub_heater;
		$ary[] = $this->temperature;
		$ary[] = $this->main_hours1;
		$ary[] = $this->main_hours2;
		$ary[] = $this->main_hours3;
		$ary[] = $this->sub_hours1;
		$ary[] = $this->sub_hours2;
		$ary[] = $this->sub_hours3;
		$ary[] = $this->main_heater_e;
		$ary[] = $this->sub_heater_e;
		$ary[] = $this->temperature_e;
		$ary[] = $this->main_hours1_e;
		$ary[] = $this->main_hours2_e;
		$ary[] = $this->main_hours3_e;
		$ary[] = $this->sub_hours1_e;
		$ary[] = $this->sub_hours2_e;
		$ary[] = $this->sub_hours3_e;

		return join(',', $ary);
	}
}

// ��˼�׻����饹
class cal_danbou {
	var $main_heater;		// ����˼���拾����
	var $sub_heater;		// �����˼���拾����
	var $temperature;		// ���겹��[��]
	var $main_hours1;		// ����˼���ѻ��֡ʵ��������[h]
	var $main_hours2;		// ����˼���ѻ��֡����ͼ����[h]
	var $main_hours3;		// ����˼���ѻ��֡�ͼ���ʹߡ�[h]
	var $sub_hours1;		// �����˼���ѻ��֡ʵ��������[h]
	var $sub_hours2;		// �����˼���ѻ��֡����ͼ����[h]
	var $sub_hours3;		// �����˼���ѻ��֡�ͼ���ʹߡ�[h]

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// ����˼����������
		$this->get_heater_info($this->main_heater, $main_ave_power, $main_max_power, $main_ave_cop, $main_heat_type);

		// �����Ӥ��Ȥ�Ǯ��٤���
		$th_load = $this->get_th_load(1, 1, $this->main_hours1, $this->temperature)
						 + $this->get_th_load(1, 2, $this->main_hours2, $this->temperature)
						 + $this->get_th_load(1, 3, $this->main_hours3, $this->temperature);

		// Ǯ�Ȼ�����
		$main_th_diffusion = $this->get_th_diffusion($main_heat_type);

		// ����˼Ω�夬�걿žʬ
		$main_startup = $this->get_startup($main_max_power, $main_heat_type, $this->main_hours1, $this->main_hours2, $this->main_hours3);

		// ����˼�ե���žʬ
		$main_fan = $this->get_fan($main_heat_type, $this->main_hours1, $this->main_hours2, $this->main_hours3);

		// ����˼����꡼�׻�
		$main_calorie = $th_load * $main_th_diffusion / $main_ave_cop + $main_startup + $main_fan;

		// ����˼���⡢CO2�׻�
		$this->get_cost_co2($this->main_heater, $main_calorie, $main_cost, $main_co2);

		if ($this->sub_heater) {
			// �����˼����������
			$this->get_heater_info($this->sub_heater, $sub_ave_power, $sub_max_power, $sub_ave_cop, $sub_heat_type);

			// �����˼Ω�夬�걿žʬ
			$sub_startup = $this->get_startup($sub_max_power, $sub_heat_type, $this->sub_hours1, $this->sub_hours2, $this->sub_hours3);

			// �����˼�ե���žʬ
			$sub_fan = $this->get_fan($sub_heat_type, $this->sub_hours1, $this->sub_hours2, $this->sub_hours3);

			// �����˼����꡼�̷׻�
			$sub_calorie = $sub_ave_power * ($this->sub_hours1 + $this->sub_hours2 + $this->sub_hours3) * DAYS_DANBOU * KWH_MCAL + $sub_startup + $sub_fan;

			// �����˼���⡢CO2�׻�
			$this->get_cost_co2($this->sub_heater, $sub_calorie, $sub_cost, $sub_co2);
		}

		// ����˼�������˼�ι��
		$calorie = $main_calorie + $sub_calorie;
		$cost = $main_cost + $sub_cost;
		$co2 = $main_co2 + $sub_co2;
	}

	// Ǯ��ټ���
	function get_th_load($room_cd, $time_cd, $hours, $temp) {
		$sql = "SELECT hl_calorie"
				. " FROM m_heater_load"
				. " WHERE hl_room_cd=$room_cd AND hl_time_cd=$time_cd AND hl_used_hours=$hours AND hl_temperature=$temp";
		return db_fetch1($sql);
	}

	// ��˼����������
	function get_heater_info($heater_cd, &$ave_power, &$max_power, &$ave_cop, &$heat_type) {
		$sql = "SELECT hp_ave_power,hp_max_power,hp_ave_cop,hp_heat_type"
				. " FROM m_heater_prm"
				. " WHERE hp_heater_cd=$heater_cd AND hp_room_cd=1";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$ave_power = $fetch->hp_ave_power;
			$max_power = $fetch->hp_max_power;
			$ave_cop = $fetch->hp_ave_cop;
			$heat_type = $fetch->hp_heat_type;
		}
	}

	// Ǯ�Ȼ���������
	function get_th_diffusion($heat_type) {
		switch ($heat_type) {
		case 1:		// ������
			return 1.2;
		case 2:		// �ռͷ�
			return 1.0;
		}
	}

	// Ω�夬�걿žʬ�׻�
	function get_startup($max_power, $heat_type, $hours1, $hours2, $hours3) {
		// Ω�夬����ּ���
		$startup_time = $this->get_startup_time($heat_type, $hours1, $hours2, $hours3);

		return $max_power * $startup_time * DAYS_DANBOU * KWH_MCAL;
	}

	// �ե���žʬ�׻�
	function get_fan($heat_type, $hours1, $hours2, $hours3) {
		// �����λ��ѻ���
		$total_hours = $hours1 + $hours2 + $hours3;

		// Ω�夬�����
		$startup_time = $this->get_startup_time($heat_type, $hours1, $hours2, $hours3);

		return 0.021 * ($total_hours + $startup_time) * DAYS_DANBOU * KWH_MCAL;
	}

	// Ω�夬����ּ���
	function get_startup_time($heat_type, $hours1, $hours2, $hours3) {
		// Ω�夬����ּ���
		switch ($heat_type) {
		case 1:		// ������
			$time = 0.25;
			break;
		case 2:		// �ռͷ�
			$time = 0.5;
			break;
		}

		// ��ž�������
		$count = ($hours1 ? 1 : 0) + ($hours2 ? 1 : 0) + ($hours3 ? 1 : 0);

		return $time * $count;
	}

	// �����CO2�׻�
	function get_cost_co2($heater, $calorie, &$cost, &$co2) {
		switch ($heater) {
		case 1:		// ����������ڷ�
		case 2:		// ��������ʥ��ͷ�
		case 6:		// �ۥåȥ����ڥå�
		case 7:		// ������
			$kwh = ele_mcal_kwh($calorie);
			$cost = cnv_ele_mny($kwh);
			$co2 = cnv_ele_co2($kwh);
			break;
		case 3:		// �����ե���ҡ���
		case 5:		// �������弰����˼
			$m3 = gas_mcal_m3($calorie);
			$cost = cnv_gas_mny($m3);
			$co2 = cnv_gas_co2($m3);
			break;
		case 4:		// �����ե���ҡ���
			$l = oil_mcal_l($calorie);
			$cost = cnv_oil_mny($l);
			$co2 = cnv_oil_co2($l);
			break;
		}
	}
}
?>