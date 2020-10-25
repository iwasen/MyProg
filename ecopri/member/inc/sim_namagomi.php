<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����ߥ������ǽ������饹
'******************************************************/

// �����ߥ������ǥ��饹
class sim_namagomi {
	// �����ǡ���
	var $weekday_dust;		// ��������(ʿ��)[kg]
	var $holiday_dust;		// ��������(����)[kg]
	var $compost;					// �����߽������䥳��ݥ��Ȥλ���

	// ���ǥǡ���
	var $compost_e;				// �����߽������䥳��ݥ��Ȥλ���

	// ���󥹥ȥ饯��
	function sim_namagomi() {
		$this->weekday_dust = 1;
		$this->holiday_dust = 1;
		$this->compost = 1;
		$this->compost_e = 0;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_namagomi;

		// �����ǡ����Ƿ׻�
		$cal->weekday_dust = $this->weekday_dust;
		$cal->holiday_dust = $this->holiday_dust;
		$cal->compost = $this->compost;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->compost = $this->compost_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ���ǥ�å���������
		if ($this->compost == 1 && $this->compost_e == 2)
			$msg = '�����߽������䥳��ݥ��Ȥ���Ѥ��롣';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_NAMAGOMI);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->weekday_dust = array_shift($ary);
			$this->holiday_dust = array_shift($ary);
			$this->compost = array_shift($ary);
			$this->compost_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->weekday_dust;
		$ary[] = $this->holiday_dust;
		$ary[] = $this->compost;
		$ary[] = $this->compost_e;

		return join(',', $ary);
	}
}

// �����ߥ������Ƿ׻����饹
class cal_namagomi {
	var $weekday_dust;		// ��������(ʿ��)[kg]
	var $holiday_dust;		// ��������(����)[kg]
	var $compost;					// �����߽������䥳��ݥ��Ȥλ���

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// �����߽������䥳��ݥ��Ȥλ��Ѿ���
		if ($this->compost) {
			// ���Ѥ���
			$dust = 0;
		} else {
			// ���Ѥ��ʤ�
			$dust = $this->weekday_dust * SINK_CORNER * DAYS_WEEKDAY + $this->holiday_dust * SINK_CORNER * DAYS_HOLIDAY;
		}

		$calorie = 0;
		$cost = 0;
		$co2 = cnv_dst_co2($dust);
	}
}
?>