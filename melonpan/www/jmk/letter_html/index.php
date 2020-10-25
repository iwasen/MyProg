<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_html;
$title_color = $TC_MASTER;

//'=== Main ===

$mag_id = "S00001";

$sql = "SELECT s_count FROM s_mailaddr WHERE mag_id='$mag_id' AND s_key='ALL'AND s_name='ALL'";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$send_num = pg_fetch_result($result, 0, 0);
else {
	$sql = "SELECT COUNT(*) FROM t_mailaddr WHERE mag_id='$mag_id'";
	$result = db_exec($sql);
	$send_num = pg_fetch_result($result, 0, 0);
}

$sql = "SELECT hl_letter_id,hl_subject,hl_body,hl_send_date,COALESCE(hl_send_num,$send_num) as hl_send_num2,hl_index_id FROM t_html_letter ORDER BY hl_letter_id DESC";
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
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■めろんぱんHTMLレター一覧</td>
			<td align="right">
				<input type="button" value="画像アップロード" onclick="location.href='upload.php'">
				<input type="button" value="新規登録" onclick="location.href='new.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>配信予定日時</nobr></th>
			<th align="center"><nobr>メルマガタイトル</nobr></th>
			<th align="center"><nobr>予定配信数</nobr></th>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $fetch->hl_send_date ?></td>
			<td><a href='edit.php?letter_id=<?= $fetch->hl_letter_id ?>'><?= htmlspecialchars($fetch->hl_subject) ?></a></td>
			<td align="right"><nobr><?= number_format($fetch->hl_send_num2) ?></nobr></td>
		</tr>
<?php
$line++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
