<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�����ۿ�������
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

// ������Ͽ�Կ�
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

	$data[$regist_date]['������Ͽ�Կ�'] = (int)$fetch->regist_count;

	$total['������Ͽ�Կ�'] += (int)$fetch->regist_count;
}

// ���������Х���ɿͿ������Ѥ��Ϳ�
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

	$data[$regist_date]['����Х���ɿͿ�']['���'][$vol_no] = (int)$fetch->count;
	$data[$regist_date]['���Ѥ��Ϳ�S']['���'][$vol_no] = (int)$fetch->rank_s;
	$data[$regist_date]['���Ѥ��Ϳ�A']['���'][$vol_no] = (int)$fetch->rank_a;
	$data[$regist_date]['���Ѥ��Ϳ�B']['���'][$vol_no] = (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�J']['���'][$vol_no] = (int)$fetch->rank_j;
	$data[$regist_date]['���Ѥ��Ϳ�SAB']['���'][$vol_no] = (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�D']['���'][$vol_no] = (int)$fetch->rank_d;

	$data[$regist_date]['����Х���ɿͿ�']['TOTAL'] += (int)$fetch->count;
	$data[$regist_date]['���Ѥ��Ϳ�S']['TOTAL'] += (int)$fetch->rank_s;
	$data[$regist_date]['���Ѥ��Ϳ�A']['TOTAL'] += (int)$fetch->rank_a;
	$data[$regist_date]['���Ѥ��Ϳ�B']['TOTAL'] += (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�J']['TOTAL'] += (int)$fetch->rank_j;
	$data[$regist_date]['���Ѥ��Ϳ�SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�D']['TOTAL'] += (int)$fetch->rank_d;

	$total['����Х���ɿͿ�']['���'][$vol_no] += (int)$fetch->count;
	$total['���Ѥ��Ϳ�S']['���'][$vol_no] += (int)$fetch->rank_s;
	$total['���Ѥ��Ϳ�A']['���'][$vol_no] += (int)$fetch->rank_a;
	$total['���Ѥ��Ϳ�B']['���'][$vol_no] += (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�J']['���'][$vol_no] += (int)$fetch->rank_j;
	$total['���Ѥ��Ϳ�SAB']['���'][$vol_no] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�D']['���'][$vol_no] += (int)$fetch->rank_d;

	$total['����Х���ɿͿ�']['TOTAL'] += (int)$fetch->count;
	$total['���Ѥ��Ϳ�S']['TOTAL'] += (int)$fetch->rank_s;
	$total['���Ѥ��Ϳ�A']['TOTAL'] += (int)$fetch->rank_a;
	$total['���Ѥ��Ϳ�B']['TOTAL'] += (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�J']['TOTAL'] += (int)$fetch->rank_j;
	$total['���Ѥ��Ϳ�SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�D']['TOTAL'] += (int)$fetch->rank_d;
}

// �����E,M��󥯰��Ѥ��Ϳ�
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

	$data[$regist_date]['���Ѥ��Ϳ�E']['���'][$vol_no] = (int)$fetch->rank_e;
	$data[$regist_date]['���Ѥ��Ϳ�M']['���'][$vol_no] = (int)$fetch->rank_m;

	$data[$regist_date]['���Ѥ��Ϳ�E']['TOTAL'] += (int)$fetch->rank_e;
	$data[$regist_date]['���Ѥ��Ϳ�M']['TOTAL'] += (int)$fetch->rank_m;

	$total['���Ѥ��Ϳ�E']['���'][$vol_no] += (int)$fetch->rank_e;
	$total['���Ѥ��Ϳ�M']['���'][$vol_no] += (int)$fetch->rank_m;

	$total['���Ѥ��Ϳ�E']['TOTAL'] += (int)$fetch->rank_e;
	$total['���Ѥ��Ϳ�M']['TOTAL'] += (int)$fetch->rank_m;
}

// ������ۿ���
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

	$data[$regist_date]['�ۿ���']['���'][$vol_no] = (int)$fetch->send_count;
	$data[$regist_date]['��ã�᡼���']['���'][$vol_no] = (int)$fetch->err_count;

	$data[$regist_date]['�ۿ���']['TOTAL'] += (int)$fetch->send_count;
	$data[$regist_date]['��ã�᡼���']['TOTAL'] += (int)$fetch->err_count;

	$total['�ۿ���']['���'][$vol_no] += (int)$fetch->send_count;
	$total['��ã�᡼���']['���'][$vol_no] += (int)$fetch->err_count;

	$total['�ۿ���']['TOTAL'] += (int)$fetch->send_count;
	$total['��ã�᡼���']['TOTAL'] += (int)$fetch->err_count;
}

// ���������å���
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

	$data[$regist_date]['����å���']['���'][$vol_no] = (int)$fetch->click_count;
	$data[$regist_date]['��ˡ�������å���']['���'][$vol_no] = (int)$fetch->uniq_count;

	$total['����å���']['���'][$vol_no] += (int)$fetch->click_count;
	$total['��ˡ�������å���']['���'][$vol_no] += (int)$fetch->uniq_count;
}

// ��³������Х���ɿͿ������Ѥ��Ϳ�
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

	$data[$regist_date]['����Х���ɿͿ�']['��³'][$vol_no] = (int)$fetch->count;
	$data[$regist_date]['���Ѥ��Ϳ�S']['��³'][$vol_no] = (int)$fetch->rank_s;
	$data[$regist_date]['���Ѥ��Ϳ�A']['��³'][$vol_no] = (int)$fetch->rank_a;
	$data[$regist_date]['���Ѥ��Ϳ�B']['��³'][$vol_no] = (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�J']['��³'][$vol_no] = (int)$fetch->rank_j;
	$data[$regist_date]['���Ѥ��Ϳ�SAB']['��³'][$vol_no] = (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�D']['��³'][$vol_no] = (int)$fetch->rank_d;

	$data[$regist_date]['����Х���ɿͿ�']['TOTAL'] += (int)$fetch->count;
	$data[$regist_date]['���Ѥ��Ϳ�S']['TOTAL'] += (int)$fetch->rank_s;
	$data[$regist_date]['���Ѥ��Ϳ�A']['TOTAL'] += (int)$fetch->rank_a;
	$data[$regist_date]['���Ѥ��Ϳ�B']['TOTAL'] += (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�J']['TOTAL'] += (int)$fetch->rank_j;
	$data[$regist_date]['���Ѥ��Ϳ�SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$data[$regist_date]['���Ѥ��Ϳ�D']['TOTAL'] += (int)$fetch->rank_d;

	$total['����Х���ɿͿ�']['��³'][$vol_no] += (int)$fetch->count;
	$total['���Ѥ��Ϳ�S']['��³'][$vol_no] += (int)$fetch->rank_s;
	$total['���Ѥ��Ϳ�A']['��³'][$vol_no] += (int)$fetch->rank_a;
	$total['���Ѥ��Ϳ�B']['��³'][$vol_no] += (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�J']['��³'][$vol_no] += (int)$fetch->rank_j;
	$total['���Ѥ��Ϳ�SAB']['��³'][$vol_no] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�D']['��³'][$vol_no] += (int)$fetch->rank_d;

	$total['����Х���ɿͿ�']['TOTAL'] += (int)$fetch->count;
	$total['���Ѥ��Ϳ�S']['TOTAL'] += (int)$fetch->rank_s;
	$total['���Ѥ��Ϳ�A']['TOTAL'] += (int)$fetch->rank_a;
	$total['���Ѥ��Ϳ�B']['TOTAL'] += (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�J']['TOTAL'] += (int)$fetch->rank_j;
	$total['���Ѥ��Ϳ�SAB']['TOTAL'] += (int)$fetch->rank_s + (int)$fetch->rank_a + (int)$fetch->rank_b;
	$total['���Ѥ��Ϳ�D']['TOTAL'] += (int)$fetch->rank_d;
}

// �����E,M��󥯰��Ѥ��Ϳ�
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

	$data[$regist_date]['���Ѥ��Ϳ�E']['��³'][$vol_no] = (int)$fetch->rank_e;
	$data[$regist_date]['���Ѥ��Ϳ�M']['��³'][$vol_no] = (int)$fetch->rank_m;

	$data[$regist_date]['���Ѥ��Ϳ�E']['TOTAL'] += (int)$fetch->rank_e;
	$data[$regist_date]['���Ѥ��Ϳ�M']['TOTAL'] += (int)$fetch->rank_m;

	$total['���Ѥ��Ϳ�E']['��³'][$vol_no] += (int)$fetch->rank_e;
	$total['���Ѥ��Ϳ�M']['��³'][$vol_no] += (int)$fetch->rank_m;

	$total['���Ѥ��Ϳ�E']['TOTAL'] += (int)$fetch->rank_e;
	$total['���Ѥ��Ϳ�M']['TOTAL'] += (int)$fetch->rank_m;
}

$max_relation_vol_no = 0;

// ��³���ۿ���
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

	$data[$regist_date]['�ۿ���']['��³'][$vol_no] = (int)$fetch->send_count;
	$data[$regist_date]['��ã�᡼���']['��³'][$vol_no] = (int)$fetch->err_count;

	$data[$regist_date]['�ۿ���']['TOTAL'] += (int)$fetch->send_count;
	$data[$regist_date]['��ã�᡼���']['TOTAL'] += (int)$fetch->err_count;

	$total['�ۿ���']['��³'][$vol_no] += (int)$fetch->send_count;
	$total['��ã�᡼���']['��³'][$vol_no] += (int)$fetch->err_count;

	$total['�ۿ���']['TOTAL'] += (int)$fetch->send_count;
	$total['��ã�᡼���']['TOTAL'] += (int)$fetch->err_count;
}

// ���
$data['���'] = $total;
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

<? center_header('�ޥ������ƥʥ󥹡ý����ۿ�������') ?>

<div align="center">
<form name="form1">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">����Ͽ���̡�ȿ��Ʊ��᡼���̿Ϳ�</td>
		<td class="lb">
			<input type="button" value=" ��� " onclick="location.href='../menu.php'">
		</td>
	</tr>
</table>
</form>
</div>

<table <?=LIST_TABLE?> class="small2">
	<tr class="tch">
		<th>��Ͽ��</th>
		<th>����<br>��Ͽ�Կ�</th>
		<th colspan=3>��ɸ����̾</th>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<th>���<?=$i?></th>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<th>��³<?=$i?></th>
<? } ?>
		<th>TOTAL</th>
	</tr>
<?
foreach ($data as $regist_date => $a) {
	for ($i = 4; $i >= 1; $i--) {
		if ($a['�ۿ���']['���'][$i] + $a['��ã�᡼���']['���'][$i] != 0)
			break;
	}
	$f = $i;

	for ($i = $max_relation_vol_no; $i >= 1; $i--) {
		if ($a['�ۿ���']['��³'][$i] + $a['��ã�᡼���']['��³'][$i] != 0)
			break;
	}
	$r = $i;
?>
	<tr>
		<td align="center" rowspan=30><?=$regist_date?></td>
		<td align="center" rowspan=30><?=number_format($a['������Ͽ�Կ�'])?></td>
		<td colspan=3>����Х���ɿͿ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right" <? if ($i <= $f && $regist_date != '���') echo 'bgcolor="cyan"' ?>><? if ($i <= $f) echo number_format($a['����Х���ɿͿ�']['���'][$i]) ?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right" <? if ($i <= $r && $regist_date != '���') echo 'bgcolor="cyan"' ?>><? if ($i <= $r) echo number_format($a['����Х���ɿͿ�']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['����Х���ɿͿ�']['TOTAL'])?></td>
	</tr>
	<tr>
		<td colspan=3>����Х����Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['����Х���ɿͿ�']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['����Х���ɿͿ�']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['����Х���ɿͿ�']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td rowspan=21>���Ѥ�<br>���</td>
		<td align="center" rowspan=3>S</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�S']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�S']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�S']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�S']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�S']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�S']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>���������Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�S']['���'][$i], $a['����Х���ɿͿ�']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�S']['��³'][$i], $a['����Х���ɿͿ�']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�S']['TOTAL'], $a['����Х���ɿͿ�']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>A</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�A']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�A']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�A']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�A']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�A']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�A']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>���������Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�A']['���'][$i], $a['����Х���ɿͿ�']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�A']['��³'][$i], $a['����Х���ɿͿ�']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�A']['TOTAL'], $a['����Х���ɿͿ�']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>B</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�B']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�B']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�B']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�B']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�B']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�B']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>���������Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�B']['���'][$i], $a['����Х���ɿͿ�']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�B']['��³'][$i], $a['����Х���ɿͿ�']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�B']['TOTAL'], $a['����Х���ɿͿ�']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>SAB<br>���</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�SAB']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�SAB']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�SAB']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�SAB']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�SAB']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�SAB']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>���������Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�SAB']['���'][$i], $a['����Х���ɿͿ�']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�SAB']['��³'][$i], $a['����Х���ɿͿ�']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�SAB']['TOTAL'], $a['����Х���ɿͿ�']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=3>J</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�J']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�J']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�J']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�J']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�J']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�J']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td>���������Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�J']['���'][$i], $a['����Х���ɿͿ�']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�J']['��³'][$i], $a['����Х���ɿͿ�']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�J']['TOTAL'], $a['����Х���ɿͿ�']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=2>D</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�D']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�D']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�D']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�D']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�D']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�D']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=2>E</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�E']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�E']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�E']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�E']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�E']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�E']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td align="center" rowspan=2>M</td>
		<td>���Ѥ��Ϳ�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['���Ѥ��Ϳ�M']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['���Ѥ��Ϳ�M']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['���Ѥ��Ϳ�M']['TOTAL'])?></td>
	</tr>
	<tr>
		<td>���Ѥ�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['���Ѥ��Ϳ�M']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo format_percent($a['���Ѥ��Ϳ�M']['��³'][$i], $a['�ۿ���']['��³'][$i], 1)?></td>
<? } ?>
		<td align="right"><?=format_percent($a['���Ѥ��Ϳ�M']['TOTAL'], $a['�ۿ���']['TOTAL'], 1)?></td>
	</tr>
	<tr>
		<td colspan=3>�ۿ�������ã������</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['�ۿ���']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['�ۿ���']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['�ۿ���']['TOTAL'])?></td>
	</tr>
	<tr>
		<td colspan=3>��ã�᡼���</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['��ã�᡼���']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"><? if ($i <= $r) echo number_format($a['��ã�᡼���']['��³'][$i])?></td>
<? } ?>
		<td align="right"><?=number_format($a['��ã�᡼���']['TOTAL'])?></td>
	</tr>
	<tr>
		<td colspan=3>����å���</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['����å���']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>����å�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['����å���']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>��ˡ�������å���</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['��ˡ�������å���']['���'][$i])?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>��ˡ�������å�Ψ</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo format_percent($a['��ˡ�������å���']['���'][$i], $a['�ۿ���']['���'][$i], 1)?></td>
<? } ?>
<? for ($i = 1; $i <= $max_relation_vol_no; $i++) { ?>
		<td align="right"></td>
<? } ?>
		<td align="right"></td>
	</tr>
	<tr>
		<td colspan=3>ʿ�ѥ���å����ʲ�/�͡�</td>
<? for ($i = 1; $i <= 4; $i++) { ?>
		<td align="right"><? if ($i <= $f) echo number_format($a['��ˡ�������å���']['���'][$i] != 0 ? 1.0 * $a['����å���']['���'][$i] / $a['��ˡ�������å���']['���'][$i] : 0, 2)?></td>
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
