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

$filename = "cr_sex_age10_myenq_$enquete_id.csv";
prepare_csv($filename);

// �����CD����
set_csv($csv, '�����CD');
set_csv($csv, $enquete_id);
output_csv($csv);

// �����̾����
set_csv($csv, '�����̾');
set_csv($csv, $job_name);
output_csv($csv);

// ���󥱡��ȼ������
set_csv($csv, '����');
set_csv($csv, $enq_type);
output_csv($csv);

if (is_array($enquete->question)) {
	// �оݼԤ����̤�ǯ��
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

	if ($age_cd) {
		// �����й�ߤ��Ѵ�
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
	$age_cnt = count($age_ary);

	// ���󥱡��ȳ�����������
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// ǯ��̾�μ���
	$sql = "SELECT ge_age_cd,ge_age_text FROM m_age10";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$age_text_ary[$fetch->ge_age_cd] = $fetch->ge_age_text;
	}

	foreach ($enquete->question as $question_no => $question) {
		if ($question->question_type == 1 || $question->question_type == 2) {
			$total_ary = array();
			$cnt_ary = array();
			$num_ary = array();

			// �����Կ�
			$sql = "SELECT COUNT(DISTINCT as_monitor_id)"
					. " FROM t_ans_select"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no";
			$member_cnt = db_fetch1($sql);

			// ���ν���
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

			// °���̽���
			$sql = "SELECT as_sel_no,mn_sex,ge_age_cd,COUNT(*) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
					. " JOIN t_monitor ON mn_monitor_id=as_monitor_id"
					. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY mn_sex,ge_age_cd,as_sel_no";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$cnt_ary[$fetch->mn_sex][$fetch->ge_age_cd][$fetch->as_sel_no] = $fetch->cnt;
			}

			// °���̲����Կ�
			$sql = "SELECT mn_sex,ge_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
					. " JOIN t_monitor ON mn_monitor_id=as_monitor_id"
					. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY mn_sex,ge_age_cd";
			$result = db_exec($sql);
			$nrow = pg_num_rows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$num_ary[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->cnt;
			}

			// ����ԣ��Խ���
			output_csv($csv);
			output_csv($csv);

			// ����ʸ����
			set_csv($csv, "��{$question_no}(����ʸ)");
			set_csv($csv, $question->question_text);
			output_csv($csv);

			// ��������������ֹ����
			set_csv($csv, decode_question_type($question->question_type));
			set_csv($csv, '');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++)
				set_csv($csv, $sno);
			set_csv($csv, '��');
			output_csv($csv);

			// ��������
			set_csv($csv, '');
			set_csv($csv, '');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++)
				set_csv($csv, $question->sel_text[$sno]);
			output_csv($csv);

			// ���η������
			set_csv($csv, '���');
			set_csv($csv, '����');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++) {
				if ($question->sel_text[$sno])
					set_csv($csv, (int)$total_ary[$sno]);
				else
					set_csv($csv, '');
			}
			set_csv($csv, $member_cnt);
			output_csv($csv);

			// °���̷������
			foreach ($sex_ary as $sex) {
				foreach ($age_ary as $age) {
					set_csv($csv, '');
					set_csv($csv, decode_sex($sex));
					set_csv($csv, $age_text_ary[$age]);

					for ($sno = 1; $sno <= MAX_SEL; $sno++) {
						if ($question->sel_text[$sno])
							set_csv($csv, (int)$cnt_ary[$sex][$age][$sno]);
						else
							set_csv($csv, '');
					}
					set_csv($csv, (int)$num_ary[$sex][$age]);
					output_csv($csv);
				}
			}

			// ���Ρ����
			set_csv($csv, '��');
			set_csv($csv, '����');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++) {
				if ($question->sel_text[$sno])
					set_csv($csv, round($member_cnt != 0 ? 100.0 * $total_ary[$sno] / $member_cnt : 0, 1));
				else
					set_csv($csv, '');
			}
			set_csv($csv, 100);
			output_csv($csv);

			// °���̡����
			foreach ($sex_ary as $sex) {
				foreach ($age_ary as $age) {
					set_csv($csv, '');
					set_csv($csv, decode_sex($sex));
					set_csv($csv, $age_text_ary[$age]);

					for ($sno = 1; $sno <= MAX_SEL; $sno++) {
						if ($question->sel_text[$sno])
							set_csv($csv, round($num_ary[$sex][$age] != 0 ? 100.0 * $cnt_ary[$sex][$age][$sno] / $num_ary[$sex][$age] : 0, 1));
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

				// �����Կ�
				$sql = "SELECT COUNT(DISTINCT ax_monitor_id)"
						. " FROM t_ans_matrix"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no";
				$member_cnt = db_fetch1($sql);

				// ���ν���
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

				// °���̽���
				$sql = "SELECT ax_hyoutou_no,mn_sex,ge_age_cd,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=ax_monitor_id"
						. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY mn_sex,ge_age_cd,ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt_ary[$fetch->mn_sex][$fetch->ge_age_cd][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// °���̲����Կ�
				$sql = "SELECT mn_sex,ge_age_cd,COUNT(DISTINCT ax_monitor_id) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_monitor ON mn_monitor_id=ax_monitor_id"
						. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY mn_sex,ge_age_cd";
				$result = db_exec($sql);
				$nrow = pg_num_rows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num_ary[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->cnt;
				}

				// ����ԣ��Խ���
				output_csv($csv);
				output_csv($csv);

				// ����ʸ����
				set_csv($csv, "��{$question_no}-{$sno}(����ʸ)");
				set_csv($csv, $question->question_text);
				set_csv($csv, $hyousoku);
				output_csv($csv);

				// ��������������ֹ����
				set_csv($csv, decode_question_type($question->question_type));
				set_csv($csv, '');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++)
					set_csv($csv, $tno);
				set_csv($csv, '��');
				output_csv($csv);

				// ��������
				set_csv($csv, '');
				set_csv($csv, '');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++)
					set_csv($csv, $question->hyoutou[$tno]);
				output_csv($csv);

				// ���η������
				set_csv($csv, '���');
				set_csv($csv, '����');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($question->hyoutou[$tno])
						set_csv($csv, (int)$total_ary[$tno]);
					else
						set_csv($csv, '');
				}
				set_csv($csv, $member_cnt);
				output_csv($csv);

				// °���̷������
				foreach ($sex_ary as $sex) {
					foreach ($age_ary as $age) {
						set_csv($csv, '');
						set_csv($csv, decode_sex($sex));
						set_csv($csv, $age_text_ary[$age]);

						for ($tno = 1; $tno <= MAX_SEL; $tno++) {
							if ($question->hyoutou[$tno])
								set_csv($csv, (int)$cnt_ary[$sex][$age][$tno]);
							else
								set_csv($csv, '');
						}
						set_csv($csv, (int)$num_ary[$sex][$age]);
						output_csv($csv);
					}
				}

				// ���Ρ����
				set_csv($csv, '��');
				set_csv($csv, '����');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++) {
					if ($question->hyoutou[$tno])
						set_csv($csv, round($member_cnt != 0 ? 100.0 * $total_ary[$tno] / $member_cnt : 0, 1));
					else
						set_csv($csv, '');
				}
				set_csv($csv, 100);
				output_csv($csv);

				// °���̡����
				foreach ($sex_ary as $sex) {
					foreach ($age_ary as $age) {
						set_csv($csv, '');
						set_csv($csv, decode_sex($sex));
						set_csv($csv, $age_text_ary[$age]);

						for ($tno = 1; $tno <= MAX_SEL; $tno++) {
							if ($question->hyoutou[$tno])
								set_csv($csv, round($num_ary[$sex][$age] != 0 ? 100.0 * $cnt_ary[$sex][$age][$tno] / $num_ary[$sex][$age] : 0, 1));
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