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
get_session_vars($_POST['pset'], 'export_list1', 'from_y', 'from_m', 'from_d', 'from_h', 'to_y', 'to_m', 'to_d', 'to_h', 'sort_col', 'sort_dir');

// 日時取得
if ($_POST['year']) {
	$year = $_POST['year'];
	$month = $_POST['month'];
} else {
	$today = getdate();

	$year = $today['year'];
	$month = $today['mon'];
}

$date = sprintf('%04d/%02d/01', $year, $month);

// 月の日数取得
$mdays = date('t', mktime (0, 0, 0, $month, 1, $year));

// 販売店メール一覧取得
$order_by = order_by(1, 0, 'dlr_dealer_cd', 'dlr_short_name', 'upt_upload_cd;dlr_dealer_cd');
$handover_date = "urg_handover_date + interval '795 minutes'";
$s01 = "SELECT urg_dealer_cd,to_char($handover_date,'DD') AS day, COUNT(*) AS handover_count"
		. " FROM t_user_regist"
		. " WHERE date_trunc('month',$handover_date)='$date'"
		. " GROUP BY urg_dealer_cd,day";
$sql = "SELECT dlr_dealer_cd,upt_upload_cd,day,dlr_short_name,handover_count,upt_bgcolor"
		. " FROM t_dealer"
		. " JOIN t_upload_dealer ON upd_dealer_cd=dlr_dealer_cd"
		. " JOIN t_upload_tantou ON upt_upload_cd=upd_upload_cd"
		. " LEFT JOIN ($s01) AS s01 ON urg_dealer_cd=dlr_dealer_cd"
		. " WHERE upt_active=true"
		. " $order_by,day";
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

<? center_header('Mailフォロー結果エクスポート') ?>

<div align="center">
<form method="post" name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■エクスポートデータ一覧</td>
		<td class="lb" style="color:navy;font-size:85%">
			<input type="radio" checked>エクスポート一覧表
			<input type="radio" onclick="location.href='list2.php'">エクスポート日時指定一覧
		</td>
	</tr>
	<tr>
		<td class="lc" colspan=2>
			<nobr>
			<select name="year"><? select_year(2003, '', $year) ?></select>年
			<select name="month"><? select_month('', $month) ?></select>月
			<input type="submit" value="表示">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($_GET['sort_col'])?>>
<input type="hidden" name="sort_dir" <?=value($_GET['sort_dir'])?>>
<input type="hidden" name="pset" value=1>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
sort_header(1, '販売店<br>CD', 2);
sort_header(2, '販売店名', 2);
sort_header(3, 'ｱｯﾌﾟﾛｰﾄﾞ<br>担当', 2);
?>
		<th colspan=<?=$mdays?>><?=$month?>月</th>
	</tr>
	<tr class="tch">
<?
for ($i = 1; $i <= $mdays; $i++) {
?>
		<th width="3%"><a href="csv1.php?date=<?=sprintf('%04d/%02d/%02d', $year, $month, $i)?>"><?=$i?></a></th>
<?
}
?>
	</tr>
<?
$i = 0;
while ($i < $nrow) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr bgcolor="<?=$fetch->upt_bgcolor == '' ? '#ffffff' : $fetch->upt_bgcolor?>">
		<td align="center"><?=$fetch->dlr_dealer_cd?></td>
		<td><?=htmlspecialchars($fetch->dlr_short_name)?></td>
		<td align="center"><?=htmlspecialchars($fetch->upt_upload_cd)?></td>
<?
	$dealer_cd = $fetch->dlr_dealer_cd;
	for ($j = 1; $j <= $mdays; $j++) {
		$count = 0;
		if ($i < $nrow) {
			$fetch = pg_fetch_object($result, $i);
			if ($fetch->dlr_dealer_cd == $dealer_cd) {
				if ($fetch->day == $j) {
					$count = $fetch->handover_count;
					$i++;
				} elseif ($fetch->day == '')
					$i++;
			}
		}
?>
		<td align="right"><?=number_format($count)?></td>
<?
	}
?>
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
