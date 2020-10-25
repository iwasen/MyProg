<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:読者情報メンテナンス
'******************************************************/

$title_text = $TT_dokusha_mainte;
$title_color = $TC_DOKUSHA;

$search_addr = stripslashes($search_addr);

//'=== Main ===
$order_by = order_by(2, 0, 'reader_id', 'mail_addr', 'regist_dt');
$sql = "SELECT reader_id, mail_addr, to_char(regist_dt,'YYYY/MM/DD') as regist_dt FROM M_MELONPA"
	. " WHERE mail_addr like '%". $search_addr ."%' " . $order_by;
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow == 0) {
		header ("location: ../admin/err_msg.php?id=2108");
	exit;
}

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
function OnClick_mymelonpan(reader_id) {
  var win;
  win = window.open("../melmaga_detail/mymelonpan.php?reader_id=" + reader_id, "info_project", "scrollbars=yes,resizable=yes,width=500,height=380");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0" action="dokusha_mainte1_2.php?search_addr=<?= $search_addr ?>">
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="search_addr" <?=value($search_addr)?>>
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■検索結果一覧【検索条件：<?= $search_addr ?>】 <font size=-1>（※項目名をクリックするとソートできます。）</font></td>
			<td align="right">
				<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='dokusha_mainte1.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
<?
	sort_header(1, 'ID');
	sort_header(2, 'メールアドレス');
	sort_header(3, '登録日');
?>
		</tr>
<?php
$line = 0;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$reader_id = $fetch->reader_id;
	$mail_addr = $fetch->mail_addr;
	$regist_dt = $fetch->regist_dt;
	if ($regist_dt == "") { $regist_dt = "<br>"; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center" title="読者登録情報を修正します"><a href='dokusha_mainte2.php?reader_id=<?= $reader_id ?>'><?= $reader_id ?></a></td>
			<td title="読者登録情報を表示します"><a href='JavaScript:OnClick_mymelonpan("<?= $reader_id ?>")'><nobr><?= $mail_addr ?></nobr></a></td>
			<td align="center"><nobr><?= $regist_dt ?></nobr></td>
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
