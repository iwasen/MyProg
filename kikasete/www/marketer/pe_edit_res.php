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

$enquete = &$pro_enq->enquete;
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>

<script type="text/javascript">
<!--
function disp_res_and_or() {
	var f = document.form1;
	var cn = 0;
	for (var i = 1; i <= <?=count($enquete->question)?>; i++) {
		var sn = f["select_no[" + i + "][]"];
		if (sn) {
			if (sn.length) {
				for (var j = 0; j < sn.length; j++) {
					if (sn[j].checked) {
						cn++;
						break;
					}
				}
			} else {
				if (sn.checked)
					cn++;
			}
		}
	}
	document.getElementById("id_res_and_or").style.display = cn >= 2 ? "" : "none";
}
window.onload = function() {
  disp_res_and_or();
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

			<form method="post" name="form1" action="pe_update_res.php">
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td bgcolor="#eff7e8">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td>■本アンケート対象条件</td>
								<td align="right" id="id_res_and_or" style="display:none">設問間結合条件　<input type="radio" name="res_and_or" <?=value_checked('A', $pro_enq->res_and_or)?>>AND　<input type="radio" name="res_and_or" <?=value_checked('O', $pro_enq->res_and_or)?>>OR</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			if ($question->question_type == 3 || $question->question_type == 6 || $question->question_type == 8)
				continue;

			$res_cond = &$pro_enq->res_cond[$qno];

			// 初期値
			if (!isset($res_cond)) {
				$res_cond->and_or = 'O';
				$res_cond->not_cond = DBFALSE;
			}

			$sa_flag = false;
			if ($question->question_type == 1 || $question->question_type == 7) {
				$res_cond->and_or = 'O';
				$sa_flag = true;
			}
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%" rowspan="3">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<input type="radio" name="not_cond[<?=$qno?>]" <?=value_checked(DBFALSE, $res_cond->not_cond)?>>選択されている
									<input type="radio" name="not_cond[<?=$qno?>]" <?=value_checked(DBTRUE, $res_cond->not_cond)?>>選択されていない
									<div style="display:none">
										<input type="radio" name="and_or[<?=$qno?>]" <?=value_checked('A', $res_cond->and_or)?> <?=$sa_flag ? 'disabled' : ''?>>AND条件
										<input type="radio" name="and_or[<?=$qno?>]" <?=value_checked('O', $res_cond->and_or)?>>OR条件
										<input type="radio" name="and_or[<?=$qno?>]" <?=value_checked('F', $res_cond->and_or)?> <?=$sa_flag ? 'disabled' : ''?>>完全一致
									</div>
								</td>
							</tr>
<?
			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
							<tr>
								<td bgcolor="#ffeecc">
<?
				foreach ($question->sel_text as $sno => $sel_text) {
?>
									<input type="checkbox" name="select_no[<?=$qno?>][]" <?=value_checked_multi($sno, $res_cond->select_no)?> onclick="disp_res_and_or()"><?=htmlspecialchars($sel_text)?><br>
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
											<td align="center"><input type="checkbox" name="select_no[<?=$qno?>][]" <?=value_checked_multi("$sno:$tno", $res_cond->select_no)?> onclick="disp_res_and_or()"></td>
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
		}
	}
?>
							<tr>
								<td bgcolor="#eeeeee">想定出現率</td>
								<td bgcolor="#ffeecc"><input type="text" name="appearance_ratio" size=5 <?=value($pro_enq->appearance_ratio)?>> %</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
			<input type="button" value="　戻る　" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
