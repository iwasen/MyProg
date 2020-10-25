<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:Ｐｒｏアンケート更新処理
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/search.php");      // 更新 2006/06/13
include("$inc/enquete.php");     // 更新 2006/06/13
include("$inc/pro_enquete.php"); // 更新 2006/06/13
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

if ($main)
    $sql = sprintf("SELECT mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=%s AND mep_pro_enq_no=%s", sql_number($marketer_id), sql_number($pro_enq_no));
else
    $sql = sprintf("SELECT mep_enquete_id FROM t_pro_enquete WHERE mep_marketer_id=%s AND mep_pro_enq_no=%s", sql_number($marketer_id), sql_number($pro_enq_no));
$enquete_id = db_fetch1($sql);

if ($enquete_id) {
    $rec['eq_page_break'] = sql_bool($pb ? DBTRUE : DBFALSE);
    db_update('t_enq_question', $rec, sprintf("eq_enquete_id=%s AND eq_question_no=%s", sql_number($enquete_id), sql_number($qno)));

    // 更新時、アンケート回答データを削除する 2006/03/28 BTI
    // 更新 2006/06/13
    $pro_enq = new pro_enquete_class;
    $pro_enq->read_db($marketer_id, $pro_enq_no);

    $sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);

}

redirect("edit_branch.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no&main=$main");
?>