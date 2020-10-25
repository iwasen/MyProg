<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:差し込み変数処理
'******************************************************/

// 使用差し込み変数配列取得（会議室関連変数）
function get_room_var_name(&$str) {
	$ary = array();

	var_check($str, '%PJT_ID%', $ary);
	var_check($str, '%PJT_CODE%', $ary);
	var_check($str, '%PJT_NAME%', $ary);
	var_check($str, '%JOB_ID%', $ary);
	var_check($str, '%JOB_CODE%', $ary);
	var_check($str, '%JOB_NAME%', $ary);
	var_check($str, '%STAR_DATE%', $ary);
	var_check($str, '%END_DATE%', $ary);
	var_check($str, '%ROOM_TYPE%', $ary);
	var_check($str, '%URL%', $ary);
	var_check($str, '%ROOM_MAIL_ADDR%', $ary);
	var_check($str, '%CONTACT_MAIL_ADDR%', $ary);

	return $ary;
}

// 使用差し込み変数配列取得（個人情報変数）
function get_member_var_name(&$str) {
	$ary = array();

	var_check($str, '%MAIL_ADDR%', $ary);
	var_check($str, '%NAME1%', $ary);
	var_check($str, '%NAME2%', $ary);
	var_check($str, '%NICKNAME%', $ary);
	var_check($str, '%PASSWORD%', $ary);
	var_check($str, '%PERSON_MAIL_ADDR%', $ary);
	var_check($str, '%ORG_ID%', $ary);
	var_check($str, '%ORG_MEMB_ID%', $ary);

	return $ary;
}

// 差し込み変数存在チェック
function var_check(&$str, $var, &$var_name_ary) {
	if (strpos($str, $var) !== false)
		$var_name_ary[] = $var;
}

// 差し込みデータ配列取得（会議室関連変数）
function get_room_var_val(&$var_name_ary, $pjt_id, $pjt_cd, $pjt_name, $job_id, $job_cd, $job_name, $job_start_date, $jon_end_date, $room_type) {
	$ary = array();

	foreach ($var_name_ary as $var) {
		switch ($var) {
		case '%PJT_ID%':					// プロジェクトID
			$ary[$var] = $pjt_id;
			break;
		case '%PJT_CODE%':				// プロジェクトコード
			$ary[$var] = $pjt_cd;
			break;
		case '%PJT_NAME%':				// プロジェクト名
			$ary[$var] = $pjt_name;
			break;
		case '%JOB_ID%':					// ジョブID
			$ary[$var] = $job_id;
			break;
		case '%JOB_CODE%':				// ジョブコード
			$ary[$var] = $job_cd;
			break;
		case '%JOB_NAME%':				// ジョブ名
			$ary[$var] = $job_name;
			break;
		case '%STAR_DATE%':				// ジョブ開始日
			$ary[$var] = format_date_j($job_start_date);
			break;
		case '%END_DATE%':				// ジョブ終了日
			$ary[$var] = format_date_j($jon_end_date);
			break;
		case '%ROOM_TYPE%':				// 会議室タイプ
			$ary[$var] = decode_room_type($room_type);
			break;
		case '%URL%':							// 参加URL
			$ary[$var] = get_room_url($pjt_cd);
			break;
		case '%ROOM_MAIL_ADDR%':	// 会議室メールアドレス
			$ary[$var] = get_room_mail_addr($pjt_cd, $job_cd);
			break;
		case '%CONTACT_MAIL_ADDR%':	// お問合せメールアドレス
			$ary[$var] = get_help_mail_addr($pjt_cd, $job_cd);
			break;
		}
	}

	return $ary;
}

// 差し込みデータ配列取得（個人情報変数）
function get_member_var_val(&$var_name_ary, $pjt_cd, $job_cd, $mail_addr, $name1, $name2, $nickname, $password, $member_cd, $org_id, $org_member_id) {
	$ary = array();

	foreach ($var_name_ary as $var) {
		switch ($var) {
		case '%MAIL_ADDR%':				// メールアドレス
			$ary[$var] = $mail_addr;
			break;
		case '%NAME1%':						// 姓
			$ary[$var] = $name1;
			break;
		case '%NAME2%':						// 名
			$ary[$var] = $name2;
			break;
		case '%NICKNAME%':				// ニックネーム
			$ary[$var] = $nickname;
			break;
		case '%PASSWORD%':				// ログインパスワード
			$ary[$var] = $password;
			break;
		case '%PERSON_MAIL_ADDR%':// 個人マスクアドレス
			$ary[$var] = get_member_mail_addr($pjt_cd, $job_cd, $member_cd);
			break;
		case '%ORG_ID%':					// 組織ID
			$ary[$var] = $org_id;
			break;
		case '%ORG_MEMB_ID%':			// 組織メンバID
			$ary[$var] = $org_member_id;
			break;
		}
	}

	return $ary;
}

// 差し込み変数置換処理
function replace_var(&$var_ary, $str) {
	foreach ($var_ary as $name => $val)
		$str = str_replace($name, $val, $str);

	return $str;
}

// 全差し込み変数処理
function replace_all_var($job_id, $member_id, &$content, $reorder = false) {
	$temp = replace_room_var($job_id, $content, $reorder);
	return replace_member_var($job_id, $member_id, $temp);
}

// 会議室関連差し込み処理
function replace_room_var($job_id, &$content, $reorder = false) {
	// 使用差し込み変数名取得
	$var_name_ary = get_room_var_name($content);
	if (count($var_name_ary) == 0)
		return $content;

	// プロジェクト、ジョブ情報取得
	if ($job_id) {
		$sql = "SELECT pj_pjt_id,pj_pjt_cd,pj_name,jb_job_id,jb_job_cd,jb_name,jb_start_date,jb_end_date,jb_room_type"
				. " FROM t_job"
				. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
				. " WHERE jb_job_id=$job_id";
		$result = db_exec($sql);
		if (pg_num_rows($result))
			$fetch = pg_fetch_object($result, 0);
	}

	// 再申請内容で置き換え
	if ($reorder) {
		$sql = "SELECT * FROM t_job_reorder WHERE jr_job_id=$job_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch2 = pg_fetch_object($result, 0);

			$fetch->jb_name = $fetch2->jr_name;
			$fetch->jb_room_type = $fetch2->jr_room_type;
			$fetch->jb_start_date = $fetch2->jr_start_date;
			$fetch->jb_end_date = $fetch2->jr_end_date;
		}
	}

	// 差し込み変数設定
	$var_val_ary = get_room_var_val($var_name_ary,
			$fetch->pj_pjt_id,
			$fetch->pj_pjt_cd,
			$fetch->pj_name,
			$fetch->jb_job_id,
			$fetch->jb_job_cd,
			$fetch->jb_name,
			$fetch->jb_start_date,
			$fetch->jb_end_date,
			$fetch->jb_room_type);

	return replace_var($var_val_ary, $content);
}

// 個人情報差し込み処理
function replace_member_var($job_id, $member_id, &$content) {
	// 使用差し込み変数名取得
	$var_name_ary = get_member_var_name($content);
	if (count($var_name_ary) == 0)
		return $content;

	// メンバー情報取得
	if ($member_id) {
		$sql = "SELECT pm_mail_addr,pm_name1,pm_name2,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_password,jm_member_cd,pm_org_id,pm_org_member_id,pj_pjt_cd,jb_job_cd"
				. " FROM t_job_member"
				. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
				. " JOIN t_job ON jb_job_id=jm_job_id"
				. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
				. " WHERE jm_job_id=$job_id AND jm_member_id=$member_id";
		$result = db_exec($sql);
		if (pg_num_rows($result))
			$fetch = pg_fetch_object($result, 0);
	}

	// 差し込み変数設定
	$var_val_ary = get_member_var_val($var_name_ary,
			$fetch->pj_pjt_cd,
			$fetch->jb_job_cd,
			$fetch->pm_mail_addr,
			$fetch->pm_name1,
			$fetch->pm_name2,
			$fetch->pm_nickname,
			$fetch->pm_password,
			$fetch->jm_member_cd,
			$fetch->pm_org_id,
			$fetch->pm_org_member_id);

	return replace_var($var_val_ary, $content);
}

// 個人情報の差し込み変数を隠す
function replace_mask_var($job_id, $member_id, &$content) {
	// 使用差し込み変数名取得
	$var_name_ary = get_member_var_name($content);
	if (count($var_name_ary) == 0)
		return $content;

	// メンバー情報取得
	$sql = "SELECT COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pj_pjt_cd,jb_job_cd"
				. " FROM t_job_member"
				. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
				. " JOIN t_job ON jb_job_id=jm_job_id"
				. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
				. " WHERE jm_job_id=$job_id AND jm_member_id=$member_id";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$fetch = pg_fetch_object($result, 0);

	// 差し込み変数設定
	$var_val_ary = get_member_var_val($var_name_ary,
			$fetch->pj_pjt_cd,
			$fetch->jb_job_cd,
			'***@***.**.**',
			'****',
			'****',
			$fetch->pm_nickname,
			'********',
			'******',
			'**',
			'**');

	return replace_var($var_val_ary, $content);
}

// 個人情報差し込み変数チェック
function check_personal_var($content) {
	// 使用差し込み変数名取得
	$var_name_ary = get_member_var_name($content);

	foreach ($var_name_ary as $var_name) {
		switch ($var_name) {
		case '%MAIL_ADDR%':
		case '%NAME1%':
		case '%NAME2%':
		case '%PASSWORD%':
		case '%PERSON_MAIL_ADDR%':
		case '%ORG_ID%':
		case '%ORG_MEMB_ID%':
			$err_ary[] = $var_name;
			break;
		}
	}
	return $err_ary;
}
?>