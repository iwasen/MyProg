<?
// 送信CSV
function csv_send($enq_id, $c1='', $c2='') {
	$enq_id_ary = make_ary($enq_id);

	$filename = "enq_send.csv";
	prepare_csv($filename);

	foreach($enq_id_ary as $enquete_id) {

		// アンケート開始日時取得
		$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$start_date = "'" . format_date(db_fetch1($sql)) . "'";


		// CSVデータ出力
		$sql = "SELECT mn_monitor_id,el_enquete_id,sr_chain,el_send_date AS date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_child,ar_area_name AS jitaku_area_name,sg_shokugyou_name"
				. " FROM t_enquete_list"
				. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
				. " JOIN t_my_enquete ON me_enquete_id=el_enquete_id"
				. " LEFT JOIN t_search ON sr_search_id=me_search_id"
				. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
				. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
				. " WHERE el_enquete_id=$enquete_id"
				. " ORDER BY el_monitor_id";
//		$csv = '番号,送信日,性別,未既婚,子供の有無,年齢,居住地域,職業';
		$csv = '回答受信連番,ＩＤ,JOB ID,チェーンコード,チェーン名,エリアコード,エリア名,業態コード,業態名,性別,未既婚,子供の有無,年齢,居住地域,職業,送信日';

		output_csv($csv);

		$result = db_exec($sql);
		$nrow = pg_num_rows($result);

		if ($nrow) {
			$fetch = pg_fetch_object($result, 0);
			get_chain_data($fetch->sr_chain, $chain_name, $area_cd, $area_name, $gyoutai_cd, $gyoutai_name);
		}

		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			set_csv($csv, $i + 1);
			set_csv($csv, $fetch->mn_monitor_id);
			set_csv($csv, $fetch->el_enquete_id);
			set_csv($csv, $fetch->sr_chain);
			set_csv($csv, $chain_name);
			set_csv($csv, $area_cd);
			set_csv($csv, $area_name);
			set_csv($csv, $gyoutai_cd);
			set_csv($csv, $gyoutai_name);
			set_csv($csv, decode_sex($fetch->mn_sex));
			set_csv($csv, decode_mikikon($fetch->mn_mikikon));
			set_csv($csv, decode_child($fetch->mn_child));
			set_csv($csv, $fetch->mn_age);
			set_csv($csv, $fetch->jitaku_area_name);
			set_csv($csv, $fetch->sg_shokugyou_name);
			set_csv($csv, format_date($fetch->date));
			output_csv($csv);
		}
		set_csv($csv, '');
		output_csv($csv);
	}
}

// 受信CSV
function csv_recv($enq_id, $c1='', $c2='') {
	$enq_id_ary = make_ary($enq_id);

	$filename = "enq_recv.csv";
	prepare_csv($filename);

	foreach($enq_id_ary as $enquete_id) {

		// アンケート開始日時取得
		$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$start_date = "'" . format_date(db_fetch1($sql)) . "'";

		// CSVデータ出力
		$sql = "SELECT an_date AS date,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_child,ar_area_name AS jitaku_area_name,sg_shokugyou_name"
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
				. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
				. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
				. " WHERE el_enquete_id=$enquete_id"
				. " ORDER BY an_date";
		$csv = '番号,回答日,性別,未既婚,子供の有無,年齢,居住地域,職業';

		output_csv($csv);

		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			set_csv($csv, $i + 1);
			set_csv($csv, format_date($fetch->date));
			set_csv($csv, decode_sex($fetch->mn_sex));
			set_csv($csv, decode_mikikon($fetch->mn_mikikon));
			set_csv($csv, decode_child($fetch->mn_child));
			set_csv($csv, $fetch->mn_age);
			set_csv($csv, $fetch->jitaku_area_name);
			set_csv($csv, $fetch->sg_shokugyou_name);

			output_csv($csv);
		}
		set_csv($csv, '');
		output_csv($csv);
	}
}

// 回答CSV
function csv_answer($enq_id, $c1='', $c2='') {
	global $g_set_csv;

	$enq_id_ary = make_ary($enq_id);

	$filename = "myenq.csv";
	prepare_csv($filename);

	foreach($enq_id_ary as $enquete_id) {

		// CSVデータ出力
		$csv = '回答受信連番,ＩＤ,JOB ID,チェーンコード,チェーン名,エリアコード,エリア名,業態コード,業態名,性別,未既婚,子供の有無,年齢,居住地域,職業,回答日時';

		$sql = "SELECT eq_question_no,hs_hyousoku_no,eq_fa_flag"
				. " FROM t_enq_question"
				. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
				. " WHERE eq_enquete_id=$enquete_id"
				. " ORDER BY eq_question_no,hs_hyousoku_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
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

		$sql = "SELECT mn_monitor_id,el_enquete_id, sr_chain, mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_child,ar_area_name AS jitaku_area_name,sg_shokugyou_name,an_date,eq_question_no,hs_hyousoku_no,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
				. " FROM t_enquete_list"
				. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
				. " JOIN t_my_enquete ON me_enquete_id=el_enquete_id"
				. " LEFT JOIN t_search ON sr_search_id=me_search_id"
				. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
				. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
				. " CROSS JOIN (SELECT eq_question_no,hs_hyousoku_no,eq_fa_flag FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE eq_enquete_id=$enquete_id) t1"
				. " JOIN t_answer ON an_enquete_id=$enquete_id AND an_monitor_id=mn_monitor_id"
				. " LEFT JOIN t_ans_select ON as_enquete_id=$enquete_id AND as_monitor_id=el_monitor_id AND as_question_no=eq_question_no"
				. " LEFT JOIN t_ans_matrix ON ax_enquete_id=$enquete_id AND ax_monitor_id=el_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
				. " WHERE el_enquete_id=$enquete_id"
				. " ORDER BY an_date,mn_monitor_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";

		$result = db_exec($sql);
		$nrow = pg_num_rows($result);

		if ($nrow) {
			$fetch = pg_fetch_object($result, 0);
			get_chain_data($fetch->sr_chain, $chain_name, $area_cd, $area_name, $gyoutai_cd, $gyoutai_name);
		}

		$no = 0;
		for ($i = 0; $i < $nrow; ) {
			$fetch = pg_fetch_object($result, $i);

			set_csv($csv, ++$no);
			set_csv($csv, $fetch->mn_monitor_id);
			set_csv($csv, $fetch->el_enquete_id);
			set_csv($csv, $fetch->sr_chain);
			set_csv($csv, $chain_name);
			set_csv($csv, $area_cd);
			set_csv($csv, $area_name);
			set_csv($csv, $gyoutai_cd);
			set_csv($csv, $gyoutai_name);
			set_csv($csv, decode_sex($fetch->mn_sex));
			set_csv($csv, decode_mikikon($fetch->mn_mikikon));
			set_csv($csv, decode_child($fetch->mn_child));
			set_csv($csv, $fetch->mn_age);
			set_csv($csv, $fetch->jitaku_area_name);
			set_csv($csv, $fetch->sg_shokugyou_name);
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

					if (!count($answer)){
						$c1_no_ans = 1;
					}

					$i++;
				}

				set_csv($csv, join(',', $answer));

				if ($fa_flag == 't')
					set_csv($csv, $fa);
			}

			// 無効回答チェック
			if($c1 && $c1_no_ans){
				$c1_no_ans = '';
				$csv = '';
				$g_set_csv = false;
				continue;
			}

			// 店舗名チェック
			if($c2){
				$fetch_last_qt = pg_fetch_object($result, ($i - 1));
				if (check_ngword($fetch_last_qt->as_free_answer)) {
					$csv = '';
					$g_set_csv = false;
					continue;
				}
			}

			output_csv($csv);
		}
		set_csv($csv, '');
		output_csv($csv);
	}
}

// 回答CSV(0,1形式）
function csv_answer2($enq_id, $c1='', $c2='') {
	global $g_set_csv;

	$enq_id_ary = make_ary($enq_id);

	$filename = "myenq.csv";
	prepare_csv($filename);

	foreach($enq_id_ary as $enquete_id) {
		$ma = array();

		// CSVデータ出力
		$csv = '回答受信連番,ＩＤ,JOB ID,チェーンコード,チェーン名,エリアコード,エリア名,業態コード,業態名,性別,未既婚,子供の有無,年齢,居住地域,職業,回答日時';

		$sql = "SELECT eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
				. " FROM t_enq_question"
				. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
				. " WHERE eq_enquete_id=$enquete_id"
				. " ORDER BY eq_question_no,hs_hyousoku_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$tmp = "設問$fetch->eq_question_no";

			if ($fetch->hs_hyousoku_no)
				$tmp .= "-$fetch->hs_hyousoku_no";

			if ($fetch->eq_question_type == 2) {
				$sql = "SELECT es_sel_no"
						. " FROM t_enq_select"
						. " WHERE es_enquete_id=$enquete_id AND es_question_no=$fetch->eq_question_no"
						. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_num_rows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					set_csv($csv, "$tmp-$fetch2->es_sel_no");
					$ma[$fetch->eq_question_no][] = $fetch2->es_sel_no;
				}
			} elseif ($fetch->eq_question_type == 5) {
				$sql = "SELECT ht_hyoutou_no"
						. " FROM t_enq_hyoutou"
						. " WHERE ht_enquete_id=$enquete_id AND ht_question_no=$fetch->eq_question_no"
						. " ORDER BY ht_hyoutou_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_num_rows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					set_csv($csv, "$tmp-$fetch2->ht_hyoutou_no");
					$ma[$fetch->eq_question_no][$fetch->hs_hyousoku_no][] = $fetch2->ht_hyoutou_no;
				}
			} else
				set_csv($csv, $tmp);

			if ($fetch->eq_fa_flag == 't')
				set_csv($csv, "$tmp-(FA)");
		}

		output_csv($csv);

		// アンケート開始日時取得
		$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$start_date = "'" . format_date(db_fetch1($sql)) . "'";

		$sql = "SELECT mn_monitor_id,el_enquete_id,sr_chain,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_child,ar_area_name AS jitaku_area_name,sg_shokugyou_name,an_date,eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
				. " FROM t_enquete_list"
				. " JOIN t_monitor ON mn_monitor_id=el_monitor_id"
				. " JOIN t_my_enquete ON me_enquete_id=el_enquete_id"
				. " LEFT JOIN t_search ON sr_search_id=me_search_id"
				. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
				. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
				. " CROSS JOIN (SELECT eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE eq_enquete_id=$enquete_id) t1"
				. " JOIN t_answer ON an_enquete_id=$enquete_id AND an_monitor_id=mn_monitor_id"
				. " LEFT JOIN t_ans_select ON as_enquete_id=$enquete_id AND as_monitor_id=el_monitor_id AND as_question_no=eq_question_no"
				. " LEFT JOIN t_ans_matrix ON ax_enquete_id=$enquete_id AND ax_monitor_id=el_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			. " WHERE el_enquete_id=$enquete_id"
				. " ORDER BY an_date,mn_monitor_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";

		$result = db_exec($sql);
		$nrow = pg_num_rows($result);

		if ($nrow) {
			$fetch = pg_fetch_object($result, 0);
			get_chain_data($fetch->sr_chain, $chain_name, $area_cd, $area_name, $gyoutai_cd, $gyoutai_name);
		}

		$no = 0;
		for ($i = 0; $i < $nrow; ) {
			$fetch = pg_fetch_object($result, $i);

			set_csv($csv, ++$no);
			set_csv($csv, $fetch->mn_monitor_id);
			set_csv($csv, $fetch->el_enquete_id);
			set_csv($csv, $fetch->sr_chain);
			set_csv($csv, $chain_name);
			set_csv($csv, $area_cd);
			set_csv($csv, $area_name);
			set_csv($csv, $gyoutai_cd);
			set_csv($csv, $gyoutai_name);
			set_csv($csv, decode_sex($fetch->mn_sex));
			set_csv($csv, decode_mikikon($fetch->mn_mikikon));
			set_csv($csv, decode_child($fetch->mn_child));
			set_csv($csv, $fetch->mn_age);
			set_csv($csv, $fetch->jitaku_area_name);
			set_csv($csv, $fetch->sg_shokugyou_name);
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

				if (!count($answer)){
					$c1_no_ans = 1;
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

			// 無効回答チェック
			if($c1 && $c1_no_ans){
				$c1_no_ans = '';
				$csv = '';
				$g_set_csv = false;
				continue;
			}

			// 店舗名チェック
			if($c2){
				$fetch_last_qt = pg_fetch_object($result, ($i - 1));
				if (check_ngword($fetch_last_qt->as_free_answer)) {
					$csv = '';
					$g_set_csv = false;
					continue;
				}
			}

			output_csv($csv);
		}
		set_csv($csv, '');
		output_csv($csv);
	}
}

function make_ary($enq_id){
	if(is_array($enq_id))
		return $enq_id;
	else
		return array($enq_id);
}

// チェーン、エリア、業態情報
function get_chain_data($chain_str, &$chain_name, &$area_cd, &$area_name, &$gyoutai_cd, &$gyoutai_name){
	$chain_name = decode_chain($chain_str);
	$area_cd = get_area_cd($chain_str);
	$area_name = decode_chain2area($chain_str);
	$gyoutai_cd = get_gyoutai_cd($chain_str);
	$gyoutai_name = decode_chain2gyoutai($chain_str);
}

function get_area_cd($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
			$sql = "SELECT ch_chain_area_cd FROM m_chain WHERE ch_chain_cd=$val";
			$c_cd[] = db_fetch1($sql);
		}
		$c_cd = array_unique($c_cd);
		$c_str = implode(',', $c_cd);
		return $c_str;
	}

}

function get_gyoutai_cd($chain_str){
	if ($chain_str){
		$chain_ary = explode(',', $chain_str);

		foreach($chain_ary as $val){
                        $sql = "SELECT ch_gyoutai_cd FROM m_chain WHERE ch_chain_cd=$val";
			$g_cd[] = db_fetch1($sql);
		}
		$g_cd = array_unique($g_cd);
		$g_str = implode(',', $g_cd);
		return $g_str;
	}

}

function check_ngword($ans){
	if ($ans) {
		$keyword = array(1 => '特になし',
										 2 => '特に無し',
										 3 => '特にない',
										 4 => '特に無い',
										 5 => 'ない',
										 6 => '無い',
										 7 => 'なし',
										 8 => '無し',
										 9 => '利用していない');
		return array_search($ans, $keyword);
	} else {
		return true;
	}
}
?>
