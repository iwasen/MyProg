<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:メールアドレス・クリーニング・サービス
'******************************************************/

$title_text = $TT_mail_cleaning;
$title_color = $TC_OTHER;

$sub1 = "SELECT CL_seq_no,"
			. " SUM(CASE WHEN CL_status='0' THEN 1 END) as yet_count,"
			. " SUM(CASE WHEN CL_status='1' THEN 1 END) as mail_err_count,"
			. " SUM(CASE WHEN CL_status='2' THEN 1 END) as send_count,"
			. " SUM(CASE WHEN CL_status='3' THEN 1 END) as send_err_count,"
			. " SUM(CASE WHEN CL_status='4' THEN 1 END) as cancel_count"
			. " FROM t_cleaning_list GROUP BY CL_seq_no";

$sql = "SELECT yet_count, mail_err_count, send_count, send_err_count, cancel_count, CD_seq_no"
		. ", CD_job_name"
		. ", CD_client_name"
		. ", TO_CHAR(CD_start_date, 'YYYY/MM/DD') AS CD_start_date"
		. ", TO_CHAR(CD_end_date, 'YYYY/MM/DD') AS CD_end_date"
		. ", CD_status"
		. " FROM m_cleaning_data LEFT JOIN ($sub1) AS sub1 ON CD_seq_no=CL_seq_no"
		. " ORDER BY CD_seq_no DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
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
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
		<td>■メールクリーニング登録一覧</td>
		<td align="right">
			<input type="button" value="インポート" onclick="location.href='import1.php'">
			<input type="button" value="新規登録" onclick="location.href='cleaning1.php'">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
		<table border=1 cellspacing=0 cellpadding=1 width='100%'>
				<tr bgcolor="#ffcc99">
					<th align="center" rowspan="2"><nobr>SEQ</nobr></th>
					<th align="center" rowspan="2"><nobr>名称</nobr></th>
					<th align="center" rowspan="2"><nobr>クライアント企業名</nobr></th>
					<th align="center" rowspan="2"><nobr>開始日時</nobr></th>
					<th align="center" rowspan="2"><nobr>終了日時</nobr></th>
					<th align="center" colspan="5"><nobr>状態別件数</nobr></th>
				</tr>
				<tr bgcolor="#ffcccc">
					<th align="center"><nobr>未送信</nobr></th>
					<th align="center"><nobr>エラー</nobr></th>
					<th align="center"><nobr>有効</nobr></th>
					<th align="center"><nobr>不達</nobr></th>
					<th align="center"><nobr>解除</nobr></th>
				</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->cd_seq_no;
	$job_name = $fetch->cd_job_name;
	$client_name = $fetch->cd_client_name;
	$subject = $fetch->cd_subject;
	$start_date = $fetch->cd_start_date;
	$end_date = $fetch->cd_end_date;
	$cd_status = $fetch->cd_status;
	$yet_count = $fetch->yet_count;
	$mail_err_count = $fetch->mail_err_count;
	$send_count = $fetch->send_count;
	$send_err_count = $fetch->send_err_count;
	$cancel_count = $fetch->cancel_count;
?>
				<tr class="tc<?= $line % 2; ?>">
					<td align="center"><a href='cleaning2.php?seq_no=<?= $seq_no ?>'><?= $seq_no ?></a></td>
					<td><?= htmlspecialchars($job_name) ?></td>
					<td><?= htmlspecialchars($client_name) ?></td>
					<td align="center"><?= $start_date ?></td>
					<td align="center"><?= $end_date ?></td>
<?
	if ($yet_count > 0) {
?>
					<td align="right"><a href='cleaning_list.php?seq_no=<?= $seq_no ?>&status=0'><?= $yet_count ?></a></td>
<? } else { ?>
					<td align="right"><?= number_format($yet_count) ?></td>
<?
	}
	if ($mail_err_count > 0) {
?>
					<td align="right"><a href='cleaning_detail.php?seq_no=<?= $seq_no ?>&status=1'><?= $mail_err_count ?></a></td>
<? } else { ?>
					<td align="right"><?= number_format($mail_err_count) ?></td>
<?
	}
	if ($send_count > 0) {
?>
					<td align="right"><a href='cleaning_list.php?seq_no=<?= $seq_no ?>&status=2'><?= $send_count ?></a></td>
<? } else { ?>
					<td align="right"><?= number_format($send_count) ?></td>
<?
	}
	if ($send_err_count > 0) {
?>
					<td align="right"><a href='cleaning_list.php?seq_no=<?= $seq_no ?>&status=3'><?= $send_err_count ?></a></td>
<? } else { ?>
					<td align="right"><?= number_format($send_err_count) ?></td>
<?
	}
	if ($cancel_count > 0) {
?>
					<td align="right"><a href='cleaning_list.php?seq_no=<?= $seq_no ?>&status=4'><?= $cancel_count ?></a></td>
<? } else { ?>
					<td align="right"><?= number_format($cancel_count) ?></td>
<? } ?>
				</tr>
<?php
$line++;
}
?>
			</table>
		</td>
	</tr>
</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
