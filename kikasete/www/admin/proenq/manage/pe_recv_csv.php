<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
//jeon_start seq=84
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/enquete.php");
//jeon_end seq=84
include("$inc/format.php");
include("$inc/pro_enq_target.php");
include("$inc/csv.php");
ob_start();
// アンケートID.開始日時取得
//jeon_start seq=84
/*
$sql = "SELECT mep_enquete2_id,en_start_date"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
		. " WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
*/
$sql = "SELECT mep_enquete2_id,en_start_date,mep_search_id"
		. " FROM t_pro_enquete"
		. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
		. " WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
//jeon_end seq=84
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->mep_enquete2_id;
	$start_date = sql_date(format_date($fetch->en_start_date));
	//jeon_end seq=84	
	$search_id = $fetch->mep_search_id;
//jeon_end seq=84
} else
	//echo("資料がありません");
	redirect('show.php');

// 利用ネットワークの取得	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;

// jeon_start seq=84////////////////

$owner_id = $marketer_id;
//$owner_id = $_SESSION['ss_owner_id'];	//Add Bti 2007/03/08
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
$pro_enq_age_option = new cell_class;
$pro_enq_age_option->read_db($pro_enq->search_id);
$age_option = $pro_enq_age_option->age_option;
$sex_option = $pro_enq_age_option->sex_option;
$cell_option = $pro_enq_age_option->cell_option;
$search = &$pro_enq->search;
if ($search->age_cd != '')
	$age_type = '1';
elseif ($search->age_from != '' || $search->age_to != '')
	$age_type = '2';
else
	$age_type = '';
if ($sex_option == 1){
	$select = "0 as mn_sex";
	$group = "";
}else {
	$select = "mn_sex";
	$group = "mn_sex,";
}
if ($age_option == 2)//5歳刻み時、またサンプル数割付しない時5歳刻み時
{
	if ($target_flg == 't')
	{
		$sql = "SELECT $select,ae_age_cd AS ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_enquete_list" 
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id" 
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " JOIN m_age ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to" 
		. " WHERE el_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ae_age_cd;";
	}
	else
	{
		$sql = "SELECT $select,ae_age_cd AS ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_answer" 
		. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
		. " JOIN m_age ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to" 
		. " WHERE an_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ae_age_cd;";		
	}
} else if($age_option == 1){
	if ($sex_option != 1){
		$group = "GROUP BY mn_sex";
	}
	if ($target_flg == 't')
	{
		$sql = "SELECT $select, 1 AS ge_age_cd,COUNT(*) AS recv_count"
		. " FROM t_enquete_list"
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
		. " $group;";
	}
	else
	{
		$sql = "SELECT $select,1 AS ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_answer" 
		. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
		. " WHERE an_enquete_id=$enquete_id AND an_valid_flag" 
		. " $group;";		
	}		
} else {
	// jeon_end seq=84//////////////////
	if ($target_flg == 't')
	{
		$sql = "SELECT $select,ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_enquete_list" 
		. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id" 
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " JOIN m_age10 ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to" 
		. " WHERE el_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ge_age_cd";
	}
	else
	{
		$sql = "SELECT $select,ge_age_cd,COUNT(*) AS recv_count" 
		. " FROM t_answer" 
		. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
		. " JOIN m_age10 ON DATE_PART('Y',AGE($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to" 
		. " WHERE an_enquete_id=$enquete_id AND an_valid_flag" 
		. " GROUP BY $group ge_age_cd";
	}
}
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$cell[$fetch->mn_sex][$fetch->ge_age_cd] = $fetch->recv_count;
}

// jeon_start seq=84
if ($age_option==2){
	if ($sex_option == 1){
		$sex_s = 0;
		$sex_e = 0;
	}else {
		$sex_s = 1;
		$sex_e = 2;
	}
	$age_s = 3;
	$age_e = 14;
}else if($age_option == 1){
	if ($sex_option == 1){
		$sex_s = 0;
		$sex_e = 0;
	}else {
		$sex_s = 1;
		$sex_e = 2;
	}
	$age_s = 1;
	$age_e = 1;	
}else {
	if ($sex_option == 1){
		$sex_s = 0;
		$sex_e = 0;
	}else {
		$sex_s = 1;
		$sex_e = 2;
	}
	$age_s = 2;
	$age_e = 8;
}
// Add Bti 2007/03/08 s
if ($sex_option == 1) {
	if ($search->sex == "1") {
		$default_sex = "男性";
	} elseif ($search->sex == "2") {
		$default_sex = "女性";
	} else {
		$default_sex = "性別指定なし";
	}
}
// Add Bti 2007/03/08 e
$filename = "recv_count_$enquete_id.csv";
prepare_csv($filename);

// jeon_start seq=84
if ($age_option == 2){
	$header = ',10代後半,20代前半,20代後半,30代前半,30代後半,40代前半,40代後半,50代前半,50代後半,60代前半,60代後半,70代以上,合計';
}else if($age_option == 1){				
	$header = ',年代指定なし,合計';
}else {
	$header = ',10代,20代,30代,40代,50代,60代,70代以上,合計';
}
output_csv($header);

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	set_csv($csv, decode_sex($sex, $default_sex));

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