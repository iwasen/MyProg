<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/decode.php");
include("$inc/csv.php");
include("$inc/pro_enq_target.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

// PRO���󥱡��Ⱦ����ɤ߹���
if (isset($pro_enq_no)) {
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else
	redirect('pe_index.php');

$cell = &$pro_enq->cell;

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

$pro_enq_target =& new pro_enq_target_class;
$pro_enq_target->read_db($pro_enq->enquete_id);

// ������ץ������
$sample_cell = $cell->get_sample_cell($pro_enq->enquete_id, $enquete->start_date, $pro_enq_target->target_flg);

$filename = "finding_count_$enquete_id.csv";
prepare_csv($filename);

switch ($cell->age_option) {
case 2:
	$header = ',10���Ⱦ,20����Ⱦ,20���Ⱦ,30����Ⱦ,30���Ⱦ,40����Ⱦ,40���Ⱦ,50����Ⱦ,50���Ⱦ,60����Ⱦ,60���Ⱦ,70��ʾ�,���';
	break;
case 3:
	$header = ',10��,20��,30��,40��,50��,60��,70��ʾ�,���';
	break;
default:
	$header = ',ǯ�����ʤ�,���';
	break;
}

output_csv($header);

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	set_csv($csv, decode_sex($sex, '���̻���ʤ�'));

	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = (int)$sample_cell[$sex][$age];
		$sum_col += $num;
		$sum_row[$age] += $num;
		set_csv($csv, $num);
	}
	set_csv($csv, $sum_col);
	output_csv($csv);
}

set_csv($csv, '���');

$sum_col = 0;
for ($age = $age_s; $age <= $age_e; $age++) {
	$num = $sum_row[$age];
	$sum_col += $num;
	set_csv($csv, $num);
}
set_csv($csv, $sum_col);
output_csv($csv);

exit;
?>