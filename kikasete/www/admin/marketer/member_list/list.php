<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メンバーリスト
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
include("inc.php");

//メイン処理
set_global($class, $section, 'メンバーリスト一覧', BACK_TOP);

$order_by = order_by(1, 0, 'mmg_group_id', 'mmg_group_name', 'mmg_regist_date', 'mml_count');

$sub1 = "SELECT mml_group_id,COUNT(*) AS mml_count FROM t_myenq_member_list GROUP BY mml_group_id";
$sql = "SELECT mmg_group_id,mmg_group_name,mmg_regist_date,mmg_update_date,mml_count"
		. " FROM t_myenq_member_grp LEFT JOIN ($sub1) AS sub1 ON mmg_group_id=mml_group_id"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_numrows($result);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
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

<form method="post" name="form1">
<table border=0 cellpadding=0 cellspacing=1 width="100%">
	<tr>
		<td class="lt">■メンバーリスト一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='<?=$mypro == 'my' ? "$top/marketer/myenq/list.php" : "$top/proenq/manage/list.php"?>'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<form method="post" name="form2">
<table border=1 cellpadding=0 cellspacing=1 width="100%">
	<tr class="tch">
<?
		sort_header(1, 'メンバーリストID');
		sort_header(2, 'メンバーリスト名');
		sort_header(3, '作成日');
		sort_header(4, '最終更新日');
		sort_header(5, '件数');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?group_id=<?=$fetch->mmg_group_id?>" title="メンバーリストを変更します。"><?=$fetch->mmg_group_id?></a></td>
		<td><a href="m_list.php?group_id=<?=$fetch->mmg_group_id?>" title="メンバーリストを表示します。"><?=htmlspecialchars($fetch->mmg_group_name)?></a></td>
		<td align="center"><?=format_date($fetch->mmg_regist_date)?></td>
		<td align="center"><?=format_date($fetch->mmg_update_date)?></td>
		<td align="right"><?=number_format($fetch->mml_count)?></td>
	</tr>
<?
}
?>
</table>
</form>

<? page_footer() ?>
</body>
</html>
