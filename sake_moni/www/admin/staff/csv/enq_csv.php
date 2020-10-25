<?
$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/mye_csv2.php");
$inc = "$top/inc";
include("$inc/login_check.php");

$enquete_id = $_POST['enquete_id'];

// アンケートID取得
if ($enquete_id == '')
	redirect('list.php');

// CSVデータ出力
switch ($type) {
case 'send':
	csv_send($enquete_id, $clean1, $clean2);
	break;
case 'recv':
	csv_recv($enquete_id, $clean1, $clean2);
	break;
case 'answer':
	csv_answer($enquete_id, $clean1, $clean2);
	break;
case 'answer2':
	csv_answer2($enquete_id, $clean1, $clean2);
	break;
}
exit;
?>