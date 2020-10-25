<?
/******************************************************
' System :Eco-footprint 管理用ページ
' Content:住居面積マスタリスト表示
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
set_global('master', 'マスタ管理｜住居関連マスタメンテナンス', '住居面積マスタ', BACK_TOP);

$order_by = order_by(1, 0, 'sp_space_cd', 'sp_space_text', 'sp_space_from', 'sp_space_to');
$sql = "SELECT sp_space_cd, sp_space_from, sp_space_to, sp_space_text FROM m_space $order_by";
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
		<td class="lt">■住居面積マスタ一覧</td>
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
		sort_header(1, '番号');
		sort_header(2, '住居面積名');
		sort_header(3, '下限');
		sort_header(4, '上限');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?sp_cd=<?=$fetch->sp_space_cd?>" title="住居面積マスタ情報を表示・変更します"><?=$fetch->sp_space_cd?></a></td>
		<td><?=htmlspecialchars($fetch->sp_space_text)?></td>
		<td align="right"><?=$fetch->sp_space_from?></td>
		<td align="right"><?=$fetch->sp_space_to?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
