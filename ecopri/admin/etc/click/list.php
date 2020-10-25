<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員CD-ROMクリック履歴
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// ログイン結果選択肢
function select_login_result($sel) {
	echo "<option ", value_selected('', $sel), ">- 指定なし -</option>";
	echo "<option ", value_selected('t', $sel), ">成功</option>";
	echo "<option ", value_selected('f', $sel), ">失敗</option>";
}

//メイン処理
set_global('etc', 'その他管理', 'CD-ROMクリック履歴', BACK_TOP);

// where条件
if ($date_y != '')
	and_join($where, 'date_trunc(\'day\',cd_date)=' . sql_date("$date_y/$date_m/$date_d"));

if ($ip_addr != '')
	and_join($where, 'cd_ip_addr LIKE ' . sql_char("%$ip_addr%"));

if ($where != '') {
	$where = "($where)";
	$where = "WHERE $where";
}

// ソート条件
$order_by = order_by(1, 1, 'cd_date', 'cd_ip_addr');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT count(cd_ip_addr) as count FROM l_cd_click $where";
$num = db_fetch1($sql);

$sql = "SELECT cd_date,cd_ip_addr FROM l_cd_click $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
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
<? page_header() ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■CD-ROMクリック一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$num?>回）</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>クリック日付<input type="text" name="date_y" size=4 <?=value($date_y)?>>年
			<select name="date_m"><? select_month('', $date_m) ?></select>月
			<select name="date_d"><? select_day('', $date_d) ?></select>日　</nobr>
			<nobr>ＩＰアドレス<input type="text" name="ip_addr" size=17 <?=value($ip_addr)?>>　</nobr>
			<input type="button" value="検索" onclick="submit()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, 'クリック日時');
		sort_header(2, 'ＩＰアドレス');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->cd_date)?></td>
		<td align="center"><?=$fetch->cd_ip_addr?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
