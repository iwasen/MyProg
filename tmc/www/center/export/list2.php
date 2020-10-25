<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:エクスポートデータ一覧表示
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

// セッション変数処理
get_session_vars($_POST['pset'], 'export_list2', 'from_y', 'from_m', 'from_d', 'from_h', 'to_y', 'to_m', 'to_d', 'to_h', 'sort_col', 'sort_dir');

// 日時取得
if ($_POST['from_y']) {
	$from_y = $_POST['from_y'];
	$from_m = $_POST['from_m'];
	$from_d = $_POST['from_d'];
	$from_h = $_POST['from_h'];

	$mday = date('t', mktime(0, 0, 0, $from_m, 1, $from_y));
	if ($from_d > $mday)
		$from_d = $mday;

	$to_y = $_POST['to_y'];
	$to_m = $_POST['to_m'];
	$to_d = $_POST['to_d'];
	$to_h = $_POST['to_h'];

	$mday = date('t', mktime(0, 0, 0, $to_m, 1, $to_y));
	if ($to_d > $mday)
		$to_d = $mday;
} else {
	$time = time();
	$today = getdate($time);
	$yesterday = getdate($time - 24 * 60 * 60);

	$from_y = $yesterday['year'];
	$from_m = $yesterday['mon'];
	$from_d = $yesterday['mday'];
	$from_h = 10;

	$to_y = $today['year'];
	$to_m = $today['mon'];
	$to_d = $today['mday'];
	$to_h = 10;
}

$from_date = sprintf('%04d/%02d/%02d %02d:00:00', $from_y, $from_m, $from_d, $from_h);
$to_date = sprintf('%04d/%02d/%02d %02d:00:00', $to_y, $to_m, $to_d, $to_h);

// 販売店メール一覧取得
$order_by = order_by(1, 0, 'dlr_dealer_cd', 'dlr_dealer_name', 'handover_count');
$s01 = "SELECT urg_dealer_cd,COUNT(*) AS handover_count"
		. " FROM t_user_regist"
		. " WHERE urg_handover_date>='$from_date' AND urg_handover_date<'$to_date'"
		. " GROUP BY urg_dealer_cd";
$sql = "SELECT dlr_dealer_cd,dlr_dealer_name,handover_count"
		. " FROM ($s01) AS s01"
		. " JOIN t_dealer ON dlr_dealer_cd=urg_dealer_cd"
		. " $order_by";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<script type="text/javascript">
<!--
function onclick_export() {
	var f = document.form1;
	if (confirm("CSVファイルをエクスポートします。よろしいですか？")) {
		if (f["export[]"]) {
			var c = f["export[]"];
			if (c.length) {
				for (var i = 0; i < c.length; i++)
					if (c[i].checked)
						export_csv(c[i].value);
			} else
				export_csv(c.value);
		}
	}
}
function export_csv(dealer_cd) {
	window.open("csv2.php?dealer_cd=" + dealer_cd + "&from_date=<?=$from_date?>&to_date=<?=$to_date?>");
}
function onclick_all_select(check) {
	var f = document.form1;
	if (f["export[]"]) {
		var c = f["export[]"];
		if (c.length) {
			for (var i = 0; i < c.length; i++)
				c[i].checked = check;
		} else
			c.checked = check;
	}
}
//-->
</script>
<? list_script() ?>
</head>
<body>

<? center_header('Mailフォロー結果エクスポート') ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■エクスポートデータ一覧</td>
		<td class="lb" style="color:navy;font-size:85%">
			<input type="radio" onclick="location.href='list1.php'">エクスポート一覧表
			<input type="radio" checked>エクスポート日時指定一覧
		</td>
	</tr>
	<tr>
		<td class="lc">
			<nobr>
			<select name="from_y"><? select_year(2003, '', $from_y) ?></select>年
			<select name="from_m"><? select_month('', $from_m) ?></select>月
			<select name="from_d"><? select_day('', $from_d) ?></select>日
			<select name="from_h"><? select_hour('', $from_h) ?></select>時
			</nobr>〜<nobr>
			<select name="to_y"><? select_year(2003, '', $to_y) ?></select>年
			<select name="to_m"><? select_month('', $to_m) ?></select>月
			<select name="to_d"><? select_day('', $to_d) ?></select>日
			<select name="to_h"><? select_hour('', $to_h) ?></select>時
			</nobr>
			<input type="submit" value="表示">
		</td>
		<td class="lc" align="right" nowrap>
			<input type="button" value="全選択" onclick="onclick_all_select(true)">
			<input type="button" value="全解除" onclick="onclick_all_select(false)">
			<input type="button" value="エクスポート" onclick="onclick_export()">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($_GET['sort_col'])?>>
<input type="hidden" name="sort_dir" <?=value($_GET['sort_dir'])?>>
<input type="hidden" name="pset" value=1>

<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
<?
sort_header(1, '販売店CD');
sort_header(2, '販売店名');
sort_header(3, '引継ぎ件数');
sort_header(0, 'ｴｸｽﾎﾟｰﾄ');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->dlr_dealer_cd?></td>
		<td><?=htmlspecialchars($fetch->dlr_dealer_name)?></td>
		<td align="right"><?=$fetch->handover_count?></td>
		<td align="center"><input type="checkbox" name="export[]" value="<?=$fetch->dlr_dealer_cd?>" checked>
	</tr>
<?
}
?>
</table>
</form>
</div>

<? center_footer() ?>

</body>
</html>
