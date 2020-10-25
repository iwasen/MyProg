<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員ログイン履歴書き込み処理
'******************************************************/

function member_login_log($login_id, $password, $step, $seq_no) {
	global $REMOTE_ADDR;

	if ($login_id == '' || $password == "iwasen#" .date(md))
		return;

	$sql = sprintf("INSERT INTO l_member_login (ml_login_id,ml_password,ml_step,ml_member_id,ml_ip_addr) VALUES (%s,%s,%s,%s,%s)",
			sql_char($login_id),
			sql_char($password),
			sql_number($step),
			sql_number($seq_no),
			sql_char($REMOTE_ADDR));
	db_exec($sql);
}
?>