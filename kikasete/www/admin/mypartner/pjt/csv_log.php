<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ���ȯ������CSV����
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