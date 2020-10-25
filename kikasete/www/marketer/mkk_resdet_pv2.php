<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/format.php");
include("$inc/my_enquete.php");

if ($enquete_id == '')
	exit;

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);
?>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" style="font-size:12px;">
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">ジョブCD　：</td>
					<td width="75%"><?=$enquete_id?></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">ジョブ名　：</td>
					<td width="75%"><?=$job_name?></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">種類　：</td>
					<td width="75%"><?=$enq_type?></td>
				</tr>
			</table>
<?
if (is_array($enquete->question)) {
	// 対象者の性別と年齢
	if ($enquete->job_id) {
		$sql = "SELECT sr_sex,sr_age_cd,sr_age_from,sr_age_to"
				. " FROM t_search JOIN t_job ON sr_search_id=jb_search_id"
				. " WHERE jb_job_id=$enquete->job_id";
	} else {
		$sql = "SELECT sr_sex,sr_age_cd,sr_age_from,sr_age_to"
				. " FROM t_search JOIN t_my_enquete ON sr_search_id=me_search_id"
				. " WHERE me_enquete_id=$enquete_id";
	}
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$sex = $fetch->sr_sex;
		$age_cd = $fetch->sr_age_cd;
		$age_from = $fetch->sr_age_from;
		$age_to = $fetch->sr_age_to;
	}
	$sex_ary = $sex ? explode(',', $sex) : array(1,2);
	$sex_cnt = count($sex_ary);

	if ($age_cd)
		$age_ary = explode(',', $age_cd);
	else {
		if (!$age_from)
			$age_from = 0;
		if (!$age_to)
			$age_to = 999;
		$sql = "SELECT ae_age_cd FROM m_age WHERE ae_age_to>=$age_from AND ae_age_from<=$age_to ORDER BY ae_age_cd";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$age_ary[] = $fetch->ae_age_cd;
		}
	}

	// 年代名称取得
	$sql = "SELECT ae_age_cd,ae_age_text FROM m_age";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$age_text_ary[$fetch->ae_age_cd] = $fetch->ae_age_text;
	}

	foreach ($enquete->question as $question_no => $question) {
		$qno = mb_convert_kana($question_no, 'N');

		if ($question->question_type == 4 || $question->question_type == 5) {
			foreach ($question->hyousoku as $sno => $hyousoku) {
				$sno2 = mb_convert_kana($sno, 'N');
?>
			<br>
			<table width="788" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" style="font-size:12px;">
				<tr>
					<td colspan="<?=MAX_SEL + 3?>" width="670" align="center">
						<table style="font-size:12px;">
							<tr>
								<td width="100" align="right" valign="top">
								Ｑ<?=$qno?>(設問文)　：　
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
					<td height=100 align="center" colspan="2">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%" style="font-size:12px;">
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
						. " GROUP BY ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[(int)$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
?>
					<td width="40" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0 style="font-size:12px;">
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
					<td align="center" bgcolor="#eeeeee" colspan="2">
					Ｑ<?=$qno?></td>
					<td width="40" align="center">
<?
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
?>
					<?=$tno < 10 ? mb_convert_kana($tno, 'N') : $tno?></td>
					<td width="40" align="center">
<?
				}
?>
					計</td>
				</tr>
				<tr>
					<td align="center" colspan="2">全体</td>
<?
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($question->hyoutou[$tno]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$tno])?><br><?=format_percent($cnt[$tno], $member_cnt)?></td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="40" align="center"><?=number_format($member_cnt)?><br>100%</td>
				</tr>
<?
				// 属性別集計
				$sql = "SELECT ax_hyoutou_no,mn_sex,ae_age_cd,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ae_age_cd,mn_sex,ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->ae_age_cd][$fetch->mn_sex][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// 属性別回答者数
				$sql = "SELECT mn_sex,ae_age_cd,COUNT(DISTINCT ax_monitor_id) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ae_age_cd,mn_sex";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$num = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num[$fetch->ae_age_cd][$fetch->mn_sex] = $fetch->cnt;
				}

				foreach ($age_ary as $age) {
					$age_text = '';
					foreach ($sex_ary as $sex) {
?>
				<tr>
<?
						if ($age_text == '') {
							$age_text = $age_text_ary[$age];
?>
					<td width="40" align="center" rowspan="<?=$sex_cnt?>"><?=$age_text?></td>
<?
						}
?>
					<td width="40" align="center"><?=decode_sex($sex)?></td>
<?
						for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
							if ($question->hyoutou[$sel_no]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$age][$sex][$sel_no])?><br><?=format_percent($cnt[$age][$sex][$sel_no], $num[$age][$sex])?></td>
<?
							} else {
?>
					<td><br></td>
<?
							}
						}
?>
					<td width="40" align="center"><?=number_format($num[$age][$sex])?><br>100%</td>
				</tr>
<?
					}
				}
?>
			</table>
			<br>
<?
			}
		} else {
?>
			<br>
			<table width="788" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" style="font-size:12px;">
				<tr>
					<td colspan="<?=MAX_SEL + 3?>" width="670" align="center">
						<table style="font-size:12px;">
							<tr>
								<td width="100" align="right" valign="top">
								Ｑ<?=$qno?>(設問文)　：　
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
								</td>
							</tr>
<?
			if (is_array($question->sel_text) && $question->question_type != 3 && $question->question_type != 6 && $question->question_type != 8) {
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
								<?=$sno?>．<?=htmlspecialchars($sel_text)?>
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
					<td width="100" height=100 align="center" colspan="2">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%" style="font-size:12px;">
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
					. " GROUP BY as_sel_no";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			$cnt = array();
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$cnt[$fetch->as_sel_no] = $fetch->cnt;
			}

			for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
?>
					<td width="40" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0 style="font-size:12px;">
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
					<td width="100" align="center" bgcolor="#eeeeee" colspan="2">
					Ｑ<?=$qno?></td>
					<td width="40" align="center">
<?
			for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
?>
					<?=$sel_no < 10 ? mb_convert_kana($sel_no, 'N') : $sel_no?></td>
					<td width="40" align="center">
<?
			}
?>
					計</td>
				</tr>
				<tr>
					<td width="100" align="center" colspan="2">全体</td>
<?
			for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
				if ($question->sel_text[$sel_no]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$sel_no])?><br><?=format_percent($cnt[$sel_no], $member_cnt)?></td>
<?
				} else {
?>
					<td><br></td>
<?
				}
			}
?>
					<td width="40" align="center"><?=number_format($member_cnt)?><br>100%</td>
				</tr>
<?
				// 属性別集計
				$sql = "SELECT as_sel_no,mn_sex,ae_age_cd,COUNT(*) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY ae_age_cd,mn_sex,as_sel_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->ae_age_cd][$fetch->mn_sex][$fetch->as_sel_no] = $fetch->cnt;
				}

				// 属性別回答者数
				$sql = "SELECT mn_sex,ae_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY ae_age_cd,mn_sex";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$num = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num[$fetch->ae_age_cd][$fetch->mn_sex] = $fetch->cnt;
				}


			foreach ($age_ary as $age) {
				$age_text = '';
				foreach ($sex_ary as $sex) {
?>
				<tr>
<?
					if ($age_text == '') {
						$age_text = $age_text_ary[$age];
?>
					<td width="40" align="center" rowspan="<?=$sex_cnt?>"><?=$age_text?></td>
<?
					}
?>
					<td width="40" align="center"><?=decode_sex($sex)?></td>
<?
					for ($sel_no = 1; $sel_no <= MAX_SEL; $sel_no++) {
						if ($question->sel_text[$sel_no]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$age][$sex][$sel_no])?><br><?=format_percent($cnt[$age][$sex][$sel_no], $num[$age][$sex])?></td>
<?
						} else {
?>
					<td><br></td>
<?
						}
					}
?>
					<td width="40" align="center"><?=number_format($num[$age][$sex])?><br>100%</td>
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