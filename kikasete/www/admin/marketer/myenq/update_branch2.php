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
include("$inc/get_form.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('myenquete', 'Ｍｙアンケート管理', 'Ｍｙアンケート更新', BACK_TOP);

// セッション処理
$myenq = &$_SESSION['ss_my_enq'];
if ($myenq->my_enq_no != $my_enq_no)
	redirect("show.php?my_enq_no=marketer_id=$marketer_id&$my_enq_no");

$enquete = &$myenq->enquete;

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
	redirect("edit_branch2.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no&qno=$qno&edit=1");
	break;
case 'add_cond':
	$branch_cond->cond_num++;
	redirect("edit_branch2.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no&qno=$qno&edit=1");
	break;
case 'all_clear':
	unset($enquete->branch_cond[$qno]);
	redirect("edit_branch2.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no&qno=$qno&edit=1");
	break;
}

// エラー無しなら次ページへ
if (!$msg) {
	// 分岐条件保存
	$enquete->write_branch_cond($qno);

	// セッション変数削除
	unset($_SESSION['ss_my_enq']);
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
<p>Ｍｙアンケートの分岐条件を設定しました。</p>
<input type="button" value="　戻る　" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg);
?>
<? page_footer() ?>
</body>
</html>
