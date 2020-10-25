<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");
include ("../inc/code.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告クリックカウンタ設定
'******************************************************/

$title_text = $TT_click;
$title_color = $TC_OTHER;

// クリック率取得
function info_rate($click,$all) {
	if ($all > 0) {
		echo number_format(($click/$all)*100, 3).'%';
	} else {
		echo '-';
	}
}

// ページ切り替えリンク
define('PAGE_SIZE', 50);
function page_change($all_count, $page) {
	$page_count = ($all_count - 1) / PAGE_SIZE;
	$page_start = (int)($page / 10) * 10;
	$page_end = min($page_start + 10, $page_count);

	if ($page_start > 0)
		echo "<a href='javascript:page_change(" . ($page_start - 1) . ")'><font size='-1'>以前１０個を表示</font></a>\n";

	for ($i = $page_start; $i < $page_end; $i++) {
		$n = $i + 1;
		if ($i == $page)
			echo "<b>$n</b>\n";
		else
			echo "<a href='javascript:page_change($i)'>$n</a>\n";
	}

	if ($page_count > $page_end)
		echo "<a href='javascript:page_change($i)'><font size='-1'>以降１０個を表示</font></a>\n";
}

// 全件数取得
$sql = "SELECT count(*) FROM t_click_counter";
$result = db_exec($sql);
$fetch = pg_fetch_row($result, 0);
$all_count = $fetch[0];

//*** main ***
$order_by = order_by(1, 1, 'cc_ct_id', 'cc_click_name', 'cc_client_name','cc_ct_id','cc_click_num','cc_all_num','rate');
$sql = "SELECT cc_ct_id,cc_click_name,cc_client_name,cc_jump_url,cc_all_num,cc_click_num,((cc_click_num*1000)/cc_all_num) as rate"
			." FROM t_click_counter $order_by LIMIT " . PAGE_SIZE . " OFFSET " . $page * PAGE_SIZE;
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
function onclick_time() {
	var f = document.form1;
	f.target = "";
	f.action = "click_time.php";
	f.submit();
}
function onclick_date() {
	var f = document.form1;
	f.target = "";
	f.action = "click_date.php";
	f.submit();
}
function OnClick_info(url) {
  var win;
  win = window.open(url, "check_url", "scrollbars=yes,resizable=yes,toolbar=yes,menubar=yes,location=yes");
  win.focus();
}
function page_change(page) {
	var f = document.form1;
	f.page.value = page;
	f.target = "";
	f.action = "index.php";
	f.submit();
}
//-->
</script>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
		<td>■広告一覧<font size="-1">（表示したい広告を選択して右のボタンをクリックしてください）</font>
		</td>
		<td align="right">
			<input type="button" value="日付別" OnClick='JavaScript:onclick_date()'>
			<input type="button" value="時間別" OnClick='JavaScript:onclick_time()'>　　
			<input type="button" value=" 新規登録 " onclick="location.href='click1.php'">
		</td>
	</tr>
</table>
<div align="right"><? page_change($all_count, $page) ?></div>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page">

<table border=1 cellspacing=0 cellpadding=2 width='100%'>
	<tr bgcolor="ffcc99">
<?
		sort_header(1, '広告ID');
		sort_header(2, '広告名');
		sort_header(3, 'クライアント名');
		sort_header(4, 'ｶｳﾝﾀ設定後URL');
		sort_header(5, 'ｸﾘｯｸ数');
		sort_header(6, '全体数');
		sort_header(7, 'ｸﾘｯｸ率');
		sort_header(0, '選択');

?>
	</tr>
<?php
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$cc_ct_id = $fetch->cc_ct_id;
	$cc_click_name = $fetch->cc_click_name;
	$cc_client_name = $fetch->cc_client_name;
	$cc_all_num = $fetch->cc_all_num;
	$cc_jump_url = $fetch->cc_jump_url;
	$cc_click_num = $fetch->cc_click_num;
?>
	<tr class="tc<?= $line % 2; ?>">
		<td align="center"><a href=""><a href='click2.php?ct_id=<?= $cc_ct_id ?>' title="登録内容を修正します"><?= $cc_ct_id ?></a></td>
		<td align="left"><?= htmlspecialchars($cc_click_name) ?></td>
		<td align="left"><?= htmlspecialchars($cc_client_name) ?></td>
		<td align="left"><a href='JavaScript:OnClick_info("<?= set_url($cc_ct_id) ?>")'><?= htmlspecialchars(set_url($cc_ct_id)) ?></a></td>
		<td align="right"><?= number_format($cc_click_num) ?></td>
		<td align="right"><?= number_format($cc_all_num) ?></td>
		<td align="right"><?= info_rate($cc_click_num,$cc_all_num) ?></td>
		<td align="center"><input type="radio" name="sel_id" <?= value($cc_ct_id) ?> <?= value_checked(0,$i) ?>></td>
		<input type="hidden" name="click_name[<?=$cc_ct_id?>]" <?= value($cc_click_name) ?>>
	</tr>
<?php
	$line++;
}
?>
	</tr>
</table>
<div align="right"><? page_change($all_count, $page) ?></div>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>

