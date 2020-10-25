<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」販売店様用ページ
' Content:ログイン画面処理
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/dealer.php");
include("$inc/select.php");

// 人数表示
function disp_number($num, $car_model_cd, $st) {
	if ($num != 0)
		echo '<a href="user.php?car_model_cd=', $car_model_cd, '&st=', $st, '" title="ユーザー一覧へ">';
	else
		echo '<font color="blue">';

	echo number_format($num), '名';

	if ($num != 0)
		'</a>';
	else
		echo '</font>';

	echo "\n";
}

// 入力パラメータ
$campaign_cd = $_POST['campaign_cd'];

// 販売店名取得
$dealer_name = get_dealer_name();

// ページ遷移履歴設定
set_page_history(0, '車種別ユーザー数一覧', '車種別ユーザー数一覧');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/default.css">
	<title><?=$dealer_name?>様用ページ</title>
</head>

<body bgcolor="#ffffff" leftmargin="5" marginwidth="5" topmargin="5" marginheight="5" rightmargin="5" bottommargin="5">

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#33a2cc" align="center" width="100%" height="50">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="10" bgcolor="#dbeff7" align="center" width="100%" height="100%">
		<tr>
			<td class="titlefont"><?=$dealer_name?>様</td>
			<td class="text12" align="right" valign="bottom"><a href="logout.php" title="ログアウト">ログアウト</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#f7df3e" width="100%" class="text14">
<tr>
	<td><?=get_page_history()?></td>
</tr>
</table>
<br>

<table border="0" cellspacing="0" cellpadding="5" bgcolor="#ffffff" width="800" class="text14" align="center">
<tr>
	<td>
		<form method="post" style="margin:0">
		<select name="campaign_cd"><? select_campaign('全てのキャンペーン', $campaign_cd) ?></select>
		<input type="submit" value="表示" class="button">
		</form>
	</td>
	<td align="right"><a href="monthly.php">月次依頼件数一覧</a></td>
</tr>
</table>
<br>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#f7df3e" align="center" width="800" class="text12">
<tr>
	<td>
		<table border="0" cellspacing="2" cellpadding="4" align="center" class="text12" bgcolor="#ffffff" id="list">
		<tr>
			<th width="200" bgcolor="#f7df3e" rowspan="2">車種名</th>
			<th width="100" bgcolor="#f7df3e" rowspan="2">フォロー<br>実施中</th>
			<th bgcolor="#f7df3e" colspan="5">引継ぎ済み</th>
			<th bgcolor="#f7df3e" colspan="4">サービス停止</th>
		</tr>
		<tr>
			<th width="100" bgcolor="#f7df3e">合計</th>
			<th width="100" bgcolor="#f7df3e">ランクS</th>
			<th width="100" bgcolor="#f7df3e">ランクA</th>
			<th width="100" bgcolor="#f7df3e">ランクB</th>
			<th width="100" bgcolor="#f7df3e">ランクJ</th>
			<th width="100" bgcolor="#f7df3e">合計</th>
			<th width="100" bgcolor="#f7df3e">ランクD</th>
			<th width="100" bgcolor="#f7df3e">ランクE</th>
			<th width="100" bgcolor="#f7df3e">ランクM</th>
		</tr>
<?
if ($campaign_cd != '')
	$cond = "AND urg_campaign_cd='$campaign_cd'";

$sql = "SELECT crm_car_model_cd,crm_car_model_name"
		. ",COUNT(CASE WHEN ust_status<=2 THEN 1 END) AS count_follow"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='S' THEN 1 END) AS count_rank_s"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='A' THEN 1 END) AS count_rank_a"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='B' THEN 1 END) AS count_rank_b"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='J' THEN 1 END) AS count_rank_j"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='D' THEN 1 END) AS count_rank_d"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='E' THEN 1 END) AS count_rank_e"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='M' THEN 1 END) AS count_rank_m"
		. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=urg_user_id AND cml_err_mail_flag=false) THEN 1 END) AS count_futatsu"
		. " FROM t_car_model"
		. " JOIN t_user_manage ON umg_car_model_cd=crm_car_model_cd"
		. " JOIN t_user_status ON ust_user_id=umg_user_id"
		. " JOIN t_user_regist ON urg_user_id=umg_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=umg_user_id"
		. " WHERE umg_dealer_cd=" . sql_char($g_dealer_cd) . " AND urg_follow_date<=current_timestamp $cond"
		. " GROUP BY crm_car_model_cd,crm_car_model_name"
		. " ORDER BY crm_car_model_cd";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$count_follow = $fetch->count_follow - $fetch->count_futatsu;
	$count_rank_s = $fetch->count_rank_s;
	$count_rank_a = $fetch->count_rank_a;
	$count_rank_b = $fetch->count_rank_b;
	$count_rank_j = $fetch->count_rank_j;
	$count_rank_d = $fetch->count_rank_d;
	$count_rank_e = $fetch->count_rank_e;
	$count_rank_m = $fetch->count_rank_m;
	$count_hikitsugi = $count_rank_s + $count_rank_a + $count_rank_b + $count_rank_j;
	$count_stop = $count_rank_d + $count_rank_e + $count_rank_m;

	$total_follow += $count_follow;
	$total_rank_s += $count_rank_s;
	$total_rank_a += $count_rank_a;
	$total_rank_b += $count_rank_b;
	$total_rank_j += $count_rank_j;
	$total_rank_d += $count_rank_d;
	$total_rank_e += $count_rank_e;
	$total_rank_m += $count_rank_m;
	$total_hikitsugi += $count_hikitsugi;
	$total_stop += $count_stop;

	$car_model_cd = $fetch->crm_car_model_cd;
?>
		<tr align="center" class="<?=$i % 2 == 0 ? 'on' : 'off'?>">
			<th><?=htmlspecialchars($fetch->crm_car_model_name)?></th>
			<td><? disp_number($count_follow, $car_model_cd, 1) ?></td>
			<td><? disp_number($count_hikitsugi, $car_model_cd, 2) ?></td>
			<td><? disp_number($count_rank_s, $car_model_cd, 3) ?></td>
			<td><? disp_number($count_rank_a, $car_model_cd, 4) ?></td>
			<td><? disp_number($count_rank_b, $car_model_cd, 5) ?></td>
			<td><? disp_number($count_rank_j, $car_model_cd, 6) ?></td>
			<td><? disp_number($count_stop, $car_model_cd, 7) ?></td>
			<td><? disp_number($count_rank_d, $car_model_cd, 8) ?></td>
			<td><? disp_number($count_rank_e, $car_model_cd, 9) ?></td>
			<td><? disp_number($count_rank_m, $car_model_cd, 10) ?></td>
		</tr>
<?
}
?>
		<tr align="center" bgcolor="#f7df3e">
			<th>合計</th>
			<td><? disp_number($total_follow, '', 1) ?></td>
			<td><? disp_number($total_hikitsugi, '', 2) ?></td>
			<td><? disp_number($total_rank_s, '', 3) ?></td>
			<td><? disp_number($total_rank_a, '', 4) ?></td>
			<td><? disp_number($total_rank_b, '', 5) ?></td>
			<td><? disp_number($total_rank_j, '', 6) ?></td>
			<td><? disp_number($total_stop, '', 7) ?></td>
			<td><? disp_number($total_rank_d, '', 8) ?></td>
			<td><? disp_number($total_rank_e, '', 9) ?></td>
			<td><? disp_number($total_rank_m, '', 10) ?></td>
		</tr>
		</table>
	</td>
</tr>
</table>


<p class="copyright" align="center">copyright &copy; 2003 e-okuruma.info All Rights Reserved.</p>

</body>

</html>
