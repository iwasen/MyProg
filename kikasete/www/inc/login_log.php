<?
/******************************************************
' System :きかせて・net事務局用ページ
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

	// 最終ログイン日時記録
	if ($member_kind == 2) {
		if ($member_id) {
			$sql = "UPDATE t_marketer_last_login SET mll_last_login_date=current_timestamp WHERE mll_marketer_id=$member_id";
			$result = db_exec($sql);
			if (pg_affected_rows($result) == 0) {
				$sql = "INSERT INTO t_marketer_last_login (mll_marketer_id,mll_last_login_date) VALUES ($member_id,current_timestamp)";
				db_exec($sql);
			}
		}
	}
}
?>