<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:問い合わせ対応・スレッド状態変更処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");

// 入力パラメータ
$comm_id = $_GET['comm_id'];
$status = $_GET['status'];
$url = $_GET['url'];

// 状態変更変更
$rec['cmt_status'] = $status;
db_update('t_comm_thread', $rec, "cmt_comm_id=$comm_id");

redirect($url);
?>
