<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");
include ("cleaning_sub.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メールアドレス・クリーニング・サービス
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

//'=== 日付書式変更 ===
$start_date = date("Y-m-d H:i:s",mktime($start_hour,0,0,$start_month,$start_day,$start_year));
$end_date = date("Y-m-d H:i:s",mktime($end_hour,0,0,$end_month,$end_day,$end_year));

//'=== Main ===

$sql = "UPDATE M_CLEANING_DATA SET"
		. " CD_job_name = '$job_name'"
		. ", CD_client_name = '$client_name'"
		. ", CD_subject = '$subject'"
		. ", CD_from = '$from'"
		. ", CD_reply_to = '$reply_to'"
		. ", CD_body = '$body'"
		. ", CD_start_date = to_timestamp('$start_date', 'YYYY-MM-DD HH24:MI:SS')"
		. ", CD_end_date = to_timestamp('$end_date', 'YYYY-MM-DD HH24:MI:SS')"
		. " WHERE CD_seq_no=$seq_no";
db_exec($sql);

if ($data_file_name != "") {
	$sql = "DELETE FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no";
	db_exec($sql);
	insert_addr_list($seq_no, $data_file);
}

//重複アドレス削除
$sql = "DELETE FROM T_CLEANING_LIST"
	. " WHERE CL_seq_no=$seq_no"
	. " AND cl_sub_seq NOT IN (SELECT MIN(cl_sub_seq) FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no GROUP BY CL_mail_adr)";
	db_exec($sql);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>
<center>
<form method="post" name="form1">

更新しました。<br><br>

<input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
