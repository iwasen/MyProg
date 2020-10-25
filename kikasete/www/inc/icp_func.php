<?
/******************************************************
' System :きかせて・net共通
' Content:ICP関連関数
'******************************************************/

// 参加URL取得
function get_room_url($pjt_cd) {
	return sprintf("%s/%s/", rtrim(icp_get_system_info('site_url'), '/'), $pjt_cd);
}

// 会議室メールアドレス取得
function get_room_mail_addr($pjt_cd, $job_cd) {
	return sprintf("%s-%s-%s@%s", icp_get_system_info('mail_prefix'), $pjt_cd, $job_cd, icp_get_system_info('mail_domain'));
}

// ICPプロジェクトID取得
function get_icp_pjt_id() {
	$fetch = get_system_info('sy_myp_icp_pjt_id');
	return $fetch->sy_myp_icp_pjt_id;
}
?>