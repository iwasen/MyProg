<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」キャンペーン事務局用ページ
' Content:ログイン画面処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/select.php");

// 登録年月選択
function select_regist_ym($min_follow_date, $max_follow_date, $selected) {
	$date = sql_time($min_follow_date);
	$min_y = (int)date('Y', $date);
	$min_m = (int)date('m', $date);

	$date = sql_time($max_follow_date);
	$max_y = (int)date('Y', $date);
	$max_m = (int)date('m', $date);

	$y = $min_y;
	$m = $min_m;
	option_tag('', $selected, '全ての年月');
	while ($y * 12 + $m <= $max_y * 12 + $max_m) {
		option_tag(sprintf('%04d%02d', $y,$m), $selected, "{$y}年{$m}月");
		if (++$m > 12) {
			$y++;
			$m = 1;
		}
	}
}

// 入力パラメータ
$sales_channel_cd = $_POST['sales_channel_cd'];
$ym = $_POST['ym'];
$campaign_cd = $_POST['campaign_cd'];

// 最新／最古のメールフォロー開始日時を取得
$sql = "SELECT min(urg_follow_date) AS min_follow_date,max(urg_follow_date) AS max_follow_date FROM t_user_regist";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$min_follow_date = $fetch->min_follow_date;
	$max_follow_date = $fetch->max_follow_date;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title>キャンペーン事務局用ページ</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#1ea22f" align="center" width="100%" height="50">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="10" bgcolor="#daf8de" align="center" width="100%" height="100%">
		<tr>
			<td class="titlefont">キャンペーン事務局用ページ</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#88d1fd" width="100%" class="text14">
<tr>
	<td>販売店別ユーザー数一覧</td>
</tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="800" class="text14" align="center">
<tr>
	<td width="400">
		<form name="from1" method="post">
			<select name="sales_channel_cd" class="textbox"><? select_sales_channel('全販売店', $sales_channel_cd) ?></select>
			<select name="ym" class="textbox"><? select_regist_ym($min_follow_date, $max_follow_date, $ym) ?></select>
			<select name="campaign_cd" class="textbox"><? select_campaign('全てのキャンペーン', $campaign_cd) ?></select>
			<input type="submit" value="表示" class="button"></td>
	<td align="right" width="400">
			<input type="button" name="" value="全データのCSVダウンロード" class="button" onclick="location.href='csv_seikyu.php?sales_channel_cd=<?=$sales_channel_cd?>&ym=<?=$ym?>&campaign_cd=<?=$campaign_cd?>'">
		</form></td>
</tr>
</table>
<br>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#88d1fd" align="center" width="800" class="text12">
<tr>
	<td>
		<table border="0" cellspacing="2" cellpadding="4" width="800" align="center" class="text12" bgcolor="#ffffff" id="list">
<?
if ($ym != '')
	and_join($cond, "to_char(urg_follow_date, 'YYYYMM')='$ym'");

if ($campaign_cd != '')
	and_join($cond, "urg_campaign_cd='$campaign_cd'");

if ($cond != '')
	$cond = "AND $cond";

if ($sales_channel_cd != '')
	$where = "WHERE sch_sales_channel_cd=$sales_channel_cd";

$sql = "SELECT sch_sales_channel_cd,sch_sales_channel_name"
		. " FROM t_sales_channel"
		. " $where"
		. " ORDER BY sch_sales_channel_cd";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$sales_channel_cd = $fetch->sch_sales_channel_cd;
?>
<tr bgcolor="#dbdbdb">
	<th colspan="5" align="left"><?=htmlspecialchars($fetch->sch_sales_channel_name)?></th>
</tr>
<tr bgcolor="#88d1fd">
	<th width="200">販売店名</th>
	<th width="150">メールフォロー<br>依頼件数</th>
	<th width="150">メールフォロー<br>実施件数</th>
	<th width="150">初期エラー件数</th>
	<th width="150">未確定（不達エラー）件数</th>
</tr>
<?
	$sql = "SELECT dlr_dealer_cd,dlr_dealer_name"
			. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') THEN 1 END) AS count_jisshi"
			. ",COUNT(CASE WHEN ust_user_id IS NULL OR urs_rank='L' THEN 1 END) AS count_err"
			. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=urg_user_id AND cml_err_mail_flag=false) THEN 1 END) AS count_futatsu"
			. " FROM t_dealer"
			. " JOIN t_user_regist ON urg_dealer_cd=dlr_dealer_cd"
			. " LEFT JOIN t_user_status ON ust_user_id=urg_user_id"
			. " LEFT JOIN t_user_result ON urs_user_id=urg_user_id"
			. " WHERE dlr_sales_channel_cd=$sales_channel_cd AND urg_follow_date<=current_timestamp $cond"
			. " GROUP BY dlr_dealer_cd,dlr_dealer_name"
			. " ORDER BY dlr_dealer_cd";
	$result2 = db_exec($sql);
	$nrow2 = pg_num_rows($result2);
	for ($j = 0; $j < $nrow2; $j++) {
		$fetch2 = pg_fetch_object($result2, $j);
		$dealer_cd = $fetch2->dlr_dealer_cd;

		$count_err = $fetch2->count_err;
		$count_futatsu = $fetch2->count_futatsu;
		$count_jisshi = $fetch2->count_jisshi - $count_futatsu;
		$count_irai = $count_jisshi + $count_err + $count_futatsu;

		$total_irai += $count_irai;
		$total_jisshi += $count_jisshi;
		$total_err += $count_err;
		$total_futatsu += $count_futatsu;
?>
<tr align="center" class="<?=$j % 2 == 0 ? 'on' : 'off'?>">
	<td><a href="dealer_page.php?dealer_cd=<?=$dealer_cd?>" title="この販売店の一覧表示" target="dealer_page"><?=htmlspecialchars($fetch2->dlr_dealer_name)?></a></td>
	<td><?=number_format($count_irai)?></td>
	<td><?=number_format($count_jisshi)?></td>
	<td><?=number_format($count_err)?></td>
	<td><?=number_format($count_futatsu)?></td>
</tr>
<?
	}
}
?>
<tr align="center" bgcolor="#88d1fd">
	<th>合計</th>
	<th><?=number_format($total_irai)?></th>
	<th><?=number_format($total_jisshi)?></th>
	<th><?=number_format($total_err)?></th>
	<th><?=number_format($total_futatsu)?></th>
</tr>
		</table>
	</td>
</tr>
</table>

<p class="copyright" align="center">copyright &copy; 2003 e-okuruma.info All Rights Reserved.</p>

</body>
</html>
