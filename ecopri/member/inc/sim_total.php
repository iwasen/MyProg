<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:����������ǯ�ֻ����̷׻�
'******************************************************/

// ǯ�ֻ����̼���
function total_use(&$energy, &$water, &$cost, &$co2) {
	// ����ֹ����
	$seq_no = $_SESSION['ss_seq_no'];

	// ľ���ǯ�����
	$sql = "SELECT max(bd_month) FROM t_base_data WHERE bd_mb_seq_no=$seq_no AND bd_commit_flag=1";
	$max_ym = db_fetch1($sql);
	if (!$max_ym)
		return;

	// ��ǯ����ǯ������
	sscanf($max_ym, "%d-%d-%d", &$year, &$month, &$day);
	$min_ym = date('Y-m-d', mktime(0, 0, 0, $month + 1, $day, $year - 1));

	// ��ǯʬ�Υǡ�����û�
	$sql = "SELECT sum(bd_el_use) AS sum_el_use,sum(bd_el_sum) AS sum_el_cost,sum(bd_gs_use) AS sum_gs_use,sum(bd_gs_sum) AS sum_gs_cost,sum(bd_ol_use) AS sum_ol_use,sum(bd_ol_sum) AS sum_ol_cost,sum(bd_wt_use) AS sum_wt_use,sum(bd_wt_sum) AS sum_wt_cost,sum(bd_gl_use) AS sum_gl_use,sum(bd_gl_sum) AS sum_gl_cost,sum(bd_gm_use) AS sum_gm_use"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month BETWEEN '$min_ym' AND '$max_ym'";
	$result = db_exec($sql);
	if (!pg_numrows($result))
		return;
	$fetch = pg_fetch_object($result, 0);

	// ���ͥ륮��������
	$energy = ele_kwh_mcal($fetch->sum_el_use) + gas_m3_mcal($fetch->sum_gs_use) + oil_l_mcal($fetch->sum_ol_use) + gso_l_mcal(sum_gl_use);

	// ��ƻ������
	$water = $fetch->sum_wt_use;

	// ����
	$cost = $fetch->sum_el_cost + $fetch->sum_gs_cost + $fetch->sum_ol_cost + $fetch->sum_wt_cost + $fetch->sum_gl_cost;

	// CO2�ӽ���
	$co2 = cnv_ele_co2($fetch->sum_el_use) + cnv_gas_co2($fetch->sum_gs_use) + cnv_oil_co2($fetch->sum_ol_use) + cnv_wtr_co2($fetch->sum_wt_use) + cnv_gso_co2(sum_gl_use) + cnv_dst_co2($fetch->sum_gm_use);
}
?>