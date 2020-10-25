<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/format.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/mye_check.php");
?>
<? staff_header('集計結果表示') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪集計結果表示≫</font></td>
		<td align="right">
			<input type="button" value="集計結果のダウンロード" onclick="location.href='csv_age.php?enquete_id=<?=$enquete_id?>'">
		</td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						<font size="3" class="honbun1">
						<a href="resdet_sex.php?enquete_id=<?=$enquete_id?>">性別</a>　｜　<strong>年代</strong>　｜　<a href="resdet_sex_age.php?enquete_id=<?=$enquete_id?>">性別・年代（１０歳毎）</a>　｜　<a href="resdet_pv.php?enquete_id=<?=$enquete_id?>">年代（５歳毎）・性別</a>　｜　<a href="enqdet1.php?enquete_id=<?=$enquete_id?>">アンケート詳細表示に戻る</a></font>
					</td>
				</tr>
			</table>
			<br><br>
<?
if ($enquete_id == '')
	exit;

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);
?>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee"><font size="3" class="honbun1">ジョブCD　：</font></td>
					<td width="75%"><font size="3" class="honbun1"><?=$enquete_id?></font></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee"><font size="3" class="honbun1">ジョブ名　：</font></td>
					<td width="75%"><font size="3" class="honbun1"><?=$job_name?></font></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee"><font size="3" class="honbun1">種類　：</font></td>
					<td width="75%"><font size="3" class="honbun1"><?=$enq_type?></font></td>
				</tr>
			</table>
<?
if (is_array($enquete->question)) {
	// 対象者の年齢
	if ($enquete->job_id) {
		$sql = "SELECT sr_age_cd,sr_age_from,sr_age_to"
				. " FROM t_search JOIN t_job ON sr_search_id=jb_search_id"
				. " WHERE jb_job_id=$enquete->job_id";
	} else {
		$sql = "SELECT sr_age_cd"
				. " FROM t_search JOIN t_my_enquete ON sr_search_id=me_search_id"
				. " WHERE me_enquete_id=$enquete_id";
	}
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$age_cd = $fetch->sr_age_cd;
		$age_from = $fetch->sr_age_from;
		$age_to = $fetch->sr_age_to;
	}

	if ($age_cd) {
		// １０歳刻みに変換
		foreach (explode(',', $age_cd) as $age_cd)
			$age_ary[] = (int)($age_cd / 2) + 1;
		$age_ary = array_unique($age_ary);
	} else {
		if (!$age_from)
			$age_from = 0;
		if (!$age_to)
			$age_to = 999;
		$sql = "SELECT ge_age_cd FROM m_age10 WHERE ge_age_to>=$age_from AND ge_age_from<=$age_to ORDER BY ge_age_cd";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$age_ary[] = $fetch->ge_age_cd;
		}
	}

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// 年代名称取得
	$sql = "SELECT ge_age_cd,ge_age_text FROM m_age10";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$age_text_ary[$fetch->ge_age_cd] = $fetch->ge_age_text;
	}

	foreach ($enquete->question as $question_no => $question) {
		$qno = mb_convert_kana($question_no, 'N');

		if ($question->question_type == 4 || $question->question_type == 5) {
			foreach ($question->hyousoku as $sno => $hyousoku) {
				$sno2 = mb_convert_kana($sno, 'N');
?>
			<br>
			<table width="680" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_SEL + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">Ｑ<?=$qno?>(設問文)　：　</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2"><?=nl2br(htmlspecialchars($question->question_text))?></font>
								</td>
							</tr>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">(表側)　：　</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2"><?=$sno2?>．<?=htmlspecialchars($hyousoku)?></font>
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
								<font size="2" class="honbun2">(表頭)　：　</font>
								</td>
<?
						}
?>
								<td width="550">
								<font size="2" class="honbun2"><?=$tno2?>．<?=htmlspecialchars($hyoutou)?></font>
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
					<td height=100 align="center">
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
						. " GROUP BY ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[(int)$fetch->ax_hyoutou_no] = $fetch->cnt;
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
					<td align="center" bgcolor="#eeeeee">
					<font size="2" class="honbun2">Ｑ<?=$qno?></font></td>
					<td width="40" align="center">
<?
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
?>
					<font size="2" class="honbun2"><?=$tno < 10 ? mb_convert_kana($tno, 'N') : $tno?></font></td>
					<td width="40" align="center">
<?
				}
?>
					<font size="2" class="honbun2">計</font></td>
				</tr>
				<tr>
					<td align="center"><font size="2" class="honbun2">全体</font></td>
<?
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($question->hyoutou[$tno]) {
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($cnt[$tno])?><br><?=format_percent($cnt[$tno], $member_cnt)?></font></td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($member_cnt)?><br>100%</font></td>
				</tr>
<?
				// 属性別集計
				$sql = "SELECT ax_hyoutou_no,ge_age_cd,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=ax_monitor_id"
						. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ge_age_cd,ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->ge_age_cd][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// 属性別回答者数
				$sql = "SELECT ge_age_cd,COUNT(DISTINCT ax_monitor_id) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=ax_monitor_id"
						. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ge_age_cd";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				$num = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num[$fetch->ge_age_cd] = $fetch->cnt;
				}

				foreach ($age_ary as $age) {
?>
				<tr>
					<td align="center"><font size="2" class="honbun2"><nobr><?=$age_text_ary[$age]?></nobr></font></td>
<?
					for ($tno = 1; $tno <= MAX_SEL; $tno++) {
						if ($question->hyoutou[$tno]) {
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($cnt[$age][$tno])?><br><?=format_percent($cnt[$age][$tno], $num[$age])?></font></td>
<?
						} else {
?>
					<td><br></td>
<?
						}
					}
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($num[$age])?><br>100%</font></td>
				</tr>
<?
				}
?>
			</table>
			<br>
<?
			}
		} else {
?>
			<br>
			<table width="680" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_SEL + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">Ｑ<?=$qno?>(設問文)　：　</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2"><?=nl2br(htmlspecialchars($question->question_text))?></font>
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
								<font size="2" class="honbun2">(選択肢)　：　</font>
								</td>
<?
					}
?>
								<td width="550">
<?
					if ($question->fa_flag == 't' && $sel_no == $last_sel_no || $question->question_type == 3) {
?>
								<font size="2" class="honbun2"><a href="resdet_fa.php?enquete_id=<?=$enquete_id?>&question_no=<?=$question_no?>&sel_no=<?=$sel_no?>&type=age" target="_blank" title="新しいウィンドウで定性回答を表示します"><?=$sno?>．<?=htmlspecialchars($sel_text)?></a></font>
<?
					} else {
?>
								<font size="2" class="honbun2"><?=$sno?>．<?=htmlspecialchars($sel_text)?></font>
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
								<td align="right"><br><font size="2" class="honbun2"><a href="resdet_fa.php?enquete_id=<?=$enquete_id?>&question_no=<?=$question_no?>&sel_no=0&type=age" target="_blank" title="新しいウィンドウで定性回答の一覧を表示します">Ｑ<?=$qno?>の回答はこちら</a></font></td>
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
					<td height=100 align="center">
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
						. " GROUP BY as_sel_no";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->as_sel_no] = $fetch->cnt;
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
					<td align="center" bgcolor="#eeeeee">
					<font size="2" class="honbun2">Ｑ<?=$qno?></font></td>
					<td width="40" align="center">
<?
				for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
?>
					<font size="2" class="honbun2"><?=$sel_no < 10 ? mb_convert_kana($sel_no, 'N') : $sel_no?></font></td>
					<td width="40" align="center">
<?
			}
?>
					<font size="2" class="honbun2">計</font></td>
				</tr>
				<tr>
					<td align="center"><font size="2" class="honbun2">全体</font></td>
	<?
				for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
					if ($question->sel_text[$sel_no]) {
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($cnt[$sel_no])?><br><?=format_percent($cnt[$sel_no], $member_cnt)?></font></td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($member_cnt)?><br>100%</font></td>
				</tr>
<?
				// 属性別集計
				$sql = "SELECT as_sel_no,ge_age_cd,COUNT(*) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=as_monitor_id"
						. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY ge_age_cd,as_sel_no";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->ge_age_cd][$fetch->as_sel_no] = $fetch->cnt;
				}

				// 属性別回答者数
				$sql = "SELECT ge_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=as_monitor_id"
						. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY ge_age_cd";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				$num = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num[$fetch->ge_age_cd] = $fetch->cnt;
				}

				foreach ($age_ary as $age) {
?>
				<tr>
					<td align="center"><font size="2" class="honbun2"><nobr><?=$age_text_ary[$age]?></nobr></font></td>
<?
					for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
						if ($question->sel_text[$sel_no]) {
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($cnt[$age][$sel_no])?><br><?=format_percent($cnt[$age][$sel_no], $num[$age])?></font></td>
<?
						} else {
?>
					<td><br></td>
<?
						}
					}
?>
					<td width="40" align="center"><font size="2" class="honbun2"><?=number_format($num[$age])?><br>100%</font></td>
				</tr>
<?
				}
			}
?>
			</table>
			<br>
<?
		}
	}
}
?>
			<br>
		</td>
	</tr>
</table>

<? staff_footer() ?>
