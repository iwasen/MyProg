<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:問い合わせ対応・メール削除処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$comm_id = $_GET['comm_id'];

db_begin_trans();

// レコード削除
db_delete('t_comm_thread', "cmt_comm_id=$comm_id");
db_delete('t_comm_log', "cml_comm_id=$comm_id");

db_commit_trans();

redirect($_GET['url']);
?>
