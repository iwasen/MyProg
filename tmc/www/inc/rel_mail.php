<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:継続リレーションメール関数
'******************************************************/

include_once("$inc/greeting.php");

function get_relation_mail($user_id, $vol_no, $mail_id, &$subject, &$body, &$from_name, &$from_addr, $end_msg = '') {
	global $login_url, $stop_url, $continue_url, $season_greeting;

	// ユーザ情報取得
	$sql = "SELECT crm_car_category_cd,umg_dealer_cd,ups_name_kanji,ups_mail_addr,ulg_login_id,ulg_password,umg_kounyu_kibou_car,tts_tantousha_name,tts_from_name,tts_from_addr,sch_sales_channel_name,dlr_dealer_name,clu_lineup"
			. " FROM t_user_personal"
			. " JOIN t_user_login ON ulg_user_id=ups_user_id"
			. " JOIN t_user_manage ON umg_user_id=ups_user_id"
			. " JOIN t_car_model ON crm_car_model_cd=umg_car_model_cd"
			. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
			. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
			. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
			. " LEFT JOIN t_car_lineup ON clu_sales_channel_cd=dlr_sales_channel_cd AND clu_car_category_cd=crm_car_category_cd"
			. " WHERE ups_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		return false;
	$fetch = pg_fetch_object($result, 0);
	$car_category_cd = $fetch->crm_car_category_cd;
	$dealer_cd = $fetch->umg_dealer_cd;

	// 件名取得
	$sql = "SELECT rls_subject FROM t_relation_subject WHERE rls_vol_no=$vol_no AND rls_car_category_cd=$car_category_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$subject = pg_fetch_result($result, 0, 0);
	else
		return false;

	// 挨拶文取得
	$sql = "SELECT rlg_contents FROM t_relation_greeting WHERE rlg_vol_no=$vol_no";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$greeting = pg_fetch_result($result, 0, 0);

	// 投げかけテーマ部取得
	$sql = "SELECT rlt_contents FROM t_relation_theme WHERE rlt_vol_no=$vol_no AND rlt_car_category_cd=$car_category_cd";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$theme = pg_fetch_result($result, 0, 0);

	// 車種情報／販売店情報取得
	$sql = "SELECT rlc_contents FROM t_relation_car_dealer WHERE rlc_vol_no=$vol_no AND rlc_dealer_cd='$dealer_cd'";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$car_dealer = pg_fetch_result($result, 0, 0);

	$body = sprintf("%s\n%s\n%s", $greeting, $theme, $car_dealer);

	// ワンクリックログインURL取得
	if (!$login_url)
		$login_url = get_system_info('url_user_login');

	// メール購読解除URL取得
	if (!$stop_url)
		$stop_url = get_system_info('url_mail_stop');

	// メール購読継続URL取得
	if (!$continue_url)
		$continue_url = get_system_info('url_continue');

	// 時節の挨拶文取得
	if (!$season_greeting)
		$season_greeting = get_season_greeting();

	// 差込変数処理
	$body = str_replace('%FOLLOW_END_MESSAGE%', $end_msg, $body);
	relation_sashikomi($fetch, $user_id, $mail_id, $login_url, $stop_url, $continue_url, $season_greeting, $subject);
	relation_sashikomi($fetch, $user_id, $mail_id, $login_url, $stop_url, $continue_url, $season_greeting, $body);

	// Fromアドレス
	$from_name = $fetch->tts_from_name;
	$from_addr = str_replace('@', $mail_id . '@', $fetch->tts_from_addr);

	return true;
}

// 差込み変数処理
function relation_sashikomi(&$fetch, &$user_id, &$mail_id, &$login_url, &$stop_url, &$continue_url, &$season_greeting, &$text) {
	$text = str_replace('%LINEUP%', $fetch->clu_lineup, $text);
	$text = str_replace('%TIMES%', $season_greeting, $text);
	$text = str_replace('%US_NAME%', $fetch->ups_name_kanji, $text);
	$text = str_replace('%CHANNEL%', $fetch->sch_sales_channel_name, $text);
	$text = str_replace('%MAIL_ADDR%', $fetch->ups_mail_addr, $text);
	$text = str_replace('%CAR_NAME%', $fetch->umg_kounyu_kibou_car, $text);
	$text = str_replace('%DEALER%', trim(str_replace('株式会社', '', $fetch->dlr_dealer_name)), $text);
	$text = str_replace('%UID%', $user_id, $text);
	$text = str_replace('%MYPASS%', "$login_url?t=$fetch->ulg_login_id", $text);
	$text = str_replace('%CANCEL%', "$stop_url?t=$fetch->ulg_login_id", $text);
	$text = str_replace('%PASSWORD%', $fetch->ulg_password, $text);
	$text = str_replace('%TANTOUSHA%', $fetch->tts_tantousha_name, $text);
	$text = str_replace('%TT_EMAIL%', str_replace('@', $mail_id . '@', $fetch->tts_from_addr), $text);
	$text = str_replace('%CONTINUE_URL%', "$continue_url?t=$fetch->ulg_login_id", $text);
}
?>