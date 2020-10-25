<?
function percent($a, $b) {
	return ($b == 0) ? 0 : floor($a / $b * 100);
}

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
	foreach ($enquete->question as $question_no => $question) {
		$qno = mb_convert_kana($question_no, 'N')
?>
			<br><br>
			<table width="680" border="1" cellspacing="0" cellpadding="2" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="13" width="670" align="center">
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
			foreach ($question->sel_text as $sel_no => $set_text) {
				$sno = mb_convert_kana($sel_no, 'N');
?>
							<tr>
<?
				if ($sel_no == 1) {
?>
								<td width="100" align="right" rowspan="10" valign="top">
								<font size="2" class="honbun2">(選択肢)　：　</font>
								</td>
<?
				}
?>
								<td width="550">
								<font size="2" class="honbun2"><?=$sno?>．<?=htmlspecialchars($set_text)?></font>
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
		$nrow = pg_num_rows($result);
		$row = 0;
		$cnt = array();
		for ($sel_no = 1; $sel_no <= 10; $sel_no++) {
			if ($row < $nrow) {
				$fetch = pg_fetch_object($result, $row);
				if ($sel_no == $fetch->as_sel_no) {
					$cnt[$sel_no] = $fetch->cnt;
					$row++;
				}
			}
		}

		for ($sel_no = 1; $sel_no <= 10; $sel_no++) {
?>
					<td width="50" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
			if ($question->sel_text[$sel_no] && $cnt[$sel_no]) {
?>
								<td width=20 height=<?=floor($cnt[$sel_no] / $member_cnt * 120)?> bgcolor="#9999ff"></td>
<?
			}
?>
							</tr>
						</table>
					</td>
<?
		}
?>
					<td width="50" height=100 align="center"><br></td>
				</tr>
				<tr>
					<td width="100" align="center" bgcolor="#eeeeee" colspan="2">
					<font size="2" class="honbun2">Ｑ<?=$qno?></font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">１</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">２</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">３</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">４</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">５</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">６</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">７</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">８</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">９</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">10</font></td>
					<td width="50" align="center">
					<font size="2" class="honbun2">計</font></td>
				</tr>
				<tr>
					<td width="100" align="center" colspan="2"><font size="2" class="honbun2">全体</font></td>
<?
		for ($sel_no = 1; $sel_no <= 10; $sel_no++) {
			if ($question->sel_text[$sel_no]) {
?>
					<td width="50" align="center"><font size="2" class="honbun2"><?=number_format($cnt[$sel_no])?><br><?=percent($cnt[$sel_no], $member_cnt)?>%</font></td>
<?
			} else {
?>
					<td><br></td>
<?
			}
		}
?>
					<td width="50" align="center"><font size="2" class="honbun2"><?=number_format($member_cnt)?><br>100%</font></td>
				</tr>
<?
		// 対象者の性別と年齢
		$sql = "SELECT sr_sex,sr_age_cd"
				. " FROM t_search JOIN t_my_enquete ON sr_search_id=me_search_id"
				. " WHERE me_enquete_id=$enquete_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$sex = $fetch->sr_sex;
			$age_cd = $fetch->sr_age_cd;
		}
		$sex_ary = $sex ? explode(',', $sex) : array(1,2);
		$age_ary = $age_cd ? explode(',', $age_cd) : array(1,2,3,4,5,6,7,8,9,10,11,12,13,14);
		$sex_cnt = count($sex_ary);

		// アンケート開始日時取得
		$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$start_date = "'" . format_date(db_fetch1($sql)) . "'";

		// 属性別集計
		$sql = "SELECT as_sel_no,mn_sex,ae_age_cd,COUNT(*) AS cnt"
				. " FROM t_ans_select JOIN t_monitor ON as_monitor_id=mn_monitor_id"
				. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
				. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
				. " GROUP BY ae_age_cd,mn_sex,as_sel_no"
				. " ORDER BY ae_age_cd,mn_sex,as_sel_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);

		// 属性別回答者数
		$sql = "SELECT mn_sex,ae_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
				. " FROM t_ans_select JOIN t_monitor ON as_monitor_id=mn_monitor_id"
				. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
				. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
				. " GROUP BY ae_age_cd,mn_sex"
				. " ORDER BY ae_age_cd,mn_sex";
		$result2 = db_exec($sql);
		$nrow2 = pg_num_rows($result2);

		$row = 0;
		$row2 = 0;
		foreach ($age_ary as $age) {
			$age_text = '';
			foreach ($sex_ary as $sex) {
?>
				<tr>
<?
				if ($age_text == '') {
					$sql = "SELECT ae_age_text FROM m_age WHERE ae_age_cd=$age";
					$age_text = db_fetch1($sql);
?>
					<td width="50" align="center" rowspan="<?=$sex_cnt?>"><font size="2" class="honbun2"><?=$age_text?></font></td>
<?
				}
?>
					<td width="50" align="center"><font size="2" class="honbun2"><?=decode_sex($sex)?></font></td>
<?
				$monitor_cnt = 0;
				if ($row2 < $nrow2) {
					$fetch2 = pg_fetch_object($result2, $row2);
					if ($age == $fetch2->ae_age_cd AND $sex == $fetch2->mn_sex) {
						$monitor_cnt = $fetch2->cnt;
						$row2++;
					}
				}

				$cnt = array();
				for ($sel_no = 1; $sel_no <= 10; $sel_no++) {
					if ($row < $nrow) {
						$fetch = pg_fetch_object($result, $row);
						if ($age == $fetch->ae_age_cd AND $sex == $fetch->mn_sex AND $sel_no == $fetch->as_sel_no) {
							$cnt[$sel_no] = $fetch->cnt;
							$row++;
						}
					}
				}

				for ($sel_no = 1; $sel_no <= 10; $sel_no++) {
					if ($question->sel_text[$sel_no]) {
?>
					<td width="50" align="center"><font size="2" class="honbun2"><?=number_format($cnt[$sel_no])?><br><?=percent($cnt[$sel_no], $monitor_cnt)?>%</font></td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="50" align="center"><font size="2" class="honbun2"><?=number_format($monitor_cnt)?><br>100%</font></td>
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
}
?>
