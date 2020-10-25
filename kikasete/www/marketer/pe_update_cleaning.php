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
	redirect("pe_edit_cleaning.php?pro_enq_no=$pro_enq_no&edit=1");
if ($next_action == 'add_cond') {
	$enquete->cleaning_cond[$cond_no + 1]->valid_flag = DBFALSE;
	redirect("pe_edit_cleaning.php?pro_enq_no=$pro_enq_no&edit=1&add_cond=1");
}

// エラー無しなら次ページへ
if (!$msg) {
	// 分岐条件保存
	$cleaning->write_db($pro_enq->enquete2_id);

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
					<td>Ｐｒｏアンケートのクリーニング条件を設定しました。</td>
				</tr>
			</table>
			<br>
			<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="戻る" name="image2" width="108" height="31" border="0"></a>
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
