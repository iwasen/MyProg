<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����Ͽ��ǧ����
'******************************************************/

$title_text = $TT_hon_touroku;
$title_color = $TC_HAKKOUSHA;

$order_by = order_by(1, 0, 'mag_pub_status_dt', 'mag_id', 'mag_nm', 'mag_volume');
$sql = "SELECT MAG_ID,MAG_NM,to_char(MAG_PUB_STATUS_DT, 'yyyy/mm/dd') as MAG_PUB_STATUS_DT,MAG_VOLUME,PUBLISHER_ID"
	. " FROM M_MAGAZINE"
	. " WHERE mag_pub_status_flg = '01' AND mag_status_flg='00' AND mag_volume >= 3 and mag_pub_stop_flg <> '02' and mag_pub_stop_flg <> '01' "
	. " $order_by";
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
		<td>������Ͽ�Ԥ�����</td>
		<td align="right">���������<span id="rec_count"></span>�� <input type="button" value=" ��� " onclick="location.href='../menu.php'"></td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<table border=1 cellspacing=0 cellpadding=1 width='90%'>
	<tr bgcolor="ffcc99">
<?
		sort_header(1, '������');
		sort_header(2, '���ޥ�ID');
		sort_header(3, '���ޥ�̾');
		sort_header(4, '�ۿ���');

?>
	</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><nobr><?= $fetch->mag_pub_status_dt ?></nobr></td>
		<td align="center"><nobr><?= $fetch->mag_id ?></nobr></td>
		<td><nobr><a href='hon_touroku2.php?mag_id=<?= $fetch->mag_id ?>' title="���ޥ��ξܺ٤�ɽ�����ޤ�"><?= htmlspecialchars($fetch->mag_nm) ?></a></nobr></td>
		<td align="right"><nobr><?= $fetch->mag_volume ?></nobr></td>
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
