<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:エージェント個人検索
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

// エージェント状態
function decode_agent_status($code, $def='設定なし') {
	switch ($code) {
	case 0:
		return '有効';
	case 1:
		return '仮登録';
	case 9:
		return '無効';
	}
	return $def;
}

//メイン処理
set_global('agent', 'エージェント管理', 'エージェント個人検索', BACK_TOP);

// セッション登録
get_session_vars($pset, 'agent_personal', 'and_or', 'mail_addr', 'name1', 'name2', 'name1_kana', 'name2_kana', 'birthday_y', 'birthday_m', 'birthday_d', 'kinmu_name', 'disp_flag', 'displine', 'sort_col', 'sort_dir', 'page');

// where条件
if ($mail_addr != '')
	and_or_join($where, "ag_mail_addr LIKE '%$mail_addr%'", $and_or);

if ($name1 != '')
	and_or_join($where, "ag_name1 LIKE '%$name1%'", $and_or);

if ($name2 != '')
	and_or_join($where, "ag_name2 LIKE '%$name2%'", $and_or);

if ($name1_kana != '')
	and_or_join($where, "ag_name1_kana LIKE '%$name1_kana%'", $and_or);

if ($name2_kana != '')
	and_or_join($where, "ag_name2_kana LIKE '%$name2_kana%'", $and_or);

if ($birthday_y != '')
	and_or_join($where, "ag_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d"), $and_or);

if ($kinmu_name != '')
	and_or_join($where, "ag_kinmu_name LIKE '%$kinmu_name%'", $and_or);

if ($where != '')
	$where = "($where)";
and_join($where, 'ag_status<>9');
$where = "WHERE $where";

// ソート条件
$order_by = order_by(1, 0, 'ag_agent_id', 'ag_mail_addr', 'ag_name1||ag_name2', 'ag_name1_kana||ag_name2_kana', 'ag_birthday', 'ag_kinmu_name', 'ag_disp_flag', 'ag_status');

// 表示行数条件
$limit = disp_limit();

$sql = "SELECT ag_agent_id,ag_mail_addr,ag_name1,ag_name2,ag_name1_kana,ag_name2_kana,ag_birthday,ag_kinmu_name,ag_disp_flag,ag_status"
		. " FROM t_agent $where $order_by $limit";
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
		<td class="lt">■エージェント一覧</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='find.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>メールアドレス<input type="text" name="mail_addr" <?=value($mail_addr)?>>　</nobr>
			<nobr>名前（漢字)姓<input type="text" name="name1" size=10 <?=value($name1)?>>
			名<input type="text" name="name2" size=10 <?=value($name2)?>>　</nobr>
			<nobr>名前（カナ)姓<input type="text" name="name1_kana" size=10 <?=value($name1_kana)?>>
			名<input type="text" name="name2_kana" size=10 <?=value($name2_kana)?>>　</nobr>
			<nobr>生年月日<input type="text" name="birthday_y" size=4 <?=value($birthday_y)?>>年
			<select name="birthday_m"><?select_month('', $birthday_m)?></select>月
			<select name="birthday_d"><?select_day('', $birthday_d)?></select>日　</nobr>
			<nobr>企業名<input type="text" name="kinmu_name" size=10 <?=value($kinmu_name)?>>　</nobr>
			<nobr>結合条件<input type="radio" name="and_or" <?=value_checked('A', $and_or)?>>AND
			<input type="radio" name="and_or" <?=value_checked('O', $and_or)?>>OR　</nobr>
			<input type="button" value="検索" onclick="submit()">
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
		sort_header(1, 'ID');
		sort_header(2, 'メールアドレス');
		sort_header(3, '名前');
		sort_header(4, '名前（カナ）');
		sort_header(5, '生年月日');
		sort_header(6, '企業名');
		sort_header(7, '表示フラグ');
		sort_header(8, '状態');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->ag_agent_id?></td>
		<td><a href="edit.php?agent_id=<?=$fetch->ag_agent_id?>" title="エージェント登録情報を表示・変更します"><?=htmlspecialchars($fetch->ag_mail_addr)?></a></td>
		<td><?=htmlspecialchars("$fetch->ag_name1 $fetch->ag_name2")?></td>
		<td><?=htmlspecialchars("$fetch->ag_name1_kana $fetch->ag_name2_kana")?></td>
		<td align="center"><?=format_date($fetch->ag_birthday)?></td>
		<td><?=htmlspecialchars($fetch->ag_kinmu_name)?></td>
		<td align="center"><?=$fetch->ag_disp_flag == 't' ? '○' : ''?></td>
		<td align="center"><?=decode_agent_status($fetch->ag_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
