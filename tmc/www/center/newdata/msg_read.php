<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:メッセージ既読処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");

// 既読セット
$rec['msr_message_id'] = sql_number($_GET['message_id']);
$rec['msr_staff_id'] = sql_number($g_staff_id);
db_insert('t_message_read', $rec);

redirect('top.php');
?>