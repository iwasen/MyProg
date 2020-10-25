<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:全配信
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
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// 送信状態
function decode_send_status($code) {
	switch ($code) {
	case 0:
		return '未送信';
	case 1:
		return '送信済';
	}
	return '不明';
}

//メイン処理
set_global('etc', 'その他管理', '全配信', BACK_TOP);

// セッション登録
get_session_vars($pset, 'send_all', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 1, 'sa_send_id', 'sa_subject', 'sa_net_kind', 'sa_send_date', 'sa_status', 'sa_send_num');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT sa_send_id,sa_send_date,sa_subject,sa_status,sa_send_num,sa_net_kind"
		. " FROM t_send_all $order_by $limit";
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
		<td class="lt">■全配信メール一覧</td>
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

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
		sort_header(1, '番号');
		sort_header(2, '件名');
		sort_header(3, '配信対象');
		sort_header(4, '送信日');
		sort_header(5, '状態');
		sort_header(6, '送信数');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->sa_send_id?></td>
		<td><a href="edit.php?send_id=<?=$fetch->sa_send_id?>" title="メール内容を表示・変更します"><?=htmlspecialchars($fetch->sa_subject)?></a></td>
		<td align="center"><?=decode_net_kind($fetch->sa_net_kind, '全て')?></td>
		<td align="center"><?=format_datetime($fetch->sa_send_date)?></td>
		<td align="center"><?=decode_send_status($fetch->sa_status)?></td>
		<td align="right"><?=number_format($fetch->sa_send_num)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
