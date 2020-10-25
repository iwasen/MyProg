<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガンバリスト前月データコピー
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

if ($ym != '') {
	$seq_no = $seq_no = $_SESSION['ss_seq_no'];
	$sub1 = "SELECT max(gn_ym) FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym<'$ym'";
	$sql = "INSERT INTO t_ganbalist (gn_seq_no,gn_ym,gn_ganbalist_id,gn_eng_bunrui,gn_energy,gn_water,gn_cost,gn_co2,gn_advice)"
			. " SELECT gn_seq_no,'$ym',gn_ganbalist_id,gn_eng_bunrui,gn_energy,gn_water,gn_cost,gn_co2,gn_advice"
			. " FROM t_ganbalist"
			. " WHERE gn_seq_no=$seq_no AND gn_ym=($sub1)";
	db_exec($sql);
}

redirect("decision_scrap.php?ym=$ym");
?>