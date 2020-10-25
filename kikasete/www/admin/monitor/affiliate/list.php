<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:アフィリエイト管理一覧
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/affiliate.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('monitor', 'モニター管理', 'アフィリエイト管理', BACK_TOP);

// セッション登録
get_session_vars($pset, 'affiliate_list', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 1, 'afm_affiliate_mgr_id', 'afm_affiliate_name', 'afm_affiliate_type', 'afm_condition', 'afm_appl_start_date', 'afm_affiliate_start_date', 'afm_regist_count');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT afm_affiliate_mgr_id,afm_affiliate_name,afm_affiliate_type,afm_condition,afm_appl_start_date,afm_appl_end_date,afm_affiliate_start_date,afm_affiliate_end_date,afm_regist_count"
		. " FROM t_affiliate_mgr"
		. " WHERE afm_status=0"
		. " $order_by $limit";
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
		<td class="lt">■アフィリエイト一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../../menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, 'ID');
		sort_header(2, 'アフィリエイト名');
		sort_header(3, '種別');
		sort_header(4, '条件');
		sort_header(5, '応募期間');
		sort_header(6, 'アフィリエイト期間');
		sort_header(7, '総登録者数');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$affiliate_mgr_id = $fetch->afm_affiliate_mgr_id;

	// 総登録者数のリンク
	$regist_count = number_format($fetch->afm_regist_count);
	if ($fetch->afm_regist_count)
		$regist_count = "<a href='reg_list.php?affiliate_mgr_id=$affiliate_mgr_id&affiliate_id=0'>$regist_count</a>";
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?affiliate_mgr_id=<?=$affiliate_mgr_id?>" title="アフィリエイト登録内容を表示します"><?=$affiliate_mgr_id?></a></td>
		<td><a href="mem_list.php?affiliate_mgr_id=<?=$affiliate_mgr_id?>" title="申請者一覧を表示します"><?=htmlspecialchars($fetch->afm_affiliate_name)?></a></td>
		<td align="center"><?=decode_affiliate_type($fetch->afm_affiliate_type)?></td>
		<td><?=htmlspecialchars($fetch->afm_condition)?></td>
		<td align="center"><?=format_date($fetch->afm_appl_start_date)?>〜<?=format_date($fetch->afm_appl_end_date)?></td>
		<td align="center"><?=format_date($fetch->afm_affiliate_start_date)?>〜<?=format_date($fetch->afm_affiliate_end_date)?></td>
		<td align="right"><?=$regist_count?></td>
		</td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
