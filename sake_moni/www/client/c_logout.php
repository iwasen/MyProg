<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");

// セッション終了
session_start();
session_destroy();

// モニタートップページへ
redirect(http_url('index.php'));
?>