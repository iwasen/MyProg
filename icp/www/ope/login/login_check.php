<?
/******************************************************
' System :ICPオペレータページ
' Content:ログインチェック
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/ope_common.php");

// IDとパスワードをチェック
$sql = sprintf("SELECT op_operator_id,op_mail_addr,op_admin_auth FROM t_operator WHERE op_mail_addr=%s AND op_password=%s AND op_status=1",
		sql_char(strtolower($_POST['mail_addr'])),
		sql_char($_POST['password']));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	session_start();

	// クッキーにメールアドレスを保存（次回デフォルトで表示させるため）
	SetCookie('icp_login_operator', $fetch->op_mail_addr, COOKIE_EXPIRE, '/');

	// セッションにログイン情報を保存
	$_SESSION[SCD]['operator_id'] = $fetch->op_operator_id;
	$_SESSION[SCD]['admin_auth'] = $fetch->op_admin_auth;
	redirect("$top/top.php");
} else {
	// クッキーのメールアドレスを削除
	SetCookie('icp_login_operator', '', 0, '/');

	// ログインエラー
	redirect('login.php?fail=1');
}
?>
