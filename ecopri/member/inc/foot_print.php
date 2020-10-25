<?
/******************************************************
' System :Eco-footprint 会員ページ
' Content:足型表示処理
'******************************************************/

function get_foot_print($seq_no, $date, &$use, &$rank) {
	// ランク初期化
	$rank['ele'] = -9999;
	$rank['gas'] = -9999;
	$rank['wtr'] = -9999;
	$rank['oil_gso'] = -9999;
	$rank['dst'] = -9999;
	$rank['co2'] = -9999;

	// 消費量取得
	$sql = "SELECT bd_el_use,bd_gs_use,bd_ol_use,bd_wt_use,bd_gl_use,bd_gm_use,bd_auto_commit,bd_gm_inp_cd,bd_gl_inp_cd,bd_ol_inp_cd"
			. " FROM t_base_data"
			. " WHERE bd_mb_seq_no=$seq_no AND bd_month='$date'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		$use['ele'] = (int)$fetch->bd_el_use;
		$use['gas'] = (int)$fetch->bd_gs_use;
		$use['wtr'] = (int)$fetch->bd_wt_use;
		$use['oil_gso'] = (int)$fetch->bd_ol_use + (int)$fetch->bd_gl_use;
		$use['dst'] = (int)$fetch->bd_gm_use;

		if ($fetch->bd_ol_inp_cd == 2 || $fetch->bd_gl_inp_cd == 2)
			$no_input['oil_gso'] = true;

		if ($fetch->bd_gm_inp_cd == 2)
			$no_input['dst'] = true;

		if ($fetch->bd_auto_commit == 1) {
			$rank['ele'] = -8888;
			$rank['gas'] = -8888;
			$rank['wtr'] = -8888;
			$rank['oil_gso'] = -8888;
			$rank['dst'] = -8888;
			$rank['co2'] = -8888;
		} else {
			// CO2排出量とランクを取得
			$sql = "SELECT cr_co2,cr_rank,cr_ele_rank,cr_gas_rank,cr_wtr_rank,cr_dst_rank,cr_oil_gso_rank"
					. " FROM t_co2_rank"
					. " WHERE cr_seq_no=$seq_no AND cr_ym='$date'";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$rank['ele'] = get_rank_no($fetch->cr_ele_rank, 0);
				$rank['gas'] = get_rank_no($fetch->cr_gas_rank, 0);
				$rank['wtr'] = get_rank_no($fetch->cr_wtr_rank, 0);
				$rank['dst'] = get_rank_no($fetch->cr_dst_rank, $no_input['dst']);
				$rank['oil_gso'] = get_rank_no($fetch->cr_oil_gso_rank, $no_input['oil_gso']);
				$rank['co2'] = get_rank_no($fetch->cr_rank, $no_input['dst'] || $no_input['oil_gso']);

				$use['co2'] = (int)$fetch->cr_co2;
			}
		}
	}
}

function get_rank_no($rank, $no_input) {
	if ($rank != 0 || $no_input)
		return $rank;
	else
		return -9999;
}
?>