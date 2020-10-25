<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:�����ߥ������ǽ������饹
'******************************************************/

// �����ߥ������ǥ��饹
class sim_shigengomi {
	// �����ǡ���
	var $milk_num;				// �����ѥå��ܿ�
	var $milk_recycle;		// �����ѥå��ꥵ������
	var $pet_num;					// �ڥåȥܥȥ��ܿ�
	var $pet_recycle;			// �ڥåȥܥȥ�ꥵ������
	var $alminum_num;			// ����ߴ̰����ܿ�
	var $alminum_recycle;	// ����ߴ̰����ꥵ������

	// ���ǥǡ���
	var $milk_recycle_e;		// �����ѥå��ꥵ������
	var $pet_recycle_e;			// �ڥåȥܥȥ�ꥵ������
	var $alminum_recycle_e;	// ����ߴ̰����ꥵ������

	// ���󥹥ȥ饯��
	function sim_shigengomi() {
		$this->milk_num = 1;
		$this->milk_recycle = 1;
		$this->pet_num = 1;
		$this->pet_recycle = 1;
		$this->alminum_num = 1;
		$this->alminum_recycle = 1;
		$this->milk_recycle_e = 1;
		$this->pet_recycle_e = 1;
		$this->alminum_recycle_e = 1;
	}

	function simulation(&$energy, &$cost, &$co2, &$msg) {
		// �׻����饹����
		$cal = new cal_shigengomi;

		// �����ǡ����Ƿ׻�
		$cal->milk_num = $this->milk_num;
		$cal->milk_recycle = $this->milk_recycle;
		$cal->pet_num = $this->pet_num;
		$cal->pet_recycle = $this->pet_recycle;
		$cal->alminum_num = $this->alminum_num;
		$cal->alminum_recycle = $this->alminum_recycle;
		$cal->calculation($calorie_c, $cost_c, $co2_c);

		// ���Ǿ��Ƿ׻�
		$cal->milk_recycle = $this->milk_recycle_e;
		$cal->pet_recycle = $this->pet_recycle_e;
		$cal->alminum_recycle = $this->alminum_recycle_e;
		$cal->calculation($calorie_e, $cost_e, $co2_e);

		// ��ʬ��׻�
		$energy = $calorie_c - $calorie_e;
		$cost = $cost_c - $cost_e;
		$co2 = $co2_c - $co2_e;

		// ���ǥ�å���������
		if ($this->milk_recycle_e)
			$msg_ary[] .= '�����ѥå�';
		if ($this->pet_recycle_e)
			$msg_ary[] = '�ڥåȥܥȥ�';
		if ($this->alminum_recycle_e)
			$msg_ary[] = '����ߴ�';
		if ($msg_ary)
			$msg = join('��', $msg_ary) . '��ꥵ�����뤹�롣';
	}

	// DB������ǥǡ������ɤ߹���
	function read_eco_data() {
		// �������ǥǡ��������
		$eco_data = read_sim_data(GANBA_SHIGENGOMI);
		if ($eco_data) {
			// ���饹�ѿ��˥��å�
			$ary = explode(',', $eco_data);
			$this->milk_num = array_shift($ary);
			$this->milk_recycle = array_shift($ary);
			$this->pet_num = array_shift($ary);
			$this->pet_recycle = array_shift($ary);
			$this->alminum_num = array_shift($ary);
			$this->alminum_recycle = array_shift($ary);
			$this->milk_recycle_e = array_shift($ary);
			$this->pet_recycle_e = array_shift($ary);
			$this->alminum_recycle_e = array_shift($ary);
		}
	}

	// �������ǥǡ����򥫥�޶��ڤ�Ǽ���
	function get_eco_data() {
		// �������ǥǡ����򥫥�޶��ڤ���Խ�
		$ary[] = $this->milk_num;
		$ary[] = $this->milk_recycle;
		$ary[] = $this->pet_num;
		$ary[] = $this->pet_recycle;
		$ary[] = $this->alminum_num;
		$ary[] = $this->alminum_recycle;
		$ary[] = $this->milk_recycle_e;
		$ary[] = $this->pet_recycle_e;
		$ary[] = $this->alminum_recycle_e;

		return join(',', $ary);
	}
}

// �񸻤��ߥ������Ƿ׻����饹
class cal_shigengomi {
	var $milk_num;				// �����ѥå��ܿ�
	var $milk_recycle;		// �����ѥå��ꥵ������
	var $pet_num;					// �ڥåȥܥȥ��ܿ�
	var $pet_recycle;			// �ڥåȥܥȥ�ꥵ������
	var $alminum_num;			// ����ߴ̰����ܿ�
	var $alminum_recycle;	// ����ߴ̰����ꥵ������

	// ����꡼�����⡢CO2�׻�
	function calculation(&$calorie, &$cost, &$co2) {
		$dust = 0;
		// �����ѥå�
		if (!$this->milk_recycle)
			$dust += $this->milk_num * 0.033 * 52;

		// �ڥåȥܥȥ�
		if (!$this->pet_recycle)
			$dust += $this->pet_num * 0.020 * 52;

		// �ڥåȥܥȥ�
		if (!$this->alminum_recycle)
			$dust += $this->alminum_num * 0.046 * 52;

		$calorie = 0;
		$cost = 0;
		$co2 = cnv_dst_co2($dust);
	}
}
?>