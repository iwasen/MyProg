<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:仮登録承認処理
'******************************************************/

$title_text = $TT_kari_touroku;
$title_color = $TC_HAKKOUSHA;

$order_by = order_by(1, 0, 'mag_pub_status_dt', 'mag_id', 'mag_nm');
$sql = "SELECT mag_id,mag_nm,publisher_id,to_char(mag_pub_status_dt, 'yyyy/mm/dd') as mag_pub_status_dt"
	. " FROM m_magazine"
	. " WHERE mag_pub_status_flg = '00' AND mag_status_flg='00' AND mag_pub_stop_flg != '02' AND mag_pub_stop_flg != '01' $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<script type="text/javascript">
<!--
function sort_list(sort, dir) {
	var f = document.form1;
	f.sort_col.value = sort;
	f.sort_dir.value = dir;
	f.submit();
}
//-->
</script>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='90%'>
		<tr>
			<td>■仮登録待ち一覧</td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='../menu.php'"></td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
	<table border=1 cellspacing=0 cellpadding=1 width='90%'>
	<tr bgcolor="ffcc99">
<?
		sort_header(1, '申請日');
		sort_header(2, 'メルマガID');
		sort_header(3, 'メルマガ名');

?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><nobr><?= $fetch->mag_pub_status_dt ?></nobr></td>
			<td align="center"><nobr><?= $fetch->mag_id ?></nobr></td>
			<td><nobr><a href='kari_touroku2.php?mag_id=<?= $fetch->mag_id ?>' title="メルマガの詳細を表示します"><?= htmlspecialchars($fetch->mag_nm) ?></a></nobr></td>
		</tr>
	<?php
	$line++;
	}
	?>
	</table>
</form>
</center>

<SCRIPT LANGUAGE=javascript>
<!--
document.all.rec_count.innerHTML = <?= $nrow; ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
