<?
/******************************************************
' System :きかせて・net事務局・エージェント用ページ
' Content:Myパートナー・発言集計CSV出力
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");
include("$inc/format.php");
include("$inc/icp_db.php");
include("$inc/myp_csv.php");
$inc = "$top/inc";
include("$inc/login_check.php");

csv_log($job_id);
exit;
?>