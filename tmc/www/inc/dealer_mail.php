<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店メール関連関数
'******************************************************/

// 販売店メール差込変数処理
function set_mail_boby($user_id, $body) {
	$sql = "SELECT ups_name_kanji,ups_mail_addr,sch_sales_channel_name,umg_kounyu_kibou_car,dlr_dealer_name,clu_lineup,ulg_login_id,ulg_password,tts_tantousha_name,tts_from_addr"
			. " FROM t_user_login"
			. " JOIN t_user_personal ON ups_user_id=ulg_user_id"
			. " JOIN t_user_manage ON umg_user_id=ulg_user_id"
			. " JOIN t_car_model ON crm_car_model_cd=umg_car_model_cd"
			. " JOIN t_dealer ON dlr_dealer_cd=umg_dealer_cd"
			. " JOIN t_sales_channel ON sch_sales_channel_cd=dlr_sales_channel_cd"
			. " JOIN t_tantousha ON tts_tantousha_id=dlr_tantousha_id"
			. " LEFT JOIN t_car_lineup ON clu_sales_channel_cd=dlr_sales_channel_cd AND clu_car_category_cd=crm_car_category_cd"
			. " WHERE ulg_user_id=$user_id";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$fetch = pg_fetch_object($result, 0);

	$body = str_replace('%US_NAME%', $fetch->ups_name_kanji, $body);
	$body = str_replace('%CHANNEL%', $fetch->sch_sales_channel_name, $body);
	$body = str_replace('%MAIL_ADDR%', $fetch->ups_mail_addr, $body);
	$body = str_replace('%CAR_NAME%', $fetch->umg_kounyu_kibou_car, $body);
	$body = str_replace('%DEALER%', trim(str_replace('株式会社', '', $fetch->dlr_dealer_name)), $body);
	$body = str_replace('%TIMES%', get_season_greeting(), $body);
	$body = str_replace('%LINEUP%', $fetch->clu_lineup, $body);
	$body = str_replace('%UID%', $user_id, $body);
	$body = str_replace('%MYPASS%', get_system_info('url_user_login') . "?t=$fetch->ulg_login_id", $body);
	$body = str_replace('%CANCEL%', get_system_info('url_mail_stop') . "?t=$fetch->ulg_login_id", $body);
	$body = str_replace('%PASSWORD%', $fetch->ulg_password, $body);
	$body = str_replace('%TANTOUSHA%', $fetch->tts_tantousha_name, $body);
	$body = str_replace('%TT_EMAIL%', str_replace('@', $mail_id . '@', $fetch->tts_from_addr), $body);

	return $body;
}
?>