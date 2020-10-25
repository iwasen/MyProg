<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/format.php");
include("$inc/my_enquete.php");
include("$inc/csv.php");
$inc = "$top/inc";
include("$inc/mypage.php");

function decode_question_type($code) {
	switch ($code) {
	case 1:
		return 'SA';
	case 2:
		return 'MA';
	case 3:
		return 'FA';
	case 4:
		return 'MatrixSA';
	case 5:
		return 'MatrixMA';
	}
}

if ($enquete_id == '')
	redirect('enq.php');

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);

$filename = "cr_sex_age5_myenq_$enquete_id.csv";
prepare_csv($filename);

// ジョブCD出力
set_csv($csv, 'ジョブCD');
set_csv($csv, $enquete_id);
output_csv($csv);

// ジョブ名出力
set_csv($csv, 'ジョブ名');
set_csv($csv, $job_name);
output_csv($csv);

// アンケート種類出力
set_csv($csv, '種類');
set_csv($csv, $enq_type);
output_csv($csv);

if (is_array($enquete->question)) {
	// 対象者の性別と年齢
	if ($enquete->job_id) {
		$sql = "SELECT sr_sex,sr_age_cd,sr_age_from,sr_age_to"
				. " FROM t_search JOIN t_job ON sr_search_id=jb_search_id"
				. " WHERE jb_job_id=$enquete->job_id";
	} else {
		$sql = "SELECT sr_sex,sr_age_cd"
				. " FROM t_search JOIN t_my_enquete ON sr_search_id=me_search_id"
				. " WHERE me_enquete_id=$enquete_id";
	}
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
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
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$age_ary[] = $fetch->ae_age_cd;
		}
	}

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// 年代名称取得
	$sql = "SELECT ae_age_cd,ae_age_text FROM m_age";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$age_text_ary[$fetch->ae_age_cd] = $fetch->ae_age_text;
	}

	foreach ($enquete->question as $question_no => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
			$total_ary = array();
			$cnt_ary = array();
			$num_ary = array();

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
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$total_ary[$fetch->as_sel_no] = $fetch->cnt;
			}

			// 属性別集計
			$sql = "SELECT as_sel_no,mn_sex,ae_age_cd,COUNT(*) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
					. " JOIN t_monitor ON mn_monitor_id=as_monitor_id"
					. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY ae_age_cd,mn_sex,as_sel_no";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$cnt_ary[$fetch->ae_age_cd][$fetch->mn_sex][$fetch->as_sel_no] = $fetch->cnt;
			}

			// 属性別回答者数
			$sql = "SELECT mn_sex,ae_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
					. " JOIN t_monitor ON mn_monitor_id=as_monitor_id"
					. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY ae_age_cd,mn_sex";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$num_ary[$fetch->ae_age_cd][$fetch->mn_sex] = $fetch->cnt;
			}

			// 空白行２行出力
			output_csv($csv);
			output_csv($csv);

			// 設問文出力
			set_csv($csv, "Ｑ{$question_no}(設問文)");
			set_csv($csv, $question->question_text);
			output_csv($csv);

			// 質問種類と選択肢番号出力
			set_csv($csv, decode_question_type($question->question_type));
			set_csv($csv, '');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++)
				set_csv($csv, $sno);
			set_csv($csv, '計');
			output_csv($csv);

			// 選択肢出力
			set_csv($csv, '');
			set_csv($csv, '');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++)
				set_csv($csv, $question->sel_text[$sno]);
			output_csv($csv);

			// 全体件数出力
			set_csv($csv, '件数');
			set_csv($csv, '全体');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++) {
				if ($question->sel_text[$sno])
					set_csv($csv, (int)$total_ary[$sno]);
				else
					set_csv($csv, '');
			}
			set_csv($csv, $member_cnt);
			output_csv($csv);

			// 属性別件数出力
			foreach ($age_ary as $age) {
				foreach ($sex_ary as $sex) {
					set_csv($csv, '');
					set_csv($csv, $age_text_ary[$age]);
					set_csv($csv, decode_sex($sex));

					for ($sno = 1; $sno <= MAX_SEL; $sno++) {
						if ($question->sel_text[$sno])
							set_csv($csv, (int)$cnt_ary[$age][$sex][$sno]);
						else
							set_csv($csv, '');
					}
					set_csv($csv, (int)$num_ary[$age][$sex]);
					output_csv($csv);
				}
			}

			// 全体％出力
			set_csv($csv, '％');
			set_csv($csv, '全体');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++) {
				if ($question->sel_text[$sno])
					set_csv($csv, round($member_cnt != 0 ? 100.0 * $total_ary[$sno] / $member_cnt : 0, 1));
				else
					set_csv($csv, '');
			}
			set_csv($csv, 100);
			output_csv($csv);

			// 属性別％出力
			foreach ($age_ary as $age) {
				foreach ($sex_ary as $sex) {
					set_csv($csv, '');
					set_csv($csv, $age_text_ary[$age]);
					set_csv($csv, decode_sex($sex));

					for ($sno = 1; $sno <= MAX_SEL; $sno++) {
						if ($question->sel_text[$sno])
							set_csv($csv, round($num_ary[$age][$sex] != 0 ? 100.0 * $cnt_ary[$age][$sex][$sno] / $num_ary[$age][$sex] : 0, 1));
						else
							set_csv($csv, '');
					}
					set_csv($csv, 100);
					output_csv($csv);
				}
			}
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
			foreach ($question->hyousoku as $sno => $hyousoku) {
				$total_ary = array();
				$cnt_ary = array();
				$num_ary = array();

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
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$total_ary[$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// 属性別集計
				$sql = "SELECT ax_hyoutou_no,mn_sex,ae_age_cd,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=ax_monitor_id"
						. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ae_age_cd,mn_sex,ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt_ary[$fetch->ae_age_cd][$fetch->mn_sex][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// 属性別回答者数
				$sql = "SELECT mn_sex,ae_age_cd,COUNT(DISTINCT ax_monitor_id) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=ax_monitor_id"
						. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ae_age_cd,mn_sex";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num_ary[$fetch->ae_age_cd][$fetch->mn_sex] = $fetch->cnt;
				}

				// 空白行２行出力
				output_csv($csv);
				output_csv($csv);

				// 設問文出力
				set_csv($csv, "Ｑ{$question_no}-{$sno}(設問文)");
				set_csv($csv, $question->question_text);
				set_csv($csv, $hyousoku);
				output_csv($csv);

				// 質問種類と選択肢番号出力
				set_csv($csv, decode_question_type($question->question_type));
				set_csv($csv, '');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++)
					set_csv($csv, $tno);
				set_csv($csv, '計');
				output_csv($csv);

				// 選択肢出力
				set_csv($csv, '');
				set_csv($csv, '');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++)
					set_csv($csv, $question->hyoutou[$tno]);
				output_csv($csv);

				// 全体件数出力
				set_csv($csv, '件数');
				set_csv($csv, '全体');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($question->hyoutou[$tno])
						set_csv($csv, (int)$total_ary[$tno]);
					else
						set_csv($csv, '');
				}
				set_csv($csv, $member_cnt);
				output_csv($csv);

				// 属性別件数出力
				foreach ($age_ary as $age) {
					foreach ($sex_ary as $sex) {
						set_csv($csv, '');
						set_csv($csv, $age_text_ary[$age]);
						set_csv($csv, decode_sex($sex));

						for ($tno = 1; $tno <= MAX_SEL; $tno++) {
							if ($question->hyoutou[$tno])
								set_csv($csv, (int)$cnt_ary[$age][$sex][$tno]);
							else
								set_csv($csv, '');
						}
						set_csv($csv, (int)$num_ary[$age][$sex]);
						output_csv($csv);
					}
				}

				// 全体％出力
				set_csv($csv, '％');
				set_csv($csv, '全体');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($question->hyoutou[$tno])
						set_csv($csv, round($member_cnt != 0 ? 100.0 * $total_ary[$tno] / $member_cnt : 0, 1));
					else
						set_csv($csv, '');
				}
				set_csv($csv, 100);
				output_csv($csv);

				// 属性別％出力
				foreach ($age_ary as $age) {
					foreach ($sex_ary as $sex) {
						set_csv($csv, '');
						set_csv($csv, $age_text_ary[$age]);
						set_csv($csv, decode_sex($sex));

						for ($tno = 1; $tno <= MAX_SEL; $tno++) {
							if ($question->hyoutou[$tno])
								set_csv($csv, round($num_ary[$age][$sex] != 0 ? 100.0 * $cnt_ary[$age][$sex][$tno] / $num_ary[$age][$sex] : 0, 1));
							else
								set_csv($csv, '');
						}
						set_csv($csv, 100);
						output_csv($csv);
					}
				}
			}
		}
	}
}
?>