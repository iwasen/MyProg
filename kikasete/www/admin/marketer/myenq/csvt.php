<?
/******************************************************
' System :きかせて・net共通
' Content:CSVファイル出力
'******************************************************/
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");
include("$inc/enquete.php");

if($mode=="1"){
	// アンケートID取得
	if ($my_enq_no)
		$sql = "SELECT me_enquete_id FROM t_my_enquete WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
	else
		redirect('show.php');

	$enquete_id = db_fetch1($sql);
	if (!$enquete_id)
		redirect('show.php');

	// CSVファイル名
	$filename = "questions_${enquete_id}.csv";
	prepare_csv($filename);

    output_attribute_csv();
    output_question_csv($enquete_id);

//kim_end seq no=49
}else if($mode=="2"){
		//kim_start seq no=49 本アンケートカンマ区きり
		// アンケートID取得
	if ($my_enq_no) {
		$sql = "SELECT me_enquete_id FROM t_my_enquete WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
		$enquete_id = db_fetch1($sql);
	}

	if (!$enquete_id)
		redirect('show.php');

	// CSVデータ出力
	csv_answer($enquete_id, true, true, 0, 'data');
	exit;
//kim_end seq no=49
}else if($mode=="3"){
//kim_start seq no=49 本アンケート０，１区きり
		// アンケートID取得
	if ($my_enq_no) {
		$sql = "SELECT me_enquete_id FROM t_my_enquete WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
		$enquete_id = db_fetch1($sql);
	}

	if (!$enquete_id)
		redirect('show.php');

	// CSVデータ出力
	csv_answer2($enquete_id, true);
	exit;
//kim_end seq no=49
}
?>