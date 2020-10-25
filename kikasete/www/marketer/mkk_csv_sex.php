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
include("$inc/mkk_mypage.php");

if ($enquete_id == '')
	redirect('mkk_enq.php');

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);

$filename = "cr_sex_myenq_$enquete_id.csv";
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
	// �оݼԤ�����
	if ($enquete->job_id) {
		$sql = "SELECT sr_sex"
				. " FROM t_search JOIN t_job ON sr_search_id=jb_search_id"
				. " WHERE jb_job_id=$enquete->job_id";
	} else {
		$sql = "SELECT sr_sex"
				. " FROM t_search JOIN t_my_enquete ON sr_search_id=me_search_id"
				. " WHERE me_enquete_id=$enquete_id";
	}
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$sex = $fetch->sr_sex;
	}
	$sex_ary = $sex ? explode(',', $sex) : array(1,2);
	$sex_cnt = count($sex_ary);

	foreach ($enquete->question as $question_no => $question) {
		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
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
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$total_ary[$fetch->as_sel_no] = $fetch->cnt;
			}

			// °���̽���
			$sql = "SELECT as_sel_no,mn_sex,COUNT(*) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
					. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY mn_sex,as_sel_no";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$cnt_ary[$fetch->mn_sex][$fetch->as_sel_no] = $fetch->cnt;
			}

			// °���̲����Կ�
			$sql = "SELECT mn_sex,COUNT(DISTINCT as_monitor_id) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
					. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY mn_sex";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$num_ary[$fetch->mn_sex] = $fetch->cnt;
			}

			// ����ԣ��Խ���
			output_csv($csv);
			output_csv($csv);

			// ����ʸ����
			set_csv($csv, "��{$question_no}(����ʸ)");
			set_csv($csv, $question->question_text);
			output_csv($csv);

			// ��������������ֹ����
			set_csv($csv, decode_question_type2($question->question_type));
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++)
				set_csv($csv, $sno);
			set_csv($csv, '��');
			output_csv($csv);

			// ��������
			set_csv($csv, '');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_SEL; $sno++)
				set_csv($csv, $question->sel_text[$sno]);
			output_csv($csv);

			// ���η������
			set_csv($csv, '���');
			set_csv($csv, '����');
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
				set_csv($csv, '');
				set_csv($csv, decode_sex($sex));

				for ($sno = 1; $sno <= MAX_SEL; $sno++) {
					if ($question->sel_text[$sno])
						set_csv($csv, (int)$cnt_ary[$sex][$sno]);
					else
						set_csv($csv, '');
				}
				set_csv($csv, (int)$num_ary[$sex]);
				output_csv($csv);
			}

			// ���Ρ����
			set_csv($csv, '��');
			set_csv($csv, '����');
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
				set_csv($csv, '');
				set_csv($csv, decode_sex($sex));

				for ($sno = 1; $sno <= MAX_SEL; $sno++) {
					if ($question->sel_text[$sno])
						set_csv($csv, round($num_ary[$sex] != 0 ? 100.0 * $cnt_ary[$sex][$sno] / $num_ary[$sex] : 0, 1));
					else
						set_csv($csv, '');
				}
				set_csv($csv, 100);
				output_csv($csv);
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
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$total_ary[$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// °���̽���
				$sql = "SELECT ax_hyoutou_no,mn_sex,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY mn_sex,ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt_ary[$fetch->mn_sex][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// °���̲����Կ�
				$sql = "SELECT mn_sex,COUNT(DISTINCT ax_monitor_id) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY mn_sex";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num_ary[$fetch->mn_sex] = $fetch->cnt;
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
				set_csv($csv, decode_question_type2($question->question_type));
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++)
					set_csv($csv, $tno);
				set_csv($csv, '��');
				output_csv($csv);

				// ��������
				set_csv($csv, '');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_SEL; $tno++)
					set_csv($csv, $question->hyoutou[$tno]);
				output_csv($csv);

				// ���η������
				set_csv($csv, '���');
				set_csv($csv, '����');
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
					set_csv($csv, '');
					set_csv($csv, decode_sex($sex));

					for ($tno = 1; $tno <= MAX_SEL; $tno++) {
						if ($question->hyoutou[$tno])
							set_csv($csv, (int)$cnt_ary[$sex][$tno]);
						else
							set_csv($csv, '');
					}
					set_csv($csv, (int)$num_ary[$sex]);
					output_csv($csv);
				}

				// ���Ρ����
				set_csv($csv, '��');
				set_csv($csv, '����');
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
					set_csv($csv, '');
					set_csv($csv, decode_sex($sex));

					for ($tno = 1; $tno <= MAX_SEL; $tno++) {
						if ($question->hyoutou[$tno])
							set_csv($csv, round($num_ary[$sex] != 0 ? 100.0 * $cnt_ary[$sex][$tno] / $num_ary[$sex] : 0, 1));
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
?>