<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:事務局メール配信ログ
'******************************************************/

$title_text = $TT_haishin_log;
$title_color = $TC_MASTER;

$order_by = order_by(1, 1, 'send_dt', 'mag_titl', 'send_volume');
$sql = "SELECT to_char(LS.send_dt, 'YYYY/MM/DD hh24:mi:ss') as send_dt, LS.mag_id, LS.send_volume, LB.mag_titl, LB.index_id"
	. " FROM L_SEND LS LEFT OUTER JOIN L_BACKNUMBER LB ON LS.index_id=LB.index_id";
if ($kind == "") {
	$sql .= " WHERE LS.mag_id='000049'";
} elseif ($kind == "1"){
	$sql .= " WHERE LS.mag_id='000013'";
} elseif ($kind == "2") {
	$sql .= " WHERE LS.mag_id='000013' OR LS.mag_id='000049'";
}
$sql .= " " . $order_by;
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
function OnClick_kind(kind) {
  location.href = "index.php?kind=" + kind;
}
function OnClick_mag(index_id) {
  var win;
  win = window.open("../melmaga_detail/mag_honbun.php?backnum=" + index_id, "info_mag", "scrollbars=yes,resizable=yes,width=600,height=800");
  win.focus();
}
function OnClick_pub(pub_id) {
  var win;
  win = window.open("../melmaga_detail/publisher.php?pub_id=" + pub_id, "info_pub", "scrollbars=yes,resizable=yes,width=600,height=500");
  win.focus();
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
			<td>■事務局メール配信ログ</td>
		</tr>
	</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td><font size="-1">
				<input type="radio" onclick="OnClick_kind('')" <? If ($kind == "") {print " checked";} ?>>めろんぱんレターのみ
				<input type="radio" onclick="OnClick_kind('1')" <? If ($kind == "1") {print " checked";} ?>>バ･キューンのみ
				<input type="radio" onclick="OnClick_kind('2')" <? If ($kind == "2") {print " checked";} ?>>全て</fonr>
			</td>
			<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='../menu.php'"><br>
			</td>
	<table border=1 cellspacing=0 cellpadding=3 width='80%'>
		<tr bgcolor ="#ffcc99">
<?
sort_header(1, '配信日時');
sort_header(2, 'メルマガタイトル');
sort_header(3, '発行部数');
?>
		</tr>
	<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$send_dt = $fetch->send_dt;
	$mag_id = $fetch->mag_id;
	$send_volume = $fetch->send_volume;
	$mag_titl = $fetch->mag_titl;
	$index_id = $fetch->index_id;
	?>
		<tr>
			<td align="center" class="tc<?= $line % 2; ?>"><?= $send_dt ?></td>
			<td class="tc<?= $line % 2; ?>"><a href='JavaScript:OnClick_mag("<?= $index_id ?>")'><?= htmlspecialchars($mag_titl) ?></a></td>
			<td align="right" class="tc<?= $line % 2; ?>"><?= number_format($send_volume) ?></td>
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
