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
include("$inc/search.php");
include("$inc/enquete.php");
include("$inc/pro_enquete.php");
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('proenquete', 'Ｐｒｏアンケート管理', 'Ｐｒｏアンケート更新', BACK_TOP);

// セッション処理
$pro_enq = &$_SESSION['ss_pro_enq'];
if ($pro_enq->pro_enq_no != $pro_enq_no)
    redirect("show.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no");

if ($main)
    $enquete = &$pro_enq->enquete2;
else
    $enquete = &$pro_enq->enquete;

$branch_cond = &$enquete->branch_cond[$qno];

// フォームから入力データ取得
$branch_cond->and_or = $_POST['and_or'];

for ($cno = 1; $cno <= $branch_cond->cond_num; $cno++) {
    $branch_cond->cond[$cno]->and_or = $_POST['sel_and_or'][$cno];
    $branch_cond->cond[$cno]->cond_question_no = $_POST['cond_question_no'][$cno];
    $branch_cond->cond[$cno]->select_no = ($_POST['select_no'][$cno] && $_POST['change_cno'] != $cno) ? join(',', $_POST['select_no'][$cno]) : '';
    $branch_cond->cond[$cno]->not_cond = $_POST['not_cond'][$cno];
}

switch ($next_action) {
case 'edit':
    redirect("edit_branch2.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no&main=$main&qno=$qno&edit=1");
    break;
case 'add_cond':
    $branch_cond->cond_num++;
    redirect("edit_branch2.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no&main=$main&qno=$qno&edit=1");
    break;
case 'all_clear':
    unset($enquete->branch_cond[$qno]);
    redirect("edit_branch2.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no&main=$main&qno=$qno&edit=1");
    break;
}

// エラー無しなら次ページへ
if (!$msg) {
    // 分岐条件保存
    $enquete->write_branch_cond($qno);

    // 更新時、アンケート回答データを削除する 2006/03/28 BTI
    $sql = "DELETE FROM t_answer WHERE an_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_select WHERE as_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);
    $sql = "DELETE FROM t_ans_matrix WHERE ax_enquete_id IN ($pro_enq->enquete_id,$pro_enq->enquete2_id)";
    db_exec($sql);

    // セッション変数削除
    unset($_SESSION['ss_pro_enq']);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p>Ｐｒｏアンケートの分岐条件を設定しました。</p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&pro_enq_no=<?=$pro_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
