<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ＰｒｏアンケートCSV出力処理（01形式）
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");

// アンケートID取得
if ($pro_enq_no) {
	$sql = "SELECT mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	$enquete_id = db_fetch1($sql);
}

if (!$enquete_id)
	redirect('show.php');

// CSVデータ出力
csv_answer2($enquete_id, true);
exit;
?>