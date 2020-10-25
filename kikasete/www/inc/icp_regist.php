<?
/******************************************************
' System :きかせて・net共通
' Content:ICP登録関連処理
'******************************************************/

// ICP権限
define('AUTH_STAFF_MARKETER', '0011010000');
define('AUTH_STAFF_AGENT', '0011010000');
define('AUTH_STAFF_MONITOR', '0000000000');
define('AUTH_SANKA_MARKETER', '1111100000');
define('AUTH_SANKA_AGENT', '1111100000');
define('AUTH_SANKA_MONITOR', '0111100000');
define('AUTH_SANKA_OPERATOR', '1001100000');

// ログインID生成
function create_member_cd() {
	while (true) {
		// ID生成
		$member_cd = get_rand_chars(6);

		// ダブりが無いかチェック
		$sql = "SELECT COUNT(*) FROM t_job_member WHERE jm_member_cd='$member_cd'";
		$result = icp_db_exec($sql);
		if (pg_fetch_result($result, 0, 0) == 0)
			break;
	}

	return $member_cd;
}

// 乱数初期化
function init_rand() {
	global $g_init_rand;

	if (!$g_init_rand) {
		mt_srand((double)microtime() * 1000000);
		$g_init_rand = true;
	}
}

// ランダムな文字列を生成
function get_rand_chars($n) {
	$chars = 'abcdefghjkmnprstwxyz23456789';

	init_rand();

	$s = '';
	for ($i = 0; $i < $n; $i++)
		$s .= $chars{mt_rand(0, strlen($chars) - 1)};

	return $s;
}

// ICPジョブ作成
function create_icp_job($pjt_id, $icp_pjt_id) {
	$sql = "SELECT * FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);

	icp_db_begin_trans();

	// 会議室フッタ
	$footer  = "〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜\r\n";
	$footer .= "　$fetch->ppj_room_name\r\n";
	$footer .= "　投稿先メールアドレス：%ROOM_MAIL_ADDR%\r\n";
	$footer .= "〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜〜\r\n";

	// ジョブ登録
	$rec['jb_pjt_id'] = sql_number($icp_pjt_id);
	$rec['jb_status'] = sql_number(4);
	$rec['jb_job_cd'] = sql_char($pjt_id);
	$rec['jb_name'] = sql_char("MyパートナープロジェクトID:$pjt_id");
	$rec['jb_description'] = sql_char($fetch->ppj_purpose);
	$rec['jb_member_id'] = sql_number(0);
	$rec['jb_start_date'] = sql_date($fetch->ppj_room_start);
	$rec['jb_end_date'] = sql_date($fetch->ppj_room_end);
	$rec['jb_member_max'] = sql_number($fetch->ppj_member_num);
	$rec['jb_member_num'] = sql_number(0);
	$rec['jb_room_name'] = sql_char($fetch->ppj_room_name);
	$rec['jb_room_type'] = sql_number(1);
	$rec['jb_room_footer'] = sql_char($footer);
	$rec['jb_room_footer'] = sql_char($footer);
	icp_db_insert('t_job', $rec);
	$job_id = icp_get_current_seq('t_job', 'jb_job_id');

	// オペレータのメンバーIDを取得
	$sql = sprintf("SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=%s AND pm_operator_flag=1",
			sql_number($icp_pjt_id));
	$operator_member_id = icp_db_fetch1($sql);

	// オペレータをジョブメンバに追加
	$rec['jm_job_id'] = sql_number($job_id);
	$rec['jm_member_id'] = sql_number($operator_member_id);
	$rec['jm_member_cd'] = sql_char(create_member_cd());
	$rec['jm_status'] = sql_number(1);
	$rec['jm_participant_auth'] = sql_char(AUTH_SANKA_OPERATOR);
	icp_db_insert('t_job_member', $rec);

	// プロジェクトにジョブ数をセット
	$rec['pj_job_num'] = sprintf("(SELECT COUNT(*) FROM t_job WHERE jb_pjt_id=%s)", sql_number($icp_pjt_id));
	icp_db_update('t_pjt', $rec, sprintf("pj_pjt_id=%s", sql_number($icp_pjt_id)));

	icp_db_commit_trans();

	return $job_id;
}

// ICPメンバー登録
function regist_icp_member($icp_pjt_id, $icp_job_id, &$data, $staff_auth, $participant_auth, $policy) {
	// ICP組織ID取得
	$fetch = get_system_info('sy_myp_icp_org_id');
	$icp_org_id = $fetch->sy_myp_icp_org_id;

	$sql = "SELECT pm_member_id FROM t_pjt_member WHERE pm_pjt_id=$icp_pjt_id AND pm_mail_addr=" . sql_char($data['mail_addr']);
	$icp_member_id = icp_db_fetch1($sql);
	if (!$icp_member_id) {
		// プロジェクトメンバー追加更新
		$rec['pm_status'] = sql_number(1);
		$rec['pm_pjt_id'] = sql_number($icp_pjt_id);
		$rec['pm_staff_auth'] = sql_char($staff_auth);
		$rec['pm_mail_addr'] = sql_char($data['mail_addr']);
		$rec['pm_password'] = sql_char($data['password']);
		$rec['pm_org_id'] = sql_number($icp_org_id);
		$rec['pm_org_member_id'] = sql_char('');
		$rec['pm_nickname'] = sql_char($data['nickname']);
		$rec['pm_name1'] = sql_char($data['name1']);
		$rec['pm_name2'] = sql_char($data['name2']);
		$rec['pm_name1_kana'] = sql_char($data['name1_kana']);
		$rec['pm_name2_kana'] = sql_char($data['name2_kana']);
		$rec['pm_sex_cd'] = sql_char(decode_sex($data['sex']));
		$rec['pm_birthday'] = sql_date($data['birthday']);
		$rec['pm_mikikon_cd'] = sql_char(decode_mikikon($data['mikikon'], ''));
		$rec['pm_shokugyou'] = sql_char(decode_shokugyou($data['shokugyou'], ''));
		$rec['pm_gyoushu'] = sql_char(decode_gyoushu($data['gyoushu'], ''));
		$rec['pm_shokushu'] = sql_char(decode_shokushu($data['shokushu'], ''));
		$rec['pm_zip_cd'] = sql_char($data['zip']);
		$rec['pm_address'] = sql_char(decode_area($data['area']));
		$rec['pm_tel_no'] = sql_char($data['tel']);
		icp_db_insert('t_pjt_member', $rec);
		$icp_member_id = icp_get_current_seq('t_pjt_member', 'pm_member_id');
	}

	// ジョブメンバーに追加
	$sql = "SELECT COUNT(*) FROM t_job_member WHERE jm_job_id=$icp_job_id AND jm_member_id=$icp_member_id";
	if (icp_db_fetch1($sql) == 0) {
		$rec['jm_job_id'] = sql_number($icp_job_id);
		$rec['jm_member_id'] = sql_number($icp_member_id);
		$rec['jm_member_cd'] = sql_char(create_member_cd());
		$rec['jm_status'] = sql_number(1);
		$rec['jm_participant_auth'] = sql_char($participant_auth);
		$rec['jm_nickname'] = sql_char($policy == 1 ? trim("{$data['name1']} {$data['name2']}") : $data['nickname']);
		icp_db_insert('t_job_member', $rec);
	}

	return $icp_member_id;
}

// マーケターをICPに登録
function regist_icp_marketer($pjt_id, $icp_pjt_id, $icp_job_id) {
	$sql = "SELECT ppj_marketer_id,ppj_policy FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$policy = $fetch->ppj_policy;

		// ICPマーケター登録
		$sql = "SELECT * FROM t_marketer WHERE mr_marketer_id=$fetch->ppj_marketer_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$data['mail_addr'] = $fetch->mr_mail_addr_low;
			$data['password'] = $fetch->mr_password;
			$data['nickname'] = "$fetch->mr_name1 $fetch->mr_name2";
			$data['name1'] = $fetch->mr_name1;
			$data['name2'] = $fetch->mr_name2;
			$data['name1_kana'] = $fetch->mr_name1_kana;
			$data['name2_kana'] = $fetch->mr_name2_kana;
			$data['sex'] = $fetch->mr_sex;
			$data['birthday'] = $fetch->mr_birthday;
			$data['mikikon'] = '';
			$data['shokugyou'] = $fetch->mr_shokugyou_cd;
			$data['gyoushu'] = $fetch->mr_gyoushu_cd;
			$data['shokushu'] = $fetch->mr_shokushu_cd;
			$data['zip'] = $fetch->mr_kinmu_zip;
			$data['area'] = $fetch->mr_kinmu_area;
			$data['tel'] = $fetch->mr_kinmu_tel;
			regist_icp_member($icp_pjt_id, $icp_job_id, $data, AUTH_STAFF_MARKETER, AUTH_SANKA_MARKETER, $policy);
		}
	}
}

// エージェントをICPに登録
function regist_icp_agent($pjt_id, $icp_pjt_id, $icp_job_id) {
	$sql = "SELECT ppj_marketer_id,ppj_policy FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$policy = $fetch->ppj_policy;

		$sql = "SELECT mr_agent_id FROM t_marketer WHERE mr_marketer_id=$fetch->ppj_marketer_id";
		$agent_id = db_fetch1($sql);
		if ($agent_id) {
			// エージェント登録
			$sql = "SELECT * FROM t_agent WHERE ag_agent_id=$agent_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);

				$data['mail_addr'] = $fetch->ag_mail_addr_low;
				$data['password'] = $fetch->ag_password;
				$data['nickname'] = "$fetch->ag_name1 $fetch->ag_name2";
				$data['name1'] = $fetch->ag_name1;
				$data['name2'] = $fetch->ag_name2;
				$data['name1_kana'] = $fetch->ag_name1_kana;
				$data['name2_kana'] = $fetch->ag_name2_kana;
				$data['sex'] = $fetch->ag_sex;
				$data['birthday'] = $fetch->ag_birthday;
				$data['mikikon'] = '';
				$data['shokugyou'] = '';
				$data['gyoushu'] = '';
				$data['shokushu'] = '';
				$data['zip'] = '';
				$data['area'] = '';
				$data['tel'] = '';
				regist_icp_member($icp_pjt_id, $icp_job_id, $data, AUTH_STAFF_AGENT, AUTH_SANKA_AGENT, $policy);
			}
		}
	}
}

// モニターをICPに登録
function regist_icp_monitor($pjt_id, $icp_pjt_id, $icp_job_id) {
	$sql = "SELECT ppj_marketer_id,ppj_policy,ppj_room_name,ppj_room_start,ppj_room_end FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$policy = $fetch->ppj_policy;

		// ICPのプロジェクトコード取得
		$sql = "SELECT pj_pjt_cd FROM t_pjt WHERE pj_pjt_id=$icp_pjt_id";
		$icp_pjt_cd = icp_db_fetch1($sql);

		// ICPのジョブコード取得
		$sql = "SELECT jb_job_cd FROM t_job WHERE jb_job_id=$icp_job_id";
		$icp_job_cd = icp_db_fetch1($sql);

		// Myパートナー参加決定メール準備
		get_mail_template('myp_reg', $subject, $from, $cc, $bcc, $repty_to, $body);
		$body = str_replace('%ROOM_NAME%', $fetch->ppj_room_name, $body);
		$body = str_replace('%ROOM_START_DATE%', format_date($fetch->ppj_room_start), $body);
		$body = str_replace('%ROOM_END_DATE%', format_date($fetch->ppj_room_end), $body);
		$body = str_replace('%ROOM_JOB_CD%', $icp_job_cd, $body);
		$body = str_replace('%ROOM_MAIL_ADDR%', get_room_mail_addr($icp_pjt_cd, $icp_job_cd), $body);
		$body = str_replace('%ROOM_URL%', get_room_url($icp_pjt_cd), $body);
		separate_address($from, $from_addr, $from_name);
		$send_mail_id = send_mail_data($subject, $from_addr, $reply_to, $body, '', '', $from_name);

		// ICPモニター登録
		$sql = "SELECT * "
				. " FROM t_mp_monitor"
				. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
				. " WHERE pmn_pjt_id=$pjt_id AND pmn_admin_choice>=1"
				. " ORDER BY pmn_monitor_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			switch ($fetch->pmn_admin_choice) {
			case 1:		// 新規追加
				$data = array();
				$data['mail_addr'] = $fetch->mn_mail_addr_low;
				$data['password'] = $fetch->mn_password;
				$data['nickname'] = $fetch->pmn_nickname;
				$data['name1'] = $fetch->mn_name1;
				$data['name2'] = $fetch->mn_name2;
				$data['name1_kana'] = $fetch->mn_name1_kana;
				$data['name2_kana'] = $fetch->mn_name2_kana;
				$data['sex'] = $fetch->mn_sex;
				$data['birthday'] = $fetch->mn_birthday;
				$data['mikikon'] = $fetch->mn_mikikon;
				$data['shokugyou'] = $fetch->mn_shokugyou_cd;
				$data['gyoushu'] = $fetch->mn_gyoushu_cd;
				$data['shokushu'] = $fetch->mn_shokushu_cd;
				$data['zip'] = $fetch->mn_jitaku_zip;
				$data['area'] = $fetch->mn_jitaku_area;
				$data['tel'] = $fetch->mn_jitaku_tel;
				$icp_member_id = regist_icp_member($icp_pjt_id, $icp_job_id, $data, AUTH_STAFF_MONITOR, AUTH_SANKA_MONITOR, $policy);

				$rec['pmn_icp_member_id'] = sql_number($icp_member_id);
				$rec['pmn_admin_choice'] = sql_number(2);
				db_update('t_mp_monitor', $rec, "pmn_pjt_id=$pjt_id AND pmn_monitor_id=$fetch->mn_monitor_id");

				// 参加通知メール送信
				$sub_seq++;
				send_mail_list($send_mail_id, $fetch->mn_mail_addr, $sub_seq);
				send_mail_embed($send_mail_id, $sub_seq, '%MONITOR_NAME%', $fetch->mn_name1);
				send_mail_embed($send_mail_id, $sub_seq, '%MAIL_ADDR%', $fetch->mn_mail_addr);
				send_mail_embed($send_mail_id, $sub_seq, '%NICKNAME%', $policy == 1 ? trim("$fetch->mn_name1 $fetch->mn_name2") : $fetch->pmn_nickname);
				break;
			case 2:		// 決定済み
				$rec['jm_status'] = sql_number(1);
				icp_db_update('t_job_member', $rec, "jm_job_id=$icp_job_id AND jm_member_id=$fetch->pmn_icp_member_id AND jm_status<>1");
				break;
			case 3:		// ＮＧ
				$rec['jm_status'] = sql_number(9);
				icp_db_update('t_job_member', $rec, "jm_job_id=$icp_job_id AND jm_member_id=$fetch->pmn_icp_member_id AND jm_status<>9");
				break;
			}
		}

		send_mail_ok($send_mail_id);
	}
}

// ICPプロジェクトメンバ登録
function regist_icp_member_all($pjt_id, $icp_pjt_id, $icp_job_id) {
	// ICPマーケター登録
	regist_icp_marketer($pjt_id, $icp_pjt_id, $icp_job_id);

	// ICPエージェント登録
	regist_icp_agent($pjt_id, $icp_pjt_id, $icp_job_id);

	// ICPモニター登録
	regist_icp_monitor($pjt_id, $icp_pjt_id, $icp_job_id);

	// ジョブの参加メンバ数設定
	$rec['jb_member_num'] = "(SELECT COUNT(*) FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=$icp_job_id AND pm_operator_flag=0 AND jm_status=1)";
	icp_db_update('t_job', $rec, "jb_job_id=$icp_job_id");
}

// 会議室に登録
function icp_regist($pjt_id) {
	// ICPプロジェクトID取得
	$icp_pjt_id = get_icp_pjt_id();

	// ICPジョブID取得
	$sql = "SELECT ppj_icp_job_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$icp_job_id = db_fetch1($sql);

	db_begin_trans();
	icp_db_begin_trans();

	// ICPジョブ登録
	if (!$icp_job_id) {
		$icp_job_id = create_icp_job($pjt_id, $icp_pjt_id);

		$rec['ppj_icp_job_id'] = sql_number($icp_job_id);
		db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");
	}

	// ICPメンバー登録
	regist_icp_member_all($pjt_id, $icp_pjt_id, $icp_job_id);

	if (db_errormessage() == '' && icp_db_errormessage() == '') {
		db_commit_trans();
		icp_db_commit_trans();
	}
}
?>