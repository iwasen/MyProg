<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:都市名マスタリスト表示
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");

//メイン処理
set_global('master', 'マスタ管理｜地域マスタ', '都市名マスタ', BACK_TOP);

$order_by = order_by(1, 0, 'ci_seq_no', 'ci_todoufuken', 'ci_shiku');
$sql = "SELECT ci_seq_no, ci_todoufuken, ci_shiku FROM m_city WHERE ci_shiku IS NOT NULL $order_by";
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
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■都市名マスタ一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '都市名ID');
		sort_header(2, '都道府県名');
		sort_header(3, '都市名');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?ci_no=<?=$fetch->ci_seq_no?>" title="都市名情報を表示・変更します"><?=$fetch->ci_seq_no?></a></td>
		<td><?=htmlspecialchars($fetch->ci_todoufuken)?></td>
		<td><?=htmlspecialchars($fetch->ci_shiku)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
