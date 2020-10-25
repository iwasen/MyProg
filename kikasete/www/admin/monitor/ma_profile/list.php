<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロファイル手動追加一覧
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

//メイン処理
set_global('monitor', 'モニター管理', 'プロファイル手動追加', BACK_TOP);

// セッション登録
get_session_vars($pset, 'ma_profile', 'sort_col', 'sort_dir');

// ソート条件
$order_by = order_by(1, 0, 'pfi_profile_id', 'pfi_order', 'pfi_profile_name') . ",pfs_order";

$sql = "SELECT pfi_profile_id,pfi_profile_name,pfi_order,pfi_hidden_flag,pfs_select_text,pfs_order"
		. " FROM t_profile_item"
		. " LEFT JOIN t_profile_sel ON pfs_profile_id=pfi_profile_id"
		. " $order_by";
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
		<td class="lt">■追加プロファイル一覧</td>
		<td class="lb">
			<input type="button" value="新規追加" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '項目ID');
		sort_header(2, '表示順');
		sort_header(3, '項目名');
		sort_header(0, '表示');
		sort_header(0, '選択肢');
		sort_header(0, '選択肢表示順');
?>
	</tr>
<?
$same_item = new join_same_item;
$same_item->prepare($result, 'pfi_profile_id');

$n = 1;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$profile_id = $fetch->pfi_profile_id;
	$row_count = $same_item->get_row_count();

	if ($row_count > 0)
		$n++;
?>
	<tr class="tc<?=$n % 2?>">
<?
	if ($row_count > 0) {
?>
		<td align="center" rowspan=<?=$row_count?>><?=$profile_id?></td>
		<td align="center" rowspan=<?=$row_count?>><?=$fetch->pfi_order?></td>
		<td rowspan=<?=$row_count?>><a href="edit.php?profile_id=<?=$fetch->pfi_profile_id?>" title="プロファイル項目を表示・変更します"><?=htmlspecialchars($fetch->pfi_profile_name)?></a></td>
		<td align="center" rowspan=<?=$row_count?>><?=$fetch->pfi_hidden_flag == DBTRUE ? '非表示' : '表示'?></td>
<?
	}
?>
		<td><?=htmlspecialchars($fetch->pfs_select_text)?></td>
		<td align="center"><?=$fetch->pfs_order?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
