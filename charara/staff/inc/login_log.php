<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:ログイン履歴書き込み処理
'******************************************************/

function staff_login_log($login_id, $password, $staff_id) {
	if ($login_id == '')
		return;

	$sql = sprintf("INSERT INTO l_staff_login (ls_login_id,ls_password,ls_staff_id,ls_ip_addr) VALUES (%s,%s,%s,%s)",
			sql_char($login_id),
			sql_char($password),
			sql_number($staff_id),
			sql_char($_SERVER['REMOTE_ADDR']));
	db_exec($sql);
}
?>