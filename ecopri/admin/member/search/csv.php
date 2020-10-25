<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員数検索
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list_item.php");

// セッション処理
session_start();
if (!isset($_SESSION['ss_litem']))
	redirect("list_item.php");
$litem = &$_SESSION['ss_litem'];

// CSV出力準備
$filename = $csv_filename . '.csv';
prepare_csv($filename);

// CSVヘッダ出力
$csv = $litem->sort_header;
output_csv($csv);

// CSVデータ出力
//$csv = '';
//set_csv($csv, $csv_data);
output_csv($litem->csv_data);

?>