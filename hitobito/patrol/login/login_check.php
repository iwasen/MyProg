<?php
/**
 * ひとびと･net パトロール隊ページ
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

$sql = sprintf("SELECT ptl_patrol_id,ptl_mail_addr FROM m_patrol WHERE ptl_mail_addr=%s AND ptl_password=%s AND ptl_status=1",
		sql_char($login_id),
		sql_char($password));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$_SESSION['patrol_login_id'] = $fetch->ptl_patrol_id;
	$_SESSION['login_mail_addr'] = $fetch->ptl_mail_addr;
	$_SESSION['login_type'] = 9;

	redirect('../top.php');
} else {
	redirect('login_error.php');
}
?>
