<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ログオフページ
 *
 *
 * @package
 * @author
 * @version
 */

session_start();
$type = $_SESSION['login_type'];

session_destroy();

header('location: ' . ($type == 3 ? '../agent/login.php' : 'login.php'));
exit;
?>