<?
function percent($a, $b) {
	return ($b == 0) ? 0 : floor($a / $b * 100);
}

if ($pro_enq_no == '')
	redirect('pe_index.php');

$owner_id = $_SESSION['ss_owner_id'];
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$enquete = &$pro_enq->enquete2;
$enquete_id = $pro_enq->enquete2_id;

$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);
?>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
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
								Ｑ<?=$qno?>(設問文)　：　
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
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
								(選択肢)　：　
								</td>
<?
				}
?>
								<td width="550">
								<?=$sno?>．<?=htmlspecialchars($set_text)?>
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
				. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
				. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no";
		$member_cnt = db_fetch1($sql);

		// 全体集計
		$sql = "SELECT as_sel_no,COUNT(*) AS cnt"
				. " FROM t_ans_select"
				. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
				. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
				. " GROUP BY as_sel_no"
				. " ORDER BY as_sel_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
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
								<td width=13 height=<?=floor($cnt[$sel_no] / $member_cnt * 120)?> bgcolor="#9999ff"></td>
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
					Ｑ<?=$qno?></td>
					<td width="50" align="center">
					１</td>
					<td width="50" align="center">
					２</td>
					<td width="50" align="center">
					３</td>
					<td width="50" align="center">
					４</td>
					<td width="50" align="center">
					５</td>
					<td width="50" align="center">
					６</td>
					<td width="50" align="center">
					７</td>
					<td width="50" align="center">
					８</td>
					<td width="50" align="center">
					９</td>
					<td width="50" align="center">
					10</td>
					<td width="50" align="center">
					計</td>
				</tr>
				<tr>
					<td width="100" align="center" colspan="2">全体</td>
<?
		for ($sel_no = 1; $sel_no <= 10; $sel_no++) {
			if ($question->sel_text[$sel_no]) {
?>
					<td width="50" align="center"><?=number_format($cnt[$sel_no])?><br><?=percent($cnt[$sel_no], $member_cnt)?>%</td>
<?
			} else {
?>
					<td><br></td>
<?
			}
		}
?>
					<td width="50" align="center"><?=number_format($member_cnt)?><br>100%</td>
				</tr>
<?
		// 対象者の性別と年齢
		$sql = "SELECT sr_sex,sr_age_cd"
				. " FROM t_search JOIN t_pro_enquete ON sr_search_id=mep_search_id"
				. " WHERE mep_enquete_id=$enquete_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$sex = $fetch->sr_sex;
			$age_cd = $fetch->sr_age_cd;
		}
		$sex_ary = $sex ? explode(',', $sex) : array(1,2);
		$age_ary = $age_cd ? explode(',', $age_cd) : array(1,2,3,4,5,6,7,8,9,10,11,12,13,14);
		$sex_cnt = count($sex_ary);

		// アンケート開始日時取得
		$start_date = sql_date(format_date($pro_enq->enquete->start_date));

		// 属性別集計
		$sql = "SELECT as_sel_no,mn_sex,ae_age_cd,COUNT(*) AS cnt"
				. " FROM t_ans_select JOIN t_monitor ON as_monitor_id=mn_monitor_id"
				. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
				. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
				. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
				. " GROUP BY ae_age_cd,mn_sex,as_sel_no"
				. " ORDER BY ae_age_cd,mn_sex,as_sel_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);

		// 属性別回答者数
		$sql = "SELECT mn_sex,ae_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
				. " FROM t_ans_select JOIN t_monitor ON as_monitor_id=mn_monitor_id"
				. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
				. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
				. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
				. " GROUP BY ae_age_cd,mn_sex"
				. " ORDER BY ae_age_cd,mn_sex";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);

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
					<td width="50" align="center" rowspan="<?=$sex_cnt?>"><?=$age_text?></td>
<?
				}
?>
					<td width="50" align="center"><?=decode_sex($sex)?></td>
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
					<td width="50" align="center"><?=number_format($cnt[$sel_no])?><br><?=percent($cnt[$sel_no], $monitor_cnt)?>%</td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="50" align="center"><?=number_format($monitor_cnt)?><br>100%</td>
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
