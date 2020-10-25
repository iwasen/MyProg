<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・クロス集計ﾒCSV出力
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
include("$inc/myp_csv.php");
include("$inc/enquete.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");

csv_crs($enquete_id, $hyousoku, $hyoutou);
exit;
?>