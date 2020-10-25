<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:金額グラフデータ生成
'******************************************************/

// 指定月の料金取得
function get_cost($seq_no, $date, &$no_input) {
	// 料金取得
	$sql = "SELECT bd_el_sum,bd_gs_sum,bd_wt_sum,bd_ol_sum,bd_gl_sum"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month='$date'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if (!$no_input['ele'])
			$cost = $fetch->bd_el_sum;

		if (!$no_input['gas'])
			$cost += $fetch->bd_gs_sum;

		if (!$no_input['wtr'])
			$cost += $fetch->bd_wt_sum;

		if (!$no_input['oil'])
			$cost += $fetch->bd_ol_sum;

		if (!$no_input['gso'])
			$cost += $fetch->bd_gl_sum;
	} else
		$cost = 0;

	return $cost;
}

// 入会からのトータル取得
function get_total_cost($seq_no, $regist_ym) {
	$sub1 = "(COALESCE(t1.bd_el_sum,0)+COALESCE(t1.bd_gs_sum,0)+COALESCE(t1.bd_wt_sum,0)"
				. "+CASE WHEN t1.bd_ol_sum<>0 OR t1.bd_ol_inp_cd=1 THEN COALESCE(t1.bd_ol_sum,0) ELSE 0 END"
				. "+CASE WHEN t1.bd_gl_sum<>0 OR t1.bd_gl_inp_cd=1 THEN COALESCE(t1.bd_gl_sum,0) ELSE 0 END)"
				. "-(CASE WHEN t1.bd_el_sum<>0 THEN COALESCE(t2.bd_el_sum,0) ELSE 0 END"
				. "+CASE WHEN t1.bd_gs_sum<>0 THEN COALESCE(t2.bd_gs_sum,0) ELSE 0 END+CASE WHEN t1.bd_wt_sum<>0 THEN COALESCE(t2.bd_wt_sum,0) ELSE 0 END"
				. "+CASE WHEN t1.bd_ol_sum<>0 OR t1.bd_ol_inp_cd=1 THEN COALESCE(t2.bd_ol_sum,0) ELSE 0 END"
				. "+CASE WHEN t1.bd_gl_sum<>0 OR t1.bd_gl_inp_cd=1 THEN COALESCE(t2.bd_gl_sum,0) ELSE 0 END)";
	$sql = "SELECT sum(CASE WHEN t1.bd_auto_commit=1 THEN 0 ELSE $sub1 END)"
			. " FROM t_base_data t1 JOIN t_base_data t2 ON t1.bd_mb_seq_no=t2.bd_mb_seq_no AND (t1.bd_month-INTERVAL '1 year')=t2.bd_month"
			. " WHERE t1.bd_mb_seq_no=$seq_no AND t1.bd_month>='$regist_ym' AND t1.bd_commit_flag=1";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_row($result, 0);
		$cost = (int)$fetch[0];
	} else
		$cost = 0;

	return $cost;
}

// 会員番号取得
$seq_no = $_SESSION['ss_seq_no'];

// 入会年月取得
$sql = "SELECT mb_regist_month FROM t_member WHERE mb_seq_no=$seq_no";
$regist_ym = db_fetch1($sql);

// 最新の消費データ年月を取得
$sql = "SELECT max(bd_month) FROM t_base_data WHERE bd_mb_seq_no=$seq_no AND bd_commit_flag=1";
$data_ym = db_fetch1($sql);
$this_month = (int)get_datepart('M', $data_ym);

// コミットフラグと灯油とガソリンの入力コード取得
$sql = "SELECT bd_auto_commit,bd_el_sum,bd_gs_sum,bd_wt_sum,bd_gl_sum,bd_ol_sum,bd_gl_inp_cd,bd_ol_inp_cd"
		. " FROM t_base_data"
		. " WHERE bd_mb_seq_no=$seq_no AND bd_month='$data_ym'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$auto_commit = $fetch->bd_auto_commit;

	if ($fetch->bd_el_sum == 0)
		$no_input['ele'] = true;

	if ($fetch->bd_gs_sum == 0)
		$no_input['gas'] = true;

	if ($fetch->bd_wt_sum == 0)
		$no_input['wtr'] = true;

	if ($fetch->bd_ol_sum == 0 && $fetch->bd_ol_inp_cd != 1)
		$no_input['oil'] = true;

	if ($fetch->bd_gl_sum == 0 && $fetch->bd_gl_inp_cd != 1)
		$no_input['gso'] = true;
}

// 今月分合計料金取得
if ($auto_commit != 1)
	$cost_cy = get_cost($seq_no, $data_ym, $no_input);
else
	$cost_cy = -9999;

// 前年同月分合計料金取得
$cost_ly = get_cost($seq_no, add_date($data_ym, -1, 0, 0), $no_input);

// 前年翌月分合計料金取得
$cost_ly2 = get_cost($seq_no, add_date($data_ym, -1, 1, 0), $no_input);

// 入会からのトータル取得
$total_cost = get_total_cost($seq_no, $regist_ym);
?>