<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:１万人アンケート管理
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

// アンケート状態
function decode_enq_status($code) {
	switch ($code) {
	case 0:
		return '作成中';
	case 4:
		return '実施待ち';
	case 5:
		return '実施中';
	case 6:
		return '一時停止';
	case 7:
		return '終了';
	case 9:
		return '削除済み';
	}
	return '不明';
}

//メイン処理
set_global('monitor', 'モニター管理', '１万人アンケート管理', BACK_TOP);

// セッション登録
get_session_vars($pset, 'monitor_enquete', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 1, 'fe_seq_no', 'en_title', 'en_start_date', 'en_end_date', 'en_status', 'em_sum', 'eu_sum');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT fe_seq_no,en_title,en_start_date,en_end_date,en_status,COALESCE(em_sum,0) AS em_sum,COALESCE(eu_sum,0) AS eu_sum"
		. " FROM t_free_enquete"
		. " JOIN t_enquete ON fe_enquete_id=en_enquete_id"
		. " LEFT JOIN t_enquete_sum ON em_enquete_id=fe_enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0"
		. " LEFT JOIN t_episode_sum ON eu_seq_no=fe_seq_no"
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
		<td class="lt">■１万人アンケート一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
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

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
		sort_header(1, '番号');
		sort_header(2, 'お題');
		sort_header(3, '開始日時');
		sort_header(4, '終了日時');
		sort_header(5, '状態');
		sort_header(6, '回答者数');
		sort_header(7, 'ｴﾋﾟｿｰﾄﾞ');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->fe_seq_no?></td>
		<td><a href="edit.php?seq_no=<?=$fetch->fe_seq_no?>" title="１万人アンケート内容を表示・変更します"><?=htmlspecialchars($fetch->en_title)?></a></td>
		<td align="center"><?=format_datetime($fetch->en_start_date)?></td>
		<td align="center"><?=format_datetime($fetch->en_end_date)?></td>
		<td align="center"><?=decode_enq_status($fetch->en_status)?></td>
<?
if ($fetch->em_sum != 0) {
?>
		<td align="right"><a href="result.php?seq_no=<?=$fetch->fe_seq_no?>" title="アンケート結果を表示します"><?=number_format($fetch->em_sum)?></a></td>
<?
} else {
?>
		<td align="right">0</td>
<?
}
if ($fetch->eu_sum != 0) {
?>
		<td align="right"><a href="episode.php?seq_no=<?=$fetch->fe_seq_no?>" title="エピソードを表示します"><?=number_format($fetch->eu_sum)?></a></td>
<?
} else {
?>
		<td align="right">0</td>
<?
}
?>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
