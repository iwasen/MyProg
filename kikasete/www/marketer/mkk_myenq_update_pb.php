<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

$owner_id = $_SESSION['ss_owner_id'];

$sql = sprintf("SELECT me_enquete_id FROM t_my_enquete WHERE me_marketer_id=%s AND me_my_enq_no=%s", sql_number($owner_id), sql_number($my_enq_no));
$enquete_id = db_fetch1($sql);

if ($enquete_id) {
	$rec['eq_page_break'] = sql_bool($pb ? DBTRUE : DBFALSE);
	db_update('t_enq_question', $rec, sprintf("eq_enquete_id=%s AND eq_question_no=%s", sql_number($enquete_id), sql_number($qno)));
}

redirect("mkk_myenq_edit_branch.php?my_enq_no=$my_enq_no&" . random_param());
?>