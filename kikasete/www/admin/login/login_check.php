<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:����������å�
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/login_log.php");

$sql = "SELECT ad_admin_id,ad_mail_addr FROM m_admin WHERE ad_mail_addr = '$login_id' AND ad_password = '$password' AND ad_status=0";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	session_start();

	setcookie('admin_mail_addr', $fetch->ad_mail_addr, COOKIE_EXPIRE, '/');

	$_SESSION['admin_login_id'] = $fetch->ad_admin_id;

	login_log(4, $login_id, $password, $fetch->ad_admin_id);
	redirect('../menu.php');
} else {
	login_log(4, $login_id, $password, null);
	redirect('login_error.php');
}
?>
