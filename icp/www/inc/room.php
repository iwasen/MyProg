<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:��ļ������״�Ϣ����
'******************************************************/

define('MYP_PJT_CD', 'myp');
define('MYP_DOMAIN', 'kikasete.xxxxx-xxxx.co.jp');

function get_mail_domain($pjt_cd) {
	if ($pjt_cd == MYP_PJT_CD)
		return MYP_DOMAIN;
	else
		return get_system_info('mail_domain');
}

// ����URL����
function get_room_url($pjt_cd) {
	return sprintf("%s/%s/", rtrim(get_system_info('site_url'), '/'), $pjt_cd);
}

// ��ļ��᡼�륢�ɥ쥹����
function get_room_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, get_mail_domain($pjt_cd));
}

// �Ŀͥޥ������ɥ쥹����
function get_member_mail_addr($pjt_cd, $job_cd, $member_cd) {
	return sprintf("%s-%s-%s-%s@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, $member_cd, get_mail_domain($pjt_cd));
}

// �ʲ�԰�ž���᡼�륢�ɥ쥹����
function get_mc_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s-mc@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, get_mail_domain($pjt_cd));
}

// ���䤤��碌�᡼�륢�ɥ쥹����
function get_help_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s-help@%s", get_system_info('mail_prefix'), $pjt_cd, $job_cd, get_mail_domain($pjt_cd));
}
?>