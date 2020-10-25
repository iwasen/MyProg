<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:�ͣ����󥱡�������CSV���Ͻ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
include("$inc/enquete.php");

// ���䥿����
function get_question_type($type) {
	switch ($type) {
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

// ���󥱡���ID����
if ($my_enq_no)
	$sql = "SELECT me_enquete_id FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
else
	redirect('show.php');

$enquete_id = db_fetch1($sql);
if (!$enquete_id)
	redirect('show.php');

// CSV�ե�����̾
$filename = "myenq_question_${staff_id}_${my_enq_no}.csv";
prepare_csv($filename);

$enquete = new enquete_class();
$enquete->read_db($enquete_id);

if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		switch ($question->question_type) {
		case 1:
		case 2:
			set_csv($csv, $qno);
			set_csv($csv, get_question_type($question->question_type));
			set_csv($csv, $question->question_text);
			if (is_array($question->sel_text)) {
				foreach ($question->sel_text as $sel_text) {
					set_csv($csv, $sel_text);
				}
			}
			output_csv($csv);
			break;
		case 3:
			set_csv($csv, $qno);
			set_csv($csv, get_question_type($question->question_type));
			set_csv($csv, $question->question_text);
			output_csv($csv);
			break;
		case 4:
		case 5:
			if (is_array($question->hyousoku)) {
				foreach ($question->hyousoku as $sno => $hyousoku) {
					if (is_array($question->hyoutou)) {
						set_csv($csv, "$qno-$sno");
						set_csv($csv, get_question_type($question->question_type));
						set_csv($csv, $hyousoku);
						foreach ($question->hyoutou as $hyoutou) {
							set_csv($csv, $hyoutou);
						}
						output_csv($csv);
					}
				}
			}
			break;
		}
	}
}
?>