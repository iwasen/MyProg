<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ログインチェック
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// メールアドレスとパスワードをチェック
$sql = sprintf("SELECT stf_staff_id,stf_mail_addr,stf_privilege FROM t_staff WHERE stf_mail_addr=%s AND stf_password=%s AND stf_status=0",
		sql_char(strtolower($_POST['mail_addr'])),
		sql_char(md5($_POST['password'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// クッキーにメールアドレスを保存（次回デフォルトで表示させるため）
	SetCookie('login_mail_addr', $fetch->stf_mail_addr, COOKIE_EXPIRE, '/');

	// セッションにログイン情報を保存
	session_start();
	$_SESSION['s_staff_id'] = $fetch->stf_staff_id;
	$_SESSION['s_mail_addr'] = $fetch->stf_mail_addr;
	$_SESSION['s_privilege'] = $fetch->stf_privilege;
	redirect('../index.php');
} else {
	// ログインエラー
	redirect('login_error.php');
}
?>
