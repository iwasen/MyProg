<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:最新情報表示・過去のメッセージ一覧
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");

// スタッフ区分選択肢
function select_privilege($sel) {
	global $g_privilege;

	echo "<option ", value_selected('', $sel), ">- 全て -</option>";
	echo "<option ", value_selected(PRIV_DHSTAFF, $sel), ">DHスタッフ</option>";
	echo "<option ", value_selected(PRIV_OPERATOR, $sel), ">オペレータ</option>";
}

// 自分宛てメッセージ条件
$where = "WHERE (msg_to_staff_id is null OR msg_to_staff_id=$g_staff_id)";

// 日付条件
if ($date != '')
	and_join($where, "date_trunc('month', msg_regist_date)='$date/1'");

// 区分条件
if ($privilege != '')
	and_join($where, "msg_privilege='$privilege'");

// 表示行数
$limit = disp_limit();

// 表示順
$order_by = order_by(1, 0, 'msg_regist_date', 'msg_title', 'msg_message', 'msg_privilege');

// メッセージ一覧取得
$sql = "SELECT msg_regist_date,msg_title,msg_message,msg_privilege" .
			" FROM t_message" .
			" $where $order_by $limit";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
</head>
<body>

<? center_header('最新情報表示｜過去のメッセージ一覧') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■過去のメッセージ</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='top.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>日付<select name="date" onchange="submit()"><?select_ym('- 全て -', $date)?></select>&nbsp</nobr>
			<nobr>区分<select name="privilege" onchange="submit()"><?select_privilege($privilege)?></select>&nbsp</nobr>
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
		sort_header(1, '登録日付');
		sort_header(2, 'タイトル');
		sort_header(3, 'メッセージ');
		sort_header(4, '区分');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=format_date($fetch->msg_regist_date)?></td>
		<td><?=htmlspecialchars($fetch->msg_title)?></td>
		<td><pre><?=$fetch->msg_message?></pre></td>
		<td align="center"><?=decode_privilege($fetch->msg_privilege)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
