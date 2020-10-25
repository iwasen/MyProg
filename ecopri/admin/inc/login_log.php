<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:ログイン履歴書き込み処理
'******************************************************/

function admin_login_log($login_id, $password, $admin_id) {
	global $REMOTE_ADDR;

	if ($login_id == '')
		return;

	$sql = sprintf("INSERT INTO l_admin_login (al_login_id,al_password,al_admin_id,al_ip_addr) VALUES (%s,%s,%s,%s)",
			sql_char($login_id),
			sql_char($password),
			sql_number($admin_id),
			sql_char($REMOTE_ADDR));
	db_exec($sql);
}
?>