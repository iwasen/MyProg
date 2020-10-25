<?
// 送信CSV
function csv_send($enquete_id) {
	// 出力準備
	$filename = "myp_send_$enquete_id.csv";
	prepare_csv($filename);

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// ヘッダ出力
	$csv = '番号,回答日時,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域';
	output_csv($csv);

	// データ出力
	$sql = "SELECT el_send_date AS date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
			. " FROM t_enquete_list"
			. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
			. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY el_monitor_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$csv = '';
		set_csv($csv, $i + 1);
		set_csv($csv, format_datetime($fetch->date));
		set_csv($csv, decode_sex($fetch->mn_sex));
		set_csv($csv, decode_mikikon($fetch->mn_mikikon));
		set_csv($csv, $fetch->mn_age);
		set_csv($csv, $fetch->jitaku_area_name);
		set_csv($csv, $fetch->sg_shokugyou_name);
		set_csv($csv, $fetch->gs_gyoushu_name);
		set_csv($csv, $fetch->ss_shokushu_name);
		set_csv($csv, $fetch->kinmu_area_name);

		output_csv($csv);
	}
}

// 受信CSV
function csv_recv($enquete_id) {
	// 出力準備
	$filename = "myp_recv_$enquete_id.csv";
	prepare_csv($filename);

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// ヘッダ出力
	$csv = '番号,回答日時,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域';
	output_csv($csv);

	// データ出力
	$sql = "SELECT an_date AS date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
			. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY an_date";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$csv = '';
		set_csv($csv, $i + 1);
		set_csv($csv, format_datetime($fetch->date));
		set_csv($csv, decode_sex($fetch->mn_sex));
		set_csv($csv, decode_mikikon($fetch->mn_mikikon));
		set_csv($csv, $fetch->mn_age);
		set_csv($csv, $fetch->jitaku_area_name);
		set_csv($csv, $fetch->sg_shokugyou_name);
		set_csv($csv, $fetch->gs_gyoushu_name);
		set_csv($csv, $fetch->ss_shokushu_name);
		set_csv($csv, $fetch->kinmu_area_name);

		output_csv($csv);
	}
}

// 該当者CSV
function csv_sel($pjt_id, $all, $output_id = false) {
	// アンケートIDと選定条件SQLを取得
	$sql = "SELECT ppj_enquete_id,ppj_select_sql FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->ppj_enquete_id;
	$select_sql = $fetch->ppj_select_sql;

	// 出力準備
	$filename = "myp_$enquete_id.csv";
	prepare_csv($filename);

	// ヘッダ出力
	if ($output_id)
		$csv = '回答受信連番,ＩＤ,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時';
	else
		$csv = '回答受信連番,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時';
	$sql = "SELECT eq_question_no,hs_hyousoku_no,eq_fa_flag"
			. " FROM t_enq_question"
			. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
			. " WHERE eq_enquete_id=$enquete_id"
			. " ORDER BY eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$tmp = "設問$fetch->eq_question_no";

		if ($fetch->hs_hyousoku_no)
			$tmp .= "-$fetch->hs_hyousoku_no";

		set_csv($csv, $tmp);

		if ($fetch->eq_fa_flag == 't')
			set_csv($csv, "$tmp-(FA)");
	}
	output_csv($csv);

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// データ出力
	$where = $all ? "pmn_pjt_id=$pjt_id" : $select_sql;
	$sql = "SELECT mn_monitor_id,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,an_date,eq_question_no,hs_hyousoku_no,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			. " FROM t_mp_monitor"
			. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
			. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			. " CROSS JOIN (SELECT eq_question_no,hs_hyousoku_no,eq_fa_flag FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE eq_enquete_id=$enquete_id) t1"
			. " JOIN t_answer ON an_enquete_id=$enquete_id AND an_monitor_id=mn_monitor_id"
			. " LEFT JOIN t_ans_select ON as_enquete_id=$enquete_id AND as_monitor_id=pmn_monitor_id AND as_question_no=eq_question_no"
			. " LEFT JOIN t_ans_matrix ON ax_enquete_id=$enquete_id AND ax_monitor_id=pmn_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			. " WHERE $where"
			. " ORDER BY an_date,mn_monitor_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$no = 0;
	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		$csv = '';
		set_csv($csv, ++$no);
		if ($output_id)
			set_csv($csv, $fetch->mn_monitor_id);
		set_csv($csv, decode_sex($fetch->mn_sex));
		set_csv($csv, decode_mikikon($fetch->mn_mikikon));
		set_csv($csv, $fetch->mn_age);
		set_csv($csv, $fetch->jitaku_area_name);
		set_csv($csv, $fetch->sg_shokugyou_name);
		set_csv($csv, $fetch->gs_gyoushu_name);
		set_csv($csv, $fetch->ss_shokushu_name);
		set_csv($csv, $fetch->kinmu_area_name);
		set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));

		$monitor_id = $fetch->mn_monitor_id;
		while ($i < $nrow) {
			if ($monitor_id != $fetch->mn_monitor_id)
				break;

			$question_no = $fetch->eq_question_no;
			$hyousoku_no = $fetch->hs_hyousoku_no;

			$answer = array();
			$fa = '';
			$fa_flag = $fetch->eq_fa_flag;
			while ($i < $nrow) {
				$fetch = pg_fetch_object($result, $i);

				if ($monitor_id != $fetch->mn_monitor_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
					break;

				if ($fetch->as_sel_no)
					$answer[] = $fetch->as_sel_no;
				elseif ($fetch->ax_hyoutou_no)
					$answer[] = $fetch->ax_hyoutou_no;

				if ($fetch->as_free_answer != '') {
					if ($fa_flag == 't') {
						if ($fa != '')
							$fa .= ',';
						$fa .= $fetch->as_free_answer;
					} else {
						$answer[] = $fetch->as_free_answer;
					}
				}

				$i++;
			}

			set_csv($csv, join(',', $answer));

			if ($fa_flag == 't')
				set_csv($csv, $fa);
		}

		output_csv($csv);
	}
}

// 該当者CSV(0,1形式）
function csv_sel2($pjt_id, $all, $output_id = false) {
	// アンケートIDと選定条件SQLを取得
	$sql = "SELECT ppj_enquete_id,ppj_select_sql FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	$fetch = pg_fetch_object($result, 0);
	$enquete_id = $fetch->ppj_enquete_id;
	$select_sql = $fetch->ppj_select_sql;

	// 出力準備
	$filename = "myp_$enquete_id.csv";
	prepare_csv($filename);

	// ヘッダ・質問文・選択肢出力
	if ($output_id) {
		$csv_h = '回答受信連番,ＩＤ,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時';
		$csv_q = '質問文→,,,,,,,,,,';
		$csv_s = '選択肢→,,,,,,,,,,';
	} else {
		$csv_h = '回答受信連番,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時';
		$csv_q = '質問文→,,,,,,,,,';
		$csv_s = '選択肢→,,,,,,,,,';
	}
	$question_no = 0;
	$sql = "SELECT eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			. " FROM t_enq_question"
			. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
			. " WHERE eq_enquete_id=$enquete_id"
			. " ORDER BY eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$tmp = "設問$fetch->eq_question_no";

		if ($fetch->eq_question_no != $question_no) {
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
		} else
			$question_text = '';

		if ($fetch->hs_hyousoku_no)
			$tmp .= "-$fetch->hs_hyousoku_no";

		switch ($fetch->eq_question_type) {
		case 1:		// SA
			$sa = array();
			$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id AND es_question_no=$fetch->eq_question_no"
					. " ORDER BY es_sel_no";
			$result2 = db_exec($sql);
			$nrow2 = pg_numrows($result2);
			for ($j = 0; $j < $nrow2; $j++) {
				$fetch2 = pg_fetch_object($result2, $j);
				$sa[] = "$fetch2->es_sel_no.$fetch2->es_sel_text";
			}
			set_csv($csv_h, $tmp);
			set_csv($csv_q, $question_text);
			set_csv($csv_s, join('／', $sa));
			break;
		case 2:		// MA
			$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id AND es_question_no=$fetch->eq_question_no"
					. " ORDER BY es_sel_no";
			$result2 = db_exec($sql);
			$nrow2 = pg_numrows($result2);
			for ($j = 0; $j < $nrow2; $j++) {
				$fetch2 = pg_fetch_object($result2, $j);
				set_csv($csv_h, "$tmp-$fetch2->es_sel_no");
				set_csv($csv_q, $question_text);
				set_csv($csv_s, "$fetch2->es_sel_no.$fetch2->es_sel_text");
				$question_text = '';
				$ma[$fetch->eq_question_no][] = $fetch2->es_sel_no;
			}
			break;
		case 4:		// MatrixSA
			$sa = array();
			$sql = "SELECT ht_hyoutou_no,ht_hyoutou_text"
					. " FROM t_enq_hyoutou"
					. " WHERE ht_enquete_id=$enquete_id AND ht_question_no=$fetch->eq_question_no"
					. " ORDER BY ht_hyoutou_no";
			$result2 = db_exec($sql);
			$nrow2 = pg_numrows($result2);
			for ($j = 0; $j < $nrow2; $j++) {
				$fetch2 = pg_fetch_object($result2, $j);
				$sa[] = "$fetch2->ht_hyoutou_no.$fetch2->ht_hyoutou_text";
			}
			set_csv($csv_h, $tmp);
			set_csv($csv_q, $question_text);
			set_csv($csv_s, join('／', $sa));
			break;
		case 5:		// MatrixMA
			$sql = "SELECT ht_hyoutou_no,ht_hyoutou_text"
					. " FROM t_enq_hyoutou"
					. " WHERE ht_enquete_id=$enquete_id AND ht_question_no=$fetch->eq_question_no"
					. " ORDER BY ht_hyoutou_no";
			$result2 = db_exec($sql);
			$nrow2 = pg_numrows($result2);
			for ($j = 0; $j < $nrow2; $j++) {
				$fetch2 = pg_fetch_object($result2, $j);
				set_csv($csv_h, "$tmp-$fetch2->ht_hyoutou_no");
				set_csv($csv_q, $question_text);
				set_csv($csv_s, "$fetch2->ht_hyoutou_no.$fetch2->ht_hyoutou_text");
				$question_text = '';
				$ma[$fetch->eq_question_no][$fetch->hs_hyousoku_no][] = $fetch2->ht_hyoutou_no;
			}
			break;
		default:
			set_csv($csv_h, $tmp);
			set_csv($csv_q, $question_text);
			set_csv($csv_s, '');
			break;
		}

		if ($fetch->eq_fa_flag == 't') {
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	output_csv($csv_s);

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";

	// データ出力
	$where = $all ? "pmn_pjt_id=$pjt_id" : $fetch->ppj_select_sql;
	$sql = "SELECT mn_monitor_id,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,an_date,eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			. " FROM t_mp_monitor"
			. " JOIN t_monitor ON mn_monitor_id=pmn_monitor_id"
			. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			. " CROSS JOIN (SELECT eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE eq_enquete_id=$enquete_id) t1"
			. " JOIN t_answer ON an_enquete_id=$enquete_id AND an_monitor_id=mn_monitor_id"
			. " LEFT JOIN t_ans_select ON as_enquete_id=$enquete_id AND as_monitor_id=pmn_monitor_id AND as_question_no=eq_question_no"
			. " LEFT JOIN t_ans_matrix ON ax_enquete_id=$enquete_id AND ax_monitor_id=pmn_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			. " WHERE $where"
			. " ORDER BY an_date,mn_monitor_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$no = 0;
	for ($i = 0; $i < $nrow; ) {
		$fetch = pg_fetch_object($result, $i);

		$csv = '';
		set_csv($csv, ++$no);
		if ($output_id)
			set_csv($csv, $fetch->mn_monitor_id);
		set_csv($csv, decode_sex($fetch->mn_sex));
		set_csv($csv, decode_mikikon($fetch->mn_mikikon));
		set_csv($csv, $fetch->mn_age);
		set_csv($csv, $fetch->jitaku_area_name);
		set_csv($csv, $fetch->sg_shokugyou_name);
		set_csv($csv, $fetch->gs_gyoushu_name);
		set_csv($csv, $fetch->ss_shokushu_name);
		set_csv($csv, $fetch->kinmu_area_name);
		set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));

		$monitor_id = $fetch->mn_monitor_id;
		while ($i < $nrow) {
			if ($monitor_id != $fetch->mn_monitor_id)
				break;

			$question_no = $fetch->eq_question_no;
			$question_type = $fetch->eq_question_type;
			$hyousoku_no = $fetch->hs_hyousoku_no;

			$answer = array();
			$fa = '';
			$fa_flag = $fetch->eq_fa_flag;
			while ($i < $nrow) {
				$fetch = pg_fetch_object($result, $i);

				if ($monitor_id != $fetch->mn_monitor_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
					break;

				if ($fetch->as_sel_no)
					$answer[] = $fetch->as_sel_no;
				elseif ($fetch->ax_hyoutou_no)
					$answer[] = $fetch->ax_hyoutou_no;

				if ($fetch->as_free_answer != '') {
					if ($fa_flag == 't') {
						if ($fa != '')
							$fa .= ',';
						$fa .= $fetch->as_free_answer;
					} else {
						$answer[] = $fetch->as_free_answer;
					}
				}

				$i++;
			}

			if ($question_type == 2) {
				if (is_array($ma[$question_no])) {
					foreach ($ma[$question_no] as $sel_no)
						set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
				}
			} elseif ($question_type == 5) {
				if (is_array($ma[$question_no][$hyousoku_no])) {
					foreach ($ma[$question_no][$hyousoku_no] as $sel_no)
						set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
				}
			} else
				set_csv($csv, join(',', $answer));

			if ($fa_flag == 't')
				set_csv($csv, $fa);
		}

		output_csv($csv);
	}
}

// ニックネーム配列の取得
function get_nickname_ary($job_id) {
	$ary = array();

	$where = sprintf("jm_job_id=%s AND jm_status=1", sql_number($job_id));
	$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,jm_participant_auth"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " WHERE $where"
			. " ORDER BY CASE WHEN substr(jm_participant_auth,1,1)='1' THEN 0 ELSE pm_member_id END";
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->pm_member_id] = $fetch->pm_nickname;
	}

	return $ary;
}

// 発言録データ配列の取得
function get_record_ary($job_id, $remark_id) {
	$remark_ary = array();
	$count_ary = array();

	get_remark_id_ary($job_id, $remark_id_ary, $remark_id);
	if ($remark_id_ary) {
		$sql = "SELECT rm_member_id,rm_seq_no,rm_content"
				. " FROM l_remark"
				. " WHERE rm_remark_id IN (" . join(',', $remark_id_ary) . ")"
				. " ORDER BY rm_seq_no";
		$result = icp_db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$member_id = $fetch->rm_member_id;
			$row = (int)$count_ary[$member_id]++;

			$remark_ary[$row][$member_id] = $fetch->rm_content;
		}
	}

	return $remark_ary;
}

// スレッド表示
function get_remark_id_ary($job_id, &$ary, $remark_id, $parent_remark_id = 0) {
	$sql = sprintf(
				"SELECT rm_remark_id,rm_remark_type,rm_child_flag"
			. " FROM l_remark"
			. " WHERE rm_job_id=%s AND %s=%s AND rm_status=1 AND rm_disp_type=1"
			. " ORDER BY rm_remark_id",
			sql_number($job_id),
			$remark_id != 0 ? 'rm_remark_id' : 'rm_parent_remark_id',
			sql_number($remark_id != 0 ? $remark_id : $parent_remark_id));
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($fetch->rm_remark_type == 3)
			$ary[] = $fetch->rm_remark_id;

		if ($fetch->rm_child_flag == DBTRUE)
			get_remark_id_ary($job_id, $ary, 0, $fetch->rm_remark_id);
	}
}

// 発言録CSV
function csv_log($job_id) {
	// ジョブコードを取得
	$sql = "SELECT jb_job_cd"
			. " FROM t_job"
			. " WHERE jb_job_id=$job_id";
	$job_cd = icp_db_fetch1($sql);

	// 出力ファイル名
	prepare_csv("alltopics_{$job_cd}.csv");

	// 発言内容をトピック毎に取得
	$where = sprintf("rm_job_id=%s AND rm_disp_type=1 AND rm_status=1 AND rm_marking_id=1", sql_number($job_id));

	// 発言録を配列に格納
	$topic_ary = array();
	$sql = "SELECT rm_remark_id,rm_subject"
			. " FROM l_remark"
			. " WHERE $where"
			. " ORDER BY rm_remark_id";
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$topic_ary[$i]['subject'] = '★' . $fetch->rm_subject;
		$topic_ary[$i]['content'] = get_record_ary($job_id, $fetch->rm_remark_id);
	}

	// メンバーの発言の有無をチェック
	$member_remark_ary = array();
	foreach ($topic_ary as $topic) {
		foreach ($topic['content'] as $remark) {
			foreach (array_keys($remark) as $member_id)
				$member_remark_ary[$member_id] = true;
		}
	}

	// ニックネーム出力
	set_csv($csv, 'トピック', true);
	$nickname_ary = get_nickname_ary($job_id);
	$member_id_ary = array();
	foreach ($nickname_ary as $member_id => $nickname) {
		if ($member_remark_ary[$member_id]) {
			set_csv($csv, $nickname, true);
			$member_id_ary[] = $member_id;
		}
	}
	output_csv($csv);

	// 発言録を出力
	foreach ($topic_ary as $topic) {
		$subject = $topic['subject'];

		foreach ($topic['content'] as $content) {
			// 件名を出力
			set_csv($csv, $subject, true);
			$subject = '';

			foreach ($member_id_ary as $member_id)
				set_csv($csv, str_replace("\r", '', $content[$member_id]), true);

			output_csv($csv);
		}
	}
}

/*
// 発言ログCSV
function csv_log($job_id) {
	// プロジェクト名、ジョブ名、ジョブ詳細を取得
	$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_description"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_job_id=$job_id";
	$result = icp_db_exec($sql);
	$fetch = pg_fetch_object($result, 0);

	// 出力ファイル名
	prepare_csv("log_{$fetch->pj_pjt_cd}_{$fetch->jb_job_cd}.csv");

	// プロジェクト名出力
	set_csv($csv, '■プロジェクト名');
	set_csv($csv, $fetch->pj_name);
	output_csv($csv);

	// ジョブ名出力
	set_csv($csv, '■ジョブ名');
	set_csv($csv, $fetch->jb_name);
	output_csv($csv);

	// ジョブ詳細出力
	set_csv($csv, '■ジョブ詳細');
	set_csv($csv, $fetch->jb_description);
	output_csv($csv);

	// 改行
	output_csv($csv);

	// ヘッダ出力
	$csv = '発言SEQ,受信日時,件名,本文,ジョブ参加ID,ニックネーム,性別,年齢,未既婚,居住地';
	output_csv($csv);

	// データ出力
	$sql = "SELECT rm_seq_no,rm_date,rm_subject,rm_content,jm_member_cd,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_sex_cd,DATE_PART('Y',AGE(pm_birthday)) AS pm_age,pm_mikikon_cd,pm_address,pm_org_id,or_name,pm_org_member_id,pm_staff_auth"
			. " FROM l_remark"
			. " LEFT JOIN t_job_member ON jm_job_id=rm_job_id AND jm_member_id=rm_member_id"
			. " LEFT JOIN t_pjt_member ON pm_member_id=rm_member_id"
			. " LEFT JOIN m_org ON or_org_id=pm_org_id"
			. " WHERE rm_job_id=$job_id AND rm_disp_type=1 AND rm_status=1"
			. " ORDER BY rm_seq_no";
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		set_csv($csv, $fetch->rm_seq_no);
		set_csv($csv, format_datetime($fetch->rm_date));
		set_csv($csv, $fetch->rm_subject);
		set_csv($csv, $fetch->rm_content);
		set_csv($csv, $fetch->jm_member_cd);
		set_csv($csv, $fetch->pm_nickname);
		set_csv($csv, $fetch->pm_sex_cd);
		set_csv($csv, $fetch->pm_age);
		set_csv($csv, $fetch->pm_mikikon_cd);
		set_csv($csv, $fetch->pm_address);

		output_csv($csv);
	}
}
*/
// 発言集計CSV
function csv_count($job_id) {
	// プロジェクト名、ジョブ名、ジョブ詳細を取得
	$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name,jb_description"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_job_id=$job_id";
	$result = icp_db_exec($sql);
	$fetch = pg_fetch_object($result, 0);

	// 出力ファイル名
	prepare_csv("count_{$fetch->pj_pjt_cd}_{$fetch->jb_job_cd}.csv");

	// プロジェクト名出力
	set_csv($csv, '■プロジェクト名');
	set_csv($csv, $fetch->pj_name);
	output_csv($csv);

	// ジョブ名出力
	set_csv($csv, '■ジョブ名');
	set_csv($csv, $fetch->jb_name);
	output_csv($csv);

	// ジョブ詳細出力
	set_csv($csv, '■ジョブ詳細');
	set_csv($csv, $fetch->jb_description);
	output_csv($csv);

	// 改行
	output_csv($csv);

	// ヘッダ出力
	$csv = 'ジョブ参加ID,ニックネーム,性別,年齢,未既婚,居住地,組織ID,組織名,組織メンバーID,総発言,発言日数';
	output_csv($csv);

	// 発言集計CSV出力
	$sb1 = "SELECT rm_member_id,COUNT(*) AS s_count1,COUNT(DISTINCT(date_trunc('day', rm_date))) AS s_count2"
			. " FROM l_remark"
			. " WHERE rm_job_id=$job_id AND rm_remark_type=3 AND rm_status=1"
			. " GROUP BY rm_member_id";
	$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_sex_cd,DATE_PART('Y',AGE(pm_birthday)) AS pm_age,pm_mikikon_cd,pm_address,pm_org_id,or_name,pm_org_member_id,s_count1,s_count2"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " JOIN m_org ON or_org_id=pm_org_id"
			. " LEFT JOIN ($sb1) sb1 ON rm_member_id=jm_member_id"
			. " WHERE jm_job_id=$job_id AND jm_status=1"
			. " ORDER BY pm_member_id";
	$result = icp_db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		set_csv($csv, $fetch->pm_member_id);
		set_csv($csv, $fetch->pm_nickname);
		set_csv($csv, $fetch->pm_sex_cd);
		set_csv($csv, $fetch->pm_age);
		set_csv($csv, $fetch->pm_mikikon_cd);
		set_csv($csv, $fetch->pm_address);
		set_csv($csv, $fetch->pm_org_id);
		set_csv($csv, $fetch->or_name);
		set_csv($csv, $fetch->pm_org_member_id);
		set_csv($csv, number_format($fetch->s_count1));
		set_csv($csv, number_format($fetch->s_count2));

		output_csv($csv);
	}
}

// クロス集計CSV
function csv_crs($enquete_id, $hyousoku, $hyoutou) {
	// 出力ファイル名
	$filename = "myp_crs_$enquete_id.csv";
	prepare_csv($filename);

	$enquete = new enquete_class;
	$enquete->read_db($enquete_id);

	$q_hyousoku = &$enquete->question[$hyousoku];
	$q_hyoutou = &$enquete->question[$hyoutou];

	// 回答者数取得
	$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id";
	$ans_count = db_fetch1($sql, 0);

	$sql = "SELECT s.as_sel_no AS s_sel_no,t.as_sel_no AS t_sel_no,COUNT(*) as sel_count"
			. " FROM t_ans_select s"
			. " JOIN t_ans_select t ON t.as_monitor_id=s.as_monitor_id"
			. " WHERE s.as_enquete_id=$enquete_id AND s.as_question_no=$hyousoku AND t.as_enquete_id=$enquete_id AND t.as_question_no=$hyoutou"
			. " GROUP BY t.as_sel_no,s.as_sel_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$count_tbl[$fetch->s_sel_no][$fetch->t_sel_no] = $fetch->sel_count;
	}

	set_csv($csv, '');
	foreach ($q_hyoutou->sel_text as $tno => $sel_text)
		set_csv($csv, "Q$hyoutou=$tno");
	output_csv($csv);

	foreach ($q_hyousoku->sel_text as $sno => $sel_text) {
		set_csv($csv, "Q$hyousoku=$sno");

		foreach ($q_hyoutou->sel_text as $tno => $sel_text)
			set_csv($csv, (int)$count_tbl[$sno][$tno]);

		output_csv($csv);
	}

	output_csv($csv);

	set_csv($csv, '');
	foreach ($q_hyoutou->sel_text as $tno => $sel_text)
		set_csv($csv, "Q$hyoutou=$tno");
	output_csv($csv);

	foreach ($q_hyousoku->sel_text as $sno => $sel_text) {
		set_csv($csv, "Q$hyousoku=$sno");

		foreach ($q_hyoutou->sel_text as $tno => $sel_text)
			set_csv($csv, format_percent($count_tbl[$sno][$tno], $ans_count, 1));

		output_csv($csv);
	}
}
?>