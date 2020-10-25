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
if (!is_uploaded_file($data_file) || $data_file_type <> "text/plain") {
	$msg = "アップロードファイルが正しく登録されていないようです。ご確認下さい。";
	$err_flag = '1';
} else {
	$sql = "SELECT MAX(CD_seq_no) AS CD_seq_no FROM M_CLEANING_DATA";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$seq_no = $fetch->cd_seq_no;
	}
	$seq_no++;

	$job_name = replace_single($job_name);
	$client_name = replace_single($client_name);
	$subject = replace_single($subject);
	$from = replace_single($from);
	$reply_to = replace_single($reply_to);
	$body = replace_single($body);

	$sql = "insert into M_CLEANING_DATA (CD_seq_no, CD_job_name, CD_client_name, CD_subject, CD_from, CD_reply_to, CD_body, CD_start_date, CD_end_date, CD_status) values ($seq_no, '$job_name', '$client_name', '$subject', '$from', '$reply_to', '$body', to_timestamp('$start_date', 'YYYY-MM-DD HH24:MI:SS'), to_timestamp('$end_date', 'YYYY-MM-DD HH24:MI:SS'), '$status')";
	db_exec($sql);

	insert_addr_list($seq_no, $data_file);

//重複アドレス削除
	$sql = "DELETE FROM T_CLEANING_LIST"
		. " WHERE CL_seq_no=$seq_no"
		. " AND cl_sub_seq NOT IN (SELECT MIN(cl_sub_seq) FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no GROUP BY CL_mail_adr)";
	db_exec($sql);
	$msg = "登録しました。";
}
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

<?= $msg ?><br><br>
<?php
if ($err_flag == '1') {
?>
<input type="button" value=" 戻る " onclick="history.back()">
<?php
} else {
?>
<input type="button" value=" 戻る " onclick="location.href='index.php'">
<?php
}
?>
</form>
</center>
<? footer_jmk(0); ?>

</body>
</html>
