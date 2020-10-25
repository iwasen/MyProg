<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");

//メイン処理
set_global('myenquete', 'Ｍｙアンケート管理', 'スペシャルパネル管理', BACK_TOP);

// セッション登録
get_session_vars($pset, 'panel', 'displine', 'sort_col', 'sort_dir');

// ソート条件
$order_by = order_by(7, 0, 'pnl_panel_id', 'pnl_panel_name', 'pnl_regist_date', 'pnl_update_date', 'pnm_count', 'pnl_monitor_update_date', 'pnl_display_order', 'pnl_display_status');

$sql = sprintf("SELECT pnl_panel_id,pnl_category_id,pnl_panel_name,pnl_regist_date,pnl_update_date,pnl_monitor_update_date,pnl_display_order,pnl_display_status,pnm_count"
		. " FROM t_panel"
		. " LEFT JOIN (SELECT pnm_panel_id,COUNT(*) AS pnm_count FROM t_panel_monitor_list JOIN t_monitor ON mn_monitor_id=pnm_monitor_id WHERE mn_status<>9 GROUP BY pnm_panel_id) Q1 ON pnm_panel_id=pnl_panel_id"
		. " WHERE pnl_category_id=%s"
		. " $order_by",
		sql_number($category_id));
$result = db_exec($sql);
$nrow = pg_numrows($result);

$panel_id_ary = array();
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$panel_id_ary[] = "\"$fetch->pnl_panel_id\"";
}
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
function onclick_change_order() {
	var f = document.form2;
	var ids = new Array(<?=join(',', $panel_id_ary)?>);
	var orders = new Array();
	for (var i = 0; i < ids.length; i++) {
		var order = f["order[" + ids[i] + "]"];
		if (!check_num(order.value)) {
			alert("表示順が正しくありません。");
			order.focus();
			return;
		}
		if (order.value != "") {
			for (var j = 0; j < orders.length; j++) {
				if (orders[j] == order.value) {
					alert("同じ表示順(" + order.value + ")が存在します。");
					order.focus();
					return;
				}
			}
			orders.push(order.value);
		}
	}
	if (confirm("対象条件表示順を更新します。よろしいですか？")) {
		f.next_action.value = "order";
		f.submit();
	}
}
function check_num(n) {
	for (i = 0; i < n.length; i++){
		if (n.charAt(i) < "0" || n.charAt(i) > "9") {
			return false;
		}
	}
	return true;
}
function onclick_status(id) {
	var f = document.form2;
	if (confirm("対象条件表示を変更します。よろしいですか？")) {
		f.panel_id.value = id;
		f.next_action.value = "status";
		f.submit();
	}
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
		<td class="lt">■スペシャルパネル一覧</td>
		<td class="lb">
			<input type="button" value="表示順更新" onclick="onclick_change_order()">
			<input type="button" value="新規登録" onclick="location.href='panel_new.php?category_id=<?=$_REQUEST['category_id']?>'">
			<input type="button" value="　戻る　" onclick="location.href='category_list.php?category_id=<?=$_REQUEST['category_id']?>'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<form method="post" name="form2" action="panel_update.php">
<input type="hidden" name="next_action">
<input type="hidden" name="category_id" <?=value($_REQUEST['category_id'])?>>
<input type="hidden" name="panel_id">
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, 'パネルID');
		sort_header(2, 'パネル名');
		sort_header(3, 'パネル登録日時');
		sort_header(4, 'パネル更新日時');
		sort_header(5, '登録モニター人数');
		sort_header(6, '登録モニター更新日時');
		sort_header(7, '対象条件表示順');
		sort_header(8, '対象条件表示');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="panel_edit.php?category_id=<?=$fetch->pnl_category_id?>&panel_id=<?=$fetch->pnl_panel_id?>" title="スペシャルパネルを表示・変更します"><?=$fetch->pnl_panel_id?></a></td>
		<td><?=htmlspecialchars($fetch->pnl_panel_name)?></td>
		<td align="center"><?=format_datetime($fetch->pnl_regist_date)?></td>
		<td align="center"><?=format_datetime($fetch->pnl_update_date)?></td>
		<td align="right"><a href="monitor_list.php?category_id=<?=$_REQUEST['category_id']?>&panel_id=<?=$fetch->pnl_panel_id?>" title="パネルモニター一覧を表示します"><?=number_format($fetch->pnm_count)?></a></td>
		<td align="center"><?=format_datetime($fetch->pnl_monitor_update_date)?></td>
		<td align="center"><input type="text" size="6" name="order[<?=$fetch->pnl_panel_id?>]" <?=value($fetch->pnl_display_order)?>></td>
		<td align="center"><input type="button" <?=value($fetch->pnl_display_status ? '○表示中' : '×非表示')?> onclick="onclick_status(<?=$fetch->pnl_panel_id?>)"></td>
	</tr>
<?
}
?>
</table>
</form>
</div>

<? page_footer() ?>
</body>
</html>
