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

$cleaning = &$pro_enq->cleaning;

// フォームから入力データ取得
$cleaning->valid_flag = $_POST['valid_flag'];

$cleaning->cleaning_cond = array();
$cond_no = 0;
if (is_array($_POST['question_no'])) {
    foreach ($_POST['question_no'] as $cond_no => $question_no) {
        $cleaning_cond = &$cleaning->cleaning_cond[$cond_no];

        $cleaning_cond->and_or = isset($_POST['cond_and_or'][$cond_no]) ? $_POST['cond_and_or'][$cond_no] : ' ';

        for ($seq_no = 1; $seq_no <= 3; $seq_no++) {
            if ($_POST['question_no'][$cond_no][$seq_no] != '') {
                $ans_cond = &$cleaning_cond->ans_cond[$seq_no];

                list($ceno, $cqno, $cmno) = explode('-', $_POST['question_no'][$cond_no][$seq_no]);

                $ans_cond->ans_enquete_id = ($ceno == 1) ? $pro_enq->enquete_id : $pro_enq->enquete2_id;
                $ans_cond->question_no = $cqno;
                $ans_cond->hyousoku_no = $cmno;
                $ans_cond->select_no = ($_POST['select_no'][$cond_no][$seq_no] && ($_POST['change_cond_no'] != $cond_no || $_POST['change_seq_no'] != $seq_no)) ? join(',', $_POST['select_no'][$cond_no][$seq_no]) : '';
                $ans_cond->and_or = $_POST['and_or'][$cond_no][$seq_no];
                $ans_cond->not_cond = $_POST['not_cond'][$cond_no][$seq_no];
            }
        }
    }
}

if ($next_action == 'edit')
    redirect("edit_cleaning.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no&edit=1");
if ($next_action == 'add_cond') {
    $enquete->cleaning_cond[$cond_no + 1]->valid_flag = DBFALSE;
    redirect("edit_cleaning.php?marketer_id=$marketer_id&pro_enq_no=$pro_enq_no&edit=1&add_cond=1");
}

// エラー無しなら次ページへ
if (!$msg) {
    // 分岐条件保存
    $cleaning->write_db($pro_enq->enquete2_id);

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
<p>Ｐｒｏアンケートのクリーニング条件を設定しました。</p>
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
