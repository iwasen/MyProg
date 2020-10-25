<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����������ǽ������饹
'******************************************************/

// �����������ǥ��饹
class sim_shoumei {
	// �����ǡ���
	var $lighting_ary;	// ���������ɤ�����
	var $hour_ary;			// �������֤�����[h]

	// ���ǥǡ���
	var $lighting_ary_e;	// ���������ɤ�����
	var $hour_ary_e;			// �������֤�����[h]

	// ���󥹥ȥ饯��
	function sim_shoumei() {
		// ���饹�ѿ�������
		$this->lighting_ary[1] = 1;
		$this->lighting_ary[2] = 3;
		$this->lighting_ary[3] = 5;
		$this->lighting_ary[4] = 9;
		$this->lighting_ary[5] = 11;
		$this->lighting_ary[6] = 13;
		$this->lighting_ary[7] = 17;

		$this->lighting_ary_e[1] = 1;
		$this->lighting_ary_e[2] = 3;
		$this->lighting_ary_e[3] = 5;
		$this->lighting_ary_e[4] = 9;
		$this->lighting_ary_e[5] = 11;
		$this->lighting_ary_e[6] = 13;
		$this->lighting_ary_e[7] = 17;

		for ($i = 1; $i <= 7; $i++) {
			$this->hour_ary[$i] = 0;
			$this->hour_ary_e[$i] = 0;
		}
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_shoumei;

		// �����ǡ����Ƿ׻�
		$cal->lighting_ary = $this->lighting_ary;
		$cal->hour_ary = $this->hour_ary;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->lighting_ary = $this->lighting_ary_e;
		$cal->hour_ary = $this->hour_ary_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ��Ǯ����ָ������ؤ�����Τ�ɽ��
		$sub1 = "SELECT lp_place_cd,lp_lighting_kind FROM m_lighting_prm WHERE lp_lighting_type=1 AND lp_lighting_cd IN (" . join(',', $this->lighting_ary) . ")";
		$sub2 = "SELECT lp_place_cd,lp_lighting_kind FROM m_lighting_prm WHERE lp_lighting_type=2 AND lp_lighting_cd IN (" . join(',', $this->lighting_ary_e) . ")";
		$sql = "SELECT DISTINCT sub1.lp_place_cd FROM ($sub1) AS sub1 JOIN ($sub2) AS sub2 ON sub1.lp_place_cd=sub2.lp_place_cd AND sub1.lp_lighting_kind=sub2.lp_lighting_kind ORDER BY sub1.lp_place_cd";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		if ($nrow) {
			$ary = array();
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_row($result, $i);
				$ary[] = shoumei_basho_name($fetch[0]);
			}
			$msg = '��Ǯ����ָ������ؤ��ޤ�����' . join(', ', $ary) . '��';
		}

		// �������֤򸺤餷����Τ�ɽ��
		$ary = array();
		for ($i = 1; $i <= 7; $i++) {
			if ($this->hour_ary[$i] > $this->hour_ary_e[$i])
				$ary[] = $this->lighting_ary_e[$i];
		}
		if (count($ary)) {
			$sql = "SELECT DISTINCT lp_place_cd FROM m_lighting_prm WHERE lp_lighting_cd IN (" . join(',', $ary) . ") ORDER BY lp_place_cd";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			$ary = array();
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_row($result, $i);
				$ary[] = shoumei_basho_name($fetch[0]);
			}
			if ($msg)
				$msg .= '<br><br>';
			$msg .= '���ޤ�˾����������������򤷤ޤ�����' . join(', ', $ary) . '��';
		}
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_SHOUMEI);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->lighting_ary[1] = array_shift($ary);
			$this->lighting_ary[2] = array_shift($ary);
			$this->lighting_ary[3] = array_shift($ary);
			$this->lighting_ary[4] = array_shift($ary);
			$this->lighting_ary[5] = array_shift($ary);
			$this->lighting_ary[6] = array_shift($ary);
			$this->lighting_ary[7] = array_shift($ary);

			$this->lighting_ary_e[1] = array_shift($ary);
			$this->lighting_ary_e[2] = array_shift($ary);
			$this->lighting_ary_e[3] = array_shift($ary);
			$this->lighting_ary_e[4] = array_shift($ary);
			$this->lighting_ary_e[5] = array_shift($ary);
			$this->lighting_ary_e[6] = array_shift($ary);
			$this->lighting_ary_e[7] = array_shift($ary);

			for ($i = 1; $i <= 7; $i++) {
				$this->hour_ary[$i] = array_shift($ary);
				$this->hour_ary_e[$i] = array_shift($ary);
			}
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->lighting_ary[1];
		$ary[] = $this->lighting_ary[2];
		$ary[] = $this->lighting_ary[3];
		$ary[] = $this->lighting_ary[4];
		$ary[] = $this->lighting_ary[5];
		$ary[] = $this->lighting_ary[6];
		$ary[] = $this->lighting_ary[7];

		$ary[] = $this->lighting_ary_e[1];
		$ary[] = $this->lighting_ary_e[2];
		$ary[] = $this->lighting_ary_e[3];
		$ary[] = $this->lighting_ary_e[4];
		$ary[] = $this->lighting_ary_e[5];
		$ary[] = $this->lighting_ary_e[6];
		$ary[] = $this->lighting_ary_e[7];

		for ($i = 1; $i <= 7; $i++) {
			$ary[] = $this->hour_ary[$i];
			$ary[] = $this->hour_ary_e[$i];
		}

		return join(',', $ary);
	}
}

// �����׻����饹
class cal_shoumei {
	var $lighting_ary;	// ���������ɤ�����
	var $hour_ary;			// �������֤�����[h]

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		// ��åȿ������
		$sql = "SELECT lp_lighting_cd,lp_watt FROM m_lighting_prm";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$watt[$fetch->lp_lighting_cd] = $fetch->lp_watt;
		}

		// ��åȿ�����
		foreach ($this->hour_ary as $i => $hour) {
			if ($hour)
				$watt_hour += $watt[$this->lighting_ary[$i]] * $hour;
		}

		// ����꡼�׻�
		$calorie = $watt_hour * 365 * KWH_MCAL / 1000;

		// �����CO2����
		$kwh = ele_mcal_kwh($calorie);
		$cost = cnv_ele_mny($kwh);
		$co2 = cnv_ele_co2($kwh);
	}
}
?>