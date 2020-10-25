<?
/******************************************************
* system  : xxx �ꥹ������
* title   : �ݸ�ɾ����̼�������
* content : �ݸ�ɾ����̤�쥹�ݥ󥹷����Ǽ�������
* version : 1.0
* author  : aizawa
*******************************************************/

// ���ݸ�ɾ���׻�
function get_hyoka_data($basic_info, $company_info, $hoken_info, $osusume_info, $type) {
	// �ݸ�ɾ�� or ��������Ƚ��
	switch ($type) {
	case $hoken_info->m_type:
		// �ݸ�����������饹
		$hyoka_info = &$hoken_info;

		// ǯ���ݸ����ʻ���̵����
		$amount0 = null;
		$amount1 = null;
		break;
	case $osusume_info->m_type:
		// ���������ݸ�����������饹
		$hyoka_info = &$osusume_info;

		// ǯ���ݸ�������
		list($amount0, $amount1) = $hoken_info->get_yearly_insurance();
		break;
	}

	// �ݸ�ɾ���׻�����
	calc_basic_info1($basic_info->m_basic_info);
	calc_company_info($company_info->m_guarantee, $company_info->m_retire_rate);
	calc_basic_info2();
	calc_seiho_info($hyoka_info->m_seiho_data);
	calc_sonpo_info($hyoka_info->m_sonpo_data);
	calc_basic_info3();
	calc_sb_hoken();
	calc_ir_hoken();
	calc_shoken_shindan();

	// ���ơ�����
	$data['���ơ�����'] = !$hoken_info->m_exist_flag ? 1 : (!$osusume_info->m_exist_flag ? 2 : 3);

	// ������
	$data['������'] = format_date($hyoka_info->m_update_date);

	// �뺧����
	$data['�뺧'] = $basic_info->m_basic_info['���ʤ�']['�ܿ�']['�뺧'];

	// ��ɾ���ǡ���
	$data['����'] = _get_hyoka_data_list($hyoka_info->m_seiho_data, $hyoka_info->m_sonpo_data, $amount0, $amount1);
	$data['��˴'] = _get_hyoka_data_sb($hyoka_info->m_seiho_data);
	$data['����'] = _get_hyoka_data_ir($hyoka_info->m_seiho_data);
	$data['»��'] = _get_hyoka_data_sn($hyoka_info->m_sonpo_data);

	return $data;
}

// ���ݸ��ΰ����ǡ������Ф�����
function _get_hyoka_data_list($seiho_data, $sonpo_data, $amount0, $amount1) {
	global $g_sheets, $g_encode;

	$c = &$g_sheets['�ڷ�����'];

	// �ܿ�
	$n = 0;
	$d = &$data['�ܿ�'];
	$d['�ݸ�����ʧ��'] = round($c['B22']);
	if ($amount0 != null)
		$d['�ݸ�������'] = round(($c['B22'] - $amount0) / 10000);
	$d['�ݸ��Ŀ�'] = count($c['A6']) + count($c['A16']);

	// �ܿ͡����ݷ�
	for ($i = 1; $i <= count($c['A6']); $i++) {
		$n++;
		$seiho_id = $g_sheets['����']['D6'][$i];

		$d = &$data['�ܿ�']["�ݸ�$n"];
		$d['�ݸ�̾��'] = $c['A6'][$i];
		$d['�ݸ����'] = $c['A7'][$i];
		$d['�ݸ�����'] = $c['A8'][$i];
		if (isset($seiho_data[$seiho_id]['����']->recommend_change_kind))
			$d['�ѹ�����'] = $seiho_data[$seiho_id]['����']->recommend_change_kind;
		$d['����ID'] = $seiho_id;
		$d['ǯ���ݸ���'] = round($c['B6'][$i]);

		$d = &$data['�ܿ�']["�ݸ�$n"]['�ݾ��ϰ���'];
		$d['��˴'] = round($c['D7'][$i]);
		$d['����'] = round($c['F7'][$i]);
		$d['������ǽ']['���շ���'] = $g_sheets['����']['D99'][$i];
		$d['������ǽ']['����'] = ($g_sheets['����']['D99'][$i] == $g_encode['���շ���']['���']) ? round($g_sheets['����']['D100'][$i]) : round($g_sheets['����']['D101'][$i] * 100, 1);
		$d['Ϸ��']['ǯ��'] = round($c['J6'][$i]);
		$d['Ϸ��']['������'] = round($c['J7'][$i]);
		$d['»��'] = '��';
	}

	// �ܿ͡�»�ݷ�
	for ($i = 1; $i <= count($c['A16']); $i++) {
		$n++;
		$sonpo_id = $g_sheets['»��']['C3'][$i];

		$d = &$data['�ܿ�']["�ݸ�$n"];
		$d['�ݸ�̾��'] = $c['A16'][$i];
		$d['�ݸ����'] = $c['A17'][$i];
		$d['�ݸ�����'] = $c['A18'][$i];
		if (isset($sonpo_data[$sonpo_id]->recom_update_flag))
			$d['�ѹ�����'] = $sonpo_data[$sonpo_id]->recom_update_flag;
		$d['»��ID'] = $sonpo_id;
		$d['ǯ���ݸ���'] = round($c['B16'][$i]);

		$d = &$data['�ܿ�']["�ݸ�$n"]['�ݾ��ϰ���'];
		$d['��˴'] = '��';
		$d['����'] = '��';
		$d['������ǽ']['���շ���'] = '��';
		$d['������ǽ']['����'] = '��';
		$d['Ϸ��']['ǯ��'] = round($c['J16'][$i]);
		$d['Ϸ��']['������'] = round($c['J17'][$i]);
		$d['»��'] = $c['L16'][$i];
	}

	// �۶���
	$n = 0;
	$d = &$data['�۶���'];
	$d['�ݸ�����ʧ��'] = round($c['B46']);
	if ($amount1 != null)
		$d['�ݸ�������'] = round(($c['B46'] - $amount1) / 10000);
	$d['�ݸ��Ŀ�'] = count($c['A30']) + count($c['A40']);

	// �۶��ԡ����ݷ�
	for ($i = 1; $i <= count($c['A30']); $i++) {
		$n++;
		$seiho_id = $g_sheets['����']['D140'][$i];

		$d = &$data['�۶���']["�ݸ�$n"];
		$d['�ݸ�̾��'] = $c['A30'][$i];
		$d['�ݸ����'] = $c['A31'][$i];
		$d['�ݸ�����'] = $c['A32'][$i];
		if (isset($seiho_data[$seiho_id]['����']->recommend_change_kind))
			$d['�ѹ�����'] = $seiho_data[$seiho_id]['����']->recommend_change_kind;
		$d['����ID'] = $seiho_id;
		$d['ǯ���ݸ���'] = round($c['B30'][$i]);

		$d = &$data['�۶���']["�ݸ�$n"]['�ݾ��ϰ���'];
		$d['��˴'] = round($c['D31'][$i]);
		$d['����'] = round($c['F31'][$i]);
		$d['������ǽ']['���շ���'] = $g_sheets['����']['D224'][$i];
		$d['������ǽ']['����'] = ($g_sheets['����']['D224'][$i] == $g_encode['���շ���']['���']) ? round($g_sheets['����']['D225'][$i]) : round($g_sheets['����']['D226'][$i] * 100, 1);
		$d['Ϸ��']['ǯ��'] = round($c['J30'][$i]);
		$d['Ϸ��']['������'] = round($c['J31'][$i]);
		$d['»��'] = '��';
	}

	// �۶��ԡ�»�ݷ�
	for ($i = 1; $i <= count($c['A40']); $i++) {
		$n++;
		$sonpo_id = $g_sheets['»��']['C3'][$i];

		$d = &$data['�۶���']["�ݸ�$n"];
		$d['�ݸ�̾��'] = $c['A40'][$i];
		$d['�ݸ����'] = $c['A41'][$i];
		$d['�ݸ�����'] = $c['A42'][$i];
		if (isset($sonpo_data[$sonpo_id]->recom_update_flag))
			$d['�ѹ�����'] = $sonpo_data[$sonpo_id]->recom_update_flag;
		$d['»��ID'] = $sonpo_id;
		$d['ǯ���ݸ���'] = '��';

		$d = &$data['�۶���']["�ݸ�$n"]['�ݾ��ϰ���'];
		$d['��˴'] = '��';
		$d['����'] = '��';
		$d['������ǽ']['���շ���'] = '��';
		$d['������ǽ']['����'] = '��';
		$d['Ϸ��']['ǯ��'] = round($c['J40'][$i]);
		$d['Ϸ��']['������'] = round($c['J41'][$i]);
		$d['»��'] = $c['L40'][$i];
	}

	return $data;
}

// ����˴�ݸ�ɾ���ǡ������Ф�����
function _get_hyoka_data_sb($seiho_data) {
	global $g_sheets;

	$c = &$g_sheets['�ڷ�����'];

	// �ܿ�
	$n = 0;
	$data['�ܿ�']['�ݸ��Ŀ�'] = 0;
	for ($i = 1; $i <= count($c['AG6']); $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['��˴�ݸ�']['D14'][$i] != '' || $g_sheets['��˴�ݸ�']['E14'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['����']['D6'][$i];

			$d = &$data['�ܿ�']["�ݸ�$n"];
			$d['�ݸ�̾��'] = $c['AG6'][$i];
			$d['�ݸ����'] = $c['AG7'][$i];
			$d['�ݸ�����'] = $c['AG8'][$i];
			if (isset($seiho_data[$seiho_id]['��˴']->recom_update_flag)) {
				$d['�ѹ�����']['�����'] = $seiho_data[$seiho_id]['��˴']->recom_update_flag;
				$d['�ѹ�����']['����'] = $seiho_data[$seiho_id]['����']->death_recom_update_flag;
			}
			$d['����ID'] = $seiho_id;
			$d['�ݸ����']['�µ���˴']['�����'] = round($c['AH6'][$i]);
			$d['�ݸ����']['�µ���˴']['����'] = round($c['AH7'][$i]);
			$d['�ݸ����']['�ҳ���˴']['�����'] = round($c['AJ6'][$i]);
			$d['�ݸ����']['�ҳ���˴']['����'] = round($c['AJ7'][$i]);
			$d['���ɾ��']['�����'] = $c['AM6'][$i];
			$d['���ɾ��']['����'] = $c['AM7'][$i];
			$d['��³��']['�����'] = $c['AN6'][$i];
			$d['��³��']['����'] = $c['AN7'][$i];
			$d['�ݸ���������']['�����'] = $c['AO6'][$i];
			$d['�ݸ���������']['����'] = $c['AO7'][$i];
			$d['�ݸ����γ�¤�']['�����'] = $c['AP6'][$i];
			$d['�ݸ����γ�¤�']['����'] = $c['AP7'][$i];
		}
	}
	$data['�ܿ�']['�ݸ��Ŀ�'] = $n;

	// �۶���
	$n = 0;
	$data['�۶���']['�ݸ��Ŀ�'] = 0;
	for ($i = 1; $i <= count($c['AG30']); $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['��˴�ݸ�']['D67'][$i] != '' || $g_sheets['��˴�ݸ�']['E67'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['����']['D140'][$i];

			$d = &$data['�۶���']["�ݸ�$n"];
			$d['�ݸ�̾��'] = $c['AG30'][$i];
			$d['�ݸ����'] = $c['AG31'][$i];
			$d['�ݸ�����'] = $c['AG32'][$i];
			if (isset($seiho_data[$seiho_id]['��˴']->recom_update_flag)) {
				$d['�ѹ�����']['�����'] = $seiho_data[$seiho_id]['��˴']->recom_update_flag;
				$d['�ѹ�����']['����'] = $seiho_data[$seiho_id]['����']->death_recom_update_flag;
			}
			$d['����ID'] = $seiho_id;
			$d['�ݸ����']['�µ���˴']['�����'] = round($c['AH30'][$i]);
			$d['�ݸ����']['�µ���˴']['����'] = round($c['AH31'][$i]);
			$d['�ݸ����']['�ҳ���˴']['�����'] = round($c['AJ30'][$i]);
			$d['�ݸ����']['�ҳ���˴']['����'] = round($c['AJ31'][$i]);
			$d['���ɾ��']['�����'] = $c['AM30'][$i];
			$d['���ɾ��']['����'] = $c['AM31'][$i];
			$d['��³��']['�����'] = $c['AN30'][$i];
			$d['��³��']['����'] = $c['AN31'][$i];
			$d['�ݸ���������']['�����'] = $c['AO30'][$i];
			$d['�ݸ���������']['����'] = $c['AO31'][$i];
			$d['�ݸ����γ�¤�']['�����'] = $c['AP30'][$i];
			$d['�ݸ����γ�¤�']['����'] = $c['AP31'][$i];
		}
	}
	$data['�۶���']['�ݸ��Ŀ�'] = $n;

	return $data;
}

// �������ݸ�ɾ���ǡ������Ф�����
function _get_hyoka_data_ir($seiho_data) {
	global $g_sheets;

	$c = &$g_sheets['�ڷ�����'];

	// �ܿ�
	$n = 0;
	$data['�ܿ�']['�ݸ��Ŀ�'] = 0;
	for ($i = 1; $i <= count($c['Z6']); $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['�����ݸ�']['D14'][$i] != '' || $g_sheets['�����ݸ�']['E14'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['����']['D6'][$i];

			$d = &$data['�ܿ�']["�ݸ�$n"];
			$d['�ݸ�̾��'] = $c['Z6'][$i];
			$d['�ݸ����'] = $c['Z7'][$i];
			$d['�ݸ�����'] = $c['Z8'][$i];
			if (isset($seiho_data[$seiho_id]['����']->recom_update_flag)) {
				$d['�ѹ�����']['�����'] = $seiho_data[$seiho_id]['����']->recom_update_flag;
				$d['�ѹ�����']['����'] = $seiho_data[$seiho_id]['����']->medi_death_recom_update_flag;
			}
			$d['����ID'] = $seiho_id;

			$d = &$data['�ܿ�']["�ݸ�$n"]['�µ��̤��ݾ�'];
			$d['���̰���']['�����'] = round($c['N6'][$i]);
			$d['���̰���']['����'] = round($c['N8'][$i]);
			$d['����']['�����'] = round($c['P6'][$i]);
			$d['����']['����'] = round($c['P8'][$i]);
			$d['Ǿ´��']['�����']['����'] = round($c['R6'][$i]);
			$d['Ǿ´��']['�����']['���ǻ�'] = round($c['R7'][$i]);
			$d['Ǿ´��']['����']['����'] = round($c['R8'][$i]);
			$d['Ǿ´��']['����']['���ǻ�'] = round($c['R9'][$i]);
			$d['����']['�����']['����'] = round($c['T6'][$i]);
			$d['����']['�����']['���ǻ�'] = round($c['T7'][$i]);
			$d['����']['����']['����'] = round($c['T8'][$i]);
			$d['����']['����']['���ǻ�'] = round($c['T9'][$i]);
			$d['���ڹ���']['�����']['����'] = round($c['V6'][$i]);
			$d['���ڹ���']['�����']['���ǻ�'] = round($c['V7'][$i]);
			$d['���ڹ���']['����']['����'] = round($c['V8'][$i]);
			$d['���ڹ���']['����']['���ǻ�'] = round($c['V9'][$i]);
			$d['��Ǣ��']['�����']['����'] = round($c['X6'][$i]);
			$d['��Ǣ��']['�����']['���ǻ�'] = round($c['X7'][$i]);
			$d['��Ǣ��']['����']['����'] = round($c['X8'][$i]);
			$d['��Ǣ��']['����']['���ǻ�'] = round($c['X9'][$i]);

			$d = &$data['�ܿ�']["�ݸ�$n"]['ɾ��'];
			$d['���ɾ��']['�����'] = $c['AB6'][$i];
			$d['���ɾ��']['����'] = $c['AB7'][$i];
			$d['��³��']['�����'] = $c['AC6'][$i];
			$d['��³��']['����'] = $c['AC7'][$i];
			$d['�ݸ���������']['�����'] = $c['AD6'][$i];
			$d['�ݸ���������']['����'] = $c['AD7'][$i];
			$d['�ݾ㳫�Ͼ��']['�����'] = $c['AE6'][$i];
			$d['�ݾ㳫�Ͼ��']['����'] = $c['AE7'][$i];
			$d['���դ�Ĺ��']['�����'] = $c['AF6'][$i];
			$d['���դ�Ĺ��']['����'] = $c['AF7'][$i];
		}
	}
	$data['�ܿ�']['�ݸ��Ŀ�'] = $n;

	// �۶���
	$n = 0;
	$data['�۶���']['�ݸ��Ŀ�'] = 0;
	for ($i = 1; $i <= count($c['Z30']); $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['�����ݸ�']['D61'][$i] != '' || $g_sheets['�����ݸ�']['E61'][$i] != '') {
			$n++;
			$seiho_id = $g_sheets['����']['D140'][$i];

			$d = &$data['�۶���']["�ݸ�$n"];
			$d['�ݸ�̾��'] = $c['Z30'][$i];
			$d['�ݸ����'] = $c['Z31'][$i];
			$d['�ݸ�����'] = $c['Z32'][$i];
			if (isset($seiho_data[$seiho_id]['����']->recom_update_flag)) {
				$d['�ѹ�����']['�����'] = $seiho_data[$seiho_id]['����']->recom_update_flag;
				$d['�ѹ�����']['����'] = $seiho_data[$seiho_id]['����']->medi_death_recom_update_flag;
			}
			$d['����ID'] = $seiho_id;

			$d = &$data['�۶���']["�ݸ�$n"]['�µ��̤��ݾ�'];
			$d['���̰���']['�����'] = round($c['N30'][$i]);
			$d['���̰���']['����'] = round($c['N32'][$i]);
			$d['����']['�����'] = round($c['P30'][$i]);
			$d['����']['����'] = round($c['P32'][$i]);
			$d['Ǿ´��']['�����']['����'] = round($c['R30'][$i]);
			$d['Ǿ´��']['�����']['���ǻ�'] = round($c['R31'][$i]);
			$d['Ǿ´��']['����']['����'] = round($c['R32'][$i]);
			$d['Ǿ´��']['����']['���ǻ�'] = round($c['R33'][$i]);
			$d['����']['�����']['����'] = round($c['T30'][$i]);
			$d['����']['�����']['���ǻ�'] = round($c['T31'][$i]);
			$d['����']['����']['����'] = round($c['T32'][$i]);
			$d['����']['����']['���ǻ�'] = round($c['T33'][$i]);
			$d['���ڹ���']['�����']['����'] = round($c['V30'][$i]);
			$d['���ڹ���']['�����']['���ǻ�'] = round($c['V31'][$i]);
			$d['���ڹ���']['����']['����'] = round($c['V32'][$i]);
			$d['���ڹ���']['����']['���ǻ�'] = round($c['V33'][$i]);
			$d['��Ǣ��']['�����']['����'] = round($c['X30'][$i]);
			$d['��Ǣ��']['�����']['���ǻ�'] = round($c['X31'][$i]);
			$d['��Ǣ��']['����']['����'] = round($c['X32'][$i]);
			$d['��Ǣ��']['����']['���ǻ�'] = round($c['X33'][$i]);

			$d = &$data['�۶���']["�ݸ�$n"]['ɾ��'];
			$d['���ɾ��']['�����'] = $c['AB30'][$i];
			$d['���ɾ��']['����'] = $c['AB31'][$i];
			$d['��³��']['�����'] = $c['AC30'][$i];
			$d['��³��']['����'] = $c['AC31'][$i];
			$d['�ݸ���������']['�����'] = $c['AD30'][$i];
			$d['�ݸ���������']['����'] = $c['AD31'][$i];
			$d['�ݾ㳫�Ͼ��']['�����'] = $c['AE30'][$i];
			$d['�ݾ㳫�Ͼ��']['����'] = $c['AE31'][$i];
			$d['���դ�Ĺ��']['�����'] = $c['AF30'][$i];
			$d['���դ�Ĺ��']['����'] = $c['AF31'][$i];
		}
	}
	$data['�۶���']['�ݸ��Ŀ�'] = $n;

	return $data;
}

// ��»���ݸ�ɾ���ǡ������Ф�����
function _get_hyoka_data_sn($sonpo_data) {
	global $g_sheets;

	$c = &$g_sheets['�ڷ�����'];

	$n = 0;
	$d = &$data;
	$d['�ݸ��Ŀ�'] = count($c['AQ6']);
	for ($i = 1; $i <= count($c['AQ6']); $i++) {
		$n++;
		$sonpo_id = $g_sheets['»��']['C3'][$i];

		$d = &$data["�ݸ�$n"];
		$d['�ݸ�̾��'] = $c['AQ6'][$i];
		$d['�ݸ����'] = $c['AQ7'][$i];
		$d['�ݸ�����'] = $c['AQ8'][$i];
		if (isset($sonpo_data[$sonpo_id]->recom_update_flag))
			$d['�ѹ�����'] = $sonpo_data[$sonpo_id]->recom_update_flag;
		$d['»��ID'] = $sonpo_id;

		$d['�к��ݸ�']['��ʪ'] = round($c['AR6'][$i]);
		$d['�к��ݸ�']['�Ⱥ�'] = round($c['AR7'][$i]);
		$d['�Ͽ��ݸ�']['��ʪ'] = round($c['AT6'][$i]);
		$d['�Ͽ��ݸ�']['�Ⱥ�'] = round($c['AT7'][$i]);
		$d['��ư���ݸ�']['�п�'] = round($c['AV6'][$i]);
		$d['��ư���ݸ�']['��ʪ'] = round($c['AV7'][$i]);
		$d['�Ŀ������Ǥ�ݸ�'] = round($c['AX6'][$i]);
	}

	return $data;
}
?>