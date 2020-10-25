<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// セッション処理
if (isset($pro_enq_no)) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq->pro_enq_no != $pro_enq_no)
		redirect("pe_make.php?pro_enq_no=$pro_enq_no");
} else
	redirect('pe_index.php');

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
	redirect("pe_edit_branch2.php?pro_enq_no=$pro_enq_no&main=$main&qno=$qno&edit=1");
	break;
case 'add_cond':
	$branch_cond->cond_num++;
	redirect("pe_edit_branch2.php?pro_enq_no=$pro_enq_no&main=$main&qno=$qno&edit=1");
	break;
case 'all_clear':
	unset($enquete->branch_cond[$qno]);
	redirect("pe_edit_branch2.php?pro_enq_no=$pro_enq_no&main=$main&qno=$qno&edit=1");
	break;
}

// エラー無しなら次ページへ
if (!$msg) {
	// 分岐条件保存
	$enquete->write_branch_cond($qno);

	// セッション変数削除
	unset($_SESSION['ss_pro_enq']);
}
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
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
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>Ｐｒｏアンケートの分岐条件を設定しました。</td>
				</tr>
			</table>
			<br>
<a href="pe_edit_branch.php?pro_enq_no=<?=$pro_enq_no?>&main=<?=$main?>"><img src="images/mkk_bt/back.gif" alt="戻る" width="108" height="31" border="0"></a>
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
