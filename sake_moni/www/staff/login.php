<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:ログインチェック
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/login_log.php");

$sql = "SELECT st_staff_id,st_mail_addr FROM t_staff WHERE st_mail_addr = " . sql_char($login_id) . " AND st_password = " . sql_char($password) . " AND st_status=0";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);

	session_start();

	setcookie('staff_mail_addr', $fetch->st_mail_addr, COOKIE_EXPIRE, '/');

	$_SESSION['ss_staff_id'] = $fetch->st_staff_id;

	login_log(2, $login_id, $password, $fetch->st_staff_id);
	redirect('myenq.php');
} else {
	login_log(2, $login_id, $password, null);
	redirect('login_error.php');
}
?>
