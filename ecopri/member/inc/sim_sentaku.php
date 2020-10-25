<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����������ǽ������饹
'******************************************************/

// �����������ǥ��饹
class sim_sentaku {
	// �����ǡ���
	var $capacity;				// �����ؤ�����[kg]
	var $weekday_times;		// ���������������ʿ����
	var $holiday_times;		// ��������������ʵ�����
	var $ratio;						// ����ʪ�̤γ��
	var $nokoriyu;				// ��Ϥ�λĤ���λ���

	// ���ǥǡ���
	var $nokoriyu_e;			// ��Ϥ�λĤ���λ���

	// ���󥹥ȥ饯��
	function sim_sentaku() {
		$this->capacity = 6;
		$this->weekday_times = 1;
		$this->holiday_times = 1;
		$this->ratio = 80;
		$this->nokoriyu = 2;
		$this->nokoriyu_e = 1;
	}

	function simulation(&$energy, &$cost, &$co2, &$water, &$msg) {
		// �׻����饹����
		$cal = new cal_sentaku;

		// �����ǡ����Ƿ׻�
		$cal->capacity = $this->capacity;
		$cal->weekday_times = $this->weekday_times;
		$cal->holiday_times = $this->holiday_times;
		$cal->ratio = $this->ratio;
		$cal->nokoriyu = $this->nokoriyu;
		$cal->calculation($calorie_c, $cost_c, $co2_c, $water_c);

		// ���Ǿ��Ƿ׻�
		$cal->nokoriyu = $this->nokoriyu_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e, $water_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;
		$water = $water_c - $water_e;

		// ���ǥ�å���������
		$msg = '�����ˡ���Ϥ�λĤ�����' . nokoriyu_name($this->nokoriyu_e) . '�פˤ��롣';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_SENTAKU);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->capacity = array_shift($ary);
			$this->weekday_times = array_shift($ary);
			$this->holiday_times = array_shift($ary);
			$this->ratio = array_shift($ary);
			$this->nokoriyu = array_shift($ary);
			$this->nokoriyu_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->capacity;
		$ary[] = $this->weekday_times;
		$ary[] = $this->holiday_times;
		$ary[] = $this->ratio;
		$ary[] = $this->nokoriyu;
		$ary[] = $this->nokoriyu_e;

		return join(',', $ary);
	}
}

// �����׻����饹
class cal_sentaku {
	var $capacity;				// �����ؤ�����[kg]
	var $weekday_times;		// ���������������ʿ����
	var $holiday_times;		// ��������������ʵ�����
	var $ratio;						// ����ʪ�̤γ��
	var $nokoriyu;				// ��Ϥ�λĤ���λ���

	// ����꡼�����⡢CO2�����̷׻�
	function calculation(&$calorie, &$cost, &$co2, &$water) {
		// ���̼���
		$water_vol = $this->get_water_vol();

		// ��ƻ���Ѳ������
		$water_use = $this->get_water_use();

		// ʿ���ȵ����ο��̷׻�
		$weekday = $water_vol * $water_use * $this->weekday_times * DAYS_WEEKDAY;
		$holiday = $water_vol * $water_use * $this->holiday_times * DAYS_HOLIDAY;

		$calorie = 0;
		$water = ($weekday + $holiday) / 1000;
		$cost = cnv_wtr_mny($water);
		$co2 = cnv_wtr_co2($water);
	}

	// ���̼���
	function get_water_vol() {
		$sql = "SELECT wp_water FROM m_washer_prm WHERE wp_capacity=$this->capacity AND wp_ratio=$this->ratio";
		return db_fetch1($sql);
	}

	// �Ĥ���λ��Ѥ����ƻ���Ѳ������
	function get_water_use() {
		switch ($this->nokoriyu) {
		case 1:		// ���Ѥ��ʤ�
			return 3;
		case 2:		// �����Τ߻���
			return 2;
		case 3;		// �����Ȥ�����1�����
			return 1;
		}
	}
}
