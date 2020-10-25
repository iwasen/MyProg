<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ログインチェック処理
'******************************************************/

session_cache_limiter('none');

session_start();

if ($agent_login_id == '') {
	header("location: $top/login/logoff.php");
	exit;
}
?>
