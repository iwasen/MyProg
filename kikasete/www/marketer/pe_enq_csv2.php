<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
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
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	if ($mode == 1) {
		$finding_enquete_id = 0;
		$enquete_id = $fetch->mep_enquete_id;
		$file_prefix = ($fetch->mep_finding_flag == DBTRUE) ? 'data_qr' : 'data';
	} else {
		$finding_enquete_id = $fetch->mep_enquete_id;
		$enquete_id = $fetch->mep_enquete2_id;
		$file_prefix = 'data';
	}
} else
	redirect('pe_index.php');

// CSVデータ出力
csv_answer($enquete_id, false, false, $finding_enquete_id, $file_prefix);
exit;
?>