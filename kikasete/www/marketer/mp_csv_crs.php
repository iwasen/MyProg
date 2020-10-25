<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/csv.php");
include("$inc/myp_csv.php");
include("$inc/enquete.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

csv_crs($enquete_id, $hyousoku, $hyoutou);
exit;
?>