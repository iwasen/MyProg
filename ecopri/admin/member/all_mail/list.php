<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:全配信メール設定
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
	}
	return '不明';
}

// decode配信対象
function decode_target($code) {
	switch ($code) {
	case 4:
		return '全員';
	case 1:
		return '仮登録１';
	case 2:
		return '仮登録２';
	case 3:
		return '本登録';
	}
	return '不明';
}

// 登録状況select
function select_step($default,$selected) {
	if ($default)
		echo "<option value=''>$default</option>", "\n";
	echo '<option ', value_selected(1, $selected), '>仮登録１会員のみ</option>', "\n";
	echo '<option ', value_selected(2, $selected), '>仮登録２会員のみ</option>', "\n";
	echo '<option ', value_selected(3, $selected), '>本登録会員のみ</option>', "\n";
	echo '<option ', value_selected(4, $selected), '>全ての会員</option>', "\n";
}


//メイン処理
set_global('member', '会員情報管理', '全配信メール設定', BACK_TOP);

// セッション登録
get_session_vars($pset, 'monitor_news', 'displine', 'target', 'sort_col', 'sort_dir', 'page');

// where条件
if ($target != '')
	and_join($where, "am_target=$target");

if ($where != '')
	$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 1, 'am_seq_no', 'am_target', 'am_subject', 'am_send_date', 'am_status', 'am_send_num');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT am_seq_no,am_target,am_send_date,am_subject,am_status,am_send_num"
		. " FROM t_all_mail $where $order_by $limit";
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
		<td class="lt">■全配信メール一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr>
			<nobr>配信対象<select name="target" onchange="submit()"><?=select_step('全て', $target)?></select></nobr>
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
		sort_header(2, '対象');
		sort_header(3, '件名');
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
		<td align="center"><?=$fetch->am_seq_no?></td>
		<td align="center"><?=decode_target($fetch->am_target)?></td>
		<td><a href="edit.php?am_id=<?=$fetch->am_seq_no?>" title="完了督促メール内容を表示・変更します"><?=htmlspecialchars($fetch->am_subject)?></a></td>
		<td align="center"><?=format_datetime($fetch->am_send_date)?></td>
		<td align="center"><?=decode_news_status($fetch->am_status)?></td>
		<td align="right"><?=number_format($fetch->am_send_num)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
