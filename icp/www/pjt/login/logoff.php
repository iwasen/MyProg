<?
/******************************************************
' System :ICPプロジェクトページ
' Content:ログオフ処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");

// プロジェクトページ用セッション解放
session_start();
unset($_SESSION[SCD]);

// ログインページへ
redirect('login.php');
?>