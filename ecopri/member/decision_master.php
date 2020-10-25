<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガンバリストマスター
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

if ($ym != '' && $ganbalist_id != '') {
	$seq_no = $seq_no = $_SESSION['ss_seq_no'];
	$sql = "SELECT * FROM t_master_mark WHERE mm_mb_seq_no=$seq_no AND mm_ganbalist_id=$ganbalist_id";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$sql = "INSERT INTO t_master_mark (mm_mb_seq_no,mm_ganbalist_id) VALUES ($seq_no,$ganbalist_id)";
		db_exec($sql);
	}
}

redirect("decision_scrap.php?ym=$ym");
?>