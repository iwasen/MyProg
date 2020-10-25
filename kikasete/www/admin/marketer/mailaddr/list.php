<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メールアドレス発行
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
include("$inc/format.php");
include("$inc/select.php");
include("$inc/list.php");

//メイン処理
set_global('marketer', 'マーケター管理', 'メールアドレス発行', BACK_TOP);

// セッション登録
get_session_vars($pset, 'marketer_mailaddr', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 0, 'mr_mail_addr', 'mr_name1_kana||mr_name2_kana', 'mr_type', 'mr_regist_date');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT mr_marketer_id,mr_mail_addr,mr_name1,mr_name2,mr_type,mr_regist_date"
		. " FROM t_marketer WHERE mr_status<>9 AND (mr_type=2 OR mr_type=3) AND mr_free_email IS NULL $order_by $limit";
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
		<td class="lt">■メールアドレス未発行者一覧</td>
		<td class="lb">
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
		sort_header(1, '登録メールアドレス');
		sort_header(2, '名前');
		sort_header(3, '種別');
		sort_header(4, '登録日');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?marketer_id=<?=$fetch->mr_marketer_id?>" title="メールアドレスを発行します"><?=htmlspecialchars($fetch->mr_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?></td>
		<td align="center"><?=decode_marketer_type($fetch->mr_type)?></td>
		<td align="center"><?=format_date($fetch->mr_regist_date)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
