<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

$owner_id = $_SESSION['ss_owner_id'];

if ($main)
	$sql = sprintf("SELECT mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=%s AND mep_pro_enq_no=%s", sql_number($owner_id), sql_number($pro_enq_no));
else
	$sql = sprintf("SELECT mep_enquete_id FROM t_pro_enquete WHERE mep_marketer_id=%s AND mep_pro_enq_no=%s", sql_number($owner_id), sql_number($pro_enq_no));
$enquete_id = db_fetch1($sql);

if ($enquete_id) {
	$rec['eq_page_break'] = sql_bool($pb ? DBTRUE : DBFALSE);
	db_update('t_enq_question', $rec, sprintf("eq_enquete_id=%s AND eq_question_no=%s", sql_number($enquete_id), sql_number($qno)));
}

redirect("pe_edit_branch.php?pro_enq_no=$pro_enq_no&main=$main&" . random_param());
?>