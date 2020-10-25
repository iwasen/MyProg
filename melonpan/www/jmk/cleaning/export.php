<?
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メールアドレス・クリーニング・サービス
'******************************************************/

header("Cache-control: no-cache");
header("Content-type: application/x-download");
header("Content-Disposition: inline; filename=\"mailaddr.txt\"");

$sql = "SELECT CL_mail_adr FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no AND CL_status='$status' ORDER BY CL_sub_seq";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	print $fetch->cl_mail_adr . "\n";
}
?>
