<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:バッチログ
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// 送信状態
function decode_batch_status($code) {
	switch ($code) {
	case 0:
		return '正常';
	case 9:
		return '<font color="red">エラー</font>';
	}
	return '不明';
}

//メイン処理
set_global('etc', 'その他管理', 'バッチログ', BACK_TOP);

// ソート条件
$order_by = order_by(1, 1, 'lb_date', 'lb_file_name', 'lb_description', 'lb_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT lb_date,lb_file_name,lb_description,lb_status"
		. " FROM l_batch $order_by $limit";
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
function onsubmit_form2() {
	return confirm("バッチログを削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■バッチログ</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '処理日時');
		sort_header(2, 'ファイル名');
		sort_header(3, '処理内容');
		sort_header(4, '状態');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->lb_date)?></td>
		<td align="center"><?=$fetch->lb_file_name?></td>
		<td><?=htmlspecialchars($fetch->lb_description)?></td>
		<td align="center"><?=decode_batch_status($fetch->lb_status)?></td>
	</tr>
<?
}
?>
</table>
<br>
<form method="post" name="form2" action="update.php" onclick="return onsubmit_form2()">
<input type="submit" value="　削除　">
</form>
</div>

<? page_footer() ?>
</body>
</html>
