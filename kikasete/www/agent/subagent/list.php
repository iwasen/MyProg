<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:サブエージェント一覧リスト表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/list.php");

//メイン処理
set_global('サブエージェント一覧', BACK_TOP);

$order_by = order_by(1, 0, 'ag_agent_id','ag_mail_addr', 'ag_name1_kana||ag_name2_kana', 'ag_kinmu_name');
$sql = "SELECT ag_agent_id,ag_mail_addr,ag_name1,ag_name2,ag_kinmu_name FROM t_agent WHERE ag_main_agent=$agent_id $order_by";
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
<? menu_script() ?>
</head>
<body>

<? page_header() ?>
<? menu_start() ?>

<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td colspan=4>■<?=$g_title?></td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'ｴｰｼﾞｪﾝﾄID');
		sort_header(2, 'メールアドレス');
		sort_header(3, '名前');
		sort_header(4, '企業名');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?subagent_id=<?=$fetch->ag_agent_id?>" title="サブエージェントの登録情報を表示／変更します"><?=htmlspecialchars($fetch->ag_agent_id)?></td>
		<td><?=htmlspecialchars($fetch->ag_mail_addr)?></td>
		<td><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2")?></td>
		<td><?=htmlspecialchars($fetch->ag_kinmu_name)?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
