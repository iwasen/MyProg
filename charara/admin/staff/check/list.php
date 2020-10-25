<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:似顔絵チェックリスト 
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
include("$inc/decode.php");

// 無効も表示
function check_flag($flag) {
	if ($flag)
		return "checked";
}

// 表示色
function check_color($status) {
	if ($status == 9)
		echo "<font color='gray'>";
	else
		echo "<font color='black'>";
}

// 納期チェック
function check_nouki($nouki_date, $dl_mail_date) {
	if ($nouki_date != '') {
		if ($dl_mail_date == '') {
			// 納期超過
			if ($nouki_date < date("Y-m-d H:i:s"))
				echo "<font color='red'><b>" . format_datetime($nouki_date) . "</b></font>";
			// 納期まで24時間以内
			elseif (add_datetime($nouki_date, 0 , 0, 0, -24, 0, 0) <= date("Y-m-d H:i:s"))
				echo "<font color='red'>" . format_datetime($nouki_date) . "</font>";
			// それ以外
			else
				echo format_datetime($nouki_date);
		} else {
			// 納品済み
			echo "<font color='green'>" . format_datetime($nouki_date) . "</font>";
		}
	} else {
		echo '未設定';
	}
}

//メイン処理
set_global('staff', 'スタッフ情報管理', '似顔絵チェックリスト', BACK_TOP);

// セッション登録
get_session_vars($pset, 'staff_list', 'kind', 'org', 'name', 'mail_addr', 'priority', 'level', 'period', 'invalid', 'displine', 'sort_col', 'sort_dir', 'page');

// where条件
and_join($where, "nd_status=6");

if ($kind != '')
	and_join($where, "so_kind_cd=" . $kind);

if ($org != '')
	and_join($where, "st_organization=" . $org);

if ($name != '')
	and_join($where, "st_name1 LIKE '%$name%'");

if ($mail_addr != '')
	and_join($where, "st_mail_addr LIKE '%$mail_addr%'");

if ($priority != '')
	and_join($where, "st_priority_rank=" . $priority);

if ($level != '')
	and_join($where, "st_level_rank=" . $level);

$where = "WHERE $where";

// ソート条件
$order_by = ORDER_BY(7, 0, 'nd_random_id', 'nd_campaign_cd', 'st_name1||st_name2', 'st_mail_addr', 'nd_priority', 'nd_photo_date', 'nd_nouki_date', 'nd_completion_date');

// 表示行数条件
$limit = disp_limit();

$sql = "select count(nd_nigaoe_id) as count from t_nigaoe_data $where";
$num = db_fetch1($sql);

$sql = "SELECT nd_nigaoe_id,nd_status,nd_server_id,nd_random_id,nd_campaign_cd,nd_photo_date,nd_completion_date,nd_nouki_date,nd_nouki_date,nd_dl_mail_date"
		. ",nd_priority,nd_staff_id,st_mail_addr"
		. " FROM t_nigaoe_data LEFT JOIN m_staff ON nd_staff_id=st_staff_id $where $order_by $limit";
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
		<td class="lt">■似顔絵リスト　<font size=-1 color="blue">（総数:&nbsp;<?=$num?>名）</font></td>
		<td class="lb" align="right">
			<input type="button" value="　戻る　" onclick='location.href="<?=$top?>/menu.php"'>
		</td>
	</tr>
	<tr>
		<td colspan=2 class="lc">
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>></nobr><br>
			<nobr>種別<select name="kind"><?=select_staff_kind('全て', $kind)?></select>&nbsp;</nobr>
			<nobr>所属<select name="org"><?=select_staff_org('全て',$kind)?></select>&nbsp;</nobr>
			<nobr>名前（漢字)姓<input type="text" name="name" size=10 <?=value($name)?>>
			<nobr>ﾒｰﾙｱﾄﾞﾚｽ<input type="text" name="mail_addr" <?=value($mail_addr)?>>&nbsp;</nobr>
			<nobr>優先度<input type="text" name="priority" <?=value($priority)?> size=7>&nbsp;</nobr>
			<nobr>レベル<select name="level"><?=select_level_rank('全て', $level)?></select>&nbsp;</nobr>
			<nobr>期間外も表示<input type="checkbox" name="period" <?=check_flag($period)?>></nobr>
			<nobr>無効も表示<input type="checkbox" name="invalid" <?=check_flag($invalid)?>></nobr>
			<input type="button" value="検索" onclick="submit()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small2">
	<tr class="tch">
<?
		sort_header(1, '似顔絵ID');
		sort_header(2, 'CP');
		sort_header(3, '担当氏名');
		sort_header(4, 'メールアドレス');
		sort_header(5, '優先度');
		sort_header(6, '写真受付');
		sort_header(7, '納期');
		sort_header(8, '完成');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$status = $fetch->st_status;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><a href="edit.php?id=<?=$fetch->nd_nigaoe_id?>"><?=htmlspecialchars($fetch->nd_random_id)?></a></td>
		<td><?=htmlspecialchars($fetch->nd_campaign_cd)?></td>
		<td><?=decode_staff_name($fetch->nd_staff_id)?></td>
		<td align="center"><a href="mailto:<?=$fetch->st_mail_addr?>" title="スタッフにメールします"><?=htmlspecialchars($fetch->st_mail_addr)?></a></td>
		<td align="center"><?=$fetch->nd_priority?></td>
		<td align="center"><?=format_date($fetch->nd_photo_date)?></td>
		<td align="center"><?=check_nouki($fetch->nd_nouki_date, $fetch->nd_dl_mail_date)?></td>
		<td align="center"><?=format_datetime($fetch->nd_completion_date)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? page_footer() ?>
</body>
</html>
