<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:コンビニマスタ一覧リスト表示
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

// コンビニステータス
function decode_conveni_status($code) {
	switch ($code) {
	case 0:
		return '有効';
	case 9:
		return '無効';
	}
	return '不明';
}

//メイン処理
set_global('etc', 'その他管理｜マスタメンテナンス', 'コンビニマスタ', BACK_TOP);

if ($del != '1')
	$where = "WHERE cv_status=0";
$order_by = order_by(1, 0, 'cv_order', 'cv_name');
$sql = "SELECT cv_conveni_cd,cv_status,cv_name,cv_order FROM m_conveni $where $order_by";
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
		<td class="lt">■コンビニマスタ一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr><input type="checkbox" name="del" <?=value_checked('1', $del)?> onclick="submit()">無効も表示</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '表示順序');
		sort_header(2, 'コンビニ名');
		sort_header(3, '状態');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?conveni_cd=<?=$fetch->cv_conveni_cd?>" title="コンビニマスタ情報を表示・変更します"><?= $fetch->cv_order ?></a></td>
		<td><?=htmlspecialchars($fetch->cv_name)?></td>
		<td align="center"><?=decode_conveni_status($fetch->cv_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
