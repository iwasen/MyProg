<?
/******************************************************
' System :Eco-footprint ����
' Content:��󥯼�������
'******************************************************/

// CO2�︺�̡���󥯡��ݥ���Ȥ��������
function get_rank_point($seq_no, $date, &$co2_ary, &$rank_ary, &$co2_cut) {
	// ������CO2�ӽ��̷׻�
	get_co2($seq_no, $date, $co2_ary);

	// ��ǯ��CO2�ӽ��̷׻�
	$date_ly = add_date($date, -1, 0, 0);
	get_co2($seq_no, $date_ly, $co2_ary_ly);

	// ̤���Ϲ��ܤϷ׻�������ʤ��褦�ˤ���
	$sql = "SELECT bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($co2_ary['ele'] == 0) {
			$co2_ary_ly['ele'] = 0;
			$no_input['ele'] = true;
		}

		if ($co2_ary['gas'] == 0) {
			$co2_ary_ly['gas'] = 0;
			$no_input['gas'] = true;
		}

		if ($co2_ary['wtr'] == 0) {
			$co2_ary_ly['wtr'] = 0;
			$no_input['wtr'] = true;
		}

		if ($fetch->bd_ol_inp_cd != 1 && $co2_ary['oil'] == 0) {
			$co2_ary_ly['oil'] = 0;
			$no_input['oil'] = true;
		}

		if ($fetch->bd_gl_inp_cd != 1 && $co2_ary['gso'] == 0) {
			$co2_ary_ly['gso'] = 0;
			$no_input['gso'] = true;
		}

		if ($fetch->bd_gm_inp_cd != 1 && $co2_ary['dst'] == 0) {
			$co2_ary_ly['dst'] = 0;
			$no_input['dst'] = true;
		}
	}

	// CO2��׷׻�
	get_co2_total($co2_ary);
	get_co2_total($co2_ary_ly);

	// �����ȥ������ι��
	$co2_ary['oil_gso'] = $co2_ary['oil']  + $co2_ary['gso'];
	$co2_ary_ly['oil_gso'] = $co2_ary_ly['oil']  + $co2_ary_ly['gso'];

	// �����ȥ�������̤���ϥ����å�
	if ($no_input['oil'] || $no_input['gso'])
		$no_input['oil_gso'] = true;

	// CO2�︺��
	if ($co2_ary_ly['ttl'])
		$co2_cut = (int)($co2_ary['ttl'] - $co2_ary_ly['ttl']);

	// CO2�ӽ��̥��饹����
	$sql = "SELECT cc_class FROM t_co2_class WHERE cc_seq_no=$seq_no";
	$class = db_fetch1($sql);
	if (!$class)
		$class = 2;

	// ɾ���ͤȥݥ���ȼ���
	return get_rank($class, $co2_ary, $co2_ary_ly, $rank_ary, $no_input);
}

// ����ǯ���CO2�ӽ��̼���
function get_co2($seq_no, $date, &$co2_ary) {
	// ���ͥ륮������ǡ�������
	$sql = "SELECT bd_el_use,bd_gs_use,bd_ol_use,bd_wt_use,bd_gl_use,bd_gm_use"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// CO2�ӽ��̷׻�
		$co2_ary['ele'] = cnv_ele_co2($fetch->bd_el_use);
		$co2_ary['gas'] = cnv_gas_co2($fetch->bd_gs_use);
		$co2_ary['wtr'] = cnv_wtr_co2($fetch->bd_wt_use);
		$co2_ary['oil'] = cnv_oil_co2($fetch->bd_ol_use);
		$co2_ary['gso'] = cnv_gso_co2($fetch->bd_gl_use);
		$co2_ary['dst'] = cnv_dst_co2($fetch->bd_gm_use);
	}
}

// CO2�ӽ��̹�׼���
function get_co2_total(&$co2_ary) {
	$total = 0;
	if (is_array($co2_ary)) {
		foreach ($co2_ary as $co2)
			$total += $co2;
	}
	$co2_ary['ttl'] = $total;
}

// CO2�ӽ��̤���ɾ���ͼ���
function get_rank($class, &$co2_ary, &$co2_ary_ly, &$rank_ary, &$no_input) {
	$point = 0;

	foreach ($co2_ary as $key => $co2) {
		$co2_ly = $co2_ary_ly[$key];

		if ($no_input[$key])
			$rank = 0;
		else {
			// �︺�̤Υѡ������
			if ($co2_ly == 0) {
				if ($co2 == 0)
					$co2_percent = 0;		// ��ǯ����ǯ��0�ʤ�0%�Ȥ���
				else
					$co2_percent = 100;	// ��ǯ��0����ǯ��0�Ǥʤ��ʤ�100%�Ȥ���
			} else
				$co2_percent = (int)(($co2 - $co2_ly) / $co2_ly * 100);

			// ��100����˴ݤ�
			if ($co2_percent > 100)
				$co2_percent = 100;
			elseif ($co2_percent < -100)
				$co2_percent = -100;

			// ��󥯤ȴ��ݥ���Ȥ����
			$sql = "SELECT ra_rank,ra_point FROM m_rank WHERE ra_class=$class AND ra_co2_low<=$co2_percent AND ra_co2_high>$co2_percent";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$rank = $fetch->ra_rank;
				if ($key == 'ttl')
					$point = $fetch->ra_point;
			}
		}

		$rank_ary[$key] = $rank;
	}

	return $point;
}
?>