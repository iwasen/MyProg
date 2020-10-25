<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:調査結果アップロード一覧
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/list.php");
include("$inc/select.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

//メイン処理
set_global('etc', 'その他管理', '調査結果アップロード', BACK_TOP);

// セッション登録
get_session_vars($pset, 'report', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 1, 'rp_report_id', 'rp_regist_date', 'rp_chain_cd', 'rp_title', 'rp_from_date');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT rp_report_id,rp_regist_date,COALESCE(ch_name,gt_gyoutai_name) AS ch_gt_name,rp_title,rp_from_date,rp_to_date"
		. " FROM t_report"
		. " LEFT JOIN m_chain ON ch_chain_cd=rp_chain_cd"
		. " LEFT JOIN m_gyoutai ON gt_gyoutai_cd=rp_gyoutai_cd"
		. " $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
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
		<td class="lt">■調査結果一覧</td>
		<td class="lb">
			<input type="button" value="新規アップロード" onclick="location.href='new.php'">
			<input type="button" value="新規アップロード（複数選択）" onclick="location.href='new_multi.php'">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
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
		sort_header(2, '登録日時');
		sort_header(3, 'チェーン');
		sort_header(4, 'タイトル');
		sort_header(5, '調査期間');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?report_id=<?=$fetch->rp_report_id?>" title="調査結果を表示します"><?=$fetch->rp_report_id?></a></td>
		<td align="center"><?=format_datetime($fetch->rp_regist_date)?></td>
		<td><?=htmlspecialchars($fetch->ch_gt_name)?></td>
		<td><?=htmlspecialchars($fetch->rp_title)?></td>
		<td align="center"><?=format_date($fetch->rp_from_date)?> 〜 <?=format_date($fetch->rp_to_date)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
