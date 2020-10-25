<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:発行者情報メンテナンス
'******************************************************/

$title_text = $TT_hakkousha_mainte;
$title_color = $TC_HAKKOUSHA;

$search_addr = stripslashes($search_addr);

//'=== Main ===
$order_by = order_by(3, 0, 'publisher_id', 'owner_nm_kana', 'mail_add_main', 'regist_dt');
$sql = "SELECT publisher_id, mail_add_main, to_char(regist_dt,'YYYY/MM/DD') as regist_dt, owner_nm_kanj, owner_nm_kana FROM M_PUBLISHER "
	. " WHERE mail_add_main like '%". $search_addr ."%' " . $order_by;
$result = db_exec($sql);
$nrow = pg_numrows($result);
if ($nrow <= 0) {
		header ("location: ../admin/err_msg.php?id=0908");
	exit;
} else {

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
<form method="post" name="form1" style="margin:0" action="hakkousha_mainte1_2.php?search_addr=<?= $search_addr ?>">
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="search_addr" <?=value($search_addr)?>>
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■検索結果一覧【検索条件：<?= $search_addr ?>】 <font size=-1>（※項目名をクリックするとソートできます。）</font></td>
			<td align="right">
				<td align="right">該当件数：<span id="rec_count"></span>件 <input type="button" value=" 戻る " onclick="location.href='hakkousha_mainte1.php'">
			</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=1 width='80%'>
		<tr bgcolor="#ffcc99">
<?
	sort_header(1, 'ID');
	sort_header(2, '発行者名ID');
	sort_header(3, 'メールアドレス');
	sort_header(4, '登録日');
?>
		</tr>
<?php
	$line = 0;
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$publisher_id = $fetch->publisher_id;
		$owner_nm_kanj = $fetch->owner_nm_kanj;
		$mail_add_main = $fetch->mail_add_main;
		$regist_dt = $fetch->regist_dt;
		if ($regist_dt == "") { $regist_dt = "<br>"; }
?>
		<tr class="tc<?= $line % 2; ?>">
			<td align="center"><a href='hakkousha_mainte2.php?publisher_id=<?= $publisher_id ?>' title="メルマガ発行者情報を修正します"><?= $publisher_id ?></a></td>
			<td title="メルマガ発行者情報を表示します"><a href='JavaScript:OnClick_pub("<?= $publisher_id ?>")'><nobr><?= htmlspecialchars($owner_nm_kanj) ?></nobr></a></td>
			<td><nobr><?= htmlspecialchars($mail_add_main) ?></nobr></td>
			<td align="center"><nobr><?= $regist_dt ?></nobr></td>
		</tr>
<?php
		$line++;
	}
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
