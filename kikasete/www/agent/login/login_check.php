<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:ログインチェック
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/login_log.php");

$sql = sprintf("SELECT ag_agent_id,ag_mail_addr,ag_type,ag_main_agent FROM t_agent WHERE ag_mail_addr_low=%s AND ag_password=%s AND ag_status=0",
		sql_char(strtolower($login_id)),
		sql_char($password));
$result = db_exec($sql);

if (pg_numrows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	session_start();

	setcookie('agent_mail_addr', $fetch->ag_mail_addr, COOKIE_EXPIRE, '/');

	$_SESSION['agent_login_id'] = $fetch->ag_agent_id;
	$_SESSION['agent_main_id'] = $fetch->ag_type == 1 ? $fetch->ag_agent_id : $fetch->ag_main_agent;
	$_SESSION['agent_type'] = $fetch->ag_type;
	login_log(3, $login_id, $password, $fetch->ag_agent_id);

	// 問い合わせ先メールアドレス取得
	$fetch = get_system_info('sy_ag_inq_to');
	$_SESSION['sy_ag_inq_to'] = $fetch->sy_ag_inq_to;

	redirect(https_url('../mypage.php'));
} else {
	login_log(3, $login_id, $password, null);
	redirect('login_error.php');
}
?>
