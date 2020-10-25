<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:配信予約一覧画面表示
'******************************************************/

$title_text = $TT_haishin_yoyaku;
$title_color = $TC_MASTER;

//=== Main ===
$order_by = order_by(4, 0, 'mag_id', 'mag_nm', 'mag_titl', 'resrv_hour', 'mag_count');
$sub = "SELECT count(*) from T_MAILADDR WHERE mag_id=R.mag_id";
$sql = "SELECT (" . $sub . ") as mag_count, R.index_id, R.body_id, R.mag_id, TO_CHAR(R.resrv_hour,'yyyy/mm/dd hh24:mi:ss') AS resrv_hour"
		. ", M.mag_nm, M.publisher_id, T.mag_titl"	
		. " FROM T_PUB_RESERVE R, M_MAGAZINE M, T_BODY T"
		. " WHERE R.resrv_flg = '0 'AND R.mag_id = M.mag_id AND R.mag_id = T.mag_id AND R.body_id = T.body_id"
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
function OnClick_mag(index_id) {
  var win;
  win = window.open("../melmaga_detail/mag_honbun.php?body=" + index_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=800");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='100%'>
		<tr>
			<td>■配信予約一覧　<font size=-1>（※項目名をクリックするとソートできます。）</font></td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='../menu.php'"></td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
	<table border=1 cellspacing=0 cellpadding=1 width='100%'>
		<tr bgcolor="#ffcc99">
<?
		sort_header(1, 'メルマガID');
		sort_header(2, 'メルマガ名');
		sort_header(3, 'タイトル');
		sort_header(4, '配信予約日時');
		sort_header(5, '予定配信数');
?>
		</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$mag_id = $fetch->mag_id;
	$mag_nm = $fetch->mag_nm;
	$mag_titl = $fetch->mag_titl;
	$resrv_hour = $fetch->resrv_hour;
	$index_id = $fetch->index_id;
	$mag_count = $fetch->mag_count;

?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><?= $fetch->mag_id ?></td>
			<td><?= htmlspecialchars($fetch->mag_nm) ?></td>
			<td><a href='JavaScript:OnClick_mag("<?= $fetch->index_id ?>")'><?= htmlspecialchars($fetch->mag_titl) ?></a></td>
			<td align="center"><?= $fetch->resrv_hour ?></td>
			<td align="right"><?= number_format($fetch->mag_count) ?></td>
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
document.all.rec_count.innerHTML = <?= $nrow ?>;
//-->
</SCRIPT>

<? footer_jmk(0); ?>

</body>
</html>
