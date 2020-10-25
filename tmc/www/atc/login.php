<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」キャンペーン事務局用ページ
' Content:ログインチェック
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

// IDとパスワードをチェック
$sql = sprintf("SELECT clg_campaign_id,clg_privilege FROM t_campaign_login WHERE clg_campaign_id=%s AND clg_password=%s AND clg_status=0",
		sql_char($_POST['login_id']),
		sql_char(md5($_POST['password'])));
$result = db_exec($sql);
if (pg_num_rows($result) > 0) {
	$fetch = pg_fetch_object($result, 0);

	// クッキーにメールアドレスを保存（次回デフォルトで表示させるため）
	SetCookie('login_campaign_id', $fetch->clg_campaign_id, COOKIE_EXPIRE, '/');

	// セッションにログイン情報を保存
	session_start();
	$_SESSION['s_campaign_id'] = $fetch->clg_campaign_id;
	$_SESSION['s_privilege'] = $fetch->clg_privilege;
	redirect('top.php');
} else {
	// ログインエラー
	redirect('index.php?fail=1');
}
?>
