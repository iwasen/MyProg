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

// アンケートID取得
if ($enquete_id == '')
	redirect('mkk_enq.php');

// CSVデータ出力
csv_answer($enquete_id, false, false, 0, 'data');
exit;
?>