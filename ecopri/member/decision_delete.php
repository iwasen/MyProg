<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:ガンバリスト削除
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
$inc = "$top/inc";
include("$inc/mypage.php");

if ($ym != '' && $ganbalist_id != '' && $eng_bunrui != '') {
	$seq_no = $seq_no = $_SESSION['ss_seq_no'];
	$sql = "DELETE FROM t_ganbalist WHERE gn_seq_no=$seq_no AND gn_ym=$ym AND gn_ganbalist_id=$ganbalist_id AND gn_eng_bunrui=$eng_bunrui";
	db_exec($sql);
}

redirect("decision_scrap.php?ym=$ym");
?>