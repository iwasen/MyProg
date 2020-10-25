<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:広告バナー設定
'******************************************************/

$title_text = $TT_banner_set;
$title_color = $TC_OTHER;

//setlocale ("LC_TIME", "ja_JP");
$now = strftime ("%Y/%m/%d %H"); 

//過去のバナー表示選択
function and_where($kind) {
	if ($kind != "1") {
		return " AND BN_end_dt >= 'now'";
	}
}

//bg_color選択
function bg_color($bn_start_dt, $bn_end_dt, $now) {
	if ($bn_start_dt <= $now && $bn_end_dt > $now) {
		return 0;
	} else {
		return 1;
	}
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
function OnLoad_body() {
	if (document.form1.kind.value == "1") {
		document.form1.ck.checked = true;
	} else {
		document.form1.ck.checked = false;
	}
}
function OnClick_kind(kind) {
	if (document.form1.ck.checked == true) {
  	location.href = "index.php?kind=1";
	} else {
	 	history.back();
	}
}
function onClick_view(type) {
  var win;
  win = window.open("banner_view.php?type=" + type, "banner_view", "scrollbars=yes,resizable=yes,width=600,height=550");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" style="margin:0">
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■広告バナー設定　
				<input type="hidden" name="kind" value="<?= $kind ?>">
				<font size="-1">（<input type="checkbox" name="ck" onclick="OnClick_kind()">過去のバナーも表示 ）</font>
			</td>
			<td align="right">
				<input type="button" value=" 新規登録 " onclick="location.href='banner_set1.php'">
				<input type="button" value=" 戻る " onclick="location.href='../menu.php'"><br>
			</td>
		</tr>
	</table><br>
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>≪トップバナー≫</td>
			<td align="right" title="広告バナーを表示します"><a href='Javascript:onClick=onClick_view("top")' ><font size=-1>>> バナー表示</font></a></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=2 width='80%'>
		<tr bgcolor ="#ffcc99">
			<th class="h1" align="center"><nobr>タイトル</nobr></t>
			<th class="h1" align="center"><nobr>表示開始日</nobr></th>
			<th class="h1" align="center"><nobr>表示終了日</nobr></th>
		</tr>
<?
$sql = "SELECT BN_id, BN_kind, BN_title, to_char(BN_start_dt, 'YYYY/MM/DD hh24') as BN_start_dt,to_char(BN_end_dt, 'YYYY/MM/DD hh24') as BN_end_dt"
	. " FROM T_banner WHERE BN_kind = '1'" . and_where($kind) . " ORDER BY BN_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$bn_id = $fetch->bn_id;
	$bn_title = $fetch->bn_title;
	$bn_start_dt = $fetch->bn_start_dt;
	$bn_end_dt = $fetch->bn_end_dt;
	$bn_kind = $fetch->bn_kind;
?>
		<tr class="tc<?= bg_color($bn_start_dt, $bn_end_dt, $now); ?>">
			<td title="広告バナー設定を変更します"><a href='banner_set2.php?bn_id=<?= $bn_id ?>'><?= $bn_title ?></a></td>
			<td align="center"><?= $bn_start_dt ?>H</td>
			<td align="center"><?= $bn_end_dt ?>H</td>
		</tr>
	<?php
}
?>
	</table><br>
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>≪サイドバナー≫</td>
			<td align="right" title="広告バナーを表示します"><a href='Javascript:onClick=onClick_view("side")'><font size=-1>>> バナー表示</font></a></td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=2 width='80%'>
		<tr bgcolor ="#ffcc99">
			<th class="h1" align="center"><nobr>タイトル</nobr></t>
			<th class="h1" align="center"><nobr>表示開始日</nobr></th>
			<th class="h1" align="center"><nobr>表示終了日</nobr></th>
		</tr>
<?
$sql = "SELECT BN_id, BN_kind, BN_title, to_char(BN_start_dt, 'YYYY/MM/DD hh24') as BN_start_dt,to_char(BN_end_dt, 'YYYY/MM/DD hh24') as BN_end_dt"
	. " FROM T_banner WHERE BN_kind = '2'" . and_where($kind) . " ORDER BY BN_id";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$bn_id = $fetch->bn_id;
	$bn_title = $fetch->bn_title;
	$bn_start_dt = $fetch->bn_start_dt;
	$bn_end_dt = $fetch->bn_end_dt;
	$bn_kind = $fetch->bn_kind;
?>
		<tr class="tc<?= bg_color($bn_start_dt, $bn_end_dt, $now); ?>">
			<td title="広告バナー設定を変更します"><a href='banner_set2.php?bn_id=<?= $bn_id ?>'><?= htmlspecialchars($bn_title) ?></a></td>
			<td align="center"><?= $bn_start_dt ?>H</td>
			<td align="center"><?= $bn_end_dt ?>H</td>
		</tr>
<?	
}
	?>
	</table>
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
