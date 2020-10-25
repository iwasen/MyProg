<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// セッション処理
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("mkk_myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('pe_index.php');

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
	redirect("mkk_myenq_edit_branch2.php?my_enq_no=$my_enq_no&qno=$qno&edit=1");
	break;
case 'add_cond':
	$branch_cond->cond_num++;
	redirect("mkk_myenq_edit_branch2.php?my_enq_no=$my_enq_no&qno=$qno&edit=1");
	break;
case 'all_clear':
	unset($enquete->branch_cond[$qno]);
	redirect("mkk_myenq_edit_branch2.php?my_enq_no=$my_enq_no&qno=$qno&edit=1");
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
<? marketer_header('Ｍｙアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<?
if (!$msg) {
?>
<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>Ｍｙアンケートの分岐条件を設定しました。</td>
				</tr>
			</table>
			<br>
<a href="mkk_myenq_edit_branch.php?my_enq_no=<?=$my_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else{?>

	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>

<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
