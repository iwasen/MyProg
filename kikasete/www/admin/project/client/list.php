<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:企業/担当者マスタ一覧表示
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/list.php");

//表示カラー
function value_color($id) {
	global $agent_id;
	if ($id == $agent_id) {
		echo 2;
	} else {
		echo 1;
	}
}

//メイン処理
set_global2('project', 'プロジェクト管理｜マスタ登録', '企業/担当者一覧', BACK_TOP);

// セッション登録
get_session_vars($pset, 'cl_list', 'direction', 'sort_col', 'sort_dir');

//表示条件
if (!$direction) {
	$direction = 1;
}
if (SERVICE == 'agent' && $direction == '1') {
	$where = " WHERE ag_agent_id=$agent_id";
} else {
	$where = "";
}

$order_by = order_by(1, 0, 'cl_client_id,ts_name_kana', 'cl_name_kana,ts_name_kana','ts_name_kana','ag_name_kana,ts_name_kana');
$sql = "SELECT cl_client_id,cl_name,cl_name_kana,ts_name1,ts_tantousha_id,ts_name2,ts_name1_kana||ts_name2_kana AS ts_name_kana,ag_agent_id,ag_name1,ag_name2,ag_name1_kana||ag_name2_kana AS ag_name_kana"
	. " FROM m_client LEFT JOIN m_tantousha ON cl_client_id=ts_client_id LEFT JOIN t_agent ON ts_agent_id=ag_agent_id"
	. " $where $order_by";
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
function direction_check(num) {
	var f = document.form1;
	f.direction.value = num;
	f.submit();
}
//-->
</script>
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td colspan=2>■<?=$g_title?></td>
		<td class="lb">
			<input type="button" value="企業新規登録" onclick="location.href='cl_new.php'">
<?
if (SERVICE == 'admin') {
?>
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
<?
}
?>
		</td>
	</tr>
<?
if (SERVICE == 'agent') {
?>
	<tr>
		<td colspan=3 class="lc">
		<input type="radio" name="direction_c" onclick="direction_check(1)" <?=value_checked(1, $direction)?>>担当企業のみ
		<input type="radio" name="direction_c" onclick="direction_check(9)" <?=value_checked(9, $direction)?>>全て</td>
	</tr>
<?
}
?>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width=95%>
	<tr class="tch">
<?
sort_header(1, 'ID');
sort_header(2, '企業名');
sort_header(3, '担当者');
sort_header(4, 'エージェント');
?>
	</tr>
<?
$same_client = new join_same_item;
$same_client->prepare($result, 'cl_client_id');

$j = 1;
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$row_count = $same_client->get_row_count();
	if ($row_count > 0)
		$j++;
?>
	<tr class="tc<?=$j % 2?>">
<?
	if ($row_count > 0) {
?>
		<td align="center" rowspan=<?=$row_count?>><a href="cl_edit.php?cl_id=<?=$fetch->cl_client_id?>" title="企業の登録情報を更新します"><?=$fetch->cl_client_id?></a></td>
		<td rowspan=<?=$row_count?>><a href="ts_new.php?cl_id=<?=$fetch->cl_client_id?>" title="担当者を登録します"><?=htmlspecialchars($fetch->cl_name)?></a></td>
<?
	}
	if ($fetch->ts_tantousha_id == null) {
?>
		<td><br></td>
<?
	} else {
?>
		<td><a href='ts_edit.php?ts_id=<?=$fetch->ts_tantousha_id?>' title="担当者を登録情報を更新します"><?=htmlspecialchars("$fetch->ts_name1 $fetch->ts_name2")?></a></td>
<?
	}
?>
		<td><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2")?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
