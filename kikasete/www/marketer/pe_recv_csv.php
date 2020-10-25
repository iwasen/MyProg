<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/pro_enq_target.php");
include("$inc/csv.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

// アンケートID.開始日時取得
$sql = "SELECT mep_enquete2_id,en_start_date"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
		. " WHERE mep_marketer_id={$_SESSION['ss_owner_id']} AND mep_pro_enq_no=$pro_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$enquete_id = $fetch->mep_enquete2_id;
} else
	redirect('pe_index.php');

// 利用ネットワークの取得	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;

if ($target_flg == 't') {
	$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
			. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
			. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_sex,ge_age_cd";
} else {
	$sql = "SELECT mn_sex,ge_age_cd,COUNT(*) AS recv_count"
			. " FROM t_answer"
			. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " GROUP BY mn_sex,ge_age_cd";
}
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$cell[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->recv_count;
}

$sex_s = 1;
$sex_e = 2;
$age_s = 2;
$age_e = 8;

$filename = "recv_count_$enquete_id.csv";
prepare_csv($filename);

$header = ',10代,20代,30代,40代,50代,60代,70代以上,合計';
output_csv($header);

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	set_csv($csv, decode_sex($sex, '性別指定なし'));

	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = (int)$cell[$sex][$age];
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