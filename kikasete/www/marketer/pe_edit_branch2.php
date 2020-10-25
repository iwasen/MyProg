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
function select_question($selected) {
	global $enquete, $qno;

	echo "<option value=''>未設定（選択してください）</option>\n";

	foreach ($enquete->question as $qno2 => $question) {
		if ($qno2 >= $qno)
			break;

		if ($question->question_type != 3 && $question->question_type != 6 && $question->question_type != 8)
			echo '<option ', value_selected($qno2, $selected), '>Q', $qno2, '.', str_trim($question->question_text, 20), '</option>', "\n";
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

if ($main)
	$enquete = &$pro_enq->enquete2;
else
	$enquete = &$pro_enq->enquete;

$question = &$enquete->question[$qno];
$branch_cond = &$enquete->branch_cond[$qno];

$and_or = $branch_cond->and_or;
if ($and_or == '')
	$and_or = 'A';

$cond_num = $branch_cond->cond_num;
if ($cond_num == 0)
	$cond_num = count($branch_cond->cond);
if ($cond_num == 0)
	$cond_num = 1;
$branch_cond->cond_num = $cond_num;
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

<script type="text/javascript">
<!--
function disp_desc() {
	var f = document.form1;
	var t1 = "";
	for (var i = 1; i <= <?=$cond_num?>; i++) {
		var t2 = "";
		var cq = f["cond_question_no[" + i + "]"];
		var sn = f["select_no[" + i + "][]"];
		var st = f["select_text[" + i + "][]"];
		var ao = f["sel_and_or[" + i + "]"];
		var nc = f["not_cond[" + i + "]"];
		if (cq.selectedIndex != 0) {
			if (sn) {
				if (sn.length) {
					for (var j = 0; j < sn.length; j++) {
						if (sn[j].checked) {
							if (t2 != "") {
								switch (radio_value(ao)) {
								case "A":
								case "F":
									t2 += "と";
									break;
								case "O":
									t2 += "または";
									break;
								}
							}
							t2 += "「" + st[j].value + "」";
						}
					}
				} else {
					if (sn.checked)
						t2 += "「" + st.value + "」";
				}
				if (radio_value(ao) == "F")
					t2 += "のみ";
				if (radio_value(nc) == 't')
					t2 += "以外";
				if (t2 != "")
					t2 = "Q" + cq.value + "で" + t2;
			}
		}
		if (t2 != "") {
			if (t1 != "") {
				if (radio_value(f.and_or) == 'A')
					t1 += "、かつ";
				else
					t1 += "、または";
			}
			t1 += t2;
		}
	}
	if (t1 != "")
		t1 += "を選んだ方にお聞きします。";
	var desc = document.getElementById("description").innerText = t1;
}
function radio_value(c) {
	for (var i = 0; i < c.length; i++) {
		if (c[i].checked)
			return c[i].value;
	}
	return "";
}
function change_question(cno) {
	var f = document.form1;
	f.next_action.value = "edit";
	f.change_cno.value = cno;
	f.submit();
}
function all_clear() {
	if (confirm("分岐条件を全て解除します。よろしいですか？")) {
		var f = document.form1;
		f.next_action.value = "all_clear";
		f.submit();
	}
}
window.onload = function() {
  disp_desc();
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

			<form method="post" name="form1" action="pe_update_branch2.php">
			<table border=0 cellspacing=2 cellpadding=3 width="100%" class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td>■分岐条件の設定</td>
								<td align="right"><input type="button" value="分岐条件の全解除" onclick="all_clear()"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="2">分岐設定対象設問</td>
								<td bgcolor="#ffeecc">Ｑ<?=mb_convert_kana($qno, 'N')?>．<?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
<?
if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
							<tr>
								<td bgcolor="#ffeecc">
<?
	foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><br>
<?
	}
?>
								</td>
							</tr>
<?
} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="2" cellpadding="2" width="100%" class="honbun2">
										<tr>
											<td>&nbsp;</td>
<?
	foreach ($question->hyoutou as $hno => $hyoutou) {
?>
											<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
	}
?>
										</tr>
<?
	foreach ($question->hyousoku as $hno => $hyousoku) {
?>
										<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
											<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
		foreach ($question->hyoutou as $hno => $hyoutou) {
?>
											<td></td>
<?
		}
?>
										</tr>
<?
	}
?>
									</table>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="20%">説明文</td>
								<td bgcolor="#ffeecc" id="description"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr<?=($cond_num >= 2) ? '' : ' style="display:none"'?>>
								<td bgcolor="#eeeeee" width="20%">分岐条件間結合条件</td>
								<td bgcolor="#ffeecc">
									<input type="radio" name="and_or" <?=value_checked('A', $and_or)?> onclick="disp_desc()">AND　<input type="radio" name="and_or" <?=value_checked('O', $and_or)?> onclick="disp_desc()">OR
								</td>
							</tr>
<?
for ($cno = 1; $cno <= $cond_num; $cno++) {
	$cond = &$branch_cond->cond[$cno];

	$cqno = $cond->cond_question_no;
	$csno = $cond->select_no;
	$and_or = $cond->and_or;
	$not_cond = $cond->not_cond;

	if ($and_or == '')
		$and_or = 'O';

	if ($not_cond == '')
		$not_cond = DBFALSE;
?>
							<tr>
								<td bgcolor="#eeeeee" rowspan=2>条件<?=$cno?></td>
								<td bgcolor="#ffeecc"><select name="cond_question_no[<?=$cno?>]" onchange="change_question(<?=$cno?>)"><? select_question($cqno) ?></select></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc" valign="top">
<?
	if ($cqno) {
		$question = &$enquete->question[$cqno];

		$sa_flag = false;
		if ($question->question_type == 1 || $question->question_type == 7) {
			$and_or = 'O';
			$sa_flag = true;
		}

		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<input type="checkbox" name="select_no[<?=$cno?>][]" <?=value_checked_multi($sno, $csno)?> onclick="disp_desc()"><?=htmlspecialchars($sel_text)?><input type="hidden" name="select_text[<?=$cno?>][]" <?=value($sel_text)?>><br>
<?
			}
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
									<table border="0" cellspacing="2" cellpadding="2" width="100%" class="honbun2">
										<tr>
											<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $tno => $hyoutou) {
?>
											<td bgcolor="#ffffff"><?=mb_convert_kana($tno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
										</tr>
<?
			foreach ($question->hyousoku as $sno => $hyousoku) {
?>
										<tr bgcolor="<?=$sno % 2 ? '#e8fbfd' : '#ffffff'?>">
											<td><?=mb_convert_kana($sno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
<?
				foreach ($question->hyoutou as $tno => $hyoutou) {
?>
											<td align="center"><input type="checkbox" name="select_no[<?=$cno?>][]" <?=value_checked_multi("$sno:$tno", $csno)?> onclick="disp_desc()"><input type="hidden" name="select_text[<?=$cno?>][]" <?=value("{$hyousoku}が{$hyoutou}")?>></td>
<?
				}
?>
										</tr>
<?
			}
?>
									</table>
<?
		}
?>
									<br>選択肢間結合条件<br>
									<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffeecc" class="honbun2">
											<tr>
											<td>
												<input type="radio" name="not_cond[<?=$cno?>]" <?=value_checked(DBFALSE, $not_cond)?> onclick="disp_desc()">選択されている
												<input type="radio" name="not_cond[<?=$cno?>]" <?=value_checked(DBTRUE, $not_cond)?> onclick="disp_desc()">選択されていない											</td>
										</tr>
										<tr style="display:none">
											<td>
												<input type="radio" name="sel_and_or[<?=$cno?>]" <?=value_checked('A', $and_or)?> onclick="disp_desc()" <?=$sa_flag ? 'disabled' : ''?>>AND条件
												<input type="radio" name="sel_and_or[<?=$cno?>]" <?=value_checked('O', $and_or)?> onclick="disp_desc()">OR条件
												<input type="radio" name="sel_and_or[<?=$cno?>]" <?=value_checked('F', $and_or)?> onclick="disp_desc()" <?=$sa_flag ? 'disabled' : ''?>>完全一致											</td>
									</table>
									<br>
<?
	}
?>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="分岐条件の追加" onclick="document.form1.next_action.value='add_cond'">
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='pe_edit_branch.php?pro_enq_no=<?=$pro_enq_no?>&main=<?=$main?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			<input type="hidden" name="main" <?=value($main)?>>
			<input type="hidden" name="qno" <?=value($qno)?>>
			<input type="hidden" name="change_cno">
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
