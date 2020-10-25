<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/csv.php");
include("$inc/icp_db.php");
include("$inc/myp_csv.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");

csv_count($job_id);
exit;
?>