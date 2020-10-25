<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:OneToOneメール・無反応状態解除処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$user_id = $_GET['user_id'];
$url = $_GET['url'];

// 無反応状態解
db_delete('t_mail_reply', "mrp_user_id=$user_id");

redirect($url);
?>
