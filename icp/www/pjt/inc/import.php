<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:���С�����ݡ��Ƚ���
'******************************************************/

function read_import_file($import_file, &$ok_ary, &$ng_ary, $pjt_id, $job_id = 0) {
	global $g_participant_auth, $g_staff_auth;

	// �ե���������
	$member_fields = array(
		'mail_addr',
		'password',
		'org_id',
		'org_member_id',
		'nickname',
		'name1',
		'name2',
		'name1_kana',
		'name2_kana',
		'sex_cd',
		'birthday',
		'mikikon_cd',
		'shokugyou',
		'gyoushu',
		'shokushu',
		'zip_cd',
		'address',
		'tel_no'
	);
	$participant_auth_fields = array(
		'�ʲ��',
		'Webȯ��',
		'�᡼������',
		'�᡼�����',
		'ȯ��ON/OFF'
	);
	$staff_auth_fields = array(
		'�ץ������ȥ��д���',
		'����ִ���',
		'ȯ�����������ݡ���',
		'ȯ��Ͽ�������ݡ���',
		'���üԥ��Х������ݡ���',
		'ȯ�����ץ������ݡ���'
	);

	$handle = fopen($import_file,"r");

	$n = 0;
	$header = true;
	$member_data = array();
	$participant_auth = array();
	$staff_auth = array();

	// CSV�ե������ɤ߹���
	while ($csv = fgetcsv($handle, 1000, ",")) {
		if ($header) {
			$header = false;
			continue;
		}

		if ($csv[0] != '') {
			$c = 0;

			// ���С��������
			foreach ($member_fields as $field)
				$member_data[$n][$field] = trim(mb_convert_encoding($csv[$c++], 'EUC-JP', 'SJIS'));

			// ���üԷϸ��¼���
			if ($job_id != 0) {
				foreach ($participant_auth_fields as $auth)
					$participant_auth[$n][$g_participant_auth[$auth]] = trim($csv[$c++]);
			}

			// �����åշϸ��¼���
			foreach ($staff_auth_fields as $auth)
				$staff_auth[$n][$g_staff_auth[$auth]] = trim($csv[$c++]);

			// �᡼�륢�ɥ쥹��ʸ����
			$member_data[$n]['mail_addr'] = strtolower($member_data[$n]['mail_addr']);

			// �᡼�륢�ɥ쥹�ν�ʣ�����å�
			for ($i = 0; $i < $n; $i++) {
				if ($member_data[$i]['mail_addr'] == $member_data[$n]['mail_addr']) {
					$member_data[$i]['mail_err'] = true;
					$member_data[$n]['mail_err'] = true;
				}
			}

			$n++;
		}
	}
	fclose($handle);

	$ok_ary = array();
	$ng_ary = array();

	// ����ݡ��ȥǡ��������å�
	for ($i = 0; $i < $n; $i++) {
		$data = &$member_data[$i];

		// �ǡ��������å�
		$err = array();
		c_mail_addr($data['mail_addr'], $data['mail_err'], $err);
		c_password($data['password'], $err);
		c_org_id($data['org_id'], $err);
		c_org_member_id($data['org_member_id'], $err);
		c_nickname($data['nickname'], $err);
//	c_birthday($data['birthday'], $err);
		if ($job_id != 0)
			c_participant_auth($participant_auth[$i], $err);
		c_staff_auth($staff_auth[$i], $err);

		// ���üԷϸ����Խ�
		if ($job_id != 0)
			$data['participant_auth'] = edit_participant_auth($participant_auth[$i], $data['mail_addr'], $pjt_id, $job_id);

		// �����åշϸ����Խ�
		$data['staff_auth'] = edit_staff_auth($staff_auth[$i], $data['mail_addr'], $pjt_id);

		// member_id���å�
		$sql = sprintf("SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=%s AND pm_mail_addr=%s",
				sql_number($pjt_id),
				sql_char($data['mail_addr']));
		$data['member_id'] = db_fetch1($sql);

		if (count($err) == 0)
			$ok_ary[] = $data;
		else {
			$data['err'] = $err;
			$ng_ary[] = $data;
		}
	}
}

// �᡼�륢�ɥ쥹�����å�
function c_mail_addr($mail_addr, $mail_err, &$err) {
	if ($mail_addr == '')
		$err[] = '�᡼�륢�ɥ쥹������ޤ���';
	elseif ($mail_err)
		$err[] = '�᡼�륢�ɥ쥹����ʣ���Ƥ��ޤ���';
	elseif (!check_mail_addr($mail_addr))
		$err[] = '�᡼�륢�ɥ쥹�������Ǥ���';
}

// �ѥ���ɥ����å�
function c_password($password, &$err) {
	if ($password == '')
		$err[] = '�ѥ���ɤ�����ޤ���';
}

// �ȿ�ID�����å�
function c_org_id($org_id, &$err) {
	if ($org_id == '')
		$err[] = '�ȿ�ID������ޤ���';
	elseif (!is_numeric($org_id))
		$err[] = '�ȿ�ID�������ǤϤ���ޤ���';
	else {
		$sql = "SELECT COUNT(*) FROM m_org WHERE or_org_id=" . sql_number($org_id);
		if (db_fetch1($sql) == 0)
			$err[] = '�ȿ�ID���ȿ��ޥ�������Ͽ����Ƥ��ޤ���';
	}
}

// �ȿ����С�ID�����å�
function c_org_member_id($org_member_id, &$err) {
	if ($org_member_id == '')
		$err[] = '�ȿ����С�ID������ޤ���';
}

// �˥å��͡�������å�
function c_nickname($nickname, &$err) {
	if ($nickname == '')
		$err[] = '�˥å��͡��ब����ޤ���';
}

// �����������å�
function c_birthday($birthday, &$err) {
	if ($birthday != '') {
		if (strtotime($birthday) == -1)
			$err[] = '�����������դ�����������ޤ���';
	}
}

// ���üԷϸ��¥����å�
function c_participant_auth($auth_ary, &$err) {
	foreach ($auth_ary as $auth) {
		if ($auth != '' && $auth != '0' && $auth != '1') {
			$err[] = '���üԷϸ��¤�0,1,Null�ʳ����ޤޤ�Ƥ��ޤ���';
			break;
		}
	}
}

// �����åշϸ��¥����å�
function c_staff_auth($auth_ary, &$err) {
	foreach ($auth_ary as $auth) {
		if ($auth != '' && $auth != '0' && $auth != '1') {
			$err[] = '�����åշϸ��¤�0,1,Null�ʳ����ޤޤ�Ƥ��ޤ���';
			break;
		}
	}
}

// ���üԷϸ����Խ�
function edit_participant_auth($import_auth, $mail_addr, $pjt_id, $job_id) {
	global $g_participant_auth;

	$sql = sprintf(
				"SELECT jm_participant_auth"
			. " FROM t_pjt_member"
			. " JOIN t_job_member ON jm_job_id=%s AND jm_member_id=pm_member_id"
			. " WHERE pm_pjt_id=%s AND pm_mail_addr=%s AND jm_status=1",
			sql_number($job_id),
			sql_number($pjt_id),
			sql_char($mail_addr));
	$auth = db_fetch1($sql, member_participant_auth());

	foreach ($g_participant_auth as $auth_key => $auth_cd) {
		switch ($import_auth[$auth_cd]) {
		case '0':
			del_participant_auth($auth_key, $auth);
			break;
		case '1':
			add_participant_auth($auth_key, $auth);
			break;
		}
	}

	return $auth;
}

// �����åշϸ����Խ�
function edit_staff_auth($import_auth, $mail_addr, $pjt_id) {
	global $g_staff_auth;

	$sql = sprintf(
				"SELECT pm_staff_auth"
			. " FROM t_pjt_member"
			. " WHERE pm_pjt_id=%s AND pm_mail_addr=%s AND pm_status=1",
			sql_number($pjt_id),
			sql_char($mail_addr));
	$auth = db_fetch1($sql, member_staff_auth());

	foreach ($g_staff_auth as $auth_key => $auth_cd) {
		switch ($import_auth[$auth_cd]) {
		case '0':
			del_staff_auth($auth_key, $auth);
			break;
		case '1':
			add_staff_auth($auth_key, $auth);
			break;
		}
	}

	return $auth;
}
?>