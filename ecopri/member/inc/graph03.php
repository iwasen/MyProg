<?
/******************************************************
' System :Eco-footprint ����ڡ���
' Content:���Ե���񥰥�եǡ�������
'******************************************************/

define('MEMBER_NORM', 55);		// ���ǯ��CO2�︺�Υ��[�ܿ�]
define('CO2_TREE', 13.9);			// CO2-�ڴ����졼��

// ����ֹ����
$seq_no = $_SESSION['ss_seq_no'];

// ����ǯ�����
$sql = "SELECT mb_regist_month FROM t_member WHERE mb_seq_no=$seq_no";
$regist_date = db_fetch1($sql);

// ������ּ���
$date_from = $regist_date;
$date_to = add_date($date_from, 0, 11, 0);

// ���δ֤�CO2�︺�̼���
$sql = "SELECT cr_co2_cut,bd_auto_commit,bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd"
		. " FROM t_base_data JOIN t_co2_rank ON bd_mb_seq_no=cr_seq_no AND bd_month=cr_ym"
		. " WHERE cr_seq_no=$seq_no" // AND cr_ym BETWEEN '$date_from' AND '$date_to'"
		. " ORDER BY cr_ym";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$co2_ary[$i] = $fetch->cr_co2_cut;
	$auto_commit[$i] = $fetch->bd_auto_commit;

	if ($fetch->bd_auto_commit != 1)
		$total_co2 += $fetch->cr_co2_cut;

	if ($fetch->bd_gm_inp_cd == 2 || $fetch->bd_gl_inp_cd == 2 || $fetch->bd_ol_inp_cd == 2)
		$no_input[$i] = true;
}

// ��ʬ�������ܿ��ʥץ饹���ޥ��ʥ���
//$total_tree_num = -round($total_co2 / CO2_TREE, 0);

// ��ʬ�ΥΥ���ܿ��ʸ����
$norm_num = MEMBER_NORM;

// ���Ϸ�¾
$past_month_num = $nrow - 12;
if ($past_month_num > 0) {
	$cr_ym = $_SESSION['ss_cr_ym'];
	$start_month = get_datepart('M', add_date(date($cr_ym), 0, 1, 0));
	$past_tree_num = 0;
} else {
	$start_month = get_datepart('M', $date_from);
	$past_tree_num = -9999;
	$past_month_num = 0;
	$nrow = 12;
}

// ������ڤ��ܿ���39�ܰʲ���
$total_tree_num = 0;
for ($i = 0; $i < $nrow; $i++) {
	$index = $i - $past_month_num;
	$tree_month[$index] = -round($co2_ary[$i] / CO2_TREE, 0);

	// ���ϥե饰
	if ($auto_commit[$i] == 1)
		$input[$index] = 2;
	elseif ($no_input[$i])
		$input[$index] = 0;
	else
		$input[$index] = 9;

	// ��ʬ�������ܿ��ʥץ饹���ޥ��ʥ���
	if ($auto_commit[$i] != 1) {
		$total_tree_num += $tree_month[$index];

		// �����������ܿ�
		if ($past_month_num > 0 && $i < $past_month_num)
			$past_tree_num += $tree_month[$index];
	}
}
?>