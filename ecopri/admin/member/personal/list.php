<?
/******************************************************
' System :Eco-footprint 管理ページ
' Content:会員個人検索
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

// 登録状況
function decode_step($code) {
	switch($code) {
	case 1:
		return "仮登録１";
		break;
	case 2:
		return "仮登録２";
		break;
	case 3:
		return "本登録";
		break;
	case 9:
		return "退会";
		break;
	default:
		return "-不明-";
	}
}

// 登録状況select
function select_step($default,$selected) {
	if ($default)
		echo "<option value=''>$default</option>", "\n";
	
	echo '<option ', value_selected(1, $selected), '>仮登録１のみ</option>', "\n";
	echo '<option ', value_selected(2, $selected), '>仮登録２のみ</option>', "\n";
	echo '<option ', value_selected(3, $selected), '>本登録のみ</option>', "\n";
}

// 種別select
function select_kind($selected) {
	echo '<option ', value_selected('', $selected), '>一般会員</option>', "\n";
	echo '<option ', value_selected(1, $selected), '>関係者</option>', "\n";
}

//メイン処理
set_global('member', '会員情報管理', '会員個人検索', BACK_TOP);

// セッション登録
get_session_vars($pset, 'member_personal', 'and_or', 'mail_addr', 'name1', 'name2', 'name1_kana', 'name2_kana', 'birthday_y', 'birthday_m', 'birthday_d', 'regist_y', 'regist_m', 'regist_d', 'kind', 'type', 'displine', 'sort_col', 'sort_dir', 'page');

// where条件
if ($member_id != '')
	and_or_join($where, "mb_id LIKE '%$member_id%'", $and_or);

if ($mail_addr != '')
	and_or_join($where, "mb_mail_addr LIKE '%$mail_addr%'", $and_or);

if ($name1 != '')
	and_or_join($where, "mb_name1 LIKE '%$name1%'", $and_or);

if ($name2 != '')
	and_or_join($where, "mb_name2 LIKE '%$name2%'", $and_or);

if ($name1_kana != '')
	and_or_join($where, "mb_name1_kana LIKE '%$name1_kana%'", $and_or);

if ($name2_kana != '')
	and_or_join($where, "mb_name2_kana LIKE '%$name2_kana%'", $and_or);

if ($birthday_y != '')
	and_or_join($where, "mb_birthday=" . sql_date("$birthday_y/$birthday_m/$birthday_d"), $and_or);

if ($regist_y != '')
	and_or_join($where, "DATE_TRUNC('day', mb_regist_date)=" . sql_date("$regist_y/$regist_m/$regist_d"), $and_or);

if ($step != '')
	and_or_join($where, "mb_step=$step", $and_or);


if ($where != '')
	$where = "($where)";
and_join($where, 'mb_step<>9');
if ($kind != '')
	and_join($where, "mb_kind=$kind", $and_or);
else
	and_join($where, "mb_kind IS NULL", $and_or);
$where = "WHERE $where";

// ソート条件
$order_by = order_by(6, 1, 'mb_id', 'mb_mail_addr', 'mb_name1||mb_name2', 'mb_name1_kana||mb_name2_kana', 'mb_birthday', 'mb_regist_date','mb_step');

// 表示行数条件
$limit = disp_limit();

$sql = "select count(mb_seq_no) as count from t_member $where";
$num = db_fetch1($sql);

$sql = "select mb_seq_no,mb_id,mb_mail_addr,mb_step,mb_name1,mb_name2,mb_name1_kana,mb_name2_kana,mb_birthday,mb_regist_date"
		. " from t_member $where $order_by $limit";
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
		<td class="lt">■会員一覧　<font size=-1 color="blue">（総数:&nbsp;<?=$num?>名）</font></td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='find.php'">
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>　</nobr>
			<nobr>会員ID<input type="text" name="member_id" <?=value($member_id)?> size=7>　</nobr>
			<nobr>ﾒｰﾙｱﾄﾞﾚｽ<input type="text" name="mail_addr" <?=value($mail_addr)?>>　</nobr>
			<nobr>名前（漢字)姓<input type="text" name="name1" size=10 <?=value($name1)?>>
			名<input type="text" name="name2" size=10 <?=value($name2)?>>　</nobr>
			<nobr>名前（カナ)姓<input type="text" name="name1_kana" size=10 <?=value($name1_kana)?>>
			名<input type="text" name="name2_kana" size=10 <?=value($name2_kana)?>>　</nobr>
			<nobr>生年月日<input type="text" name="birthday_y" size=4 <?=value($birthday_y)?>>年
			<select name="birthday_m"><?select_month('', $birthday_m)?></select>月
			<select name="birthday_d"><?select_day('', $birthday_d)?></select>日　</nobr>
			<nobr>登録日<input type="text" name="regist_y" size=4 <?=value($regist_y)?>>年
			<select name="regist_m"><?select_month('', $regist_m)?></select>月
			<select name="regist_d"><?select_day('', $regist_d)?></select>日　</nobr>
			<nobr>登録状況<select name="step"><?=select_step('全て',$step)?></select></nobr>
			<nobr>種別<select name="kind"><?=select_kind($kind)?></select></nobr>
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
		sort_header(6, '登録日付');
		sort_header(7, '登録状況');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><a href="edit.php?seq_no=<?=$fetch->mb_seq_no?>" title="会員登録情報を表示・変更します"><?=$fetch->mb_id?></a></td>
		<td><?=htmlspecialchars($fetch->mb_mail_addr)?></td>
		<td><?=htmlspecialchars("$fetch->mb_name1 $fetch->mb_name2")?></td>
		<td><?=htmlspecialchars("$fetch->mb_name1_kana $fetch->mb_name2_kana")?></td>
		<td align="center"><?=str_replace('-', '/', $fetch->mb_birthday)?></td>
		<td align="center"><?=format_date($fetch->mb_regist_date)?></td>
		<td align="center"><?=decode_step($fetch->mb_step)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
