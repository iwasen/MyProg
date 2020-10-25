<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・リアルタイム集計
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global2('mypartner', 'Myパートナー管理', 'リアルタイム集計', BACK_TOP);

define('MAX_QUESTION', 20);

$myp = new my_partner_class;
$myp->read_db($pjt_id);
$enquete = &$myp->enquete;
$enquete_id = $myp->enquete_id;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■<?=htmlspecialchars($myp->room_name)?>（<?=$myp->pjt_id?>）</td>
	</tr>
</table>

<form method="post" name="form1" action="update.php" onsubmit="return onsubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="720">
	<tr>
		<td class="m0" colspan=2>■集計結果</td>
	</tr>
	<tr>
		<td class="n1">
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $question_no => $question) {
		$qno = mb_convert_kana($question_no, 'N');

		if ($question_no > 1) {
?>
			<br>
<?
		}

		if ($question->question_type == 4 || $question->question_type == 5) {
			foreach ($question->hyousoku as $sno => $hyousoku) {
				$sno2 = mb_convert_kana($sno, 'N');
?>
			<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="720" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_QUESTION + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2">Ｑ<?=$qno?>-<?=$sno2?>(設問文)　：　</font>
								</td>
								<td width="550">
								<font size="2"><?=nl2br(htmlspecialchars($question->question_text))?></font>
								</td>
							</tr>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2">(表側)　：　</font>
								</td>
								<td width="550">
								<font size="2"><?=$sno2?>．<?=htmlspecialchars($hyousoku)?></font>
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
								<font size="2">(表頭)　：　</font>
								</td>
<?
						}
?>
								<td width="550">
								<font size="2"><?=$tno2?>．<?=htmlspecialchars($hyoutou)?></font>
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
							<tr><td align="right" height=30><font size="2">100%</font></td></tr>
							<tr><td align="right" height=30><font size="2">75%</font></td></tr>
							<tr><td align="right" height=30><font size="2">50%</font></td></tr>
							<tr><td align="right" height=30><font size="2">25%</font></td></tr>
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
				for ($tno = 1; $tno <= MAX_QUESTION; $tno++) {
					if ($row < $nrow) {
						$fetch = pg_fetch_object($result, $row);
						if ($tno == $fetch->ax_hyoutou_no) {
							$cnt[$tno] = $fetch->cnt;
							$row++;
						}
					}
				}

				for ($tno = 1; $tno <= MAX_QUESTION; $tno++) {
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
					<font size="2">Ｑ<?=$qno?>-<?=$sno?></font></td>
<?
				for ($q = 1; $q <= MAX_QUESTION; $q++) {
?>
					<td width="40" align="center">
					<font size="2"><?=$q < 10 ? mb_convert_kana($q, 'N') : $q?></font></td>
<?
				}
?>
					<td width="40" align="center">
					<font size="2">計</font></td>
				</tr>
			</table>
<?
			}
		} else {
?>
			<table border="1" cellspacing="0" cellpadding="0" bgcolor="#ffffff" width="720" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_QUESTION + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2">Ｑ<?=$qno?>(設問文)　：　</font>
								</td>
								<td width="550">
								<font size="2"><?=nl2br(htmlspecialchars($question->question_text))?></font>
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
								<td width="100" align="right" rowspan="<?=MAX_QUESTION?>" valign="top">
								<font size="2">(選択肢)　：　</font>
								</td>
<?
					}
?>
								<td width="550">
<?
					if ($question->fa_flag == 't' && $sel_no == $last_sel_no || $question->question_type == 3) {
?>
								<font size="2"><a href="fa.php?pjt_id=<?=$pjt_id?>&question_no=<?=$question_no?>&sel_no=<?=$sel_no?>" target="_blank" title="新しいウィンドウで定性回答を表示します"><?=$sno?>．<?=htmlspecialchars($sel_text)?></a></font>
<?
					} else {
?>
								<font size="2"><?=$sno?>．<?=htmlspecialchars($sel_text)?></font>
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
								<td align="right"><br><font size="2"><a href="fa.php?pjt_id=<?=$pjt_id?>&question_no=<?=$question_no?>&sel_no=0" target="_blank" title="新しいウィンドウで定性回答の一覧を表示します">Ｑ<?=$qno?>の回答はこちら</a></font></td>
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
							<tr><td align="right" height=30><font size="2">100%</font></td></tr>
							<tr><td align="right" height=30><font size="2">75%</font></td></tr>
							<tr><td align="right" height=30><font size="2">50%</font></td></tr>
							<tr><td align="right" height=30><font size="2">25%</font></td></tr>
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
				for ($sel_no = 1; $sel_no <= MAX_QUESTION; $sel_no++) {
					if ($row < $nrow) {
						$fetch = pg_fetch_object($result, $row);
						if ($sel_no == $fetch->as_sel_no) {
							$cnt[$sel_no] = $fetch->cnt;
							$row++;
						}
					}
				}

				for ($sel_no = 1; $sel_no <= MAX_QUESTION; $sel_no++) {
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
					<font size="2">Ｑ<?=$qno?></font></td>
<?
				for ($q = 1; $q <= MAX_QUESTION; $q++) {
?>
					<td width="40" align="center">
					<font size="2"><?=$q < 10 ? mb_convert_kana($q, 'N') : $q?></font></td>
<?
				}
?>
					<td width="40" align="center">
					<font size="2">計</font></td>
				</tr>
<?
			}
?>
			</table>
<?
		}
	}
}
?>
		</td>
	</tr>
	<tr>
		<td align="center">
			<br>
			<input type="button" value="CSV取得" onclick="location.href='csv_sel.php?pjt_id=<?=$pjt_id?>&all=1'">
			<input type="button" value="CSV取得(0,1)" onclick="location.href='csv_sel2.php?pjt_id=<?=$pjt_id?>&all=1'">
			<input type="button" value="設問間クロス集計" onclick="location.href='crs.php?enquete_id=<?=$enquete_id?>'">
			<input type="button" value="　戻る　" onclick="history.back()">
		</td>
	</tr>
</table>
</form>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
