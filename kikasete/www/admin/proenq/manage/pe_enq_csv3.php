<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");

// アンケートID取得
$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
    $fetch = pg_fetch_object($result, 0);

    $enquete_id = $fetch->mep_enquete_id;
    $enquete2_id = $fetch->mep_enquete2_id;

}

// CSVデータ出力
//csv_answer2_test($enquete_id,$enquete2_id, true, false, $mode);

// 回答格納履歴の際はテストデータだけCSV出力する 2006/06/14 BTI
//csv_answer2_test($enquete_id,$enquete2_id, true, false, $mode, $history);
if ($mode == 1)
	csv_answer2(0, false, false, $enquete_id, 'qr', true);
else
	csv_answer2($enquete2_id, false, false, $enquete_id, 'qrdr', true);

exit;
?>