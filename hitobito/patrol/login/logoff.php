<?php
/**
 * ひとびと･net パトロール隊ページ
 *
 * ログオフページ
 *
 *
 * @package
 * @author
 * @version
 */

session_start();
session_destroy();
header('location: login.php');
exit;
?>