<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:ログインチェック
'******************************************************/

$top = '..';
$inc = "$top/../inc";

include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/login_log.php");

$sql = "SELECT ad_admin_id,ad_mail_addr FROM m_admin WHERE ad_mail_addr =" . sql_char($login_id) . " AND ad_password =" . sql_char($password) . " AND ad_status=0";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	SetCookie('admin_login_id', $fetch->ad_admin_id, 0, '/');
	SetCookie('admin_mail_addr', $fetch->ad_mail_addr, COOKIE_EXPIRE, '/');
	admin_login_log($login_id, $password, $fetch->ad_admin_id);
	redirect('../menu.php');
} else {
	SetCookie('admin_login_id', '', 0, '/');
	admin_login_log($login_id, $password, null);
	redirect('login_error.php');
}

?>
