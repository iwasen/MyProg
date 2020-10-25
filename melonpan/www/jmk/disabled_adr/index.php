<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:購読禁止メールアドレス登録
'******************************************************/

$title_text = $TT_disabled_adr;
$title_color = $TC_DOKUSHA;

//'=== Main ===
$order_by = order_by(1, 0, 'mail_addr', 'register_dt');
$sql = "SELECT mail_addr, to_char(register_dt, 'YYYY/MM/DD') AS register_dt FROM T_MAILADDR_DISABLED"
		. " " . $order_by;
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■購読禁止メールアドレス一覧
			<td align="right">
				<input type="button" value="新規登録" onclick="location.href='disabled_adr1.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
			</td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
			<th align="center"><nobr>seq</nobr></th>
<?
sort_header(1, 'メールアドレス');
sort_header(2, '登録日');
?>
<!--			<th align="center"><nobr>メールアドレス</nobr></th>
			<th align="center"><nobr>登録日</nobr></th> -->
		</tr>
<?php
$count = 1;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mail_addr = $fetch->mail_addr;
	$register_dt = $fetch->register_dt;
?>
		<tr class="tc<?= $line % 2; ?>">
			<input type="hidden" value="<?= $mail_addr ?>">
			<td align="center"><?= $count ?></td>
			<td><a href="disabled_adr2.php?mail_addr=<?= $mail_addr ?>" title="購読禁止メールアドレスを修正します"><?= $mail_addr ?></a></td>
			<td align="center"><?= $register_dt ?></td>
		</tr>
<?php
$line++; $count++;
}
?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
