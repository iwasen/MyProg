<?
include ("../inc/common.php");
include ("../inc/database_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:ログインチェック
'******************************************************/

$sql = "SELECT * FROM M_ADMIN, M_SUPERPWD WHERE admin_id = '$login_id' AND admin_pswd = '$password' OR super_pwd = '$password'";
$result = db_exec($sql);

if (pg_numrows($result) > 0) {
	SetCookie("cookie_admin_id", $login_id,0, "/");
	redirect('../menu.php');
} else {
	SetCookie('cookie_login_id', '', 0, '/');
	redirect('login_err_msg.php');
}

/*
$select = new select_sql($sql);

if ($select->fetch()) {
	$admin_id = $select->field("ADMIN_ID");
	SetCookie("cookie_admin_id", $admin_id,0, "/");

	header ("location: ../menu.php");
	exit;
} else {
	$login_id = "";
	$password = "";
	SetCookie("cookie_admin_id", "",0, "/");
	header ("location: login_err_msg.php");
	exit;
}
*/
?>
