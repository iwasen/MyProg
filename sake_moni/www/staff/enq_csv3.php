<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv.php");
if ($staff_id == '') {
	$inc = "$top/inc";
	include("$inc/mypage.php");

	$staff_id = $_SESSION['ss_staff_id'];
}

// アンケートID取得
if ($enquete_id == '')
	redirect('enq.php');

// CSVデータ出力
csv_answer2($enquete_id);
exit;
?>