<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

define('MAX_SEL', 21);

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
$enquete_id = $myp->enquete_id;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- myパートナー -->

			<p>
			<table border=0 cellspacing=0 cellpadding=1 width="100%">
				<tr>
					<td>■<?=htmlspecialchars($myp->room_name)?>（<?=$myp->pjt_id?>）</td>
				</tr>
			</table>
			</p>

			<div align="center">
			<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
			<table border=0 cellspacing=2 cellpadding=3 width="80%">
				<tr>
					<td colspan=2>■集計結果</td>
				</tr>
				<tr>
					<td>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $question_no => $question) {
		$qno = mb_convert_kana($question_no, 'N');

		if ($question->question_type == 4 || $question->question_type == 5) {
			foreach ($question->hyousoku as $sno => $hyousoku) {
				$sno2 = mb_convert_kana($sno, 'N');
?>
			<br>
			<table width="99%" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_SEL + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								Ｑ<?=$qno?>-<?=$sno2?>(設問文)　：　
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
								</td>
							</tr>
							<tr>
								<td width="100" align="right" valign="top">
								(表側)　：　
								</td>
								<td width="550">
								<?=$sno2?>．<?=htmlspecialchars($hyousoku)?>
								</td>
							</tr>
<?
				if (is_array($question->hyoutou)) {
					foreach ($question->hyoutou as $tno => $hyoutou) {
						$tno2 = mb_convert_kana($tno, 'N');
?>
							<tr>
<?
						if ($tno == 1) {
?>
								<td width="100" align="right" rowspan="<?=count($question->hyoutou)?>" valign="top">
								(表頭)　：　
								</td>
<?
						}
?>
								<td width="550">
								<?=$tno2?>．<?=htmlspecialchars($hyoutou)?>
								</td>
							</tr>
<?
					}
				}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td width="40" height=100 align="center">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
							<tr><td align="right" height=30>100%</td></tr>
							<tr><td align="right" height=30>75%</td></tr>
							<tr><td align="right" height=30>50%</td></tr>
							<tr><td align="right" height=30>25%</td></tr>
							<tr><td align="right" height=15></td></tr>
						</table>
					</td>
<?
				// 回答者数
				$sql = "SELECT COUNT(DISTINCT ax_monitor_id)"
						. " FROM t_ans_matrix"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no";
				$member_cnt = db_fetch1($sql);

				// 全体集計
				$sql = "SELECT ax_hyoutou_no,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ax_hyoutou_no"
						. " ORDER BY ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$row = 0;
				$cnt = array();
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($row < $nrow) {
						$fetch = pg_fetch_object($result, $row);
						if ($tno == $fetch->ax_hyoutou_no) {
							$cnt[$tno] = $fetch->cnt;
							$row++;
						}
					}
				}

				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
?>
					<td width="40" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
					if ($question->hyoutou[$tno] && $cnt[$tno]) {
?>
								<td width=15 height=<?=floor($cnt[$tno] / $member_cnt * 120)?> bgcolor="#9999ff"></td>
<?
					} else
						echo "<td><br></td>\n";
?>
							</tr>
						</table>
					</td>
<?
				}
?>
					<td width="40" height=100 align="center"><br></td>
				</tr>
				<tr>
					<td width="40" align="center" bgcolor="#eeeeee">
					Ｑ<?=$qno?>-<?=$sno?></td>
<?
				for ($q = 1; $q <= MAX_SEL; $q++) {
?>
					<td width="40" align="center">
					<?=$q < 10 ? mb_convert_kana($q, 'N') : $q?></td>
<?
				}
?>
					<td width="40" align="center">
					計</td>
				</tr>
			</table>
			<br>
<?
			}
		} else {
?>
			<br>
			<table width="99%" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_SEL + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								Ｑ<?=$qno?>(設問文)　：　
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
								</td>
							</tr>
<?
			if (is_array($question->sel_text)) {
				$tmp_ary = array_keys($question->sel_text);
				$last_sel_no = $tmp_ary[count($tmp_ary) - 1];
				foreach ($question->sel_text as $sel_no => $sel_text) {
					$sno = mb_convert_kana($sel_no, 'N');
?>
							<tr>
<?
					if ($sel_no == 1) {
?>
								<td width="100" align="right" rowspan="<?=MAX_SEL?>" valign="top">
								(選択肢)　：　
								</td>
<?
					}
?>
								<td width="550">
<?
					if ($question->fa_flag == 't' && $sel_no == $last_sel_no || $question->question_type == 3) {
?>
								<a href="mp_total_fa.php?pjt_id=<?=$pjt_id?>&question_no=<?=$question_no?>&sel_no=<?=$sel_no?>" target="_blank" title="新しいウィンドウで定性回答を表示します"><?=$sno?>．<?=htmlspecialchars($sel_text)?></a>
<?
					} else {
?>
								<?=$sno?>．<?=htmlspecialchars($sel_text)?>
<?
					}
?>
								</td>
							</tr>
<?
				}
			} elseif ($question->question_type == 3) {
?>
							<tr>
								<td width="100"></td>
								<td align="right"><br><a href="mp_total_fa.php?pjt_id=<?=$pjt_id?>&question_no=<?=$question_no?>&sel_no=0" target="_blank" title="新しいウィンドウで定性回答の一覧を表示します">Ｑ<?=$qno?>の回答はこちら</a></td>
							</tr>
<?
			}
?>
						</table>
					</td>
				</tr>
<?
			if ($question->question_type != 3) {
?>
				<tr>
					<td width="40" height=100 align="center">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
							<tr><td align="right" height=30>100%</td></tr>
							<tr><td align="right" height=30>75%</td></tr>
							<tr><td align="right" height=30>50%</td></tr>
							<tr><td align="right" height=30>25%</td></tr>
							<tr><td align="right" height=15></td></tr>
						</table>
					</td>
<?
				// 回答者数
				$sql = "SELECT COUNT(DISTINCT as_monitor_id)"
						. " FROM t_ans_select"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no";
				$member_cnt = db_fetch1($sql);

				// 全体集計
				$sql = "SELECT as_sel_no,COUNT(*) AS cnt"
						. " FROM t_ans_select"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY as_sel_no"
						. " ORDER BY as_sel_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$row = 0;
				$cnt = array();
				for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
					if ($row < $nrow) {
						$fetch = pg_fetch_object($result, $row);
						if ($sel_no == $fetch->as_sel_no) {
							$cnt[$sel_no] = $fetch->cnt;
							$row++;
						}
					}
				}

				for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
?>
					<td width="40" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
					if ($question->sel_text[$sel_no] && $cnt[$sel_no]) {
?>
								<td width=15 height=<?=floor($cnt[$sel_no] / $member_cnt * 120)?> bgcolor="#9999ff"></td>
<?
					} else
						echo "<td><br></td>\n";
?>
							</tr>
						</table>
					</td>
<?
				}
?>
					<td width="40" height=100 align="center"><br></td>
				</tr>
				<tr>
					<td width="40" align="center" bgcolor="#eeeeee">
					Ｑ<?=$qno?></td>
<?
				for ($q = 1; $q <= MAX_SEL; $q++) {
?>
					<td width="40" align="center">
					<?=$q < 10 ? mb_convert_kana($q, 'N') : $q?></td>
<?
				}
?>
					<td width="40" align="center">
					計</td>
				</tr>
<?
			}
?>
			</table>
			<br>
<?
		}
	}
}
?>
					</td>
				</tr>
			</table>
			<input type="button" value="CSV取得" onclick="location.href='mp_csv_sel.php?pjt_id=<?=$pjt_id?>&all=1'">
			<input type="button" value="CSV取得(0,1)" onclick="location.href='mp_csv_sel2.php?pjt_id=<?=$pjt_id?>&all=1'">
			<input type="button" value="設問間クロス集計" onclick="location.href='mp_crs.php?enquete_id=<?=$enquete_id?>'">
			<input type="button" value="　戻る　" onclick="history.back()">
			</form>
			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
