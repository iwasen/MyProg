<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:ログイン履歴書き込み処理
'******************************************************/

function admin_login_log($login_id, $password, $admin_id) {
	global $REMOTE_ADDR;

	if ($login_id == '')
		return;

	$sql = sprintf("INSERT INTO l_admin_login (la_login_id,la_password,la_admin_id,la_ip_addr) VALUES (%s,%s,%s,%s)",
			sql_char($login_id),
			sql_char($password),
			sql_number($admin_id),
			sql_char($REMOTE_ADDR));
	db_exec($sql);
}
?>