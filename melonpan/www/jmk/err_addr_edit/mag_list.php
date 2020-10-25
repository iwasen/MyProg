<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:不正アドレス修正画面
'******************************************************/

$title_text = "不正アドレス修正";
$title_color = $TC_MASTER;

//'=== Main ====
$sql = "SELECT TM.mail_id, TM.mag_id, TM.reader_id, to_char(TM.modify_dt, 'YYYY/MM/DD') as modify_dt, TM.err_count, MM.mag_nm"
	. " FROM T_MAILADDR TM, M_MAGAZINE MM"
	. " WHERE TM.mail_addr='$mail_addr' AND TM.mag_id=MM.mag_id"
	. " ORDER BY modify_dt DESC";
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
<form method="post" name="form1" style="margin:0" action="err_addr_edit1.php">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
	  <tr>
	    <td>■【<?= $mail_addr ?>】購読メルマガ一覧</td>
			<td align="right">
				<input type="button" value=" 戻る " onclick="history.back()">
			</td>
		</tr>
	</table>
  <table border=1 cellspacing=0 cellpadding=0 width='80%'>
    <tr bgcolor ="#ffcc99">
      <th class="h1" align="center">不達<br>回数</th>
      <th class="h1" align="center"><nobr>購読登録日</nobr></th>
      <th class="h1" align="center"><nobr>メルマガID</nobr></th>
      <th class="h1" align="center"><nobr>メルマガ名</nobr></th>
    </tr>
<?php
$line = 0; $i = 0;

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$err_count = $fetch->err_count;
	$mail_id = $fetch->mail_id;
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$modify_dt = $fetch->modify_dt;
	$mag_count = $fetch->mag_count;
	if ($err_count <= 0) { $err_count = 0; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" name="mail_id[]" value="<?= $mail_id ?>">
			<input type="hidden" name="reader_id[]" value="<?= $reader_id ?>">
			<input type="hidden" name="old_mail_addr[]" value="<?= $mail_addr ?>">
			<input type="hidden" name="mag_id[]" value="<?= $mag_id ?>">
			<td align="center"><?= $err_count ?></td>
			<td align="center"><?= $modify_dt ?></td>
			<td align="center"><?= $mag_id ?></a></td>
			<td align="left"><?= $mag_nm ?></a></td>
		</tr>
<?php
//	}
	$line++; $i++;
}
?>
	</table><br>
<input type="hidden" name="reader_id" value="<?= $reader_id ?>">
<input type="hidden" name="kind" value="<?= $kind_id ?>">
<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
