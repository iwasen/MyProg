<?
/******************************************************
' System :Eco-footprint 共通
' Content:ランク取得処理
'******************************************************/

// CO2削減量、ランク、ポイントを取得する
function get_rank_point($seq_no, $date, &$co2_ary, &$rank_ary, &$co2_cut) {
	// 指定月のCO2排出量計算
	get_co2($seq_no, $date, $co2_ary);

	// 前年のCO2排出量計算
	$date_ly = add_date($date, -1, 0, 0);
	get_co2($seq_no, $date_ly, $co2_ary_ly);

	// 未入力項目は計算に入れないようにする
	$sql = "SELECT bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		if ($co2_ary['ele'] == 0) {
			$co2_ary_ly['ele'] = 0;
			$no_input['ele'] = true;
		}

		if ($co2_ary['gas'] == 0) {
			$co2_ary_ly['gas'] = 0;
			$no_input['gas'] = true;
		}

		if ($co2_ary['wtr'] == 0) {
			$co2_ary_ly['wtr'] = 0;
			$no_input['wtr'] = true;
		}

		if ($fetch->bd_ol_inp_cd != 1 && $co2_ary['oil'] == 0) {
			$co2_ary_ly['oil'] = 0;
			$no_input['oil'] = true;
		}

		if ($fetch->bd_gl_inp_cd != 1 && $co2_ary['gso'] == 0) {
			$co2_ary_ly['gso'] = 0;
			$no_input['gso'] = true;
		}

		if ($fetch->bd_gm_inp_cd != 1 && $co2_ary['dst'] == 0) {
			$co2_ary_ly['dst'] = 0;
			$no_input['dst'] = true;
		}
	}

	// CO2合計計算
	get_co2_total($co2_ary);
	get_co2_total($co2_ary_ly);

	// 灯油とガソリンの合計
	$co2_ary['oil_gso'] = $co2_ary['oil']  + $co2_ary['gso'];
	$co2_ary_ly['oil_gso'] = $co2_ary_ly['oil']  + $co2_ary_ly['gso'];

	// 灯油とガソリンの未入力チェック
	if ($no_input['oil'] || $no_input['gso'])
		$no_input['oil_gso'] = true;

	// CO2削減量
	if ($co2_ary_ly['ttl'])
		$co2_cut = (int)($co2_ary['ttl'] - $co2_ary_ly['ttl']);

	// CO2排出量クラス取得
	$sql = "SELECT cc_class FROM t_co2_class WHERE cc_seq_no=$seq_no";
	$class = db_fetch1($sql);
	if (!$class)
		$class = 2;

	// 評価値とポイント取得
	return get_rank($class, $co2_ary, $co2_ary_ly, $rank_ary, $no_input);
}

// 指定年月のCO2排出量取得
function get_co2($seq_no, $date, &$co2_ary) {
	// エネルギー消費データ取得
	$sql = "SELECT bd_el_use,bd_gs_use,bd_ol_use,bd_wt_use,bd_gl_use,bd_gm_use"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month=" . sql_date($date);
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// CO2排出量計算
		$co2_ary['ele'] = cnv_ele_co2($fetch->bd_el_use);
		$co2_ary['gas'] = cnv_gas_co2($fetch->bd_gs_use);
		$co2_ary['wtr'] = cnv_wtr_co2($fetch->bd_wt_use);
		$co2_ary['oil'] = cnv_oil_co2($fetch->bd_ol_use);
		$co2_ary['gso'] = cnv_gso_co2($fetch->bd_gl_use);
		$co2_ary['dst'] = cnv_dst_co2($fetch->bd_gm_use);
	}
}

// CO2排出量合計取得
function get_co2_total(&$co2_ary) {
	$total = 0;
	if (is_array($co2_ary)) {
		foreach ($co2_ary as $co2)
			$total += $co2;
	}
	$co2_ary['ttl'] = $total;
}

// CO2排出量から評価値取得
function get_rank($class, &$co2_ary, &$co2_ary_ly, &$rank_ary, &$no_input) {
	$point = 0;

	foreach ($co2_ary as $key => $co2) {
		$co2_ly = $co2_ary_ly[$key];

		if ($no_input[$key])
			$rank = 0;
		else {
			// 削減量のパーセント
			if ($co2_ly == 0) {
				if ($co2 == 0)
					$co2_percent = 0;		// 前年も当年も0なら0%とする
				else
					$co2_percent = 100;	// 前年が0で当年が0でないなら100%とする
			} else
				$co2_percent = (int)(($co2 - $co2_ly) / $co2_ly * 100);

			// ±100以内に丸め
			if ($co2_percent > 100)
				$co2_percent = 100;
			elseif ($co2_percent < -100)
				$co2_percent = -100;

			// ランクと基準ポイントを取得
			$sql = "SELECT ra_rank,ra_point FROM m_rank WHERE ra_class=$class AND ra_co2_low<=$co2_percent AND ra_co2_high>$co2_percent";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$rank = $fetch->ra_rank;
				if ($key == 'ttl')
					$point = $fetch->ra_point;
			}
		}

		$rank_ary[$key] = $rank;
	}

	return $point;
}
?>