<?
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
//kim_start seq no=49 設問
	if ($pro_enq_no)
	    $sql = "SELECT mep_finding_flag,mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
	else
	    redirect('show.php');

	$result = db_exec($sql);
	if (pg_numrows($result)){
	    $fetch = pg_fetch_object($result, 0);

	    // CSVファイル名
	    $filename = "questions_{$fetch->mep_enquete_id}.csv"; // ダウンロードファイルの名称を変更 2006/03/28 BTI
	    prepare_csv($filename);

	    output_attribute_csv();

	    if ($fetch->mep_finding_flag == DBTRUE) {
		    output_question_csv($fetch->mep_enquete_id, true);
	        output_question_csv($fetch->mep_enquete2_id);
	    } else
		    output_question_csv($fetch->mep_enquete_id);
	}
exit;
}else if($mode=="2"){
//kim_start seq no=49 本アンケート カンマ区きり
		// アンケートID取得
	if ($pro_enq_no) {
		$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_finding_flag FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
		$result = db_exec($sql);
		if (pg_numrows($result)){
		    $fetch = pg_fetch_object($result, 0);

			$enquete_id = $fetch->mep_enquete_id;
			$enquete2_id = $fetch->mep_enquete2_id;
			$finding_flag = $fetch->mep_finding_flag;
		}
	}

	if (!$enquete_id)
		redirect('show.php');

	// CSVデータ出力
	csv_answer($enquete2_id, true, true, 0, $finding_flag == DBTRUE ? 'data_dr' : 'data', false, $enquete_id);
	exit;
//kim_end seq no=49
}else if($mode=="3"){
	//kim_start seq no=49 本アンケート０，１区きり
	// アンケートID取得
	if ($pro_enq_no) {
		$sql = "SELECT mep_enquete_id,mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
		$result = db_exec($sql);
		if (pg_numrows($result)){
		    $fetch = pg_fetch_object($result, 0);

			$enquete_id = $fetch->mep_enquete_id;
			$enquete2_id = $fetch->mep_enquete2_id;
		}
	}

	if (!$enquete_id)
		redirect('show.php');

	// CSVデータ出力
	csv_answer2($enquete2_id, true, true, 0, 'dr01', false, $enquete_id);
	exit;
//kim_end seq no=49
}

?>
