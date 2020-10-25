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

if ($status == "0")
	$msg = "■未送信アドレス一覧";
elseif ($status == "2")
	$msg = "■有効アドレス一覧";
elseif ($status == "3")
	$msg = "■不達アドレス一覧";
elseif ($status == "4")
	$msg = "■解除アドレス一覧";
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
<form method="post" name="form1" action="export.php">
<table border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=0 width="100%">
				<tr>
					<td><?= $msg ?></td>
					<td align="right">該当件数：<span id="rec_count"></span>件 </td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=3 width="100%">
<?php
$sql = "SELECT CD_job_name, CD_client_name FROM M_CLEANING_DATA WHERE CD_seq_no=$seq_no";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$job_name = $fetch->cd_job_name;
	$client_name = $fetch->cd_client_name;
?>
				<tr>
					<td class="h8" width="10%">名称</td>
					<td width="40%"><?= $job_name ?></td>
					<td class="h8" width="10%"><nobr>企業名</nobr></td>
					<td width="35%"><?= $client_name ?></td>
				</tr>
<?php
}
?>
			</table>
		</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=2>
				<tr>
					<td class="h1" align="center"><nobr>メールアドレス</nobr></td>
				</tr>
				<tr>
					<td>
						<textarea cols=80 rows=30 readonly>
<?
$sql = "SELECT * FROM T_CLEANING_LIST WHERE CL_seq_no=$seq_no AND CL_status='$status' ORDER BY CL_sub_seq";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$line=0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sub_seq = $fetch->cl_sub_seq;
	$mail_adr = $fetch->cl_mail_adr;
	echo $mail_adr . "\n";
$line++;
}
?>
						</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<?php
if ($status == '2') {
?>
<input type="hidden" name="seq_no" value="<?= $seq_no ?>">
<input type="hidden" name="status" value="<?= $status ?>">
<input type="submit" value="エクスポート">
<?php
}
?>
<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
