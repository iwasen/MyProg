<?
/******************************************************
' System :Eco-footprint �����ڡ���
' Content:���������
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

// ���å�������
session_start();
if (!isset($_SESSION['ss_litem']))
	redirect("list_item.php");
$litem = &$_SESSION['ss_litem'];

// CSV���Ͻ���
$filename = $csv_filename . '.csv';
prepare_csv($filename);

// CSV�إå�����
$csv = $litem->sort_header;
output_csv($csv);

// CSV�ǡ�������
//$csv = '';
//set_csv($csv, $csv_data);
output_csv($litem->csv_data);

?>