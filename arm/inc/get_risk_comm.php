<?
/******************************************************
* system  : xxx �ꥹ������
* title   : �ꥹ�����ǥ����ȼ�������
* content : �ꥹ�����ǥ����Ȥ�쥹�ݥ󥹷����Ǽ�������
* version : 1.0
* author  : aizawa
*******************************************************/

// ���ꥹ�����ǥ����ȼ���
function get_risk_comm() {
	global $g_sheets;

	if ($g_sheets['����']['D5'] == 0 && $g_sheets['����']['D139'] == 0 && $g_sheets['»��']['C2'] == 0)
		return _get_risk_comm1();		// �ݸ�̤����
	else
		return _get_risk_comm2();		// �ݸ����ϺѤ�
}

// ���ݸ�̤���ϻ������ȼ���
function _get_risk_comm1() {
	global $g_sheets;

	// �ܿ͵ڤӶ��̻��ȥǡ���
	$ref['��˴���С��ݥ����'] = $g_sheets['��˴']['J16'] * 100;		// DEATH_21
	$ref['�뺧'] = $g_sheets['����']['F7'];		// A02i
	$ref['�Ҷ��Ϳ�'] = $g_sheets['����']['K3'];		// A03i
	$ref['���߳�'] = $g_sheets['����']['C49'];		// A12i/DEATH_17
	$ref['��˴��ɬ�׻����'] = $g_sheets['��˴']['J9'];		// DEATH_6
	$ref['�����������'] = $g_sheets['����']['C60'];		// F02i
	$ref['���ߤ�������'] = $g_sheets['����']['C48'];		// [���ܷ׻�]J25
	$ref['�ܿͼ���'] = $g_sheets['����']['D9'];		// A07i
	$ref['�۶��Լ���'] = $g_sheets['����']['E9'];		// A07y
	$ref['����ǯ��'] = $g_sheets['����']['D8'];		// [���ܷ׻�]A21
	$ref['����'] = $g_sheets['����']['D6'];		// [���ϵ���]A01
	$ref['������ǽ�ݥ����'] = $g_sheets['���']['J17'];		// IMP_74
	$ref['������ǽ�Ҳ��ݾ�'] = $g_sheets['������ǽ']['C39'];		// IMP_70
	$ref['�������'] = $g_sheets['����']['J27'];		// G07i
	$ref['��������Ĺ��ǯ��'] = VB_MAX('����', 'F30');		// G20i
	$ref['������ǽ��������'] = $g_sheets['����']['F60'];		// F06
	$ref['�Ҳ��ݾ��¸��'] = $g_sheets['Ϸ��']['C26'];		// OLD_42
	$ref['60�и�ϫƯ��¸��'] = $g_sheets['Ϸ��']['C27'];		// OLD_43
	$ref['Ϸ��������︺���'] = $g_sheets['Ϸ��']['J26'];		// OLD_44
	$ref['ǯ��ɬ�����߶��'] = $g_sheets['Ϸ��']['J27'];		// OLD_45
	$ref['���ޤ�'] = $g_sheets['����']['C28'];		// G01i

//echo '<pre>';var_dump($ref);echo '</pre>';

	// �ܿͥꥹ�����ǥ����ȼ���
	$comm['���'] = _get_risk_comm1_sg($ref);
	$comm['��˴']['�ܿ�'] = _get_risk_comm1_sb($ref);
	$comm['����']['�ܿ�'] = _get_risk_comm1_ir($ref);
	$comm['������ǽ']['�ܿ�'] = _get_risk_comm1_sf($ref);
	$comm['Ϸ��'] = _get_risk_comm1_rg($ref);
	$comm['»��'] = _get_risk_comm1_sn($ref);

	// �۶��Ի��ȥǡ���
	$ref['��˴���С��ݥ����'] = $g_sheets['��˴']['X16'] * 100;		// DEATH_21
	$ref['��˴��ɬ�׻����'] = $g_sheets['��˴']['X9'];		// DEATH_6
	$ref['�����������'] = $g_sheets['����']['D60'];		// F02i
	$ref['�ܿͼ���'] = $g_sheets['����']['E9'];		// A07i
	$ref['�۶��Լ���'] = $g_sheets['����']['D9'];		// A07y
	$ref['����ǯ��'] = $g_sheets['����']['E8'];		// [���ܷ׻�]A21
	$ref['����'] = $g_sheets['����']['E6'];		// [���ϵ���]A01
	$ref['������ǽ�ݥ����'] = $g_sheets['���']['W17'];		// IMP_74
	$ref['������ǽ�Ҳ��ݾ�'] = $g_sheets['������ǽ']['R38'];		// IMP_70
	$ref['������ǽ��������'] = $g_sheets['����']['G60'];		// F06

	// �۶��ԥꥹ�����ǥ����ȼ���
	$comm['��˴']['�۶���'] = _get_risk_comm1_sb($ref);
	$comm['����']['�۶���'] = _get_risk_comm1_ir($ref);
	$comm['������ǽ']['�۶���'] = _get_risk_comm1_sf($ref);

	return $comm;
}

// ���ݸ�̤���ϻ���祳���ȼ���
function _get_risk_comm1_sg($ref) {
	$comment = new comment_class;

	$comment->set('rfb_tp01');

	return $comment->get();
}

// ���ݸ�̤���ϻ���˴�����ȼ���
function _get_risk_comm1_sb($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['��˴���С��ݥ����'] >= 90) {
		if ($ref['�뺧'] == $g_encode['�뺧']['����'] || $ref['�Ҷ��Ϳ�'] > 0)
			$comment->set('rfb_sb01', 1);
		else
			$comment->set('rfb_sb01', 2);
	} else
		$comment->set('rfb_sb01', 3);

	// �ʣ���
	if ($ref['���߳�'] > 500 && $ref['���߳�'] / $ref['��˴��ɬ�׻����'] > 0.1)
		$comment->set('rfb_sb02', 1, 1, 'mata');
	if (($ref['�뺧'] == $g_encode['�뺧']['����'] || $ref['�Ҷ��Ϳ�'] > 0) && $ref['�����������'] / $ref['���ߤ�������'] < 0.6)
		$comment->set('rfb_sb02', 2, 2, 'mata');
	if ($ref['�۶��Լ���'] >= 130 && $ref['�뺧'] == $g_encode['�뺧']['����'])
		$comment->set('rfb_sb02', 3, 3, 'mata');

	// �귿��
	$comment->set('rfb_sb03');

	return $comment->get();
}

// ���ݸ�̤���ϻ����ť����ȼ���
function _get_risk_comm1_ir($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ����귿��
	$comment->set('rfb_ir01');

	// �ʣ���
	if ($ref['����ǯ��'] < 40)
		$comment->set('rfb_ir02', 1, 1, 'mata');
	else
		$comment->set('rfb_ir02', 2, 1, 'mata');

	// �ʣ���
	if ($ref['����'] == $g_encode['����']['��'] && $ref['����ǯ��'] <= 29)
		$comment->set('rfb_ir03', 1, 2, 'doyo');
	if ($ref['����'] == $g_encode['����']['��'])
		$comment->set('rfb_ir03', 2, 1, 'doyo');

	// �귿��
	$comment->set('rfb_ir04');

	return $comment->get();
}

// ���ݸ�̤���ϻ�������ǽ�����ȼ���
function _get_risk_comm1_sf($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['����ǯ��'] >= 57)
		$comment->set('rfb_sf01', 1);
	else {
		if ($ref['������ǽ�ݥ����'] >= 90) {
			if ($ref['�۶��Լ���'] < 130 || $ref['�뺧'] == $g_encode['�뺧']['�ȿ�'])
				$comment->set('rfb_sf01', 2);
			elseif ($ref['�۶��Լ���'] >= 130)
				$comment->set('rfb_sf01', 3);
		} else {
			if ($ref['�뺧'] == $g_encode['�뺧']['����'] || $ref['�Ҷ��Ϳ�'] > 0)
				$comment->set('rfb_sf01', 4);
			elseif ($ref['�뺧'] == $g_encode['�뺧']['�ȿ�'] && $ref['�Ҷ��Ϳ�'] == 0)
				$comment->set('rfb_sf01', 5);
		}
	}

	// �ʣ���
	if ($ref['����ǯ��'] < 57 && $ref['�������'] > 0)
		$comment->set('rfb_sf02', 1, 2, 'mata');
	if ($ref['������ǽ��������'] / $ref['���ߤ�������'] < 0.8)
		$comment->set('rfb_sf02', 2, 1, 'mata');
	if ($ref['������ǽ�Ҳ��ݾ�'] > 50)
		$comment->set('rfb_sf02', 3, 3, 'mata');

	// �귿��
	$comment->set('rfb_sf03');

	return $comment->get();
}

// ���ݸ�̤���ϻ�Ϸ�女���ȼ���
function _get_risk_comm1_rg($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['Ϸ��������︺���'] <= 0) {
		$comment->set('rfb_rg01', 1);
	} elseif (0 < $ref['Ϸ��������︺���'] && $ref['Ϸ��������︺���'] <= 5) {
		$tmp = $ref['ǯ��ɬ�����߶��'] / ($ref['�뺧'] == $g_encode['�뺧']['����'] ? $ref['�ܿͼ���'] + $ref['�۶��Լ���'] : $ref['�ܿͼ���']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('rfb_rg01', 2);
		elseif ($tmp >= 0.2)
			$comment->set('rfb_rg01', 3);
	} else {
		$tmp = $ref['ǯ��ɬ�����߶��'] / ($ref['�뺧'] == $g_encode['�뺧']['����'] ? $ref['�ܿͼ���'] + $ref['�۶��Լ���'] : $ref['�ܿͼ���']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('rfb_rg01', 4);
		elseif ($tmp >= 0.2)
			$comment->set('rfb_rg01', 5);
	}

	// �ʣ���
	if ($ref['60�и�ϫƯ��¸��'] > 0.25)
		$comment->set('rfb_rg02', 1, 2, 'mata');
	if ($ref['�Ҳ��ݾ��¸��'] > 0.7)
		$comment->set('rfb_rg02', 2, 1, 'mata');
	if ((60 - $ref['����ǯ��']) < $ref['��������Ĺ��ǯ��'] && $ref['����ǯ��'] < 60)
		$comment->set('rfb_rg02', 3, 3, 'mata');
	if ($ref['���ޤ�'] == $g_encode['����']['����'])
		$comment->set('rfb_rg02', 5, 4, 'mata');
	if ($ref['���ޤ�'] == $g_encode['����']['����'])
		$comment->set('rfb_rg02', 4, 4, 'mata');

	// �귿��
	$comment->set('rfb_rg03');

	return $comment->get();
}

// ���ݸ�̤���ϻ�»�������ȼ���
function _get_risk_comm1_sn($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �귿��
	$comment->set('rfb_sg01');

	// �귿��
	$comment->set('rfb_sg02');

	return $comment->get();
}

// ���ݸ����ϺѤߥ����ȼ���
function _get_risk_comm2() {
	global $g_sheets;

	// �ܿ͵ڤӶ��̻��ȥǡ���
	$ref['��˴���С��ݥ����'] = $g_sheets['��˴']['J16'] * 100;		// DEATH_21
	$ref['�ҳ���˴�������ݸ���۹��'] = $g_sheets['����']['I28'] + $g_sheets['����']['I35'];
	$ref['��˴�ݸ���۹��'] = $g_sheets['����']['I37'];		// rs_sb31/DEATH_19
	$ref['���ݼ�����ݸ�����'] = $g_sheets['����']['D10'];		// sh_sal03
	$ref['��˴�ݸ����'] = $g_sheets['����']['D37'];		// rs_sb21
	$ref['������'] = $g_sheets['����']['D13'];		// sh_sal06
	$ref['�����Ի�˴����²�����³'] = $g_sheets['����']['D14'];		// sh_sal07
	$ref['�����ݸ�����'] = $g_sheets['����']['D30'];
	$ref['Ǿ´��ݥ����'] = $g_sheets['����']['K12'] * 100;		// MEDI_41
	$ref['����ݥ����'] = $g_sheets['����']['K15'] * 100;		// MEDI_42
	$ref['���ڹ��ɥݥ����'] = $g_sheets['����']['K18'] * 100;		// MEDI_43
	$ref['����ɾ���ݥ����'] = $g_sheets['���']['J12'];		// MEDI_45
	$ref['����ǯ��'] = $g_sheets['����']['D8'];		// [���ܷ׻�]A21
	$ref['������ǽ�ݥ����'] = $g_sheets['���']['J17'];		// IMP_74
	$ref['60����λ��'] = $g_sheets['����']['D103'];		// sh_ssf05
	$ref['Ϸ��������︺���'] = $g_sheets['Ϸ��']['J26'];		// OLD_44
	$ref['ǯ��ɬ�����߶��'] = $g_sheets['Ϸ��']['J27'];		// OLD_45
	$ref['�Ŀ�ǯ��'] = VB_SUM('����', 'D91');		// sh_srg01
	$ref['�뺧'] = $g_sheets['����']['F7'];		// A02i
	$ref['�ܿͼ���'] = $g_sheets['����']['D9'];		// A07i
	$ref['�۶��Լ���'] = $g_sheets['����']['E9'];		// A07y
	$ref['»��ɾ���ݥ����'] = $g_sheets['���']['J26'];		// DAM_24
	$ref['�������'] = $g_sheets['����']['C29'];		// G02
	$ref['»�ݼ�����ݸ�����'] = $g_sheets['»��']['C13'];		// sp_al08
	$ref['�������'] = $g_sheets['����']['J27'];		// G07i
	$ref['�кҷ�ʪɾ�����'] = $g_sheets['»��']['G49'];		// sp_ks01
	$ref['�кҲȺ�ɾ�����'] = $g_sheets['»��']['G51'];		// sa_ks03
	$ref['�����Ǥ�ݸ�'] = $g_sheets['»��']['G27'];		// sp_sn12
	$ref['���ޤ�'] = $g_sheets['����']['C28'];		// G01i

	// �ܿͥꥹ�����ǥ����ȼ���
	$comm['���'] = _get_risk_comm2_sg($ref);
	$comm['��˴']['�ܿ�'] = _get_risk_comm2_sb($ref);
	$comm['����']['�ܿ�'] = _get_risk_comm2_ir($ref);
	$comm['������ǽ']['�ܿ�'] = _get_risk_comm2_sf($ref);
	$comm['Ϸ��'] = _get_risk_comm2_rg($ref);
	$comm['»��'] = _get_risk_comm2_sn($ref);

	// �۶��Ի��ȥǡ���
	$ref['��˴���С��ݥ����'] = $g_sheets['��˴']['X16'] * 100;		// DEATH_21
	$ref['�ҳ���˴�������ݸ���۹��'] = $g_sheets['����']['I162'] + $g_sheets['����']['I169'];
	$ref['��˴�ݸ���۹��'] = $g_sheets['����']['I171'];		// rs_sb31/DEATH_19
	$ref['���ݼ�����ݸ�����'] = $g_sheets['����']['D144'];		// sh_sal03
	$ref['��˴�ݸ����'] = $g_sheets['����']['D171'];		// rs_sb21
	$ref['������'] = $g_sheets['����']['D147'];		// sh_sal06
	$ref['�����Ի�˴����²�����³'] = $g_sheets['����']['D148'];		// sh_sal07
	$ref['�����ݸ�����'] = $g_sheets['����']['D164'];
	$ref['Ǿ´��ݥ����'] = $g_sheets['����']['X12'] * 100;		// MEDI_41
	$ref['����ݥ����'] = $g_sheets['����']['X15'] * 100;		// MEDI_42
	$ref['���ڹ��ɥݥ����'] = $g_sheets['����']['X18'] * 100;		// MEDI_43
	$ref['����ɾ���ݥ����'] = $g_sheets['���']['W12'];		// MEDI_45
	$ref['����ǯ��'] = $g_sheets['����']['E8'];		// [���ܷ׻�]A21
	$ref['������ǽ�ݥ����'] = $g_sheets['���']['W17'];		// IMP_74
	$ref['60����λ��'] = $g_sheets['����']['D228'];		// sh_ssf05
	$ref['�Ŀ�ǯ��'] = VB_SUM('����', 'D215');		// sh_srg01
	$ref['�ܿͼ���'] = $g_sheets['����']['E9'];		// A07i
	$ref['�۶��Լ���'] = $g_sheets['����']['D9'];		// A07y

	// �۶��Կ��ǥ����ȼ���
	$comm['��˴']['�۶���'] = _get_risk_comm2_sb($ref);
	$comm['����']['�۶���'] = _get_risk_comm2_ir($ref);
	$comm['������ǽ']['�۶���'] = _get_risk_comm2_sf($ref);

	return $comm;
}

// ���ݸ����ϺѤ���祳���ȼ���
function _get_risk_comm2_sg($ref) {
	$comment = new comment_class;

	$comment->set('rfb_tp02');

	return $comment->get();
}
// ���ݸ����ϺѤ߻�˴�����ȼ���
function _get_risk_comm2_sb($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['��˴���С��ݥ����'] >= 110)
		$comment->set('sfb_sb01', 1);
	elseif ($ref['��˴���С��ݥ����'] >= 90)
		$comment->set('sfb_sb01', 2);
	else
		$comment->set('sfb_sb01', 3);

	// �ʣ���
	if ($ref['�ҳ���˴�������ݸ���۹��'] > $ref['��˴�ݸ���۹��'] * 1.2)
		$comment->set('sfb_sb02', 1, 2, 'mata');
	if (is_array($ref['���ݼ�����ݸ�����'])) {
		foreach ($ref['���ݼ�����ݸ�����'] as $key => $val) {
			if ($val == $g_encode['�ݸ�����']['���롼���ݸ�'] && $ref['��˴�ݸ����'][$key] / $ref['��˴�ݸ���۹��'] >= 0.6) {
				$comment->set('sfb_sb02', 2, 1, 'mata');
				break;
			}
		}
	}

	// �귿��
	$comment->set('sfb_sb03');

	return $comment->get();
}


// ���ݸ����ϺѤ߰��ť����ȼ���
function _get_risk_comm2_ir($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['����ɾ���ݥ����'] >= 110)
		$comment->set('sfb_ir01', 1);
	elseif ($ref['����ɾ���ݥ����'] >= 90)
		$comment->set('sfb_ir01', 2);
	else
		$comment->set('sfb_ir01', 3);

	// �ʣ���
	if (($ref['Ǿ´��ݥ����'] < 80 || $ref['���ڹ��ɥݥ����'] < 80) && $ref['����ǯ��'] > 45)
		$comment->set('sfb_ir02', 1, 2, 'mata');
	if (is_array($ref['������'])) {
		foreach ($ref['������'] as $key => $val) {
			if ($val == $g_encode['������']['�۶��Ԥؤ�����'] && $ref['�����Ի�˴����²�����³'][$key] == $g_encode['�����Ի�˴����²�����³']['�������'] && $ref['�����ݸ�����'][$key] == $g_encode['�ݸ�����']['�����ݸ�']) {
				$comment->set('sfb_ir02', 2, 1, 'mata');
				break;
			}
		}
	}
	if ($ref['����ݥ����'] >= 110)
		$comment->set('sfb_ir02', 3, 3, 'mata');

	// �귿��
	$comment->set('sfb_ir03');

	return $comment->get();
}

// ���ݸ����ϺѤ߽�����ǽ�����ȼ���
function _get_risk_comm2_sf($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['����ǯ��'] >= 57)
		$comment->set('sfb_sf01', 1);
	else {
		if ($ref['������ǽ�ݥ����'] >= 110)
			$comment->set('sfb_sf01', 2);
		elseif ($ref['������ǽ�ݥ����'] >= 90)
			$comment->set('sfb_sf01', 3);
		else
			$comment->set('sfb_sf01', 4);
	}

	// �ʣ���
	if (is_array($ref['60����λ��'])) {
		foreach ($ref['60����λ��'] as $key => $val) {
			if ($val == $g_encode['60����λ']['���']) {
				$comment->set('sfb_sf02', '', 1, 'mata');
				break;
			}
		}
	}

	// �귿��
	$comment->set('sfb_sf03');

	return $comment->get();
}

// ���ݸ����ϺѤ�Ϸ�女���ȼ���
function _get_risk_comm2_rg($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['Ϸ��������︺���'] <= 0) {
		$comment->set('sfb_rg01', 1);
	} elseif ($ref['Ϸ��������︺���'] <= 5) {
		$tmp = $ref['ǯ��ɬ�����߶��'] / ($ref['�뺧'] == $g_encode['�뺧']['����'] ? $ref['�ܿͼ���'] + $ref['�۶��Լ���'] : $ref['�ܿͼ���']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('sfb_rg01', 2);
		elseif ($tmp >= 0.2)
			$comment->set('sfb_rg01', 3);
	} else {
		$tmp = $ref['ǯ��ɬ�����߶��'] / ($ref['�뺧'] == $g_encode['�뺧']['����'] ? $ref['�ܿͼ���'] + $ref['�۶��Լ���'] : $ref['�ܿͼ���']);
		if (0 <= $tmp && $tmp < 0.2)
			$comment->set('sfb_rg01', 4);
		elseif ($tmp >= 0.2)
			$comment->set('sfb_rg01', 5);
	}

	// �ʣ���
	if ($ref['�Ŀ�ǯ��'] > 0)
		$comment->set('sfb_rg02', 1, 1, 'mata');
	elseif ($ref['�Ŀ�ǯ��'] == 0)
		$comment->set('sfb_rg02', 2, 1, 'mata');

	// �귿��
	$comment->set('sfb_rg03');

	return $comment->get();
}

// ���ݸ����ϺѤ�»�������ȼ���
function _get_risk_comm2_sn($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['»��ɾ���ݥ����'] >= 110)
		$comment->set('sfb_sn01', 1);
	elseif ($ref['»��ɾ���ݥ����'] >= 90)
		$comment->set('sfb_sn01', 2);
	else
		$comment->set('sfb_sn01', 3);

	// �ʣ���
	if (is_array($ref['»�ݼ�����ݸ�����'])) {
		foreach ($ref['»�ݼ�����ݸ�����'] as $val) {
			if ($ref['�������'] == $g_encode['�������']['��Ʊ�����Ŵ�ڡ�'] && ($val == $g_encode['»���ݸ�����']['����к��ݸ�'] || $val == $g_encode['»���ݸ�����']['��������ݸ�'])) {
				$comment->set('sfb_sn02', 1, 2, 'mata');
				break;
			}
		}
	}
	if ($ref['�������'] > 0)
		$comment->set('sfb_sn02', 2, 1, 'mata');
	if ($ref['�кҷ�ʪɾ�����'] == 0 || $ref['�кҲȺ�ɾ�����'] == 0)
		$comment->set('sfb_sn02', 3, 4, 'mata');
	if ($ref['�����Ǥ�ݸ�'] >= 10000)
		$comment->set('sfb_sn02', 4, 5, 'mata');
	if ($ref['���ޤ�'] == $g_encode['����']['����'] && $ref['�кҲȺ�ɾ�����'] == 0)
		$comment->set('sfb_sn02', 5, 3, 'mata');

	// �귿��
	$comment->set('sfb_sn03');

	return $comment->get();
}
?>