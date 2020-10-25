<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:プロモーション管理
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

//=== 媒体選択肢 ===
function select_media($media_id) {
	echo '<option ', value_selected('', $media_id), '>- 指定なし -</option>';

	$sql = "SELECT md_media_id,md_name FROM m_media ORDER BY md_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->md_media_id, $media_id), ">$fetch->md_name</option>\n";
	}
}

//=== 広告タイプ選択肢 ===
function select_adv_type($adv_id) {
	echo '<option ', value_selected('', $adv_id), '>- 指定なし -</option>';

	$sql = "SELECT at_adv_id,at_name FROM m_adv_type ORDER BY at_order";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		echo '<option ', value_selected($fetch->at_adv_id, $adv_id), ">$fetch->at_name</option>\n";
	}
}

// メイン処理
set_global('monitor', 'モニター管理', 'プロモーション管理', BACK_TOP);

// セッション登録
get_session_vars($pset, 'promotion', 'media_id', 'adv_id', 'displine', 'sort_col', 'sort_dir', 'page');

// where条件
if ($media_id != '')
	and_join($where, "pr_media_id=$media_id");

if ($adv_id != '')
	and_join($where, "pr_adv_id=$adv_id");

if ($where != '')
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'pr_pr_id', 'pr_media_id', 'pr_pr_name', 'pr_adv_id', 'pr_haishin_suu', 'pr_click_suu', 'pr_haishin_start', 'pr_haishin_end', 'pr_click_count', 'wm_count', 'mn_count');

// 表示行数条件
$limit = disp_limit();

$sub1 = "SELECT wm_pr_id,COUNT(*) AS wm_count FROM w_monitor WHERE wm_pr_id IS NOT NULL GROUP BY wm_pr_id";
$sub2 = "SELECT mn_pr_id,COUNT(*) AS mn_count FROM t_monitor WHERE mn_status<>9 AND mn_pr_id IS NOT NULL GROUP BY mn_pr_id";

$sql = "SELECT pr_pr_id,pr_media_id,pr_pr_name,pr_adv_id,pr_haishin_suu,pr_click_suu,pr_haishin_start,pr_haishin_end,md_name,at_name,pr_click_count,COALESCE(wm_count,0)+COALESCE(mn_count,0) AS wm_count,mn_count"
		. " FROM t_promotion"
		. " LEFT JOIN m_media ON pr_media_id=md_media_id"
		. " LEFT JOIN m_adv_type ON pr_adv_id=at_adv_id"
		. " LEFT JOIN ($sub1) AS sub1 ON pr_pr_id=wm_pr_id"
		. " LEFT JOIN ($sub2) AS sub2 ON pr_pr_id=mn_pr_id"
		.	" $where $order_by $limit";
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
<form method="get" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■プロモーション一覧</td>
		<td class="lb">
			<input type="button" value="新規作成" onclick="location.href='edit.php'">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><? select_displine($displine) ?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>媒体<select name="media_id" onchange="submit()"><? select_media($media_id) ?></select>　</nobr>
			<nobr>広告タイプ<select name="adv_id" onchange="submit()"><? select_adv_type($adv_id) ?></select>　</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, 'PRID');
		sort_header(2, '媒体名');
		sort_header(3, 'PR名称');
		sort_header(4, '広告タイプ');
		sort_header(5, '配信数');
		sort_header(6, '保証ｸﾘｯｸ数');
		sort_header(7, '配信開始');
		sort_header(8, '配信終了');
		sort_header(9, 'ｸﾘｯｸ数');
		sort_header(10, '仮登録数');
		sort_header(11, '登録数');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?pr_id=<?=$fetch->pr_pr_id?>" title="プロモーション登録情報を表示・変更します"><?=$fetch->pr_pr_id?></a></td>
		<td><?=htmlspecialchars($fetch->md_name)?></td>
		<td><?=htmlspecialchars($fetch->pr_pr_name)?></td>
		<td><?=htmlspecialchars($fetch->at_name)?></td>
		<td align="right"><?=number_format($fetch->pr_haishin_suu)?></td>
		<td align="right"><?=number_format($fetch->pr_click_suu)?></td>
		<td align="center"><?=format_date($fetch->pr_haishin_start)?></td>
		<td align="center"><?=format_date($fetch->pr_haishin_end)?></td>
		<td align="right"><?=number_format($fetch->pr_click_count)?></td>
		<td align="right"><?=number_format($fetch->wm_count)?></td>
		<td align="right"><?=number_format($fetch->mn_count)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>