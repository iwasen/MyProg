<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:誕生日メール配信管理
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
include("$inc/list.php");

//メイン処理
set_global('monitor', 'モニター管理', '誕生日メール配信', BACK_TOP);

// セッション登録
get_session_vars($pset, 'birthday_mail', 'displine', 'sort_col', 'sort_dir', 'page');

// ソート条件
$order_by = order_by(1, 0, 'bm_priority', 'bm_sex', 'bm_age_from', 'bm_subject', 'bm_void_flag');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT bm_seq_no, bm_priority, bm_sex, bm_age_from, bm_age_to, bm_subject, bm_void_flag"
		. " FROM t_birthday_mail"
		. " $order_by $limit";
$result = db_exec($sql);
$nrow = pg_numrows($result);

// 有効/無効
function decode_void_flag($code) {
	switch ($code) {
	case 1:
		return '有効';
	case 2:
		return '無効';
	}
}

// 年齢範囲
function get_age_range($from, $to) {
	if($from && $to)
		return $from . '才〜' . $to . '才';
	else {
		if ($from)
			return $from . '才以上';
		elseif ($to)
			return $to . '才以下';
		else
			return '設定なし';
	}
}
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
		<td class="lt">■誕生日メール一覧</td>
		<td class="lb">
			<input type="button" value="新規追加" onclick="location.href='new.php'">
			<input type="button" value="　戻る　" onclick="location.href='<?=$top?>/menu.php'">
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
		sort_header(1, '優先順位');
		sort_header(2, '性別');
		sort_header(3, '年齢範囲');
		sort_header(4, '件名');
		sort_header(5, '有効');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?seq_no=<?=$fetch->bm_seq_no?>" title="誕生日メールを変更します"><?=$fetch->bm_priority?></a></td>
		<td align="center"><?=decode_sex($fetch->bm_sex)?></td>
		<td align="center"><?=get_age_range($fetch->bm_age_from, $fetch->bm_age_to)?></td>
		<td><?=htmlspecialchars($fetch->bm_subject)?></td>
		<td align="center"><?=decode_void_flag($fetch->bm_void_flag)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
