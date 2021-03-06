<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:対象条件マスタ一覧表示
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/list.php");

set_global2('project', 'プロジェクト管理｜テンプレート編集', '対象条件テンプレート一覧', BACK_TOP);

$order_by = order_by(1, 1, 'ps_seq_no', 'ps_title','ps_regist_date');
$sql = "SELECT ps_seq_no,ps_title,ps_regist_date FROM m_pjt_search $order_by";
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
<table border=0 cellspacing=0 cellpadding=1 width="95%">
	<tr>
		<td colspan=2>■<?=$g_title?></td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../submenu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="direction" <?=value($direction)?>>
<input type="hidden" name="pset" value=1>
</form>
<table <?=LIST_TABLE?> width="95%">
	<tr class="tch">
<?
		sort_header(1, 'seq');
		sort_header(2, 'タイトル');
		sort_header(3, '登録日');
?>
	</tr>
<?

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=htmlspecialchars($fetch->ps_seq_no)?></td>
		<td align="left">
			<a href="edit.php?ps_no=<?=$fetch->ps_seq_no?>" title="対象条件テンプレートを修正します"><?=htmlspecialchars($fetch->ps_title)?></a>
		</td>
		<td align="center"><?=format_datetime($fetch->ps_regist_date)?></td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
