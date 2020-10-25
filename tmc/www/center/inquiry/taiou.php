<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:問い合わせ対応・対応済み扱い処理
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

// コミュニケーション履歴の未対応フラグをクリア
$rec['cml_answer_flag'] = 'false';
db_update('t_comm_log', $rec, "cml_comm_id=$comm_id");

// スレッド管理の未対応フラグをクリア
$sql = "SELECT cml_thread_comm_id FROM t_comm_log WHERE cml_comm_id=$comm_id";
$thread_comm_id = db_fetch1($sql);
if ($thread_comm_id) {
	$rec['cmt_staff_id'] = sql_number($g_staff_id);
	$rec['cmt_answer_flag'] = "EXISTS(SELECT * FROM t_comm_log WHERE cml_thread_comm_id=cmt_comm_id AND cml_answer_flag=true)";
	db_update('t_comm_thread', $rec, "cmt_comm_id=$thread_comm_id");
}

db_commit_trans();

redirect($_GET['url']);
?>
