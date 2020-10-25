<?
/******************************************************
' System :ICP共通インクルードファイル
' Content:会議室タイプ関連処理
'******************************************************/

define('MYP_PJT_CD', 'myp');
define('MYP_DOMAIN', 'kikasete.xxxxx-xxxx.co.jp');

function get_mail_domain($pjt_cd) {
	if ($pjt_cd == MYP_PJT_CD)
		return MYP_DOMAIN;
	else
		return get_system_info('mail_domain');
}

// 参加URL取得
function get_room_url($pjt_cd) {
	return sprintf("%s/%s/", rtrim(get_system_info('site_url'), '/'), $pjt_cd);
}

// 会議室メールアドレス取得
function get_room_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, get_mail_domain($pjt_cd));
}

// 個人マスクアドレス取得
function get_member_mail_addr($pjt_cd, $job_cd, $member_cd) {
	return sprintf("%s-%s-%s-%s@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, $member_cd, get_mail_domain($pjt_cd));
}

// 司会者宛転送メールアドレス取得
function get_mc_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s-mc@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, get_mail_domain($pjt_cd));
}

// お問い合わせメールアドレス取得
function get_help_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s-help@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, get_mail_domain($pjt_cd));
}
?>