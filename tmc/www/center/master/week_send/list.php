<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:週別配信数集計
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/center.php");
include("$inc/format.php");
include("$inc/list.php");

$data = array();

// 新規登録者数
$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,COUNT(*) AS regist_count"
		. " FROM t_user_regist"
		. " JOIN t_user_manage ON umg_user_id=urg_user_id"
		. " GROUP BY regist_date"
		. " ORDER BY regist_date";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;

	$data[$regist_date]['新規登録者数'] = (int)$fetch->regist_count;

	$total['新規登録者数'] += (int)$fetch->regist_count;
}

// 初期：インバウンド人数・引継ぎ人数
$sb1 = "SELECT MIN(cml_comm_id) AS sb1_comm_id"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE (cmc_comm_type=2 OR cmc_comm_type=3) AND cml_send_recv='R'"
		. " GROUP BY cml_user_id";

$sb2 = "SELECT cml_thread_comm_id AS sb2_comm_id"
		. " FROM ($sb1) sb1"
		. " JOIN t_comm_log ON cml_comm_id=sb1_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE cmc_comm_type=2";

$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,cmc_vol_no AS vol_no,COUNT(*) AS count"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='S' THEN 1 END) AS rank_s"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='A' THEN 1 END) AS rank_a"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='B' THEN 1 END) AS rank_b"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='J' THEN 1 END) AS rank_j"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='D' THEN 1 END) AS rank_d"
		. " FROM ($sb2) sb2"
		. " JOIN t_comm_log ON cml_comm_id=sb2_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_regist ON urg_user_id=cml_user_id"
		. " JOIN t_user_status ON ust_user_id=cml_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=cml_user_id"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	$data[$regist_date]['インバウンド人数']['初期'][$vol_no] = (int)$fetch->count;
	$data[$regist_date]['引継ぎ人数S']['初期'][$vol_no] = (int)$fetch->rank_s;
	$data[$regist_date]['引継ぎ人数A']['初期'][$vol_no] = (int)$fetch->rank_a;
	$data[$regist_date]['引継ぎ人数B']['初期'][$vol_no] = (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数J']['初期'][$vol_no] = (int)$fetch->rank_j;
	$data[$regist_date]['引継ぎ人数SAB']['初期'][$vol_no] = (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数D']['初期'][$vol_no] = (int)$fetch->rank_d;

	$data[$regist_date]['インバウンド人数']['TOTAL'] += (int)$fetch->count;
	$data[$regist_date]['引継ぎ人数S']['TOTAL'] += (int)$fetch->rank_s;
	$data[$regist_date]['引継ぎ人数A']['TOTAL'] += (int)$fetch->rank_a;
	$data[$regist_date]['引継ぎ人数B']['TOTAL'] += (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数J']['TOTAL'] += (int)$fetch->rank_j;
	$data[$regist_date]['引継ぎ人数SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数D']['TOTAL'] += (int)$fetch->rank_d;

	$total['インバウンド人数']['初期'][$vol_no] += (int)$fetch->count;
	$total['引継ぎ人数S']['初期'][$vol_no] += (int)$fetch->rank_s;
	$total['引継ぎ人数A']['初期'][$vol_no] += (int)$fetch->rank_a;
	$total['引継ぎ人数B']['初期'][$vol_no] += (int)$fetch->rank_b;
	$total['引継ぎ人数J']['初期'][$vol_no] += (int)$fetch->rank_j;
	$total['引継ぎ人数SAB']['初期'][$vol_no] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['引継ぎ人数D']['初期'][$vol_no] += (int)$fetch->rank_d;

	$total['インバウンド人数']['TOTAL'] += (int)$fetch->count;
	$total['引継ぎ人数S']['TOTAL'] += (int)$fetch->rank_s;
	$total['引継ぎ人数A']['TOTAL'] += (int)$fetch->rank_a;
	$total['引継ぎ人数B']['TOTAL'] += (int)$fetch->rank_b;
	$total['引継ぎ人数J']['TOTAL'] += (int)$fetch->rank_j;
	$total['引継ぎ人数SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['引継ぎ人数D']['TOTAL'] += (int)$fetch->rank_d;
}

// 初期：E,Mランク引継ぎ人数
$sb1 = "SELECT MAX(cml_comm_id) AS sb1_comm_id"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE (cmc_comm_type=2 OR cmc_comm_type=3) AND cml_send_recv='S'"
		. " GROUP BY cml_user_id";

$sb2 = "SELECT cml_comm_id AS sb2_comm_id"
		. " FROM ($sb1) sb1"
		. " JOIN t_comm_log ON cml_comm_id=sb1_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE cmc_comm_type=2";

$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,cmc_vol_no AS vol_no"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='E' THEN 1 END) AS rank_e"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='M' THEN 1 END) AS rank_m"
		. " FROM ($sb2) sb2"
		. " JOIN t_comm_log ON cml_comm_id=sb2_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_regist ON urg_user_id=cml_user_id"
		. " JOIN t_user_status ON ust_user_id=cml_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=cml_user_id"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	$data[$regist_date]['引継ぎ人数E']['初期'][$vol_no] = (int)$fetch->rank_e;
	$data[$regist_date]['引継ぎ人数M']['初期'][$vol_no] = (int)$fetch->rank_m;

	$data[$regist_date]['引継ぎ人数E']['TOTAL'] += (int)$fetch->rank_e;
	$data[$regist_date]['引継ぎ人数M']['TOTAL'] += (int)$fetch->rank_m;

	$total['引継ぎ人数E']['初期'][$vol_no] += (int)$fetch->rank_e;
	$total['引継ぎ人数M']['初期'][$vol_no] += (int)$fetch->rank_m;

	$total['引継ぎ人数E']['TOTAL'] += (int)$fetch->rank_e;
	$total['引継ぎ人数M']['TOTAL'] += (int)$fetch->rank_m;
}

// 初期：配信数
$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,cmc_vol_no AS vol_no"
		. ",COUNT(CASE WHEN cml_err_mail_flag=false THEN 1 END) AS send_count"
		. ",COUNT(CASE WHEN cml_err_mail_flag=true THEN 1 END) AS err_count"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_regist ON urg_user_id=cml_user_id"
		. " JOIN t_user_status ON ust_user_id=cml_user_id"
		. " WHERE cmc_comm_type=2 AND cml_send_recv='S'"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	$data[$regist_date]['配信数']['初期'][$vol_no] = (int)$fetch->send_count;
	$data[$regist_date]['不達メール数']['初期'][$vol_no] = (int)$fetch->err_count;

	$data[$regist_date]['配信数']['TOTAL'] += (int)$fetch->send_count;
	$data[$regist_date]['不達メール数']['TOTAL'] += (int)$fetch->err_count;

	$total['配信数']['初期'][$vol_no] += (int)$fetch->send_count;
	$total['不達メール数']['初期'][$vol_no] += (int)$fetch->err_count;

	$total['配信数']['TOTAL'] += (int)$fetch->send_count;
	$total['不達メール数']['TOTAL'] += (int)$fetch->err_count;
}

// 初期：クリック数
$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,fln_vol_no AS vol_no"
		. ",COUNT(*) AS click_count"
		. ",COUNT(DISTINCT ccl_user_id) AS uniq_count"
		. " FROM t_click_counter_log"
		. " JOIN t_follow_click ON fln_cc_id=ccl_cc_id"
		. " JOIN t_user_regist ON urg_user_id=ccl_user_id"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	$data[$regist_date]['クリック数']['初期'][$vol_no] = (int)$fetch->click_count;
	$data[$regist_date]['ユニーククリック数']['初期'][$vol_no] = (int)$fetch->uniq_count;

	$total['クリック数']['初期'][$vol_no] += (int)$fetch->click_count;
	$total['ユニーククリック数']['初期'][$vol_no] += (int)$fetch->uniq_count;
}

// 継続：インバウンド人数・引継ぎ人数
$sb1 = "SELECT MIN(cml_comm_id) AS sb1_comm_id"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE (cmc_comm_type=2 OR cmc_comm_type=3) AND cml_send_recv='R'"
		. " GROUP BY cml_user_id";

$sb2 = "SELECT cml_thread_comm_id AS sb2_comm_id"
		. " FROM ($sb1) sb1"
		. " JOIN t_comm_log ON cml_comm_id=sb1_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE cmc_comm_type=3";

$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,cmc_vol_no AS vol_no,COUNT(*) AS count"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='S' THEN 1 END) AS rank_s"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='A' THEN 1 END) AS rank_a"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='B' THEN 1 END) AS rank_b"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='J' THEN 1 END) AS rank_j"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='D' THEN 1 END) AS rank_d"
		. " FROM ($sb2) sb2"
		. " JOIN t_comm_log ON cml_comm_id=sb2_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_regist ON urg_user_id=cml_user_id"
		. " JOIN t_user_status ON ust_user_id=cml_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=cml_user_id"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	$data[$regist_date]['インバウンド人数']['継続'][$vol_no] = (int)$fetch->count;
	$data[$regist_date]['引継ぎ人数S']['継続'][$vol_no] = (int)$fetch->rank_s;
	$data[$regist_date]['引継ぎ人数A']['継続'][$vol_no] = (int)$fetch->rank_a;
	$data[$regist_date]['引継ぎ人数B']['継続'][$vol_no] = (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数J']['継続'][$vol_no] = (int)$fetch->rank_j;
	$data[$regist_date]['引継ぎ人数SAB']['継続'][$vol_no] = (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数D']['継続'][$vol_no] = (int)$fetch->rank_d;

	$data[$regist_date]['インバウンド人数']['TOTAL'] += (int)$fetch->count;
	$data[$regist_date]['引継ぎ人数S']['TOTAL'] += (int)$fetch->rank_s;
	$data[$regist_date]['引継ぎ人数A']['TOTAL'] += (int)$fetch->rank_a;
	$data[$regist_date]['引継ぎ人数B']['TOTAL'] += (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数J']['TOTAL'] += (int)$fetch->rank_j;
	$data[$regist_date]['引継ぎ人数SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['引継ぎ人数D']['TOTAL'] += (int)$fetch->rank_d;

	$total['インバウンド人数']['継続'][$vol_no] += (int)$fetch->count;
	$total['引継ぎ人数S']['継続'][$vol_no] += (int)$fetch->rank_s;
	$total['引継ぎ人数A']['継続'][$vol_no] += (int)$fetch->rank_a;
	$total['引継ぎ人数B']['継続'][$vol_no] += (int)$fetch->rank_b;
	$total['引継ぎ人数J']['継続'][$vol_no] += (int)$fetch->rank_j;
	$total['引継ぎ人数SAB']['継続'][$vol_no] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['引継ぎ人数D']['継続'][$vol_no] += (int)$fetch->rank_d;

	$total['インバウンド人数']['TOTAL'] += (int)$fetch->count;
	$total['引継ぎ人数S']['TOTAL'] += (int)$fetch->rank_s;
	$total['引継ぎ人数A']['TOTAL'] += (int)$fetch->rank_a;
	$total['引継ぎ人数B']['TOTAL'] += (int)$fetch->rank_b;
	$total['引継ぎ人数J']['TOTAL'] += (int)$fetch->rank_j;
	$total['引継ぎ人数SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['引継ぎ人数D']['TOTAL'] += (int)$fetch->rank_d;
}

// 初期：E,Mランク引継ぎ人数
$sb1 = "SELECT MAX(cml_comm_id) AS sb1_comm_id"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE (cmc_comm_type=2 OR cmc_comm_type=3) AND cml_send_recv='S'"
		. " GROUP BY cml_user_id";

$sb2 = "SELECT cml_thread_comm_id AS sb2_comm_id"
		. " FROM ($sb1) sb1"
		. " JOIN t_comm_log ON cml_comm_id=sb1_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " WHERE cmc_comm_type=3";

$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,cmc_vol_no AS vol_no"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='E' THEN 1 END) AS rank_e"
		. ",COUNT(CASE WHEN ust_status>=3 AND urs_rank='M' THEN 1 END) AS rank_m"
		. " FROM ($sb2) sb2"
		. " JOIN t_comm_log ON cml_comm_id=sb2_comm_id"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_regist ON urg_user_id=cml_user_id"
		. " JOIN t_user_status ON ust_user_id=cml_user_id"
		. " LEFT JOIN t_user_result ON urs_user_id=cml_user_id"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	$data[$regist_date]['引継ぎ人数E']['継続'][$vol_no] = (int)$fetch->rank_e;
	$data[$regist_date]['引継ぎ人数M']['継続'][$vol_no] = (int)$fetch->rank_m;

	$data[$regist_date]['引継ぎ人数E']['TOTAL'] += (int)$fetch->rank_e;
	$data[$regist_date]['引継ぎ人数M']['TOTAL'] += (int)$fetch->rank_m;

	$total['引継ぎ人数E']['継続'][$vol_no] += (int)$fetch->rank_e;
	$total['引継ぎ人数M']['継続'][$vol_no] += (int)$fetch->rank_m;

	$total['引継ぎ人数E']['TOTAL'] += (int)$fetch->rank_e;
	$total['引継ぎ人数M']['TOTAL'] += (int)$fetch->rank_m;
}

$max_relation_vol_no = 0;

// 継続：配信数
$sql = "SELECT to_char(urg_regist_date,'YYYY/MM/DD') AS regist_date,cmc_vol_no AS vol_no"
		. ",COUNT(CASE WHEN cml_err_mail_flag=false THEN 1 END) AS send_count"
		. ",COUNT(CASE WHEN cml_err_mail_flag=true THEN 1 END) AS err_count"
		. " FROM t_comm_log"
		. " JOIN t_comm_contents ON cmc_contents_id=cml_contents_id"
		. " JOIN t_user_regist ON urg_user_id=cml_user_id"
		. " JOIN t_user_status ON ust_user_id=cml_user_id"
		. " WHERE cmc_comm_type=3 AND cml_send_recv='S'"
		. " GROUP BY regist_date,vol_no";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$regist_date = $fetch->regist_date;
	$vol_no = (int)$fetch->vol_no;

	if ($vol_no > $max_relation_vol_no)
		$max_relation_vol_no = $vol_no;

	$data[$regist_date]['配信数']['継続'][$vol_no] = (int)$fetch->send_count;
	$data[$regist_date]['不達メール数']['継続'][$vol_no] = (int)$fetch->err_count;

	$data[$regist_date]['配信数']['TOTAL'] += (int)$fetch->send_count;
	$data[$regist_date]['不達メール数']['TOTAL'] += (int)$fetch->err_count;

	$total['配信数']['継続'][$vol_no] += (int)$fetch->send_count;
	$total['不達メール数']['継続'][$vol_no] += (int)$fetch->err_count;

	$total['配信数']['TOTAL'] += (int)$fetch->send_count;
	$total['不達メール数']['TOTAL'] += (int)$fetch->err_count;
}

// 総計
$data['総計'] = $total;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/center.css">
<? list_script() ?>
<style type="text/css">
<!--
td
{
	white-space: nowrap;
}
-->
</style>
</head>
<body>

<? center_header('マスタメンテナンス｜週別配信数集計') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■登録日別／反応同報メール別人数</td>
		<td class="lb">
			<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
</form>
</div>

<table <?=LIST_TABLE?> class="small2">
	<tr class="tch">
		<th>登録日</th>
		<th>新規<br>登録者数</th>
		<th colspan=3>指標項目名</th>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<th>初期<?=$i?></th>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<th>継続<?=$i?></th>
<? } ?>
		<th>TOTAL</th>
	</tr>
<?
foreach ($data as $regist_date => $a) {
	for ($i = 4; $i >= 1; $i--) {
		if ($a['配信数']['初期'][$i] + $a['不達メール数']['初期'][$i] != 0)
			break;
	}
	$f = $i;

	for ($i = $max_relation_vol_no; $i >= 1; $i--) {
		if ($a['配信数']['継続'][$i] + $a['不達メール数']['継続'][$i] != 0)
			break;
	}
	$r = $i;
?>
	<tr>
		<td align="center" rowspan=30><?=$regist_date?></td>
		<td align="center" rowspan=30><?=number_format($a['新規登録者数'])?></td>
		<td colspan=3>インバウンド人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right" <? if ($i <= $f && $regist_date != '総計') echo 'bgcolor="cyan"' ?>><? if ($i <= $f) echo number_format($a['インバウンド人数']['初期'][$i]) ?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right" <? if ($i <= $r && $regist_date != '総計') echo 'bgcolor="cyan"' ?>><? if ($i <= $r) echo number_format($a['インバウンド人数']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['インバウンド人数']['TOTAL'])?></td>
	</tr>
	<tr>
		<td colspan=3>インバウンド率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['インバウンド人数']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['インバウンド人数']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['インバウンド人数']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td rowspan=21>引継ぎ<br>件数</td>
		<td align="center" rowspan=3>S</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数S']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数S']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数S']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数S']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数S']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数S']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>アクション率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数S']['初期'][$i], $a['インバウンド人数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数S']['継続'][$i], $a['インバウンド人数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数S']['TOTAL'], $a['インバウンド人数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>A</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数A']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数A']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数A']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数A']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数A']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数A']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>アクション率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数A']['初期'][$i], $a['インバウンド人数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数A']['継続'][$i], $a['インバウンド人数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数A']['TOTAL'], $a['インバウンド人数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>B</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数B']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数B']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数B']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数B']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数B']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数B']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>アクション率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数B']['初期'][$i], $a['インバウンド人数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数B']['継続'][$i], $a['インバウンド人数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数B']['TOTAL'], $a['インバウンド人数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>SAB<br>合計</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数SAB']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数SAB']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数SAB']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数SAB']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数SAB']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数SAB']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>アクション率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数SAB']['初期'][$i], $a['インバウンド人数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数SAB']['継続'][$i], $a['インバウンド人数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数SAB']['TOTAL'], $a['インバウンド人数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>J</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数J']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数J']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数J']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数J']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数J']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数J']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>アクション率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数J']['初期'][$i], $a['インバウンド人数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数J']['継続'][$i], $a['インバウンド人数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数J']['TOTAL'], $a['インバウンド人数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=2>D</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数D']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数D']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数D']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数D']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数D']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数D']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=2>E</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数E']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数E']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数E']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数E']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数E']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数E']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=2>M</td>
		<td>引継ぎ人数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['引継ぎ人数M']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['引継ぎ人数M']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['引継ぎ人数M']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>引継ぎ率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['引継ぎ人数M']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['引継ぎ人数M']['継続'][$i], $a['配信数']['継続'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['引継ぎ人数M']['TOTAL'], $a['配信数']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td colspan=3>配信数（不達除く）</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['配信数']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['配信数']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['配信数']['TOTAL'])?></td>
	</tr>
	<tr>
		<td colspan=3>不達メール数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['不達メール数']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['不達メール数']['継続'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['不達メール数']['TOTAL'])?></td>
	</tr>
	<tr>
		<td colspan=3>クリック数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['クリック数']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>クリック率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['クリック数']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>ユニーククリック数</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['ユニーククリック数']['初期'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>ユニーククリック率</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['ユニーククリック数']['初期'][$i], $a['配信数']['初期'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>平均クリック数（回/人）</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['ユニーククリック数']['初期'][$i] != 0 ? 1.0 * $a['クリック数']['初期'][$i] / $a['ユニーククリック数']['初期'][$i] : 0, 2)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
<?
}
?>
</table>

<? center_footer() ?>

</body>
</html>
