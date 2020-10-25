<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:事務局からのお知らせ一覧リスト表示
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");

//メイン処理
set_global('member', '会員トップページ設定', '事務局からのお知らせ', BACK_TOP);

// ソート条件
$order_by = order_by(3, 1, 'an_seq_no', 'an_title', 'an_start_date', 'an_end_date', 'an_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT * FROM t_admin_msg $order_by $limit";
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
		<td class="lt">■事務局からのお知らせ一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='../menu.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '番号');
		sort_header(2, 'タイトル');
		sort_header(3, '開始日');
		sort_header(4, '終了日');
		sort_header(0, '表示');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	if (date("Y-m-d") >= $fetch->an_start_date && date("Y-m-d") <= $fetch->an_end_date)
		$disp = "<font color='red'>表示中</font>";
	elseif (date("Y-m-d") > $fetch->an_end_date)
		$disp = "終了";
	else
		$disp = "";
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->an_seq_no?></td>
		<td><a href="edit.php?an_no=<?=$fetch->an_seq_no?>" title="事務局からのお知らせ情報を表示・変更します"><?=htmlspecialchars($fetch->an_title)?></a></td>
		<td align="center"><?=format_date($fetch->an_start_date)?></td>
		<td align="center"><?=format_date($fetch->an_end_date)?></td>
		<td align="center"><?=$disp?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
