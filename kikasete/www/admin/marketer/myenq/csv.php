<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:ＭｙアンケートCSV出力処理
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
if ($my_enq_no) {
	$sql = "SELECT me_enquete_id FROM t_my_enquete WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
	$enquete_id = db_fetch1($sql);
}

if (!$enquete_id)
	redirect('show.php');

// CSVデータ出力
csv_answer($enquete_id, true);
exit;
?>