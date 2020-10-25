<?
/******************************************************
' System :Eco-footprint 共通
' Content:入力完了処理
'******************************************************/

// ランクを決定し、ポイントを与える
function inp_close($bd_seq_no, $self_flag=0) {
	// 入力データ情報取得
	$sql = "SELECT bd_mb_seq_no,bd_month,bd_el_use,bd_gs_use,bd_wt_use,bd_wt_sum,bd_gm_use,bd_gl_use,bd_ol_use,bd_auto_commit FROM t_base_data WHERE bd_seq_no=$bd_seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mb_seq_no = $fetch->bd_mb_seq_no;
		$date = $fetch->bd_month;
		$el_use = $fetch->bd_el_use;
		$gs_use = $fetch->bd_gs_use;
		$wt_use = $fetch->bd_wt_use;
		$wt_sum = $fetch->bd_wt_sum;
		$gm_use = $fetch->bd_gm_use;
		$gl_use = $fetch->bd_gl_use;
		$ol_use = $fetch->bd_ol_use;
		$auto_commit = $fetch->bd_auto_commit;
	} else
		return;

	// トランザクション開始
	db_begin_trans();

	// 水道データ
	$last_m = date("Y-m-d", mktime (0,0,0,get_datepart('M', $date) - 1, 1, get_datepart('Y', $date)));
	$where = "where bd_mb_seq_no=$mb_seq_no and bd_month=" . sql_date($last_m);
	$sql = "select mb_water_month from t_member where mb_seq_no=$mb_seq_no";
	$wt_month = db_fetch1($sql);
	if (($wt_month % 2) == (get_datepart('M',$date) % 2)) {
			// 水道データあり(前月分データ書き換え)
		if ($wt_use) {
			$sql = "select bd_wt_use,bd_wt_sum from t_base_data $where";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$sql = "update t_base_data set bd_wt_use=" . sql_number($wt_use) . ", bd_wt_sum=" . sql_number($wt_sum) . " $where";
				db_exec($sql);
			}
		}
	} else {
		// 水道データなし(直前情報取得して当月分書き換え)
		
		$sql = "select bd_wt_use,bd_wt_sum from t_base_data $where";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$sql = "update t_base_data set bd_wt_use=" . sql_number($fetch->bd_wt_use) . ", bd_wt_sum=" . sql_number($fetch->bd_wt_sum)
					. " where bd_seq_no=$bd_seq_no";
			db_exec($sql);
		}
	}
/*
	// 登録月初回処理
	$sql = "select cr_seq_no from t_co2_rank where cr_seq_no=$mb_seq_no";
	if (!db_fetch1($sql)) {
		$sql = "select bd_ol_use,bd_ol_sum,bd_gl_use,bd_gl_sum,bd_gm_use from t_base_data where bd_mb_seq_no=$mb_seq_no and bd_month = ". sql_date($last_m);
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$sql = sprintf("update t_base_data set bd_ol_use=%s,bd_ol_sum=%s,bd_gl_use=%s,bd_gl_sum=%s,bd_gm_use=%s where bd_seq_no=$bd_seq_no",
				sql_number($fetch->bd_ol_use),
				sql_number($fetch->bd_ol_sum),
				sql_number($fetch->bd_gl_use),
				sql_number($fetch->bd_gl_sum),
				sql_number($fetch->bd_gm_use));
			db_exec($sql);
		}
	}
*/
	// commit_flagを立てる
	if ($self_flag == 1)
		$sql = "UPDATE t_base_data SET bd_commit_flag=1,bd_auto_commit=3 WHERE bd_seq_no=$bd_seq_no";
	else
		$sql = "UPDATE t_base_data SET bd_commit_flag=1 WHERE bd_seq_no=$bd_seq_no";
	db_exec($sql);

	// CO2排出量、ランク、基準ポイントを取得
	$point = get_rank_point($mb_seq_no, $date, $co2_ary, $rank_ary, $co2_cut);

	// 前月のCO2%を取得
	$sql = "SELECT cr_co2_cut FROM t_co2_rank WHERE cr_seq_no=$mb_seq_no AND cr_ym="
			. sql_date(date('Y-m-d', mktime (0, 0, 0, get_datepart('M', $date), 1, get_datepart('Y', $date) - 1)));
	$co2_ly = db_fetch1($sql);

	// スペシャルポイント加算
	if ($co2_ly < 0 && $co2_cut < 0) {
		// ２ヶ月連続ダウンなら+100P
		$point += 100;
	} elseif ($co2_ly > 0 && $co2_cut > 0) {
		// ２ヶ月連続アップなら-5P
		$point += -5;
	}

	// 電気未入力チェック
	if ($el_use == 0) {
		// 未入力ならランク、ポイントは０
		$point = 0;
		$rank_ary['ttl'] = 0;
	}

	// ガス未入力チェック
	if ($gs_use == 0) {
		// 未使用でない場合はランク、ポイントは０
		$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
		if (db_fetch1($sql) != 3) {
			$point = 0;
			$rank_ary['ttl'] = 0;
		}
	}

	// ゴミ、灯油、ガソリン入力によるポイント
	if ($gm_use)		// ゴミ
		$point += 5;
	if ($gl_use)		// ガソリン
		$point += 5;
	if ($ol_use)		// 灯油
		$point += 5;

	// 強制終了ならポイント無し
	if ($auto_commit == 1)
		$point = 0;

	$sql = "SELECT cr_seq_no FROM t_co2_rank WHERE cr_seq_no=$mb_seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		// 既にデータがあったら削除
		$sql = "DELETE FROM t_co2_rank WHERE cr_seq_no=$mb_seq_no AND cr_ym=" . sql_date($date);
		db_exec($sql);
	}

	// CO2ランクテーブルに追加
	$sql = sprintf("INSERT INTO t_co2_rank (cr_seq_no,cr_ym,cr_co2,cr_co2_cut,cr_rank,cr_ele_co2,cr_ele_rank,cr_gas_co2,cr_gas_rank,cr_oil_co2,cr_oil_rank,cr_wtr_co2,cr_wtr_rank,cr_gso_co2,cr_gso_rank,cr_dst_co2,cr_dst_rank,cr_oil_gso_rank,cr_point) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
			sql_number($mb_seq_no),
			sql_date($date),
			sql_number($co2_ary['ttl']),
			sql_number($co2_cut),
			sql_number($rank_ary['ttl']),
			sql_number($co2_ary['ele']),
			sql_number($rank_ary['ele']),
			sql_number($co2_ary['gas']),
			sql_number($rank_ary['gas']),
			sql_number($co2_ary['oil']),
			sql_number($rank_ary['oil']),
			sql_number($co2_ary['wtr']),
			sql_number($rank_ary['wtr']),
			sql_number($co2_ary['gso']),
			sql_number($rank_ary['gso']),
			sql_number($co2_ary['dst']),
			sql_number($rank_ary['dst']),
			sql_number($rank_ary['oil_gso']),
			sql_number($point));
	db_exec($sql);

	// ポイント付与
	add_point(PT_MONTHLY, $point);

	// コミット
	db_commit_trans();
}
?>