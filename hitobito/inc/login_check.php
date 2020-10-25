<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ログインチェックページ
 *
 *
 * @package
 * @author
 * @version
 */

session_cache_limiter('none');

session_start();

if (!isset($_SESSION['login_type'])) {
//	header("location: $top/login/login.php");
	header("location: https://www.hitobito.net/admin/login/login.php");
	exit;
}
?>