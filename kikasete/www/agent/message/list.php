<?
/******************************************************
' System :きかせて・netエージェント用ページ
' Content:事務局からのお知らせ一覧リスト表示
'******************************************************/

$top = '..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/define.php");
include("$inc/format.php");
include("$inc/list.php");

//メイン処理
$where = "WHERE cs_agent_mypage AND cs_status=0";

//最新/選択メッセージ表示
if ($cs_no) {
	$and_where = "AND cs_seq_no=$cs_no";
}
$sql = "SELECT cs_title,cs_message FROM t_center_msg $where $and_where ORDER BY cs_start_date DESC";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$msg_title = htmlspecialchars($fetch->cs_title);
	$msg = nl2br($fetch->cs_message);
} else {
	$msg_title = "事務局からのお知らせ";
	$msg = "現在は、ありません。";
}

//リスト表示
set_global('事務局からのお知らせ', BACK_TOP);
$order_by = order_by(1, 1, 'cs_end_date', 'cs_title');
$sql = "SELECT cs_seq_no, cs_title, cs_start_date, cs_end_date FROM t_center_msg $where $order_by";
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
<table border=0 cellspacing=0 cellpadding=1 width=95%>
	<tr>
		<td>■<?=$g_title?></td>
	</tr>
</table><br>
<table border=0 cellspacing=0 cellpadding=1 width=95%>
	<tr>
		<td>
			<table border=1 cellspacing=0 cellpadding=1 width=420 align="left">
				<tr>
					<td class="lt"><?=$msg_title?></td>
				</tr>
				<tr>
					<td><tt><?=$msg?></tt></td>
				</tr>
			</table>
		</tr>
	</td>
</table><br>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="cs_no" <?=value($cs_no)?>>
</form>
<table <?=LIST_TABLE?> width=95%>
	<tr class="tch">
<?
		sort_header(1, '表示日');
		sort_header(2, 'タイトル');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_date($fetch->cs_start_date)."〜".format_date($fetch->cs_end_date)?></td>
		<td align="left">
			<a href="list.php?sort_col=<?=$sort_col?>&sort_dir=<?=$sort_dir?>&cs_no=<?=$fetch->cs_seq_no?>" title="事務局からのお知らせ情報を表示します"><?=htmlspecialchars($fetch->cs_title)?></a>
		</td>
	</tr>
<?
}
?>
</table>

<? menu_end() ?>
<? page_footer() ?>

</body>
</html>
