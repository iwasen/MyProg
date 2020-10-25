<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:システムマスタ情報更新処理
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

set_global('etc', 'その他管理｜マスタメンテナンス', 'システムマスタ', BACK_TOP);

$sql = "UPDATE m_system SET" .
			" sy_system_name=" . sql_char($sy_system_name) .
			", sy_url_top=" . sql_char($sy_url_top) .
			", sy_url_monitor=" . sql_char($sy_url_monitor) .
			", sy_url_marketer=" . sql_char($sy_url_marketer) .
			", sy_url_agant=" . sql_char($sy_url_agant) .
			", sy_url_admin=" . sql_char($sy_url_admin) .
			", sy_news1_from=" . sql_char($sy_news1_from) .
			", sy_news1_name=" . sql_char($sy_news1_name) .
			", sy_news2_from=" . sql_char($sy_news2_from) .
			", sy_news2_name=" . sql_char($sy_news2_name) .
			", sy_mail_from=" . sql_char($sy_mail_from) .
			", sy_mail_name=" . sql_char($sy_mail_name) .
			", sy_marketer_reg_to=" . sql_char($sy_marketer_reg_to) .
			", sy_agent_reg_to=" . sql_char($sy_agent_reg_to) .
			", sy_myenq_to=" . sql_char($sy_myenq_to) .
			", sy_mk_inq_to=" . sql_char($sy_mk_inq_to) .
			", sy_mku_inq_to=" . sql_char($sy_mku_inq_to) .
			", sy_mkk_inq_to=" . sql_char($sy_mkk_inq_to) .
			", sy_pop_server=" . sql_char($sy_pop_server) .
			", sy_mail_domain=" . sql_char($sy_mail_domain) .
			", sy_envelope_addr=" . sql_char($sy_envelope_addr) .
			", sy_envelope_pwd=" . sql_char($sy_envelope_pwd) .
			", sy_enq_domain=" . sql_char($sy_enq_domain) .
			", sy_enq_account=" . sql_char($sy_enq_account) .
			", sy_enq_pwd=" . sql_char($sy_enq_pwd) .
			", sy_enq_mail=" . sql_char($sy_enq_mail) .
			", sy_enq_web=" . sql_char($sy_enq_web) .
			", sy_mym_account=" . sql_char($sy_mym_account) .
			", sy_mym_pwd=" . sql_char($sy_mym_pwd) .
			", sy_mym_point=" . sql_char($sy_mym_point) .
			", sy_mail_notify_to=" . sql_char($sy_mail_notify_to) .
			", sy_myp_max_send=" . sql_number($sy_myp_max_send) .
			", sy_myp_icp_pjt_id=" . sql_number($sy_myp_icp_pjt_id) .
			", sy_myp_icp_org_id=" . sql_number($sy_myp_icp_org_id) .
			", sy_myp_max_question=" . sql_number($sy_myp_max_question) .
			", sy_myp_monthly_point=" . sql_number($sy_myp_monthly_point) .
			", sy_point_validity_period=" . sql_number($sy_point_validity_period) .
			", sy_point_extension=" . sql_number($sy_point_extension) .
			", sy_point_campaign_period1=" . sql_date($sy_point_campaign_period1) .
			", sy_point_campaign_period2=" . sql_date($sy_point_campaign_period2) .
			", sy_point_campaign_point=" . sql_number($sy_point_campaign_point) .
			", sy_haimail_addr=" . sql_char($sy_haimail_addr) .
			", sy_haimail_mail=" . sql_char($sy_haimail_mail) .
			", sy_haimail_web=" . sql_char($sy_haimail_web) .
			", sy_haimail_domain=" . sql_char($sy_haimail_domain) .
			", sy_haimail_account=" . sql_char($sy_haimail_account) .
			", sy_haimail_pwd=" . sql_char($sy_haimail_pwd) .
			" WHERE sy_system_id=$sys_id";
db_exec($sql);

if ($campaign_point_reset) {
	$sql = "UPDATE t_monitor_point SET mp_campaign_flag=false WHERE mp_campaign_flag=true";
	db_exec($sql);
}

$msg = 'システムマスタ情報を更新しました。';
$ret = 'location.href=\'list.php\'';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
