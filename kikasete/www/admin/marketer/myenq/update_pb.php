<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｍｙアンケート変更処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

$sql = sprintf("SELECT me_enquete_id FROM t_my_enquete WHERE me_marketer_id=%s AND me_my_enq_no=%s", sql_number($marketer_id), sql_number($my_enq_no));
$enquete_id = db_fetch1($sql);

if ($enquete_id) {
	$rec['eq_page_break'] = sql_bool($pb ? DBTRUE : DBFALSE);
	db_update('t_enq_question', $rec, sprintf("eq_enquete_id=%s AND eq_question_no=%s", sql_number($enquete_id), sql_number($qno)));
}

redirect("edit_branch.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no&" . random_param());
?>