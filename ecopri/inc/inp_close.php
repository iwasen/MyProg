<?
/******************************************************
' System :Eco-footprint ����
' Content:���ϴ�λ����
'******************************************************/

// ��󥯤���ꤷ���ݥ���Ȥ�Ϳ����
function inp_close($bd_seq_no, $self_flag=0) {
	// ���ϥǡ����������
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

	// �ȥ�󥶥�����󳫻�
	db_begin_trans();

	// ��ƻ�ǡ���
	$last_m = date("Y-m-d", mktime (0,0,0,get_datepart('M', $date) - 1, 1, get_datepart('Y', $date)));
	$where = "where bd_mb_seq_no=$mb_seq_no and bd_month=" . sql_date($last_m);
	$sql = "select mb_water_month from t_member where mb_seq_no=$mb_seq_no";
	$wt_month = db_fetch1($sql);
	if (($wt_month % 2) == (get_datepart('M',$date) % 2)) {
			// ��ƻ�ǡ�������(����ʬ�ǡ����񤭴���)
		if ($wt_use) {
			$sql = "select bd_wt_use,bd_wt_sum from t_base_data $where";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$sql = "update t_base_data set bd_wt_use=" . sql_number($wt_use) . ", bd_wt_sum=" . sql_number($wt_sum) . " $where";
				db_exec($sql);
			}
		}
	} else {
		// ��ƻ�ǡ����ʤ�(ľ�����������������ʬ�񤭴���)
		
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
	// ��Ͽ�������
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
	// commit_flag��Ω�Ƥ�
	if ($self_flag == 1)
		$sql = "UPDATE t_base_data SET bd_commit_flag=1,bd_auto_commit=3 WHERE bd_seq_no=$bd_seq_no";
	else
		$sql = "UPDATE t_base_data SET bd_commit_flag=1 WHERE bd_seq_no=$bd_seq_no";
	db_exec($sql);

	// CO2�ӽ��̡���󥯡����ݥ���Ȥ����
	$point = get_rank_point($mb_seq_no, $date, $co2_ary, $rank_ary, $co2_cut);

	// �����CO2%�����
	$sql = "SELECT cr_co2_cut FROM t_co2_rank WHERE cr_seq_no=$mb_seq_no AND cr_ym="
			. sql_date(date('Y-m-d', mktime (0, 0, 0, get_datepart('M', $date), 1, get_datepart('Y', $date) - 1)));
	$co2_ly = db_fetch1($sql);

	// ���ڥ����ݥ���Ȳû�
	if ($co2_ly < 0 && $co2_cut < 0) {
		// ������Ϣ³������ʤ�+100P
		$point += 100;
	} elseif ($co2_ly > 0 && $co2_cut > 0) {
		// ������Ϣ³���åפʤ�-5P
		$point += -5;
	}

	// �ŵ�̤���ϥ����å�
	if ($el_use == 0) {
		// ̤���Ϥʤ��󥯡��ݥ���Ȥϣ�
		$point = 0;
		$rank_ary['ttl'] = 0;
	}

	// ����̤���ϥ����å�
	if ($gs_use == 0) {
		// ̤���ѤǤʤ����ϥ�󥯡��ݥ���Ȥϣ�
		$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
		if (db_fetch1($sql) != 3) {
			$point = 0;
			$rank_ary['ttl'] = 0;
		}
	}

	// ���ߡ�����������������Ϥˤ��ݥ����
	if ($gm_use)		// ����
		$point += 5;
	if ($gl_use)		// �������
		$point += 5;
	if ($ol_use)		// ����
		$point += 5;

	// ������λ�ʤ�ݥ����̵��
	if ($auto_commit == 1)
		$point = 0;

	$sql = "SELECT cr_seq_no FROM t_co2_rank WHERE cr_seq_no=$mb_seq_no";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		// ���˥ǡ��������ä�����
		$sql = "DELETE FROM t_co2_rank WHERE cr_seq_no=$mb_seq_no AND cr_ym=" . sql_date($date);
		db_exec($sql);
	}

	// CO2��󥯥ơ��֥���ɲ�
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

	// �ݥ������Ϳ
	add_point(PT_MONTHLY, $point);

	// ���ߥå�
	db_commit_trans();
}
?>