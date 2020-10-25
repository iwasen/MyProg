<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:クライアントログイン履歴
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/format.php");
include("$inc/list.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// ログイン結果選択肢
function select_login_result($sel) {
	echo "<option ", value_selected('', $sel), ">- 指定なし -</option>";
	echo "<option ", value_selected('t', $sel), ">成功</option>";
	echo "<option ", value_selected('f', $sel), ">失敗</option>";
}

//メイン処理
set_global('client', 'クライアント管理', 'ログイン履歴', BACK_TOP);

// where条件
$where = 'WHERE lg_member_kind=3';

if ($date_y != '')
	and_join($where, 'lg_date=' . sql_date("$date_y/$date_m/$date_d"));

if ($mail_addr != '')
	and_join($where, 'lg_login_id LIKE ' . sql_char("%$mail_addr%"));

if ($login_result != '')
	and_join($where, $login_result == 't' ? 'lg_member_id IS NOT NULL' : 'lg_member_id IS NULL');

if ($ip_addr != '')
	and_join($where, 'lg_ip_addr LIKE ' . sql_char("%$ip_addr%"));

// ソート条件
$order_by = order_by(1, 1, 'lg_date', 'lg_login_id', 'lg_member_id', 'cl_name1||cl_name2', 'lg_ip_addr');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT lg_date,lg_login_id,lg_member_id,cl_name1,cl_name2,lg_ip_addr"
		. " FROM l_login LEFT JOIN m_client ON lg_member_id=cl_client_id"
		. " $where $order_by $limit";
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
		<td class="lt">■クライアントログイン履歴一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>ログイン日付<input type="text" name="date_y" size=4 <?=value($date_y)?>>年
			<select name="date_m"><? select_month('', $date_m) ?></select>月
			<select name="date_d"><? select_day('', $date_d) ?></select>日　</nobr>
			<nobr>メールアドレス<input type="text" name="mail_addr" <?=value($mail_addr)?>>　</nobr>
			<nobr>ログイン結果<select name="login_result"><? select_login_result($login_result) ?></select>　</nobr>
			<nobr>ＩＰアドレス<input type="text" name="ip_addr" size=17 <?=value($ip_addr)?>>　</nobr>
			<input type="button" value="検索" onclick="submit()">
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
		sort_header(1, 'ログイン日時');
		sort_header(2, 'メールアドレス');
		sort_header(3, 'ログイン結果');
		sort_header(4, 'クライアント名');
		sort_header(5, 'ＩＰアドレス');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_datetime($fetch->lg_date)?></td>
		<td><a href="<?=$top?>/common/client_info.php?mail_addr=<?=$fetch->lg_login_id?>" target="_blank" title="クライアント情報を表示します"><?=htmlspecialchars($fetch->lg_login_id)?></a></td>
		<td align="center"><?=$fetch->lg_member_id ? '成功' : '<font color="red">失敗</font>'?></td>
		<td><?=htmlspecialchars("$fetch->cl_name1 $fetch->cl_name2")?></td>
		<td align="center"><?=$fetch->lg_ip_addr?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
