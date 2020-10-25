<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:販売店メール一覧表示
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/center.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/list.php");
include("$inc/select.php");

// 送信状態選択肢
function select_sendmail_status($default, $selected) {
	if ($default)
		echo "<option value=''>$default</option>\n";

	echo "<option ", value_selected('1', $selected), ">未作成</option>\n";
	echo "<option ", value_selected('2', $selected), ">送信待</option>\n";
	echo "<option ", value_selected('3', $selected), ">送信中</option>\n";
	echo "<option ", value_selected('4', $selected), ">送信済</option>\n";
}

// 送信状態表示
function decode_status($status) {
	switch ($status) {
	case 1:
		return '<font color="green">未作成</font>';
	case 2:
		return '<font color="brown">送信待</font>';
	case 3:
		return '<font color="red">送信中</font>';
	case 4:
		return '<font color="blue">送信済</font>';
	}
	return "不明";
}

// セッション変数処理
get_session_vars($_GET['pset'], 'dealermail', 'subject', 'date', 'status', 'displine', 'sort_col', 'sort_dir', 'page');

$where = '';

// 件名条件
if ($subject != '')
	and_join($where, "dml_subject LIKE '%$subject%'");

// 日付条件
if ($date != '')
	and_join($where, "date_trunc('month', dml_send_date)='$date/1'");

// 状態条件
if ($status != '')
	and_join($where, "dml_status=$status");

if ($where)
	$where = "WHERE $where";

// 表示行数条件付加
$limit = disp_limit();

// 販売店メール一覧取得
$order_by = order_by(1, 1, 'dml_dealer_mail_id', 'dml_subject', "coalesce(dml_send_date,'2000/01/01')", 'dml_status;dml_dealer_mail_id');
$sql = "SELECT dml_dealer_mail_id,dml_subject,dml_send_date,dml_status" .
			" FROM t_dealer_mail" .
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

<? center_header('販売店メール配信') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録済み販売店メール一覧</td>
		<td class="lb">
			<input type="button" value="新規登録" onclick="location.href='new.php'">
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>表示行数<select name="displine" onchange="submit()"><?select_displine($displine)?></select><input type="button" value="前頁" onclick="page.value=<?=$page - 1?>;submit()"<?=disabled($page == 0)?>><input type="button" value="次頁"onclick="page.value=<?=$page + 1?>;submit()"<?=disabled($displine == 0 || $nrow < $displine)?>>&nbsp</nobr>
			<nobr>件名<input type="text" name="subject" <?=value($subject)?>><input type="submit" value="検索">&nbsp</nobr>
			<nobr>日付<select name="date" onchange="submit()"><?select_ym('- 全て -', $date)?></select>&nbsp</nobr>
			<nobr>状態<select name="status" onchange="submit()"><?select_sendmail_status('- 全て -', $status)?></select>&nbsp</nobr>
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($_GET['sort_col'])?>>
<input type="hidden" name="sort_dir" <?=value($_GET['sort_dir'])?>>
<input type="hidden" name="page" value=0>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1, 'SEQ');
sort_header(2, '件名');
sort_header(3, '送信日時');
sort_header(4, '状態');
?>
	</tr>
<?
$same_id = new join_same_item;
$same_id->prepare($result, 'dml_dealer_mail_id');

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$row_count = $same_id->get_row_count();
?>
	<tr class="tc<?=$i % 2?>">
<?
	$project_id = $fetch->pj_project_id;
	if ($row_count > 0) {
?>
		<td align="center" rowspan=<?=$row_count?>><a href="contents.php?dealer_mail_id=<?=$fetch->dml_dealer_mail_id?>" title="販売店メールの表示・変更を行います"><?=$fetch->dml_dealer_mail_id?></a></td>
<?
	}
?>
		<td><?=htmlspecialchars($fetch->dml_subject)?></td>
		<td align="center"><?=format_datetime($fetch->dml_send_date)?></td>
		<td align="center"><?=decode_status($fetch->dml_status)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
