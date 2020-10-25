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

// PROアンケート情報読み込み
if (isset($pro_enq_no)) {
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else
	redirect('pe_index.php');

$cell = &$pro_enq->cell;

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

$pro_enq_target =& new pro_enq_target_class;
$pro_enq_target->read_db($pro_enq->enquete_id);

// 現サンプル数取得
$sample_cell = $cell->get_sample_cell($pro_enq->enquete_id, $enquete->start_date, $pro_enq_target->target_flg);

$filename = "finding_count_$enquete_id.csv";
prepare_csv($filename);

switch ($cell->age_option) {
case 2:
	$header = ',10代後半,20代前半,20代後半,30代前半,30代後半,40代前半,40代後半,50代前半,50代後半,60代前半,60代後半,70代以上,合計';
	break;
case 3:
	$header = ',10代,20代,30代,40代,50代,60代,70代以上,合計';
	break;
default:
	$header = ',年代指定なし,合計';
	break;
}

output_csv($header);

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	set_csv($csv, decode_sex($sex, '性別指定なし'));

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

set_csv($csv, '合計');

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