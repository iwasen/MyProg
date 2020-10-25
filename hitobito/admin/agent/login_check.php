<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ログインID/Pass入力チェック
 *
 *
 * @package
 * @author
 * @version
 */

$top = '..';
$inc = "$top/../inc";
require_once("$inc/common.php");
require_once("$inc/database.php");

// POST変数取得
$login_id = $_POST['login_id'];
$password = $_POST['password'];

session_start();

$sql = sprintf("SELECT adm_admin_id,adm_mail_addr,adm_type FROM m_admin WHERE adm_mail_addr=%s AND adm_password=%s AND adm_status=1 AND adm_type=3",
		sql_char($login_id),
		sql_char($password));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$_SESSION['admin_login_id'] = $fetch->adm_admin_id;
	$_SESSION['login_mail_addr'] = $fetch->adm_mail_addr;
	$_SESSION['login_type'] = $fetch->adm_type;

	redirect('menu.php');
} else {
	redirect('login_error.php');
}
?>
