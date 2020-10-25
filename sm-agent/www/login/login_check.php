<?
/******************************************************
' System :メール配信サービス
' Content:ログインチェック
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/common.php");
include("$inc/database.php");

$sql = sprintf("SELECT us_user_id,us_mail_addr FROM m_user WHERE us_mail_addr=%s AND us_password=%s",
		sql_char($_POST['login_id']),
		sql_char($_POST['password']));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	// クッキーにメールアドレスを保存（次回デフォルトで表示させるため）
	if ($_POST['system'] == '')
		SetCookie('sma_login_id', $fetch->us_mail_addr, COOKIE_EXPIRE, '/');

	// セッションにログイン情報を保存
	session_start();
	$_SESSION['sma_login_id'] = $fetch->us_user_id;
	$_SESSION['sma_mail_addr'] = $fetch->us_mail_addr;
	$_SESSION['sma_system'] = $_POST['system'];

	redirect('../sendmail/list.php');
} else {
	redirect('login_error.php');
}
?>
