<?
/******************************************************
* system  : xxx �ꥹ������
* title   : �ݸ�ɾ�������ȼ�������
* content : �ݸ�ɾ�������Ȥ�쥹�ݥ󥹷����Ǽ�������
* version : 1.0
* author  : aizawa
*******************************************************/

// ���ݸ�ɾ�������ȼ���
function get_hyoka_comm() {
	$comm['��˴'] = _get_hyoka_comm_sb();
	$comm['����'] = _get_hyoka_comm_ir();

	return $comm;
}

// ����˴�ݸ�ɾ�������ȼ���
function _get_hyoka_comm_sb() {
	global $g_sheets;

	$s = &$g_sheets['��˴�ݸ�'];

	// �ܿ�
	$n = 0;
	for ($i = 1; $i <= $g_sheets['����']['D5']; $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['��˴�ݸ�']['D14'][$i] != '' || $g_sheets['��˴�ݸ�']['E14'][$i] != '') {
			$n++;

			// ����󻲾ȥǡ���
			$ref['���Ρ���ʱ�'] = $s['D32'][$i];		// hs_sb01
			$ref['�ݸ���������'] = $s['D34'][$i];			// hs_sb02
			$ref['�ݸ�����¤�'] = $s['D35'][$i];			// hs_sb03
			$ref['��³��'] = $s['D36'][$i];						// hs_sb04
			$ref['�������'] = $s['D14'][$i];					// sh_ssb01
			$ref['�ݸ�����'] = $s['D8'][$i];					// sh_sal03
			$ref['��������ݸ�'] = $s['D25'][$i];			// sh_ssb08
			$ref['����������'] = $s['D15'][$i];			// sh_ssb03
			$ref['�����λǯ��'] = $s['D16'][$i];			// sh_ssb03
			$ref['������'] = $s['D11'][$i];					// C9
			$ref['�����Ի�˴����²�����³'] = $s['D12'][$i];		// C10
			$ref['�������'] = '�����';

			// ����󥳥��ȼ���
			$comm['�ܿ�']["�ݸ�$n"]['�ݸ���������']['�����'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['�ܿ�']["�ݸ�$n"]['�ݸ����γ�¤�']['�����'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['�ܿ�']["�ݸ�$n"]['��³��']['�����'] = _get_hyoka_comm_sb_keizokusei($ref);

			// ���󻲾ȥǡ���
			$ref['���Ρ���ʱ�'] = $s['E32'][$i];		// hs_sb01
			$ref['�ݸ���������'] = $s['E34'][$i];			// hs_sb02
			$ref['�ݸ�����¤�'] = $s['E35'][$i];			// hs_sb03
			$ref['��³��'] = $s['E36'][$i];						// hs_sb04
			$ref['�������'] = $s['E14'][$i];					// sh_tsb01
			$ref['�ݸ�����'] = $s['E8'][$i];					// sh_tal04
			$ref['��������ݸ�'] = $s['E25'][$i];			// sh_tsb08
			$ref['����������'] = $s['E15'][$i];			// sh_tsb03
			$ref['�����λǯ��'] = $s['E16'][$i];			// sh_tsb03
			$ref['������'] = $s['D11'][$i];					// C9
			$ref['�����Ի�˴����²�����³'] = $s['D12'][$i];		// C10
			$ref['�������'] = '����';

			// ���󥳥��ȼ���
			$comm['�ܿ�']["�ݸ�$n"]['�ݸ���������']['����'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['�ܿ�']["�ݸ�$n"]['�ݸ����γ�¤�']['����'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['�ܿ�']["�ݸ�$n"]['��³��']['����'] = _get_hyoka_comm_sb_keizokusei($ref);
		}
	}

	// �۶���
	$n = 0;
	for ($i = 1; $i <= $g_sheets['����']['D139']; $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['��˴�ݸ�']['D67'][$i] != '' || $g_sheets['��˴�ݸ�']['E67'][$i] != '') {
			$n++;

			// ����󻲾ȥǡ���
			$ref['���Ρ���ʱ�'] = $s['D85'][$i];		// hs_sb01
			$ref['�ݸ���������'] = $s['D87'][$i];			// hs_sb02
			$ref['�ݸ�����¤�'] = $s['D88'][$i];			// hs_sb03
			$ref['��³��'] = $s['D89'][$i];						// hs_sb04
			$ref['�������'] = $s['D67'][$i];					// sh_ssb01
			$ref['�ݸ�����'] = $s['D61'][$i];					// sh_sal03
			$ref['��������ݸ�'] = $s['D78'][$i];			// sh_ssb08
			$ref['����������'] = $s['D68'][$i];			// sh_ssb03
			$ref['�����λǯ��'] = $s['D69'][$i];			// sh_ssb03
			$ref['������'] = $s['D64'][$i];					// C9
			$ref['�����Ի�˴����²�����³'] = $s['D65'][$i];		// C10
			$ref['�������'] = '�����';

			// ����󥳥��ȼ���
			$comm['�۶���']["�ݸ�$n"]['�ݸ���������']['�����'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['�۶���']["�ݸ�$n"]['�ݸ����γ�¤�']['�����'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['�۶���']["�ݸ�$n"]['��³��']['�����'] = _get_hyoka_comm_sb_keizokusei($ref);

			// ���󻲾ȥǡ���
			$ref['���Ρ���ʱ�'] = $s['E85'][$i];		// hs_sb01
			$ref['�ݸ���������'] = $s['E87'][$i];			// hs_sb02
			$ref['�ݸ�����¤�'] = $s['E88'][$i];			// hs_sb03
			$ref['��³��'] = $s['E89'][$i];						// hs_sb04
			$ref['�������'] = $s['E67'][$i];					// sh_tsb01
			$ref['�ݸ�����'] = $s['E61'][$i];					// sh_tal04
			$ref['��������ݸ�'] = $s['E78'][$i];			// sh_tsb08
			$ref['����������'] = $s['E68'][$i];			// sh_tsb03
			$ref['�����λǯ��'] = $s['E69'][$i];			// sh_tsb03
			$ref['������'] = $s['D64'][$i];					// C9
			$ref['�����Ի�˴����²�����³'] = $s['D65'][$i];		// C10
			$ref['�������'] = '����';

			// ���󥳥��ȼ���
			$comm['�۶���']["�ݸ�$n"]['�ݸ���������']['����'] = _get_hyoka_comm_sb_anteido($ref);
			$comm['�۶���']["�ݸ�$n"]['�ݸ����γ�¤�']['����'] = _get_hyoka_comm_sb_wariyasusa($ref);
			$comm['�۶���']["�ݸ�$n"]['��³��']['����'] = _get_hyoka_comm_sb_keizokusei($ref);
		}
	}

	return $comm;
}

// ����˴�ݸ����ݸ��������٥����ȼ���
function _get_hyoka_comm_sb_anteido($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['�������'] == $g_encode['������ּ���']['����']) {
		if ($ref['�ݸ���������'] >= 100)
			$comment->set('sbs_a01', 1, 1);
		elseif ($ref['�ݸ���������'] > 80)
			$comment->set('sbs_a01', 2, 2);
		else
			$comment->set('sbs_a01', 3, 3);
	}

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����ġ�']) {
		if ($ref['�ݸ���������'] > 80)
			$comment->set('sbs_a01', 4, 4);
		else
			$comment->set('sbs_a01', 5, 5, null, array('E39' => $ref['����������']));
	}

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����Բġ�']) {
		if ($ref['�ݸ���������'] > 80)
			$comment->set('sbs_a01', 6, 6, null, array('E40' => $ref['�����λǯ��']));
		else
			$comment->set('sbs_a01', 7, 7, null, array('E40' => $ref['�����λǯ��']));
	}

	return $comment->get();
}

// ����˴�ݸ����ݸ����γ�¤������ȼ���
function _get_hyoka_comm_sb_wariyasusa($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['�ݸ�����'] == $g_encode['�ݸ�����']['�����ݸ�'] ||$ref['�ݸ�����'] == $g_encode['�ݸ�����']['��Ϸ�ݸ�']) {
		if ($ref['�ݸ�����¤�'] >= 88)
			$comment->set('sbs_b01', 1, 1);
		elseif ($ref['�ݸ�����¤�'] >= 56)
			$comment->set('sbs_b01', 2, 2);
		else
			$comment->set('sbs_b01', 3, 3);
	}

	if ($ref['�ݸ�����'] == $g_encode['�ݸ�����']['���롼���ݸ�'])
		$comment->set('sbs_b01', 4, 4);

	if ($ref['�ݸ�����'] == $g_encode['�ݸ�����']['�ѳ۽����ݸ�'])
		$comment->set('sbs_b01', 5, 5);

	if ($ref['�ݸ�����'] != '' && $ref['�ݸ�����'] != $g_encode['�ݸ�����']['���롼���ݸ�'] && $ref['��������ݸ�'] == 0)
		$comment->set('sbs_b01', 6, 6);

	if ($ref['���Ρ���ʱ�'] == 1)
		$comment->set('sbs_b01', 7, 7);

	return $comment->get();
}

// ����˴�ݸ�����³�������ȼ���
function _get_hyoka_comm_sb_keizokusei($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['�������'] == $g_encode['������ּ���']['����'])
		$comment->set('sbs_c01', 1, 1);

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����ġ�']) {
		if ($ref['�������'] == '�����')
			$comment->set('sbs_c01', 2, 2);
		if ($ref['�������'] == '����')
			$comment->set('sbs_c01', 3, 3);
	}

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����Բġ�']) {
		if ($ref['��³��'] >= 100)
			$comment->set('sbs_c01', 4, 4, null, array('E40' => $ref['�����λǯ��']));
		else
			$comment->set('sbs_c01', 5, 5, null, array('E40' => $ref['�����λǯ��']));
	}

	// �ʣ���
	if ($ref['������'] == $g_encode['������']['�۶��Ԥؤ�����'] && $ref['�����Ի�˴����²�����³'] == $g_encode['�����Ի�˴����²�����³']['�������'])
		$comment->set('sbs_c02', '', 1, 'mata');

	return $comment->get();
}

// �������ݸ�ɾ�������ȼ���
function _get_hyoka_comm_ir() {
	global $g_sheets;

	$s = &$g_sheets['�����ݸ�'];

	// �ܿ�
	$n = 0;
	for ($i = 1; $i <= $g_sheets['����']['D5']; $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['�����ݸ�']['D14'][$i] != '' || $g_sheets['�����ݸ�']['E14'][$i] != '') {
			$n++;

			// ����󻲾ȥǡ���
			$ref['��³��'] = $s['D33'][$i];						// hs_ir01
			$ref['�ݸ�����ʧ��ô'] = $s['D34'][$i];		// hs_ir02
			$ref['�ݾ���ϰ�'] = $s['D35'][$i];				// hs_ir03
			$ref['���դ�Ĺ��'] = $s['D36'][$i];				// hs_ir04
			$ref['�������'] = $s['D14'][$i];					// sh_sir01
			$ref['������°����'] = $s['D26'][$i];		// sh_sir10
			$ref['�ٵ볫����'] = $s['D29'][$i];				// sh_sir13
			$ref['����������ʬ'] = $s['D30'][$i];			// sh_sir14
			$ref['����������'] = $s['D15'][$i];			// sh_ssb03
			$ref['�����λǯ��'] = $s['D16'][$i];			// sh_ssb03
			$ref['������'] = $s['D11'][$i];					// sh_sal06
			$ref['�����Ի�˴����²�����³'] = $s['D12'][$i];		// sh_sal07
			$ref['���մ���'] = $s['D31'][$i];					// sh_sir16

			// ����󥳥��ȼ���
			$comm['�ܿ�']["�ݸ�$n"]['��³��']['�����'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['�ܿ�']["�ݸ�$n"]['�ݸ�����ʧ��ô']['�����'] = _get_hyoka_comm_ir_futan($ref);
			$comm['�ܿ�']["�ݸ�$n"]['�ݾ���ϰ�']['�����'] = _get_hyoka_comm_ir_hani($ref);
			$comm['�ܿ�']["�ݸ�$n"]['���դ�Ĺ��']['�����'] = _get_hyoka_comm_ir_nagasa($ref);

			// ���󻲾ȥǡ���
			$ref['��³��'] = $s['E33'][$i];						// hs_ir01
			$ref['�ݸ�����ʧ��ô'] = $s['E34'][$i];		// hs_ir02
			$ref['�ݾ���ϰ�'] = $s['E35'][$i];				// hs_ir03
			$ref['���դ�Ĺ��'] = $s['E36'][$i];				// hs_ir04
			$ref['�������'] = $s['E14'][$i];					// sh_tir01
			$ref['������°����'] = $s['E26'][$i];		// sh_tir10
			$ref['�ٵ볫����'] = $s['E29'][$i];				// sh_tir13
			$ref['����������ʬ'] = $s['E30'][$i];			// sh_tir14
			$ref['����������'] = $s['E15'][$i];			// sh_tsb03
			$ref['�����λǯ��'] = $s['E16'][$i];			// sh_tsb03
			$ref['������'] = $s['D11'][$i];					// sh_sal06
			$ref['�����Ի�˴����²�����³'] = $s['D12'][$i];		// sh_sal07
			$ref['���մ���'] = $s['E31'][$i];					// sh_tir16

			// ���󥳥��ȼ���
			$comm['�ܿ�']["�ݸ�$n"]['��³��']['����'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['�ܿ�']["�ݸ�$n"]['�ݸ�����ʧ��ô']['����'] = _get_hyoka_comm_ir_futan($ref);
			$comm['�ܿ�']["�ݸ�$n"]['�ݾ���ϰ�']['����'] = _get_hyoka_comm_ir_hani($ref);
			$comm['�ܿ�']["�ݸ�$n"]['���դ�Ĺ��']['����'] = _get_hyoka_comm_ir_nagasa($ref);
		}
	}

	// �۶���
	$n = 0;
	for ($i = 1; $i <= $g_sheets['����']['D139']; $i++) {
		// ������ּ��̤������Τ�����ͭ���Ȥ���
		if ($g_sheets['�����ݸ�']['D61'][$i] != '' || $g_sheets['�����ݸ�']['E61'][$i] != '') {
			$n++;

			// ����󻲾ȥǡ���
			$ref['��³��'] = $s['D80'][$i];						// hs_ir01
			$ref['�ݸ�����ʧ��ô'] = $s['D81'][$i];		// hs_ir02
			$ref['�ݾ���ϰ�'] = $s['D82'][$i];				// hs_ir03
			$ref['���դ�Ĺ��'] = $s['D83'][$i];				// hs_ir04
			$ref['�������'] = $s['D61'][$i];					// sh_sir01
			$ref['������°����'] = $s['D73'][$i];		// sh_sir10
			$ref['�ٵ볫����'] = $s['D76'][$i];				// sh_sir13
			$ref['����������ʬ'] = $s['D77'][$i];			// sh_sir14
			$ref['����������'] = $s['D62'][$i];			// sh_ssb03
			$ref['�����λǯ��'] = $s['D63'][$i];			// sh_ssb03
			$ref['������'] = $s['D58'][$i];					// sh_sal06
			$ref['�����Ի�˴����²�����³'] = $s['D59'][$i];		// sh_sal07
			$ref['���մ���'] = $s['D78'][$i];					// sh_sir16

			// ����󥳥��ȼ���
			$comm['�۶���']["�ݸ�$n"]['��³��']['�����'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['�۶���']["�ݸ�$n"]['�ݸ�����ʧ��ô']['�����'] = _get_hyoka_comm_ir_futan($ref);
			$comm['�۶���']["�ݸ�$n"]['�ݾ���ϰ�']['�����'] = _get_hyoka_comm_ir_hani($ref);
			$comm['�۶���']["�ݸ�$n"]['���դ�Ĺ��']['�����'] = _get_hyoka_comm_ir_nagasa($ref);

			// ���󻲾ȥǡ���
			$ref['��³��'] = $s['E80'][$i];						// hs_ir01
			$ref['�ݸ�����ʧ��ô'] = $s['E81'][$i];		// hs_ir02
			$ref['�ݾ���ϰ�'] = $s['E82'][$i];				// hs_ir03
			$ref['���դ�Ĺ��'] = $s['E83'][$i];				// hs_ir04
			$ref['�������'] = $s['E61'][$i];					// sh_tir01
			$ref['������°����'] = $s['E73'][$i];		// sh_tir10
			$ref['�ٵ볫����'] = $s['E76'][$i];				// sh_tir13
			$ref['����������ʬ'] = $s['E77'][$i];			// sh_tir14
			$ref['����������'] = $s['E62'][$i];			// sh_tsb03
			$ref['�����λǯ��'] = $s['E63'][$i];			// sh_tsb03
			$ref['������'] = $s['D58'][$i];					// sh_sal06
			$ref['�����Ի�˴����²�����³'] = $s['D59'][$i];		// sh_sal07
			$ref['���մ���'] = $s['E78'][$i];					// sh_tir16

			// ���󥳥��ȼ���
			$comm['�۶���']["�ݸ�$n"]['��³��']['����'] = _get_hyoka_comm_ir_keizokusei($ref);
			$comm['�۶���']["�ݸ�$n"]['�ݸ�����ʧ��ô']['����'] = _get_hyoka_comm_ir_futan($ref);
			$comm['�۶���']["�ݸ�$n"]['�ݾ���ϰ�']['����'] = _get_hyoka_comm_ir_hani($ref);
			$comm['�۶���']["�ݸ�$n"]['���դ�Ĺ��']['����'] = _get_hyoka_comm_ir_nagasa($ref);
		}
	}

	return $comm;
}

// �������ݸ�����³�������ȼ���
function _get_hyoka_comm_ir_keizokusei($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['�������'] == $g_encode['������ּ���']['����'])
		$comment->set('irs_a01', 1, 1);

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����ġ�'])
		$comment->set('irs_a01', 2, 2);

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����Բġ�']) {
		if ($ref['��³��'] >= 80)
			$comment->set('irs_a01', 3, 3, null, array('C14' => $ref['�����λǯ��']));
		elseif ($ref['��³��'] >= 70)
			$comment->set('irs_a01', 4, 4, null, array('C14' => $ref['�����λǯ��']));
		else
			$comment->set('irs_a01', 5, 5, null, array('C14' => $ref['�����λǯ��']));
	}

	// �ʣ���
	if ($ref['������'] == $g_encode['������']['�۶��Ԥؤ�����'] && $ref['�����Ի�˴����²�����³'] == $g_encode['�����Ի�˴����²�����³']['�������'])
		$comment->set('irs_a02', '', 1, 'mata');

	return $comment->get();
}

// �������ݸ����ݸ�����ʧ��ô�����ȼ���
function _get_hyoka_comm_ir_futan($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['�������'] == $g_encode['������ּ���']['����']) {
		if ($ref['�ݸ�����ʧ��ô'] >= 100)
			$comment->set('irs_b01', 1, 1);
		elseif ($ref['�ݸ�����ʧ��ô'] >= 80)
			$comment->set('irs_b01', 2, 2);
		else
			$comment->set('irs_b01', 3, 3);
	}

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����ġ�']) {
		if ($ref['�ݸ�����ʧ��ô'] >= 80)
			$comment->set('irs_b01', 4, 4);
		else
			$comment->set('irs_b01', 5, 5, null, array('C13' => $ref['����������']));
	}

	if ($ref['�������'] == $g_encode['������ּ���']['����ʹ����Բġ�']) {
		if ($ref['�ݸ�����ʧ��ô'] >= 80)
			$comment->set('irs_b01', 6, 6, null, array('C14' => $ref['�����λǯ��']));
		elseif ($ref['�ݸ�����ʧ��ô'] >= 70)
			$comment->set('irs_b01', 7, 7, null, array('C14' => $ref['�����λǯ��']));
		else
			$comment->set('irs_b01', 8, 8, null, array('C14' => $ref['�����λǯ��']));
	}

	return $comment->get();
}

// �������ݸ����ݾ���ϰϥ����ȼ���
function _get_hyoka_comm_ir_hani($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['�ݾ���ϰ�'] >= 100)
		$comment->set('irs_c01', 1, 2);

	if ($ref['�ݾ���ϰ�'] == 100 && ($ref['�ٵ볫����'] == $g_encode['�ٵ볫����']['4��5���ʾ�'] || $ref['�ٵ볫����'] == $g_encode['�ٵ볫����']['7��8���ʾ�']) && $ref['����������ʬ'] == $g_encode['����������ʬ']['�ٵ뤢��'])
		$comment->set('irs_c01', 2, 1);

	if (80 <= $ref['�ݾ���ϰ�'] && $ref['�ݾ���ϰ�'] < 100)
		$comment->set('irs_c01', 3, 3);

	if ($ref['�ݾ���ϰ�'] < 80)
		$comment->set('irs_c01', 4, 4);

	// �ʣ���
	if ($ref['������°����'] > 0)
		$comment->set('irs_c02', '', 1, 'mata');

	return $comment->get();
}

// �������ݸ������դ�Ĺ�������ȼ���
function _get_hyoka_comm_ir_nagasa($ref) {
	global $g_encode;

	$comment = new comment_class;

	// �ʣ���
	if ($ref['���դ�Ĺ��'] >= 100)
		$comment->set('irs_d01', 1, 1);
	elseif ($ref['���դ�Ĺ��'] > 80)
		$comment->set('irs_d01', 2, 2);
	elseif ($ref['���դ�Ĺ��'] == 80)
		$comment->set('irs_d01', 3, 3);
	else
		$comment->set('irs_d01', 4, 4, null, array('C29' => $ref['���մ���']));

	return $comment->get();
}
?>