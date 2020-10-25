<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");
include("$inc/myp_csv.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

csv_sel($pjt_id, $all);
exit;
?>