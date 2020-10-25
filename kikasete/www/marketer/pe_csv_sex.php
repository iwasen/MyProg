<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/format.php");
include("$inc/pro_enquete.php");
include("$inc/pro_enq_target.php");
include("$inc/csv.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

if ($pro_enq_no == '')
	redirect('pe_index.php');

$owner_id = $_SESSION['ss_owner_id'];
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$enquete = &$pro_enq->enquete2;
$enquete_id = $pro_enq->enquete2_id;

$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);

$filename = "cr_sex_proenq_$enquete_id.csv";
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

if ($pro_enq->finding_flag == DBTRUE) {
	if (is_array($pro_enq->enquete->question))
		output_enquete($pro_enq->enquete_id, $pro_enq->enquete, '�ƣ�');
}
if (is_array($pro_enq->enquete2->question))
	output_enquete($pro_enq->enquete2_id, $pro_enq->enquete2, '');

function output_enquete($enquete_id, &$enquete, $fd) {
	global $pro_enq;

	// �оݼԤ�����
	$search = &$pro_enq->search;
	$sex = $search->sex;

	$sex_ary = $sex ? explode(',', $sex) : array(1,2);

	// ���ѥͥåȥ���μ���	2006/02/27 BTI
	$pro_enq_target = new pro_enq_target_class;
	$pro_enq_target->read_db($enquete_id);
	$target_flg = $pro_enq_target->target_flg;

	foreach ($enquete->question as $question_no => $question) {
		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
			$total_ary = array();
			$cnt_ary = array();
			$num_ary = array();

			// �����Կ�
			$sql = "SELECT COUNT(DISTINCT as_monitor_id)"
					. " FROM t_ans_select"
					. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no";
			$member_cnt = db_fetch1($sql);

			// ���ν���
			$sql = "SELECT as_sel_no,COUNT(*) AS cnt"
					. " FROM t_ans_select"
					. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
					. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
					. " GROUP BY as_sel_no";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$total_ary[$fetch->as_sel_no] = $fetch->cnt;
			}

			// °���̽���
			if ($target_flg == 't') {
				$sql = "SELECT as_sel_no,mn_sex,COUNT(*) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
						. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY mn_sex,as_sel_no";
			} else {
				$sql = "SELECT as_sel_no,mn_sex,COUNT(*) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
						. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY mn_sex,as_sel_no";
			}
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$cnt_ary[$fetch->mn_sex][$fetch->as_sel_no] = $fetch->cnt;
			}

			// °���̲����Կ�
			if ($target_flg == 't') {
				$sql = "SELECT mn_sex,COUNT(DISTINCT as_monitor_id) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
						. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
						. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY mn_sex";
			} else {
				$sql = "SELECT mn_sex,COUNT(DISTINCT as_monitor_id) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
						. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY mn_sex";
			}
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
			set_csv($csv, "{$fd}��{$question_no}(����ʸ)");
			set_csv($csv, $question->question_text);
			output_csv($csv);

			// ��������������ֹ����
			set_csv($csv, decode_question_type2($question->question_type));
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_PRO_SEL; $sno++)
				set_csv($csv, $sno);
			set_csv($csv, '��');
			output_csv($csv);

			// ��������
			set_csv($csv, '');
			set_csv($csv, '');
			for ($sno = 1; $sno <= MAX_PRO_SEL; $sno++)
				set_csv($csv, $question->sel_text[$sno]);
			output_csv($csv);

			// ���η������
			set_csv($csv, '���');
			set_csv($csv, '����');
			for ($sno = 1; $sno <= MAX_PRO_SEL; $sno++) {
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

				for ($sno = 1; $sno <= MAX_PRO_SEL; $sno++) {
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
			for ($sno = 1; $sno <= MAX_PRO_SEL; $sno++) {
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

				for ($sno = 1; $sno <= MAX_PRO_SEL; $sno++) {
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
						. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no";
				$member_cnt = db_fetch1($sql);

				// ���ν���
				$sql = "SELECT ax_hyoutou_no,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$total_ary[$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// °���̽���
				if ($target_flg == 't') {
					$sql = "SELECT ax_hyoutou_no,mn_sex,COUNT(*) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
							. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY mn_sex,ax_hyoutou_no";
				} else {
					$sql = "SELECT ax_hyoutou_no,mn_sex,COUNT(*) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY mn_sex,ax_hyoutou_no";
				}
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt_ary[$fetch->mn_sex][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// °���̲����Կ�
				if ($target_flg == 't') {
					$sql = "SELECT mn_sex,COUNT(DISTINCT ax_monitor_id) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
							. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY mn_sex";
				} else {
					$sql = "SELECT mn_sex,COUNT(DISTINCT ax_monitor_id) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY mn_sex";
				}
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
				set_csv($csv, "{$fd}��{$question_no}-{$sno}(����ʸ)");
				set_csv($csv, $question->question_text);
				set_csv($csv, $hyousoku);
				output_csv($csv);

				// ��������������ֹ����
				set_csv($csv, decode_question_type2($question->question_type));
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++)
					set_csv($csv, $tno);
				set_csv($csv, '��');
				output_csv($csv);

				// ��������
				set_csv($csv, '');
				set_csv($csv, '');
				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++)
					set_csv($csv, $question->hyoutou[$tno]);
				output_csv($csv);

				// ���η������
				set_csv($csv, '���');
				set_csv($csv, '����');
				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
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

					for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
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
				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
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

					for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
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