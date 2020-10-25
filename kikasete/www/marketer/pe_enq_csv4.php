<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/enquete.php");
include("$inc/mye_csv.php");
if ($owner_id == '') {
	$inc = "$top/inc";
	include("$inc/mkk_mypage.php");

	$owner_id = $_SESSION['ss_owner_id'];
}

if ($pro_enq_no == '')
	redirect('pe_index.php');

// アンケートID取得
$sql = "SELECT mep_enquete_id,mep_enquete2_id,mep_finding_flag FROM t_pro_enquete WHERE mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq_no";
$result = db_exec($sql);
if (pg_numrows($result))
	$fetch = pg_fetch_object($result, 0);
else
	redirect('pe_index.php');

// CSVファイル名
$filename = "questions_{$fetch->mep_enquete_id}.csv";
prepare_csv($filename);

output_attribute_csv();
if ($fetch->mep_finding_flag == DBTRUE) {
    output_question_csv($fetch->mep_enquete_id, true);
    output_question_csv($fetch->mep_enquete2_id);
} else
    output_question_csv($fetch->mep_enquete_id);
exit;
?>