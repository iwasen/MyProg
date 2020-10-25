<?
/******************************************************
' System :メール配信サービス
' Content:メールアドレスダウンロード
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/csv.php");

$sql = "SELECT md_seq_no FROM t_mail_data WHERE md_send_mail_id=$send_mail_id";
$seq_no = db_fetch1($sql);

prepare_csv("mail_${seq_no}_${status}.dat");

$sql = "SELECT ml_mail_addr FROM t_mail_list WHERE ml_send_mail_id=$send_mail_id AND ml_status=$status ORDER BY ml_mail_addr";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	output_csv($fetch->ml_mail_addr);
}
?>