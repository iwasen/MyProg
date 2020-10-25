<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メールニュース配信
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");

// 送信状態
function decode_news_status($code) {
	switch ($code) {
	case 0:
		return '未送信';
	case 1:
		return '送信済';
	case 2:
		return '送信中';
	}
	return '不明';
}

//メイン処理
set_global('monitor', 'モニター管理', 'メールニュース配信', BACK_TOP);

// セッション登録
get_session_vars($pset, 'monitor_news', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 1, 'ns_news_id', 'ns_subject', 'ns_send_date', 'ns_status', 'ns_send_num');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT ns_news_id,ns_send_date,ns_subject,ns_status,ns_send_num,ns_send_assign,ns_bunkatsu_flag"
		. " FROM t_mail_news WHERE ns_target=1 $order_by $limit";
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
		<td class="lt">■メールニュース一覧</td>
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
		sort_header(0, '送信回数');
		sort_header(3, '送信日');
		sort_header(4, '状態');
		sort_header(5, '送信数');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->ns_bunkatsu_flag == DBTRUE) {
		$sql = "SELECT mns_seq_no,mns_status,mns_send_date,mns_send_num FROM t_mail_news_send WHERE mns_news_id=$fetch->ns_news_id ORDER BY mns_seq_no";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
?>
	<tr class="tc<?=$i % 2?>">
<?
			if ($j == 0) {
?>
		<td align="center" rowspan="<?=$nrow2?>"><?=$fetch->ns_news_id?></td>
		<td rowspan="<?=$nrow2?>"><a href="edit.php?news_id=<?=$fetch->ns_news_id?>" title="メールニュース内容を表示・変更します"><?=htmlspecialchars($fetch->ns_subject)?></a></td>
<?
			}
?>
		<td align="center"><?=$fetch2->mns_seq_no?>回目</td>
		<td align="center"><?=format_datetime($fetch2->mns_send_date)?></td>
		<td align="center"><?=decode_news_status($fetch2->mns_status)?></td>
		<td align="right"><?=number_format($fetch2->mns_send_num)?></td>
	</tr>
<?
		}
	} else {
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->ns_news_id?></td>
		<td><a href="edit.php?news_id=<?=$fetch->ns_news_id?>" title="メールニュース内容を表示・変更します"><?=htmlspecialchars($fetch->ns_subject)?></a></td>
		<td align="center">-</td>
		<td align="center"><?=format_datetime($fetch->ns_send_date, '即時')?></td>
		<td align="center"><?=decode_news_status($fetch->ns_status)?></td>
		<td align="right"><?=number_format($fetch->ns_status == 1 ? $fetch->ns_send_num : $fetch->ns_send_assign)?></td>
	</tr>
<?
	}
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
