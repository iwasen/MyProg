<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// 設問選択
function select_question($ans_enquete_id, $cqno, $cmno) {
	global $pro_enq;

	echo "<option value=''>未設定（選択してください）</option>\n";

	$ceno = ($pro_enq->enquete2_id == $ans_enquete_id) ? '2' : '1';
	$selected = "$ceno-$cqno-$cmno";

	if ($pro_enq->finding_flag == DBTRUE) {
		foreach ($pro_enq->enquete->question as $qno => $question) {
			if ($qno) {
				if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7)
					echo '<option ', value_selected("1-$qno-0", $selected), '>FDQ', $qno, '.', str_trim($question->question_text, 20), '</option>', "\n";
				elseif ($question->question_type == 4 || $question->question_type == 5) {
					foreach ($question->hyousoku as $sno => $hyousoku)
						echo '<option ', value_selected("1-$qno-$sno", $selected), '>FDQ', $qno, 'M-', $sno, '.', str_trim($hyousoku, 20), '</option>', "\n";
				}
			}
		}
	}

	foreach ($pro_enq->enquete2->question as $qno => $question) {
		if ($qno) {
			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7)
				echo '<option ', value_selected("2-$qno-0", $selected), '>Q', $qno, '.', str_trim($question->question_text, 20), '</option>', "\n";
			elseif ($question->question_type == 4 || $question->question_type == 5) {
				foreach ($question->hyousoku as $sno => $hyousoku)
					echo '<option ', value_selected("2-$qno-$sno", $selected), '>Q', $qno, 'M-', $sno, '.', str_trim($hyousoku, 20), '</option>', "\n";
			}
		}
	}
}

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq_no == '') {
		$pro_enq->marketer_id = $_SESSION['ss_owner_id'];
		$pro_enq->creator_id = $_SESSION['ss_marketer_id'];
	} else
		$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
}

$cleaning = &$pro_enq->cleaning;

$cond_num = count($cleaning->cleaning_cond);
if ($cond_num == 0 || isset($add_cond)) {
	$cond_num++;
	$cleaning->cleaning_cond[$cond_num]->and_or = 'A';
	$cleaning->cleaning_cond[$cond_num]->not_cond = false;
}
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

<script type="text/javascript">
<!--
function change_question(cond_no, seq_no) {
	var f = document.form1;
	f.next_action.value = "edit";
	f.change_cond_no.value = cond_no;
	f.change_seq_no.value = seq_no;
	f.submit();
}
//-->
</script>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

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

			<form method="post" name="form1" action="pe_update_cleaning.php">
			<table border=0 cellspacing=2 cellpadding=3 width="100%" class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td>■サンプルクリーニング条件の設定</td>
								<td align="right"><a href="pe_webprev.php?pro_enq_no=<?=$pro_enq_no?>&popup=1" target="_blank">アンケートWEBプレビュー</a></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="23%">条件種別</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="valid_flag" <?=value_checked(DBFALSE, $cleaning->valid_flag)?>>無効回答を設定する　<input type="radio" name="valid_flag" <?=value_checked(DBTRUE, $cleaning->valid_flag)?>>有効回答を設定する
								</td>
							</tr>
<?
foreach ($cleaning->cleaning_cond as $cond_no => $cleaning_cond) {
	if ($cond_no > 1) {
?>
							<tr>
								<td bgcolor="#eeeeee" colspan=2 align="center">サンプルクリーニング条件間
									<input type="radio" name="cond_and_or[<?=$cond_no?>]" <?=value_checked('A', $cleaning_cond->and_or)?>>AND条件
									<input type="radio" name="cond_and_or[<?=$cond_no?>]" <?=value_checked('O', $cleaning_cond->and_or)?>>OR条件
								</td>
							</tr>
<?
	}
?>
							<tr>
								<td bgcolor="#eeeeee" rowspan=6>サンプルクリーニング条件<?=mb_convert_kana($cond_no, 'N')?><p><span class="footer_text">※一つの条件で3つまで設定できます(AND条件)</span></p></td>
<?
	$first = true;
	for ($seq_no = 1; $seq_no <= 3; $seq_no++) {
		$ans_cond = &$cleaning_cond->ans_cond[$seq_no];

		$ans_enquete_id = $ans_cond->ans_enquete_id;
		$cqno = $ans_cond->question_no;
		$cmno = $ans_cond->hyousoku_no;
		$csno = $ans_cond->select_no;
		$and_or = $ans_cond->and_or;
		$not_cond = $ans_cond->not_cond;

		if ($and_or == '')
			$and_or = 'O';

		if ($not_cond == '')
			$not_cond = DBFALSE;

		if ($first)
			$first = false;
		else {
?>
							<tr>
<?
		}
?>
								<td bgcolor="#ffeecc" valign="top">
									<select name="question_no[<?=$cond_no?>][<?=$seq_no?>]" onchange="change_question(<?=$cond_no?>,<?=$seq_no?>)"><? select_question($ans_enquete_id, $cqno, $cmno) ?></select>
							</td></tr>
							<tr>
								<td bgcolor="#ffeecc" valign="top">

<?
		if ($cqno) {
?>



									<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffeecc" class="honbun2">

										<tr>
											<td>
<?
			if ($ans_enquete_id == $pro_enq->enquete_id)
				$question = &$pro_enq->enquete->question[$cqno];
			else
				$question = &$pro_enq->enquete2->question[$cqno];

			$sa_flag = false;
			if ($question->question_type == 1 || $question->question_type == 4 || $question->question_type == 7) {
				$sa_flag = true;
				$and_or = 'O';
			}

			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
				foreach ($question->sel_text as $sno => $sel_text) {
?>
												<input type="checkbox" name="select_no[<?=$cond_no?>][<?=$seq_no?>][]" <?=value_checked_multi($sno, $csno)?>><?=htmlspecialchars($sel_text)?><br>
<?
				}
			} elseif ($question->question_type == 4 || $question->question_type == 5) {
				foreach ($question->hyoutou as $sno => $sel_text) {
?>
												<input type="checkbox" name="select_no[<?=$cond_no?>][<?=$seq_no?>][]" <?=value_checked_multi($sno, $csno)?>><?=htmlspecialchars($sel_text)?><br>
<?
				}
			}
?>
												<br>
												<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffeecc" class="honbun2">
														<td>
															<input type="radio" name="not_cond[<?=$cond_no?>][<?=$seq_no?>]" <?=value_checked(DBFALSE, $not_cond)?>>通常
															<input type="radio" name="not_cond[<?=$cond_no?>][<?=$seq_no?>]" <?=value_checked(DBTRUE, $not_cond)?>>Not条件
														</td>
													</tr>
													<tr>
														<td>
															<input type="radio" name="and_or[<?=$cond_no?>][<?=$seq_no?>]" <?=value_checked('A', $and_or)?> <?=$sa_flag ? 'disabled' : ''?>>AND条件
															<input type="radio" name="and_or[<?=$cond_no?>][<?=$seq_no?>]" <?=value_checked('O', $and_or)?>>OR条件
															<input type="radio" name="and_or[<?=$cond_no?>][<?=$seq_no?>]" <?=value_checked('F', $and_or)?> <?=$sa_flag ? 'disabled' : ''?>>完全一致
														</td>
												</table>
												<br>
											</td>
										</tr>
									</table>
<?
		}
?>
								</td>
							</tr>
<?
	}
}
?>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="条件の追加" onclick="document.form1.next_action.value='add_cond'">
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			<input type="hidden" name="change_cond_no">
			<input type="hidden" name="change_seq_no">
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
