<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ログイン履歴書き込み処理
'******************************************************/

function login_log($member_kind, $login_id, $password, $member_id) {
	global $REMOTE_ADDR;

	if ($login_id == '')
		return;

	$sql = sprintf("INSERT INTO l_login (lg_member_kind,lg_login_id,lg_password,lg_member_id,lg_ip_addr) VALUES (%s,%s,%s,%s,%s)",
			sql_number($member_kind),
			sql_char($login_id),
			sql_char($password),
			sql_number($member_id),
			sql_char($REMOTE_ADDR));
	db_exec($sql);
}
?>