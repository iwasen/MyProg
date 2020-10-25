<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:我が家の結果グラフデータ生成
'******************************************************/

// １年分の消費データを取得
function get_use_data($seq_no, $date, $init, &$use, &$co2, &$commit, &$inp) {
	// 配列初期化
	for ($i = 0; $i < 12; $i++) {
		$use[$i]['ele'] = $init;
		$use[$i]['gas'] = $init;
		$use[$i]['wtr'] = $init;
		$use[$i]['oil'] = $init;
		$use[$i]['gso'] = $init;
		$use[$i]['dst'] = $init;
	}

	$date2 = add_date($date, 0, 11, 0);
	$sql = "SELECT bd_month,bd_el_use,bd_gs_use,bd_ol_use,bd_wt_use,bd_gl_use,bd_gm_use,cr_co2,bd_auto_commit,bd_ol_inp_cd,bd_gl_inp_cd,bd_gm_inp_cd,cr_seq_no,cr_ele_co2,cr_gas_co2,cr_wtr_co2,cr_oil_co2,cr_gso_co2,cr_dst_co2"
			. " FROM t_base_data LEFT JOIN t_co2_rank ON bd_mb_seq_no=cr_seq_no AND bd_month=cr_ym"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_commit_flag=1 AND bd_month BETWEEN '$date' AND '$date2'"
			. " ORDER BY bd_month";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$month0 = get_datepart('Y', $date) * 12 + get_datepart('M', $date);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$n = get_datepart('Y', $fetch->bd_month) * 12 + get_datepart('M', $fetch->bd_month) - $month0;

		// 使用量
		$use[$n]['ele'] = $fetch->bd_el_use == '' ? 0 : $fetch->bd_el_use;
		$use[$n]['gas'] = $fetch->bd_gs_use == '' ? 0 : $fetch->bd_gs_use;
		$use[$n]['wtr'] = $fetch->bd_wt_use == '' ? 0 : $fetch->bd_wt_use;
		$use[$n]['oil'] = $fetch->bd_ol_use == '' ? 0 : $fetch->bd_ol_use;
		$use[$n]['gso'] = $fetch->bd_gl_use == '' ? 0 : $fetch->bd_gl_use;
		$use[$n]['dst'] = $fetch->bd_gm_use == '' ? 0 : $fetch->bd_gm_use;

		// CO2
		if ($fetch->cr_seq_no != null) {
			$co2[$n]['ele'] = $fetch->cr_ele_co2;
			$co2[$n]['gas'] = $fetch->cr_gas_co2;
			$co2[$n]['wtr'] = $fetch->cr_wtr_co2;
			$co2[$n]['oil'] = $fetch->cr_oil_co2;
			$co2[$n]['gso'] = $fetch->cr_gso_co2;
			$co2[$n]['dst'] = $fetch->cr_dst_co2;
		} else {
			$co2[$n]['ele'] = cnv_ele_co2($fetch->bd_el_use);
			$co2[$n]['gas'] = cnv_gas_co2($fetch->bd_gs_use);
			$co2[$n]['wtr'] = cnv_wtr_co2($fetch->bd_wt_use);
			$co2[$n]['oil'] = cnv_oil_co2($fetch->bd_ol_use);
			$co2[$n]['gso'] = cnv_gso_co2($fetch->bd_gl_use);
			$co2[$n]['dst'] = cnv_dst_co2($fetch->bd_gm_use);
		}

		// 強制コミットフラグ
		$commit[$n] = $fetch->bd_auto_commit;

		// 未入力フラグ
		$inp[$n]['oil'] = $fetch->bd_ol_inp_cd;
		$inp[$n]['gso'] = $fetch->bd_gl_inp_cd;
		$inp[$n]['dst'] = $fetch->bd_gm_inp_cd;
	}
}

// 入力状態フラグ取得
function get_input_flag($seq_no, &$use_cy, &$use_ly, &$co2_cy, &$co2_ly, &$commit_cy, &$inp_cy, &$status, $this_index) {
	// ガス使用状態取得
	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$seq_no";
	$use_gas = (db_fetch1($sql) == 3) ? 1 : 0;

	for ($i = 0; $i < 12; $i++) {
		// CO2以外
		$status[$i]['ele'] = get_input_flag_sub($use_cy[$i]['ele'], $commit_cy[$i], 0);
		$status[$i]['gas'] = get_input_flag_sub($use_cy[$i]['gas'], $commit_cy[$i], $use_gas);
		$status[$i]['wtr'] = get_input_flag_sub($use_cy[$i]['wtr'], $commit_cy[$i], 0);
		$status[$i]['oil'] = get_input_flag_sub($use_cy[$i]['oil'], $commit_cy[$i], $inp_cy[$i]['oil']);
		$status[$i]['gso'] = get_input_flag_sub($use_cy[$i]['gso'], $commit_cy[$i], $inp_cy[$i]['gso']);
		$status[$i]['dst'] = get_input_flag_sub($use_cy[$i]['dst'], $commit_cy[$i], $inp_cy[$i]['dst']);

		$n = min($i, $this_index);

		// 灯油入力チェック
		if ($use_cy[$n]['oil'] <= 0 && $inp_cy[$n]['oil'] != 1) {
			$use_ly[$i]['oil'] = 0;
			$co2_ly[$i]['oil'] = 0;
		}

		//ガソリン入力チェック
		if ($use_cy[$n]['gso'] <= 0 && $inp_cy[$n]['gso'] != 1) {
			$use_ly[$i]['gso'] = 0;
			$co2_ly[$i]['gso'] = 0;
		}

		// ゴミ入力チェック
		if ($use_cy[$n]['dst'] <= 0 && $inp_cy[$n]['dst'] != 1) {
			$use_ly[$i]['dst'] = 0;
			$co2_ly[$i]['dst'] = 0;
		}

		// CO2
		$use_cy[$i]['co2'] = round(get_total($co2_cy[$i]));
		$use_ly[$i]['co2'] = round(get_total($co2_ly[$i]));

		if ($commit_cy[$i] == 1) {
			// 強制完了（未完了）
			$status[$i]['co2'] = 2;
		} else {
			// 自己完了
			$status[$i]['co2'] = in_array(0, $status[$i]) ? 0 : 9;
		}
	}
}

function get_input_flag_sub($use, $commit, $inp) {
	if ($commit == 1) {
		// 強制完了（未完了）
		if ($inp == 1 || $use > 0)
			return 1;	// 未完了で入力有り
		else
			return 2;	// 未完了で入力無し
	} else {
		// 自己完了
		if ($inp == 1 || $use > 0)
			return 9;	// 完了で入力有り
		elseif ($inp == 2)
			return 0;	// 面倒だから入力しない
		else
			return 3;	// 入力無し設定
	}
}

// 配列の合計取得
function get_total($ary) {
	if (is_array($ary))
		return array_sum($ary);
	else
		return 0;
}

// リミット処理
function limit_data(&$use) {
	for ($i = 0; $i < 12; $i++) {
		$use[$i]['ele'] = min($use[$i]['ele'], 1000);
		$use[$i]['gas'] = min($use[$i]['gas'], 100);
		$use[$i]['wtr'] = min($use[$i]['wtr'], 60);
		$use[$i]['oil'] = min($use[$i]['oil'], 200);
		$use[$i]['gso'] = min($use[$i]['gso'], 200);
		$use[$i]['dst'] = min($use[$i]['dst'], 300);
		$use[$i]['co2'] = min($use[$i]['co2'], 1000);
	}
}

// ランク取得
function get_rank($seq_no, $date, &$rank) {
	// ランク→gifファイル名
	$gif = array('08', '01', '02', '03', '04', '05', '06', '07');

	// 配列初期化
	$rank['ele'] = $gif[0];
	$rank['gas'] = $gif[0];
	$rank['wtr'] = $gif[0];
	$rank['oil'] = $gif[0];
	$rank['gso'] = $gif[0];
	$rank['dst'] = $gif[0];

	$sql = "SELECT cr_ele_rank,cr_gas_rank,cr_wtr_rank,cr_dst_rank,cr_oil_rank,cr_gso_rank"
			. " FROM t_co2_rank"
			. " WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$rank['ele'] = $gif[$fetch->cr_ele_rank];
		$rank['gas'] = $gif[$fetch->cr_gas_rank];
		$rank['wtr'] = $gif[$fetch->cr_wtr_rank];
		$rank['oil'] = $gif[$fetch->cr_oil_rank];
		$rank['gso'] = $gif[$fetch->cr_gso_rank];
		$rank['dst'] = $gif[$fetch->cr_dst_rank];
	}
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

// 表示基準年月（左端）を取得
$start_ym = max($regist_ym, add_date($data_ym, 0, -11, 0));

// 今月データ位置取得
$n = (get_datepart('Y', $data_ym) * 12 + get_datepart('M', $data_ym)) - (get_datepart('Y', $start_ym) * 12 + get_datepart('M', $start_ym));

// 本年分消費データを取得
get_use_data($seq_no, $start_ym, -9999, $use_cy_ary, $co2_cy_ary, $commit_cy_ary, $inp_cy_ary);

// 昨年分消費データを取得
get_use_data($seq_no, add_date($start_ym, -1, 0, 0), 0, $use_ly_ary, $co2_ly_ary, $commit_ly_ary, $inp_ly_ary);

// 入力状態フラグ取得（0：未入力、1:未完了で入力あり、2:未完了で入力なし）
get_input_flag($seq_no, $use_cy_ary, $use_ly_ary, $co2_cy_ary, $co2_ly_ary, $commit_cy_ary, $inp_cy_ary, $status_ary, $n);

// 今月分消費データ取得
$use_ary = $use_cy_ary[$n];

// 削減量データ取得
foreach ($use_ary as $key => $val)
	$comp_ary[$key] = $val - $use_ly_ary[$n][$key];

// ランク取得
get_rank($seq_no, $data_ym, $rank);

// 例示換算
$metaphor['ele'] = (int)(abs($comp_ary['ele']) / 0.17);
$metaphor['gas'] = (int)(abs($comp_ary['gas']) * gas_calorie() / 0.34);
$metaphor['wtr'] = (int)(abs($comp_ary['wtr']) / 0.2);
$metaphor['oil'] = (int)(abs($comp_ary['oil']) / 1.8);
$metaphor['gso'] = (int)(abs($comp_ary['gso']) * 26.1);
$metaphor['dst'] = (int)(abs($comp_ary['dst']) / 0.4);
$metaphor['co2'] = (int)(abs($use_ary['co2']) / 13.9);

// グラフリミット処理
//limit_data($use_cy_ary);
//limit_data($use_ly_ary);
?>