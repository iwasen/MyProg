<?
/******************************************************
' System :ICPプロジェクトページ
' Content:メンバーインポート処理
'******************************************************/

function read_import_file($import_file, &$ok_ary, &$ng_ary, $pjt_id, $job_id = 0) {
	global $g_participant_auth, $g_staff_auth;

	// フィールド定義
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
		'司会者',
		'Web発言',
		'メール送信',
		'メール受信',
		'発言ON/OFF'
	);
	$staff_auth_fields = array(
		'プロジェクトメンバ管理',
		'ジョブ管理',
		'発言ログエクスポート',
		'発言録エクスポート',
		'参加者メンバエクスポート',
		'発言集計エクスポート'
	);

	$handle = fopen($import_file,"r");

	$n = 0;
	$header = true;
	$member_data = array();
	$participant_auth = array();
	$staff_auth = array();

	// CSVファイル読み込み
	while ($csv = fgetcsv($handle, 1000, ",")) {
		if ($header) {
			$header = false;
			continue;
		}

		if ($csv[0] != '') {
			$c = 0;

			// メンバー情報取得
			foreach ($member_fields as $field)
				$member_data[$n][$field] = trim(mb_convert_encoding($csv[$c++], 'EUC-JP', 'SJIS'));

			// 参加者系権限取得
			if ($job_id != 0) {
				foreach ($participant_auth_fields as $auth)
					$participant_auth[$n][$g_participant_auth[$auth]] = trim($csv[$c++]);
			}

			// スタッフ系権限取得
			foreach ($staff_auth_fields as $auth)
				$staff_auth[$n][$g_staff_auth[$auth]] = trim($csv[$c++]);

			// メールアドレスを小文字へ
			$member_data[$n]['mail_addr'] = strtolower($member_data[$n]['mail_addr']);

			// メールアドレスの重複チェック
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

	// インポートデータチェック
	for ($i = 0; $i < $n; $i++) {
		$data = &$member_data[$i];

		// データチェック
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

		// 参加者系権限編集
		if ($job_id != 0)
			$data['participant_auth'] = edit_participant_auth($participant_auth[$i], $data['mail_addr'], $pjt_id, $job_id);

		// スタッフ系権限編集
		$data['staff_auth'] = edit_staff_auth($staff_auth[$i], $data['mail_addr'], $pjt_id);

		// member_idセット
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

// メールアドレスチェック
function c_mail_addr($mail_addr, $mail_err, &$err) {
	if ($mail_addr == '')
		$err[] = 'メールアドレスがありません。';
	elseif ($mail_err)
		$err[] = 'メールアドレスが重複しています。';
	elseif (!check_mail_addr($mail_addr))
		$err[] = 'メールアドレスが不正です。';
}

// パスワードチェック
function c_password($password, &$err) {
	if ($password == '')
		$err[] = 'パスワードがありません。';
}

// 組織IDチェック
function c_org_id($org_id, &$err) {
	if ($org_id == '')
		$err[] = '組織IDがありません。';
	elseif (!is_numeric($org_id))
		$err[] = '組織IDが数字ではありません。';
	else {
		$sql = "SELECT COUNT(*) FROM m_org WHERE or_org_id=" . sql_number($org_id);
		if (db_fetch1($sql) == 0)
			$err[] = '組織IDが組織マスタに登録されていません。';
	}
}

// 組織メンバーIDチェック
function c_org_member_id($org_member_id, &$err) {
	if ($org_member_id == '')
		$err[] = '組織メンバーIDがありません。';
}

// ニックネームチェック
function c_nickname($nickname, &$err) {
	if ($nickname == '')
		$err[] = 'ニックネームがありません。';
}

// 誕生日チェック
function c_birthday($birthday, &$err) {
	if ($birthday != '') {
		if (strtotime($birthday) == -1)
			$err[] = '誕生日の日付が正しくありません。';
	}
}

// 参加者系権限チェック
function c_participant_auth($auth_ary, &$err) {
	foreach ($auth_ary as $auth) {
		if ($auth != '' && $auth != '0' && $auth != '1') {
			$err[] = '参加者系権限に0,1,Null以外が含まれています。';
			break;
		}
	}
}

// スタッフ系権限チェック
function c_staff_auth($auth_ary, &$err) {
	foreach ($auth_ary as $auth) {
		if ($auth != '' && $auth != '0' && $auth != '1') {
			$err[] = 'スタッフ系権限に0,1,Null以外が含まれています。';
			break;
		}
	}
}

// 参加者系権限編集
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

// スタッフ系権限編集
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