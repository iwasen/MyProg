#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:ログインチェック
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = sprintf("SELECT st_staff_id,st_mail_addr,st_auth FROM m_staff WHERE st_mail_addr=%s AND st_password=%s AND st_status=1",
		sql_char($mail_addr),
		sql_char($password));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$s_staff_id = $fetch->st_staff_id;
	$s_staff_mail_addr = $fetch->st_mail_addr;
	$s_staff_auth = $fetch->st_auth;
	session_register('s_staff_id', 's_staff_mail_addr', 's_staff_auth');

	redirect('../index.php');
} else {
	redirect('login_error.php');
}
?>
