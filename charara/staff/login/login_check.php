<?
/******************************************************
' System :「きゃららFactory」作画スタッフ用ページ
' Content:ログインチェック
'******************************************************/

$top = '..';
$inc = "$top/../inc";

include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_log.php");

$sql = sprintf("SELECT st_staff_id,st_mail_addr FROM m_staff WHERE st_mail_addr=%s AND st_password=%s AND st_status=0",
		sql_char($login_id),
		sql_char($password));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	SetCookie('staff_login_id', $fetch->st_staff_id, 0, '/');
	SetCookie('staff_mail_addr', $fetch->st_mail_addr, time()+365*24*60*60, '/');
	staff_login_log($login_id, $password, $fetch->st_staff_id);
	redirect('../top.php');
} else {
	SetCookie('staff_login_id', '', 0, '/');
	staff_login_log($login_id, $password, null);
	redirect('login_error.php');
}

?>
