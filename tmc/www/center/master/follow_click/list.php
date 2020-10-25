<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:初期フォローメール車種別クリック一覧
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/list.php");

// セッション変数処理
get_session_vars($_GET['pset'], 'follow_click', 'sort_col', 'sort_dir');

// 配信数集計用サブクエリ
$sb1 = "SELECT COUNT(*) AS sb1_send_count,umg_car_model_cd AS sb1_car_model_cd"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_manage ON umg_user_id=cml_user_id"
		. " WHERE cmc_comm_type=2 AND cml_send_recv='S' AND cml_err_mail_flag=false"
		. " GROUP BY sb1_car_model_cd";

// クリック数数集計用サブクエリ
$sb2 = "SELECT COUNT(*) AS sb2_click,COUNT(DISTINCT ccl_user_id) AS sb2_uniq_click,umg_car_model_cd AS sb2_car_model_cd"
		. " FROM t_click_counter_log"
		. " JOIN t_user_manage ON umg_user_id=ccl_user_id"
		. " JOIN t_follow_click ON fln_cc_id=ccl_cc_id"
		. " GROUP BY sb2_car_model_cd";

// 車種名取得用サブクエリ
$sb3 = "SELECT crm_car_model_name,sb1_send_count,COALESCE(sb2_click,0) AS click_num,COALESCE(sb2_uniq_click,0) AS uniq_click_num"
		. " FROM t_car_model"
		. " JOIN ($sb1) sb1 ON sb1_car_model_cd=crm_car_model_cd"
		. " LEFT JOIN ($sb2) sb2 ON sb2_car_model_cd=crm_car_model_cd";

$order_by = order_by(2, 1, 'crm_car_model_name', 'sb1_send_count', 'click_num', 'click_rate', 'uniq_click_num', 'uniq_click_rate', 'click_average');
$sql = "SELECT *"
		. ",100.0*click_num/sb1_send_count AS click_rate"
		. ",100.0*uniq_click_num/sb1_send_count AS uniq_click_rate"
		. ",CASE WHEN uniq_click_num=0 THEN 0 ELSE 1.0*click_num/uniq_click_num END AS click_average"
		. " FROM ($sb3) sb3"
		. " $order_by";
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

<? center_header('マスタメンテナンス｜初期フォローメール車種別クリック一覧') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■車種別クリックカウント</td>
		<td class="lb">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
<input type="hidden" name="sort_col" <?=value($sort_col)?>>
<input type="hidden" name="sort_dir" <?=value($sort_dir)?>>
<input type="hidden" name="pset" value=1>
</form>

<table <?=LIST_TABLE?> width="100%" class="small">
	<tr class="tch">
<?
sort_header(1,'車種');
sort_header(2,'配信数');
sort_header(3,'クリック数');
sort_header(4,'クリック率');
sort_header(5,'ユニーク<br>クリック数');
sort_header(6,'ユニーク<br>クリック率');
sort_header(7,'平均クリック<br>回数/人');
?>
	</tr>
<?
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
?>
	<tr class="tc<?=$i % 2?>">
		<td><?=htmlspecialchars($fetch->crm_car_model_name)?></td>
		<td align="right"><?=number_format($fetch->sb1_send_count)?></td>
		<td align="right"><?=number_format($fetch->click_num)?></td>
		<td align="right"><?=number_format($fetch->click_rate,1)?>%</td>
		<td align="right"><?=number_format($fetch->uniq_click_num)?></td>
		<td align="right"><?=number_format($fetch->uniq_click_rate,1)?>%</td>
		<td align="right"><?=number_format($fetch->click_average,2)?></td>
	</tr>
<?
}
?>
</table>
</div>

<? center_footer() ?>

</body>
</html>
