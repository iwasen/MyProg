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

// 販売店名取得
$dealer_name = get_dealer_name();

// ページ遷移履歴設定
set_page_history(0, '車種別ユーザー数一覧', '車種別ユーザー数一覧', 'top.php');
set_page_history(1, '月次依頼件数', '月次依頼件数');
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
	<td align="right"><a href="top.php" title="トップページへ戻る">トップページへ戻る</a></td>
</tr>
</table>
<br>

<table border="0" cellspacing="1" cellpadding="0" bgcolor="#f7df3e" align="center" width="800" class="text12">
<tr>
	<td>
		<table border="0" cellspacing="2" cellpadding="2" bgcolor="#ffffff" align="center" width="800" class="text12" id="list">
		<tr bgcolor="#f7df3e">
			<th width="200">&nbsp;</th>
			<th width="150">メールフォロー依頼件数</th>
			<th width="150">メールフォロー実施件数</th>
			<th width="150">初期エラー件数</th>
			<th width="150">継続時エラー件数</th>
		</tr>
<?
$sql = "SELECT cmp_campaign_cd,cmp_campaign_name FROM t_campaign ORDER BY cmp_campaign_cd";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$total_irai = 0;
	$total_jisshi = 0;
	$total_err = 0;
	$total_futatsu = 0;

	$sql = "SELECT to_char(urg_follow_date, 'YYYYMM') AS regist_ym"
			. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') THEN 1 END) AS count_jisshi"
			. ",COUNT(CASE WHEN ust_user_id IS NULL OR urs_rank='L' THEN 1 END) AS count_err"
			. ",COUNT(CASE WHEN NOT (ust_user_id IS NULL OR urs_rank IS NOT NULL AND urs_rank='L') AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=urg_user_id AND cml_err_mail_flag=false) THEN 1 END) AS count_futatsu"
			. " FROM t_user_regist"
			. " LEFT JOIN t_user_status ON ust_user_id=urg_user_id"
			. " LEFT JOIN t_user_result ON urs_user_id=urg_user_id"
			. " WHERE urg_dealer_cd=" . sql_char($g_dealer_cd) . " AND urg_follow_date<=current_timestamp AND urg_campaign_cd=$fetch->cmp_campaign_cd"
			. " GROUP BY regist_ym"
			. " ORDER BY regist_ym";
	$result2 = db_exec($sql);
	$nrow2 = pg_num_rows($result2);
	for ($j = 0; $j < $nrow2; $j++) {
		$fetch2 = pg_fetch_object($result2, $j);
		sscanf($fetch2->regist_ym, '%4d%2d', &$year, &$month);

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
			<td><?=$month?>月ご依頼</td>
			<td><?=number_format($count_irai)?></td>
			<td><?=number_format($count_jisshi)?></td>
			<td><?=number_format($count_err)?></td>
			<td><?=number_format($count_futatsu)?></td>
		</tr>
<?
	}
?>
		<tr align="center" bgcolor="#f7df3e">
			<th><?=htmlspecialchars($fetch->cmp_campaign_name)?>計</th>
			<th><?=number_format($total_irai)?></th>
			<th><?=number_format($total_jisshi)?></th>
			<th><?=number_format($total_err)?></th>
			<th><?=number_format($total_futatsu)?></th>
		</tr>
<?
	$all_total_irai += $total_irai;
	$all_total_jisshi += $total_jisshi;
	$all_total_err += $total_err;
	$all_total_futatsu += $total_futatsu;
}
?>
		<tr align="center" class="on" bgcolor="#f7df3e">
			<th width="200">合計</th>
			<th width="150"><?=number_format($all_total_irai)?></th>
			<th width="150"><?=number_format($all_total_jisshi)?></th>
			<th width="150"><?=number_format($all_total_err)?></th>
			<th width="150"><?=number_format($all_total_futatsu)?></th>
		</tr>
		</table>
	</td>
</tr>
</table>


<p class="copyright" align="center">copyright &copy; 2003 e-okuruma.info All Rights Reserved.</p>

</body>

</html>
