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
$sql = "SELECT mep_enquete2_id FROM t_pro_enquete WHERE mep_marketer_id=$owner_id AND mep_pro_enq_no=$pro_enq_no";
$enquete_id = db_fetch1($sql);
if ($enquete_id == '')
	redirect('pe_index.php');

// CSVデータ出力
switch ($type) {
case 'send':
	csv_send($enquete_id, false);
	break;
case 'recv':
	csv_recv($enquete_id, false);
	break;
}
exit;
?>