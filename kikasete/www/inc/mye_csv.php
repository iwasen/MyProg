<?
include ("$inc/pro_enq_target.php");

// 送信CSV
function csv_send($enquete_id, $output_kinmusaki = true)
{
	// 出力準備
	$filename = "enq_send_$enquete_id.csv";
	prepare_csv($filename);
	// ヘッダ出力
	$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv = "番号,送信日,性別,未既婚,年齢,居住地域,職業,業種,職種{$header_kinmusaki}";
	output_csv($csv);
	 //データ出力
	$sql = "SELECT el_send_date AS date,mn_sex,"
		."mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,"
		."sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
		. " FROM t_enquete_list"
		. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " WHERE el_enquete_id=$enquete_id"
		. " ORDER BY el_monitor_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		set_csv($csv, $i +1);
		set_csv($csv, format_date($fetch->date));
		set_csv($csv, decode_sex($fetch->mn_sex));
		set_csv($csv, decode_mikikon($fetch->mn_mikikon));
		set_csv($csv, $fetch->mn_age);
		//set_csv($csv, decode_age($fetch->mn_age,5));
		//set_csv($csv, decode_age($fetch->mn_age,10));
		//set_csv($csv, decode_age($fetch->mn_age,15));
		set_csv($csv, $fetch->jitaku_area_name);
		set_csv($csv, $fetch->sg_shokugyou_name);
		set_csv($csv, $fetch->gs_gyoushu_name);
		set_csv($csv, $fetch->ss_shokushu_name);
		if ($output_kinmusaki)
			set_csv($csv, $fetch->kinmu_area_name);
		output_csv($csv);
	}
}

// 受信CSV
function csv_recv($enquete_id, $output_kinmusaki = true)
{
	// 出力準備
	$filename = "enq_recv_$enquete_id.csv";
	prepare_csv($filename);
	// ヘッダ出力
	$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv = "番号,回答日,性別,未既婚,年齢,居住地域,職業,業種,職種{$header_kinmusaki}";
	output_csv($csv);
	// データ出力
	$sql = "SELECT pet_target_flg FROM t_pro_enq_target WHERE pet_enquete_id=$enquete_id";
	if (db_fetch1($sql) == 'g') {
		$sql = "SELECT an_date AS date,mn_sex,mn_age,"
			."mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,"
			."ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
			. " FROM t_answer"
			. " JOIN g_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
			. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
			. " ORDER BY an_date";
	} else {
		$sql = "SELECT an_date AS date,mn_sex,mn_age,"
			."mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,"
			."ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id AND an_valid_flag"
			. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
			. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY an_date";
	}
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		set_csv($csv, $i +1);
		set_csv($csv, format_date($fetch->date));
		set_csv($csv, decode_sex($fetch->mn_sex));
		set_csv($csv, decode_mikikon($fetch->mn_mikikon));
		set_csv($csv, $fetch->mn_age);
		//set_csv($csv, decode_age($fetch->mn_age,5));
		//set_csv($csv, decode_age($fetch->mn_age,10));
		//set_csv($csv, decode_age($fetch->mn_age,15));
		set_csv($csv, $fetch->jitaku_area_name);
		set_csv($csv, $fetch->sg_shokugyou_name);
		set_csv($csv, $fetch->gs_gyoushu_name);
		set_csv($csv, $fetch->ss_shokushu_name);
		if ($output_kinmusaki)
			set_csv($csv, $fetch->kinmu_area_name);
		output_csv($csv);
	}
}
//kim_start seq no=67 ファインディング カンマ 関数生成
/* 2007/12/06 xxx 未使用と思われるので削除
function csv_answer_one($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr')
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;
	// 出力準備
	$filename = "";
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "an_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = $enquete_id;
		$filename = "qr_$enquete_id.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	//$header_id = $output_id ? ',ＩＤ' : '';
	//$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h =  "回答受信連番,ＩＤ,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時,性別コード,未既婚コード,居住地域コード,職業コード,業種コード,職種コード,勤務先地域コード";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id && $file_prefix != 'dr01') ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
			//$question_text = '';
		}
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
				$sql = " SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa_1[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_t, join('／', $sa_1));
				set_csv($csv_v, null);
				set_csv($csv_n, null);
				set_csv($csv_s, join('／', $sa_1));
				$sa_1=null;
				$sa_2=null;
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
						. " FROM t_enq_select"
						. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
						. " ORDER BY es_sel_no";
					$result2 = db_exec($sql);
					$nrow2 = pg_numrows($result2);
					$st = "";
					for ($j = 0; $j < $nrow2; $j++)
					{
						$fetch2 = pg_fetch_object($result2, $j);
						if ($st != "") $st .= "/";
						$st .= $fetch2->es_sel_no.".".$fetch2->es_sel_text;
					}
						set_csv($csv_h,$tmp);
						set_csv($csv_q, $question_text);
						set_csv($csv_t,$st);
						set_csv($csv_v,null);
						set_csv($csv_n,null);
						set_csv($csv_s, $st);
					break;

			case 4 : // MatrixSA

				$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
				$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = "$fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text";
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp . "-". $fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;

				//kim_end seq no=67

			case 5 : // MatrixMA
			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = "$fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text";
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp . "-". $fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;
			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;

		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_t,null);
			set_csv($csv_v,null);
			set_csv($csv_n,null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else if($fetch->eq_question_type==1||$fetch->eq_question_type==2||$fetch->eq_question_type==3||$fetch->eq_question_type==6||$fetch->eq_question_type==''){
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		// 利用ネットワーク
		$pro_enq_target = & new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex,"
					. "DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,"
					. " ss_order,gs_order,"
					. " ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,"
					. " sg_shokugyou_cd,"
					. " mn_mikikon,mn_jitaku_area AS jitaku_area_cd,sg_shokugyou_cd,"
					. " gs_gyoushu_cd,ss_shokushu_cd,ar_area_cd AS kinmu_area_cd,an_date,"
					. " eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. " AND an_valid_flag"
					. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
					. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
					. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
					. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
					. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. " FROM t_enq_question "
					. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2" . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
			$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age, "
				  . " ss_order,gs_order,"
			      . " ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,"
			      ."  mn_mikikon,mn_jitaku_area AS jitaku_area_cd, "
			      ."  sg_shokugyou_cd,gs_gyoushu_cd,ss_shokushu_cd, "
			      ."  ar_area_cd AS kinmu_area_cd,an_date,"
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag"
			      . " JOIN ${mon_flg}_monitor ON mn_monitor_id=an_monitor_id"
			      . " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
			      . " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			      . " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			      . " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			//if ($output_id)
				set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			//if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
				set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
				set_csv($csv, $fetch->mn_sex);
				set_csv($csv, $fetch->mn_mikikon);
				set_csv($csv, $fetch->jitaku_area_cd);
				set_csv($csv, $fetch->sg_shokugyou_cd);
				set_csv($csv, $fetch->gs_order);
				set_csv($csv, $fetch->ss_order);
				set_csv($csv, $fetch->kinmu_area_cd);
				$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;
					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
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
}

//kim_end sep no=67
//kim_start seq no=67 ファインでリグ 0,1 関数生成
function csv_answer_two($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr01')
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;
	// 出力準備
	$filename = "qr01_{$enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	//$header_id = $output_id ? ',ＩＤ' : '';
	//$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h =  "回答受信連番,ＩＤ,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時,性別コード,未既婚コード,居住地域コード,職業コード,業種コード,職種コード,勤務先地域コード";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id && $file_prefix != 'dr01') ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
			//$question_text = '';
		}
		//if ($fetch->hs_hyousoku_no)
		//	$tmp .= "-$fetch->hs_hyousoku_no";
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
				$sa = array ();
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n,null);
				set_csv($csv_s, join('／', $sa));
				set_csv($csv_t, join('／', $sa));
				set_csv($csv_v, null);
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					set_csv($csv_h, "$tmp-$fetch2->es_sel_no");
					set_csv($csv_q, $question_text);
					set_csv($csv_n, null);
					set_csv($csv_s, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_t, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_v, null);
					$question_text = '';
					$ma[$enquete_id_tmp][$question_no][] = $fetch2->es_sel_no;
				}
				break;

			case 4 : // MatrixSA

			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);

				}
				$check=true;
				break;
				//kim_end seq no=67
			case 5 : // MatrixMA

    			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
    					" FROM t_enq_hyoutou " .
    					" WHERE ht_enquete_id=$enquete_id_tmp " .
    					" AND ht_question_no=$question_no" .
    					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

			    $st = array();
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$st[ $fetch2->ht_hyoutou_no ] = $fetch2->ht_hyoutou_no .".". $fetch2->ht_hyoutou_text;
				}

			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";

				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)	{
					$fetch2 = pg_fetch_object($result2, $j);
                    foreach( $st as  $key => $value  ) {
					   $hs_num = $fetch2->hs_hyousoku_no;
					   set_csv($csv_h, $tmp."-".$hs_num."-".$key);
					   set_csv($csv_q, $question_text);
					   //kim_start seq no=67 表側内容挿入関数追加-
					   set_csv($csv_v, $hs_num.".".$fetch2->hs_hyousoku_text);
					   set_csv($csv_t, $value);
					   $ma[$enquete_id_tmp][$question_no][$hs_num][] = $key;
                    }
				}
				$check=true;
				break;

			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;
		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_n, null);
			set_csv($csv_v, null);
			set_csv($csv_t, null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else if($fetch->eq_question_type==1||$fetch->eq_question_type==2||$fetch->eq_question_type==3||$fetch->eq_question_type==6||$fetch->eq_question_type==''){
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		// 利用ネットワーク
		$pro_enq_target = & new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex," .
					"DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age," .
					" ss_order,gs_order,".
					" ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,".
					"mn_mikikon,mn_jitaku_area AS jitaku_area_cd,sg_shokugyou_cd," .
					"gs_gyoushu_cd,ss_shokushu_cd,ar_area_cd AS kinmu_area_cd,an_date," .
					"eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. " AND an_valid_flag"
					. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
					. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
					. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
					. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
					. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. " FROM t_enq_question "
					. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2"
					. " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
			$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age, "
				  . " ss_order,gs_order,"
			      . " ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,"
			      ."  mn_mikikon,mn_jitaku_area AS jitaku_area_cd, "
			      ."  sg_shokugyou_cd,gs_gyoushu_cd,ss_shokushu_cd, "
			      ."  ar_area_cd AS kinmu_area_cd,an_date,"
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag"
			      . " JOIN ${mon_flg}_monitor ON mn_monitor_id=an_monitor_id"
			      . " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
			      . " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			      . " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			      . " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			//if ($output_id)
				set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			//if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
				set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
				set_csv($csv, $fetch->mn_sex);
				set_csv($csv, $fetch->mn_mikikon);
				set_csv($csv, $fetch->jitaku_area_cd);
				set_csv($csv, $fetch->sg_shokugyou_cd);
				set_csv($csv, $fetch->gs_order);
				set_csv($csv, $fetch->ss_order);
				set_csv($csv, $fetch->kinmu_area_cd);
			$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;
					//kim_start seq no=67 answer配列に表側値を入力 追加
					elseif($fetch->ax_hyousoku_no) $answer[] = $fetch->ax_hyousoku_no;
					//kim_end seq no=67
					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
							$answer[] = $fetch->as_free_answer;
						}
					}
					$i++;
				}
				if ($question_type == 2)
				{
					if (is_array($ma[$enquete_id][$question_no]))
					{
						foreach ($ma[$enquete_id][$question_no] as $sel_no)
							set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
					}
				}
				elseif ($question_type == 5)
				{
					if (is_array($ma[$enquete_id][$question_no][$hyousoku_no]))
					{
						foreach ($ma[$enquete_id][$question_no][$hyousoku_no] as $sel_no)
						set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');

					}
				}
				else
					set_csv($csv, join(',', $answer));
				if ($fa_flag == 't')
					set_csv($csv, $fa);
			}
			output_csv($csv);
		}
	}
}
*/
//kim_end seq no=67
// 回答CSV
function csv_answer($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr', $test = false, $file_enquete_id = 0)
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;

	// 出力準備
	if ($file_enquete_id == 0)
		$file_enquete_id = $enquete_id;
	$filename = "{$file_prefix}_{$file_enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	$csv_h =  "#SEQ,ID,性別,年齢,未既婚,居住地域,職業";
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,hs_hyousoku_text,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);

		$question_no = (($fetch->eq_enquete_id == $finding_enquete_id) ? 'FDQ' : 'Q') . $fetch->eq_question_no;
		$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));

		switch ($fetch->eq_question_type) {
		case 4 : // MatrixSA
		case 5 : // MatrixMA
			$tmp = "■{$question_no}-{$fetch->hs_hyousoku_no}.{$question_text}({$fetch->hs_hyousoku_text})";
			set_csv($csv_h, $tmp);
			break;
		default :
			$tmp = "■{$question_no}.{$question_text}";
			set_csv($csv_h, $tmp);
			break;
		}

		if ($fetch->eq_fa_flag == 't')
		{
			$sql = "SELECT es_sel_text FROM t_enq_select WHERE es_enquete_id=$fetch->eq_enquete_id AND es_question_no=$fetch->eq_question_no AND es_ex_flag=false ORDER BY es_sel_no DESC LIMIT 1";
			$tmp = $question_no . '.' . db_fetch1($sql, 'その他');
			set_csv($csv_h, $tmp);
		}
	}
	output_csv($csv_h);

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		if ($test) {
			$mon_flg = 't_test';
			$test_flg = 'AND an_test_flg';
			$mon_join = "JOIN {$mon_flg}_monitor ON mn_monitor_id=an_monitor_id";
		} else {
			// 利用ネットワーク
			$pro_enq_target = & new pro_enq_target_class;
			$pro_enq_target->read_db($enquete_id);
			$mon_flg = $pro_enq_target->target_flg;
			$test_flg = '';
			$mon_join = "JOIN {$mon_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id";
		}
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex,"
					. " mn_age,mn_mikikon,mn_jitaku_area,mn_shokugyou_cd,"
					. " eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id AND an_valid_flag"
					. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. " FROM t_enq_question "
					. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2" . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
				$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_jitaku_area,mn_shokugyou_cd, "
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag $test_flg"
				  . " $mon_join"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			set_csv($csv, $fetch->mn_monitor_id);
			set_csv($csv, $fetch->mn_sex);
			set_csv($csv, $fetch->mn_age);
			set_csv($csv, $fetch->mn_mikikon);
			set_csv($csv, $fetch->mn_jitaku_area);
			set_csv($csv, $fetch->mn_shokugyou_cd);

			$monitor_id = $fetch->mn_monitor_id;

			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;
					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
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
}

// 回答CSV(0,1形式）
function csv_answer2($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr01', $test = false, $file_enquete_id = 0)
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;

	// 出力準備
	if ($file_enquete_id == 0)
		$file_enquete_id = $enquete_id;
	$filename = "{$file_prefix}_{$file_enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}

	// ヘッダ・質問文・選択肢出力
	$csv_h =  "#SEQ,ID,性別,年齢,未既婚,居住地域,職業";
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,hs_hyousoku_text,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);

		$question_no = (($fetch->eq_enquete_id == $finding_enquete_id) ? 'FDQ' : 'Q') . $fetch->eq_question_no;
		$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));

        switch ($fetch->eq_question_type) {
        case 2:     // MA
            $sql = "SELECT es_sel_no"
                    . " FROM t_enq_select"
                    . " WHERE es_enquete_id=$fetch->eq_enquete_id AND es_question_no=$fetch->eq_question_no"
                    . " ORDER BY es_sel_no";
            $result2 = db_exec($sql);
            $nrow2 = pg_numrows($result2);
            for ($j = 0; $j < $nrow2; $j++) {
                $fetch2 = pg_fetch_object($result2, $j);
				$tmp = "■{$question_no}-{$fetch2->es_sel_no}.{$question_text}";
				set_csv($csv_h, $tmp);
                $ma[$fetch->eq_enquete_id][$fetch->eq_question_no][] = $fetch2->es_sel_no;
            }
            break;
        case 4:     // MatrixSA
			$tmp = "■{$question_no}-{$fetch->hs_hyousoku_no}.{$question_text}({$fetch->hs_hyousoku_text})";
			set_csv($csv_h, $tmp);
            break;
        case 5:     // MatrixMA
            $sql = "SELECT ht_hyoutou_no"
                    . " FROM t_enq_hyoutou"
                    . " WHERE ht_enquete_id=$fetch->eq_enquete_id AND ht_question_no=$fetch->eq_question_no"
                    . " ORDER BY ht_hyoutou_no";
            $result2 = db_exec($sql);
            $nrow2 = pg_numrows($result2);
            for ($j = 0; $j < $nrow2; $j++) {
                $fetch2 = pg_fetch_object($result2, $j);
				$tmp = "■{$question_no}-{$fetch->hs_hyousoku_no}-{$fetch2->ht_hyoutou_no}.{$question_text}({$fetch->hs_hyousoku_text})";
				set_csv($csv_h, $tmp);
                $ma[$fetch->eq_enquete_id][$fetch->eq_question_no][$fetch->hs_hyousoku_no][] = $fetch2->ht_hyoutou_no;
            }
            break;
        default:
			$tmp = "■{$question_no}.{$question_text}";
			set_csv($csv_h, $tmp);
            break;
        }

		if ($fetch->eq_fa_flag == 't')
		{
			$sql = "SELECT es_sel_text FROM t_enq_select WHERE es_enquete_id=$fetch->eq_enquete_id AND es_question_no=$fetch->eq_question_no AND es_ex_flag=false ORDER BY es_sel_no DESC LIMIT 1";
			$tmp = $question_no . '.' . db_fetch1($sql, 'その他');
			set_csv($csv_h, $tmp);
		}
	}
	output_csv($csv_h);

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		if ($test) {
			$mon_flg = 't_test';
			$test_flg = 'AND an_test_flg';
			$mon_join = "JOIN {$mon_flg}_monitor ON mn_monitor_id=an_monitor_id";
		} else {
			// 利用ネットワーク
			$pro_enq_target = & new pro_enq_target_class;
			$pro_enq_target->read_db($enquete_id);
			$mon_flg = $pro_enq_target->target_flg;
			$test_flg = '';
			$mon_join = "JOIN {$mon_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id";
		}
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex," .
					"mn_age,mn_mikikon,mn_jitaku_area,mn_shokugyou_cd," .
					"eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. " AND an_valid_flag"
					. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. " FROM t_enq_question "
					. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2"
					. " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
			$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,mn_jitaku_area,mn_shokugyou_cd, "
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag $test_flg"
				  . " $mon_join"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			set_csv($csv, $fetch->mn_monitor_id);
			set_csv($csv, $fetch->mn_sex);
			set_csv($csv, $fetch->mn_age);
			set_csv($csv, $fetch->mn_mikikon);
			set_csv($csv, $fetch->mn_jitaku_area);
			set_csv($csv, $fetch->mn_shokugyou_cd);

			$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;

					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
							$answer[] = $fetch->as_free_answer;
						}
					}
					$i++;
				}
				if ($question_type == 2)
				{
					if (is_array($ma[$enquete_id][$question_no]))
					{
						foreach ($ma[$enquete_id][$question_no] as $sel_no)
							set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
					}
				}
				elseif ($question_type == 5)
				{
					if (is_array($ma[$enquete_id][$question_no][$hyousoku_no]))
					{
						foreach ($ma[$enquete_id][$question_no][$hyousoku_no] as $sel_no)
						set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');

					}
				}
				else
					set_csv($csv, join(',', $answer));
				if ($fa_flag == 't')
					set_csv($csv, $fa);
			}
			output_csv($csv);
		}
	}
}

 //kim_end seq no=67 marketer csv出力部分に 用いる関数追加
/* 2007/12/06 xxx 未使用と思われるので削除
// 回答CSV
function t_csv_answer($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr')
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;
	// 出力準備
	$filename = "{$file_prefix}_{$enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	$header_id = $output_id ? ',ＩＤ' : '';
	$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h = "回答受信連番{$header_id},性別,未既婚,年齢,居住地域,職業,業種,職種{$header_kinmusaki},回答日時";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id && $file_prefix != 'dr') ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
			//$question_text = '';
		}
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
				$sql = " SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa_1[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_t, join('／', $sa_1));
				set_csv($csv_v, null);
				set_csv($csv_n, null);
				set_csv($csv_s, join('／', $sa_1));
				$sa_1=null;
				$sa_2=null;
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
						. " FROM t_enq_select"
						. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
						. " ORDER BY es_sel_no";
					$result2 = db_exec($sql);
					$nrow2 = pg_numrows($result2);
					$st = "";
					for ($j = 0; $j < $nrow2; $j++)
					{
						$fetch2 = pg_fetch_object($result2, $j);
						if ($st != "") $st .= "/";
						$st .= $fetch2->es_sel_no.".".$fetch2->es_sel_text;
					}
						set_csv($csv_h,$tmp);
						set_csv($csv_q, $question_text);
						set_csv($csv_t,$st);
						set_csv($csv_v,null);
						set_csv($csv_n,null);
						set_csv($csv_s, $st);
					break;

			case 4 : // MatrixSA

				$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
				$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;

				//kim_end seq no=67

			case 5 : // MatrixMA
			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;
			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;

		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_t,null);
			set_csv($csv_v,null);
			set_csv($csv_n,null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else if($fetch->eq_question_type==1||$fetch->eq_question_type==2||$fetch->eq_question_type==3||$fetch->eq_question_type==6||$fetch->eq_question_type==''){
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		// 利用ネットワーク
		$pro_enq_target = & new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex," .
					"DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age," .
					"mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name," .
					"gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,an_date," .
					"eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. "AND an_valid_flag" //kim_start seq no=67
					. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
					. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
					. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
					. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
					. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
					. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. "FROM t_enq_question " .
					"LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2" . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
		 $sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age, "
			      ."  mn_mikikon,m_area1.ar_area_name AS jitaku_area_name, "
			      ."  sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name, "
			      ."  m_area2.ar_area_name AS kinmu_area_name,an_date,"
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag"
			      . " JOIN ${mon_flg}_monitor ON mn_monitor_id=an_monitor_id"
			      . " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			      . " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			      . " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			      . " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			      . " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			if ($output_id)
				set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
			set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
			$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;
					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
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
}
// 回答CSV(0,1形式）
function t_csv_answer2($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr01')
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;
	// 出力準備
	$filename = "{$file_prefix}_{$enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	$header_id = $output_id ? ',ＩＤ' : '';
	$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h = "回答受信連番{$header_id},性別,未既婚,年齢,居住地域,職業,業種,職種{$header_kinmusaki},回答日時";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id && $file_prefix != 'dr01') ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
		}
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
				$sa = array ();
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n,null);
				set_csv($csv_s, join('／', $sa));
				set_csv($csv_t, join('／', $sa));
				set_csv($csv_v, null);
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					set_csv($csv_h, "$tmp-$fetch2->es_sel_no");
					set_csv($csv_q, $question_text);
					set_csv($csv_n, null);
					set_csv($csv_s, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_t, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_v, null);
					$question_text = '';
					$ma[$enquete_id_tmp][$question_no][] = $fetch2->es_sel_no;
				}
				break;

			case 4 : // MatrixSA

			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);

				}
				$check=true;
				break;
				//kim_end seq no=67
			case 5 : // MatrixMA

    			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
    					" FROM t_enq_hyoutou " .
    					" WHERE ht_enquete_id=$enquete_id_tmp " .
    					" AND ht_question_no=$question_no" .
    					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

			    $st = array();
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$st[ $fetch2->ht_hyoutou_no ] = $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
				}

			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";

				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)	{
					$fetch2 = pg_fetch_object($result2, $j);
                    foreach( $st as  $key => $value  ) {
					   $hs_num = $fetch2->hs_hyousoku_no;
					   set_csv($csv_h, $tmp."-".$hs_num . "-" . $key);
					   set_csv($csv_q, $question_text);
					   //kim_start seq no=67 表側内容挿入関数追加-
					   set_csv($csv_v, $hs_num.".".$fetch2->hs_hyousoku_text);
					   set_csv($csv_t, $value);
					   $ma[$enquete_id_tmp][$question_no][$hs_num][] = $key;
                    }
				}
				$check=true;
				break;

			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;
		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_n, null);
			set_csv($csv_v, null);
			set_csv($csv_t, null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else if($fetch->eq_question_type==1||$fetch->eq_question_type==2||$fetch->eq_question_type==3||$fetch->eq_question_type==6||$fetch->eq_question_type==''){
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		// 利用ネットワーク
		$pro_enq_target = & new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		// データ出力
		if ($mon_flg == 't')
		{
			$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex," .
					"DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age," .
					"mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name," .
					"gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,an_date," .
					"eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. "AND an_valid_flag" //kim_start seq no=67
					. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
					. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
					. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
					. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
					. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
					. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. "FROM t_enq_question " .
					"LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2" . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
			$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age, "
			      ."  mn_mikikon,m_area1.ar_area_name AS jitaku_area_name, "
			      ."  sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name, "
			      ."  m_area2.ar_area_name AS kinmu_area_name,an_date,"
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag"
			      . " JOIN ${mon_flg}_monitor ON mn_monitor_id=an_monitor_id"
			      . " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
			      . " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
			      . " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			      . " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			      . " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			if ($output_id)
				set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
			set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
			$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;

					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
							$answer[] = $fetch->as_free_answer;
						}
					}
					$i++;
				}
				if ($question_type == 2)
				{
					if (is_array($ma[$enquete_id][$question_no]))
					{
						foreach ($ma[$enquete_id][$question_no] as $sel_no)
							set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
					}
				}
				elseif ($question_type == 5)
				{
					if (is_array($ma[$enquete_id][$question_no][$hyousoku_no]))
					{
						foreach ($ma[$enquete_id][$question_no][$hyousoku_no] as $sel_no)
						set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');

					}
				}
				else
					set_csv($csv, join(',', $answer));
				if ($fa_flag == 't')
					set_csv($csv, $fa);
			}
			output_csv($csv);
		}
	}
}
*/
//kim_end seq no=100

// 回答CSV
// 回答格納テスト管理画面のＣＳＶデータダウンロードのダウンロード関数
//function csv_answer_test($enquete_id,$enquete2_id, $output_id = false, $output_kinmusaki = true, $mode) {
// 回答格納履歴の際はテストデータだけCSV出力する 2006/06/14 BTI
function csv_answer_test($enquete_id, $enquete2_id, $output_id = false, $output_kinmusaki = true, $mode, $history)
{
	// 出力準備
	$check=false;
	$filename = ""; // ダウンロードファイルの名称を変更 2006/03/28 BTI
	if ($mode != 1)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
		//$answer_join = "en_enquete_id";
		$filename = "qrdr_$enquete_id.csv";
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "an_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = $enquete_id;
		$filename = "qr_$enquete_id.csv";
	}
	prepare_csv($filename);
	// ヘッダ・質問文・選択肢出力
	$header_id = $output_id ? ',ＩＤ' : '';
	$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h = "回答受信連番{$header_id},性別,年齢,未既婚,居住地域,職業,業種,職種{$header_kinmusaki},回答日時";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id) ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
		}
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
			case 7 : // プルダウン
				$sql = " SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa_1[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_t, join('／', $sa_1));
				set_csv($csv_v, null);
				set_csv($csv_n, null);
				set_csv($csv_s, join('／', $sa_1));
				$sa_1=null;
				$sa_2=null;
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
						. " FROM t_enq_select"
						. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
						. " ORDER BY es_sel_no";
					$result2 = db_exec($sql);
					$nrow2 = pg_numrows($result2);
					$st = "";
					for ($j = 0; $j < $nrow2; $j++)
					{
						$fetch2 = pg_fetch_object($result2, $j);
						if ($st != "") $st .= "/";
						$st .= $fetch2->es_sel_no.".".$fetch2->es_sel_text;
					}
						set_csv($csv_h,$tmp);
						set_csv($csv_q, $question_text);
						set_csv($csv_t,$st);
						set_csv($csv_v,null);
						set_csv($csv_n,null);
						set_csv($csv_s, $st);
					break;

			case 4 : // MatrixSA

				$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
				$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;

				//kim_end seq no=67

			case 5 : // MatrixMA
			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;
			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;

		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_t,null);
			set_csv($csv_v,null);
			set_csv($csv_n,null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else{
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	if ($mode != 1 || ($mode == 1 && $enquete_id != $enquete2_id))
	{
		// アンケート開始日時取得
		$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$start_date = "'" . format_date(db_fetch1($sql)) . "'";
		// データ出力
		//$sql = csv_answer_make_sql_test($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3);
		if (!$history)
			$sql = csv_answer_make_sql_test($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3);
		else
			$sql = csv_answer_make_sql_test2($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3);
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			if ($output_id)
				set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
				set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
				$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭number生成
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;
					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
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
}
// 回答CSV(0,1形式）
// 回答格納テスト管理画面のＣＳＶデータダウンロードのダウンロード関数
//function csv_answer2_test($enquete_id,$enquete2_id, $output_id = false, $output_kinmusaki = true, $mode) {
// 回答格納履歴の際はテストデータだけCSV出力する 2006/06/14 BTI
function csv_answer2_test($enquete_id, $enquete2_id, $output_id = false, $output_kinmusaki = true, $mode, $history)
{
	// 出力準備
	$check=false;
	$filename = ""; // ダウンロードファイルの名称を変更 2006/03/28 BTI
	if ($mode != 1)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
		//$answer_join = "en_enquete_id";
		$filename = "qrdr01_$enquete_id.csv";
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "an_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = $enquete_id;
		$filename = "qr01_$enquete_id.csv";
	}
	prepare_csv($filename);
	// ヘッダ・質問文・選択肢出力
	$header_id = $output_id ? ',ＩＤ' : '';
	$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h = "回答受信連番{$header_id},性別,年齢,未既婚,居住地域,職業,業種,職種{$header_kinmusaki},回答日時";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id) ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
		}
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
			case 7 : // プルダウン
				$sa = array ();
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n,null);
				set_csv($csv_s, join('／', $sa));
				set_csv($csv_t, join('／', $sa));
				set_csv($csv_v, null);
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					set_csv($csv_h, "$tmp-$fetch2->es_sel_no");
					set_csv($csv_q, $question_text);
					set_csv($csv_n, null);
					set_csv($csv_s, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_t, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_v, null);
					$question_text = '';
					$ma[$enquete_id_tmp][$question_no][] = $fetch2->es_sel_no;
				}
				break;

			case 4 : // MatrixSA

			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);

				}
				$check=true;
				break;
				//kim_end seq no=67
			case 5 : // MatrixMA

    			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
    					" FROM t_enq_hyoutou " .
    					" WHERE ht_enquete_id=$enquete_id_tmp " .
    					" AND ht_question_no=$question_no" .
    					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

			    $st = array();
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$st[ $fetch2->ht_hyoutou_no ] = $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
				}

			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";

				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)	{
					$fetch2 = pg_fetch_object($result2, $j);
                    foreach( $st as  $key => $value  ) {
					   $hs_num = $fetch2->hs_hyousoku_no;
					   set_csv($csv_h, $tmp."-".$hs_num."-".$key);
					   set_csv($csv_q, $question_text);
					   //kim_start seq no=67 表側内容挿入関数追加-
					   set_csv($csv_v, $hs_num .".". $fetch2->hs_hyousoku_text);
					   set_csv($csv_t, $value);
					   $ma[$enquete_id_tmp][$question_no][$hs_num][] = $key;
                    }
				}
				$check=true;
				break;

			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;
		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_n, null);
			set_csv($csv_v, null);
			set_csv($csv_t, null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else{
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67
	if ($mode != 1 || ($mode == 1 && $enquete_id != $enquete2_id))
	{
		// アンケート開始日時取得
		$sql_a = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$start_date = "'" . format_date(db_fetch1($sql_a)) . "'";
		// データ出力
		//$sql = csv_answer_make_sql_test($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3);
		if (!$history)
			$sql = csv_answer_make_sql_test($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3);
		else
			$sql = csv_answer_make_sql_test2($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3);
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			if ($output_id)
				set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
				set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
				$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭number生成
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67

                $answer = array();
                $fa = '';
                $fa_flag = $fetch->eq_fa_flag;
                while ($i < $nrow) {
                    $fetch = pg_fetch_object($result, $i);

                    if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
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
                    if (is_array($ma[$enquete_id][$question_no])) {
                        foreach ($ma[$enquete_id][$question_no] as $sel_no)
                            set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
                    }
                } elseif ($question_type == 5) {
                    if (is_array($ma[$enquete_id][$question_no][$hyousoku_no])) {
                        foreach ($ma[$enquete_id][$question_no][$hyousoku_no] as $sel_no)
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
}

	// Sql文作成
// 回答格納テスト管理画面のＣＳＶデータダウンロードのダウンロード関数
function csv_answer_make_sql_test($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3)
{
	$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex,"
		."DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,"
		. "sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,"
		. "an_date,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
		. " FROM t_enquete"
		. " JOIN t_answer ON an_enquete_id=$answer_join AND an_test_flg AND an_valid_flag"//kim_start seq no=67
		. " JOIN t_test_monitor ON mn_monitor_id=an_monitor_id"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
		. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
		. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
		. " WHERE $enquete_id_cond2"
		. " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
	return $sql;
}
// 回答格納履歴の際はテストデータだけCSV出力する 2006/06/14 BTI
function csv_answer_make_sql_test2($enquete_id, $start_date, $answer_join, $enquete_id_cond, $enquete_id_cond2, $enquete_id_cond3)
{
	$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex,"
		. "DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,"
		. "sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,"
		. "an_date,eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
		. " FROM t_enquete"
		. " JOIN t_answer ON an_enquete_id=$answer_join"
		. " AND an_test_flg"
		. " JOIN t_test_monitor ON mn_monitor_id=an_monitor_id"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
		. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
		. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
		. " WHERE $enquete_id_cond2"
		. " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
	return $sql;
}
//------------------------------------------------関数 追加---------------------------------
/* 2007/12/06 xxx 未使用と思われるので削除
function csv_answer_marketer($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr')
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;
	// 出力準備
	$filename = "{$file_prefix}_{$enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	//$header_id = $output_id ? ',ＩＤ' : '';
	//$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h =  "回答受信連番,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id && $file_prefix != 'dr') ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
		}
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
				$sql = " SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa_1[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_t, join('／', $sa_1));
				set_csv($csv_v, null);
				set_csv($csv_n, null);
				set_csv($csv_s, join('／', $sa_1));
				$sa_1=null;
				$sa_2=null;
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
						. " FROM t_enq_select"
						. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
						. " ORDER BY es_sel_no";
					$result2 = db_exec($sql);
					$nrow2 = pg_numrows($result2);
					$st = "";
					for ($j = 0; $j < $nrow2; $j++)
					{
						$fetch2 = pg_fetch_object($result2, $j);
						if ($st != "") $st .= "/";
						$st .= $fetch2->es_sel_no.".".$fetch2->es_sel_text;
					}
						set_csv($csv_h,$tmp);
						set_csv($csv_q, $question_text);
						set_csv($csv_t,$st);
						set_csv($csv_v,null);
						set_csv($csv_n,null);
						set_csv($csv_s, $st);
					break;

			case 4 : // MatrixSA

				$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
				$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;

				//kim_end seq no=67

			case 5 : // MatrixMA
			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);
				}
				$check=true;
				break;
			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;

		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_t,null);
			set_csv($csv_v,null);
			set_csv($csv_n,null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else if($fetch->eq_question_type==1||$fetch->eq_question_type==2||$fetch->eq_question_type==3||$fetch->eq_question_type==6||$fetch->eq_question_type==''){
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		// 利用ネットワーク
		$pro_enq_target = & new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex,"
					. "DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,"
					. " ss_order,gs_order,"
					. " ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,"
					. " sg_shokugyou_cd,"
					. " mn_mikikon,mn_jitaku_area AS jitaku_area_cd,sg_shokugyou_cd,"
					. " gs_gyoushu_cd,ss_shokushu_cd,ar_area_cd AS kinmu_area_cd,an_date,"
					. " eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. " AND an_valid_flag"
					. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
					. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
					. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
					. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
					. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. " FROM t_enq_question "
					. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2" . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
				$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age, "
				  . " ss_order,gs_order,"
			      . " ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,"
			      ."  mn_mikikon,mn_jitaku_area AS jitaku_area_cd, "
			      ."  sg_shokugyou_cd,gs_gyoushu_cd,ss_shokushu_cd, "
			      ."  ar_area_cd AS kinmu_area_cd,an_date,"
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag"
			      . " JOIN ${mon_flg}_monitor ON mn_monitor_id=an_monitor_id"
			      . " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
			      . " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			      . " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			      . " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			//if ($output_id)
				//set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			//if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
				set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
				//set_csv($csv, $fetch->mn_sex);
				//set_csv($csv, $fetch->mn_mikikon);
				//set_csv($csv, $fetch->jitaku_area_cd);
				//set_csv($csv, $fetch->sg_shokugyou_cd);
				//set_csv($csv, $fetch->gs_order);
				//set_csv($csv, $fetch->ss_order);
				//set_csv($csv, $fetch->kinmu_area_cd);
				$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;
					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
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
}
// 回答CSV(0,1形式）
function csv_answer2_marketer($enquete_id, $output_id = false, $output_kinmusaki = true, $finding_enquete_id = 0, $file_prefix = 'dr01')
{
	if ($finding_enquete_id)
	{
		$enquete2_id = $enquete_id;
		$enquete_id = $finding_enquete_id;
	}
	else
		$enquete2_id = 0;
	// 出力準備
	$filename = "{$file_prefix}_{$enquete_id}.csv";
	prepare_csv($filename);
	if ($enquete2_id)
	{
		$enquete_id_cond = "eq_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond2 = "el_enquete_id IN ($enquete_id,$enquete2_id)";
		$enquete_id_cond3 = "an_enquete_id IN ($enquete_id,$enquete2_id)";
		$answer_join = $enquete2_id;
	}
	else
	{
		$enquete_id_cond = "eq_enquete_id=$enquete_id";
		$enquete_id_cond2 = "el_enquete_id=$enquete_id";
		$enquete_id_cond3 = "an_enquete_id=$enquete_id";
		$answer_join = "en_enquete_id";
	}
	// ヘッダ・質問文・選択肢出力
	//$header_id = $output_id ? ',ＩＤ' : '';
	//$header_kinmusaki = $output_kinmusaki ? ',勤務先地域' : '';
	$csv_h =  "回答受信連番,性別,未既婚,年齢,居住地域,職業,業種,職種,勤務先地域,回答日時";
	$comma = str_repeat(',', count(explode(',', $csv_h)) - 1);
	$csv_q = '質問文→' . $comma;
	//kim_start seq no=67 表側 及び 表頭 題目変数 追加
	$csv_v = '選択肢（表側）→' . $comma;
	$csv_t = '選択肢（表頭）→' . $comma;
	$csv_n = null;
	//kim_end seq no=67
	$csv_s = '選択肢→' . $comma;
	$question_no = 0;
	$enquete_id_tmp = 0;
	$sql = "SELECT eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,hs_hyousoku_no,eq_fa_flag"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no"
		. " WHERE $enquete_id_cond"
		. " ORDER BY eq_enquete_id,eq_question_no,hs_hyousoku_no";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	$check=false;
	for ($i = 0; $i < $nrow; $i++)
	{
		$fetch = pg_fetch_object($result, $i);
		$tmp = (($fetch->eq_enquete_id == $enquete_id && $enquete_id != $enquete2_id && $file_prefix != 'dr01') ? 'FD' : '') . "設問$fetch->eq_question_no";
		if ($fetch->eq_enquete_id != $enquete_id_tmp || $fetch->eq_question_no != $question_no)
		{
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $fetch->eq_question_text)));
			$question_no = $fetch->eq_question_no;
			$enquete_id_tmp = $fetch->eq_enquete_id;
		}
		else {
	        continue;
			//$question_text = '';
		}
		//if ($fetch->hs_hyousoku_no)
		//	$tmp .= "-$fetch->hs_hyousoku_no";
		switch ($fetch->eq_question_type)
		{
			case 1 : // SA
				$sa = array ();
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa[] = $fetch2->es_sel_no.".".$fetch2->es_sel_text;
				}
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n,null);
				set_csv($csv_s, join('／', $sa));
				set_csv($csv_t, join('／', $sa));
				set_csv($csv_v, null);
				break;
			case 2 : // MA
				$sql = "SELECT es_sel_no,es_sel_text"
					. " FROM t_enq_select"
					. " WHERE es_enquete_id=$enquete_id_tmp AND es_question_no=$question_no"
					. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					set_csv($csv_h, "$tmp-$fetch2->es_sel_no");
					set_csv($csv_q, $question_text);
					set_csv($csv_n, null);
					set_csv($csv_s, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_t, "$fetch2->es_sel_no.".".$fetch2->es_sel_text");
					set_csv($csv_v, null);
					$question_text = '';
					$ma[$enquete_id_tmp][$question_no][] = $fetch2->es_sel_no;
				}
				break;

			case 4 : // MatrixSA

			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
					" FROM t_enq_hyoutou " .
					" WHERE ht_enquete_id=$enquete_id_tmp " .
					" AND ht_question_no=$question_no " .
					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
			    $st = "";
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					if ($st != "") $st .= "/";
					$st .= $fetch2->ht_hyoutou_no.".".$fetch2->ht_hyoutou_text;
					//kim_end seq no=67
				}
			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$sa = $fetch2->hs_hyousoku_no.".".$fetch2->hs_hyousoku_text;
					//kim_start seq no=67 表側の 内容 保管する配列追加

				 	set_csv($csv_v, $sa);
					set_csv($csv_t, $st);
					set_csv($csv_h, $tmp."-".$fetch2->hs_hyousoku_no);
					set_csv($csv_q, $question_text);

				}
				$check=true;
				break;
				//kim_end seq no=67
			case 5 : // MatrixMA

    			$sql="SELECT ht_hyoutou_no,ht_hyoutou_text " .
    					" FROM t_enq_hyoutou " .
    					" WHERE ht_enquete_id=$enquete_id_tmp " .
    					" AND ht_question_no=$question_no" .
    					" ORDER BY ht_hyoutou_no";
			    $result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);

			    $st = array();
				for ($j = 0; $j < $nrow2; $j++)
				{
					$fetch2 = pg_fetch_object($result2, $j);
					$st[ $fetch2->ht_hyoutou_no ] = $fetch2->ht_hyoutou_no .".".$fetch2->ht_hyoutou_text;
				}

			$sql="SELECT hs_hyousoku_no,hs_hyousoku_text" .
					" FROM t_enq_hyousoku " .
					" WHERE hs_enquete_id=$enquete_id_tmp " .
					" AND hs_question_no=$question_no" .
					" ORDER BY hs_hyousoku_no";

				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++)	{
					$fetch2 = pg_fetch_object($result2, $j);
                    foreach( $st as  $key => $value  ) {
					   $hs_num = $fetch2->hs_hyousoku_no;
					   set_csv($csv_h, $tmp."-".$hs_num."-".$key);
					   set_csv($csv_q, $question_text);
					   //kim_start seq no=67 表側内容挿入関数追加-
					   set_csv($csv_v, $hs_num .".".$fetch2->hs_hyousoku_text);
					   set_csv($csv_t, $value);
					   $ma[$enquete_id_tmp][$question_no][$hs_num][] = $key;
                    }
				}
				$check=true;
				break;

			default :
				set_csv($csv_h, $tmp);
				set_csv($csv_q, $question_text);
				set_csv($csv_n, null);
				set_csv($csv_t, null);
				set_csv($csv_v, null);
				set_csv($csv_s, '');
				break;
		}
		if ($fetch->eq_fa_flag == 't')
		{
			set_csv($csv_h, "$tmp-(FA)");
			set_csv($csv_q, '');
			set_csv($csv_n, null);
			set_csv($csv_v, null);
			set_csv($csv_t, null);
			set_csv($csv_s, '');
		}
	}
	output_csv($csv_h);
	output_csv($csv_q);
	//kim_start seq no=67 csv 表側タイトル追加 及び 出力区分
	if($check==1||$fetch->eq_question_type==4||$fetch->eq_question_type==5){
		output_csv($csv_v);
		output_csv($csv_t);
	}else if($fetch->eq_question_type==1||$fetch->eq_question_type==2||$fetch->eq_question_type==3||$fetch->eq_question_type==6||$fetch->eq_question_type==''){
		output_csv($csv_n);
		output_csv($csv_s);
	}
	//kim_end seq no=67

	// アンケート開始日時取得
	$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
	$start_date = "'" . format_date(db_fetch1($sql)) . "'";
	//kim_start seq no=67 $start_date 値がないし 実行ない
	if($start_date != "'設定なし'"){
		// 利用ネットワーク
		$pro_enq_target = & new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		// データ出力
		if ($mon_flg == 't')
		{$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex," .
					"DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,".
					" ss_order,gs_order,".
					" ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,".
					"mn_mikikon,mn_jitaku_area AS jitaku_area_cd,sg_shokugyou_cd," .
					"gs_gyoushu_cd,ss_shokushu_cd,ar_area_cd AS kinmu_area_cd,an_date," .
					"eq_question_no,hs_hyousoku_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
					. " FROM t_enquete"
					. " JOIN t_enquete_list ON el_enquete_id=en_enquete_id"
					. " JOIN t_answer ON an_enquete_id=$answer_join AND an_monitor_id=el_monitor_id "
					. " AND an_valid_flag"
					. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
					. " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
					. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
					. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
					. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
					. " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag "
					. " FROM t_enq_question "
					. " LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
					. " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
					. " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
					. " WHERE $enquete_id_cond2"
					. " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		else
		{
			$sql = "SELECT en_enquete_id,mn_monitor_id,mn_sex, "
			      ."  DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age, "
				  .	" ss_order,gs_order,"
			      . " ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,ar_area_name AS kinmu_area_name,"
			      ."  mn_mikikon,mn_jitaku_area AS jitaku_area_cd, "
			      ."  sg_shokugyou_cd,gs_gyoushu_cd,ss_shokushu_cd, "
			      ."  ar_area_cd AS kinmu_area_cd,an_date,"
			      ."  eq_question_no,hs_hyousoku_no,eq_question_type,"
			      ."  eq_fa_flag,as_sel_no,as_free_answer,ax_hyoutou_no"
			      . " FROM t_enquete"
			      . " JOIN t_answer ON an_enquete_id=$answer_join AND an_valid_flag"
			      . " JOIN ${mon_flg}_monitor ON mn_monitor_id=an_monitor_id"
			      . " LEFT JOIN m_area ON ar_area_cd=mn_jitaku_area"
			      . " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
			      . " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
			      . " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
			      . " JOIN (SELECT eq_enquete_id,eq_question_no,eq_question_type,hs_hyousoku_no,eq_fa_flag"
			      . " FROM t_enq_question LEFT JOIN t_enq_hyousoku ON hs_enquete_id=eq_enquete_id AND hs_question_no=eq_question_no WHERE $enquete_id_cond) t1 ON eq_enquete_id=en_enquete_id"
			      . " LEFT JOIN t_ans_select ON as_enquete_id=en_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=eq_question_no"
			      . " LEFT JOIN t_ans_matrix ON ax_enquete_id=en_enquete_id AND ax_monitor_id=an_monitor_id AND ax_question_no=eq_question_no AND ax_hyousoku_no=hs_hyousoku_no"
			      . " WHERE $enquete_id_cond3"
			      . " ORDER BY an_date,mn_monitor_id,eq_enquete_id,eq_question_no,as_sel_no,hs_hyousoku_no,ax_hyoutou_no";
		}
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		$no = 0;
		for ($i = 0; $i < $nrow;)
		{
			$fetch = pg_fetch_object($result, $i);
			set_csv($csv, ++ $no);
			//if ($output_id)
				//set_csv($csv, $fetch->mn_monitor_id);
				set_csv($csv, decode_sex($fetch->mn_sex));
				set_csv($csv, decode_mikikon($fetch->mn_mikikon));
				set_csv($csv, $fetch->mn_age);
				set_csv($csv, $fetch->jitaku_area_name);
				set_csv($csv, $fetch->sg_shokugyou_name);
				set_csv($csv, $fetch->gs_gyoushu_name);
				set_csv($csv, $fetch->ss_shokushu_name);
			//if ($output_kinmusaki)
				set_csv($csv, $fetch->kinmu_area_name);
				set_csv($csv, format_datetime($fetch->an_date, '（未回答）'));
				//set_csv($csv, $fetch->mn_sex);
				//set_csv($csv, $fetch->mn_mikikon);
				//set_csv($csv, $fetch->jitaku_area_cd);
				//set_csv($csv, $fetch->sg_shokugyou_cd);
				//set_csv($csv, $fetch->gs_order);
				//set_csv($csv, $fetch->ss_order);
				//set_csv($csv, $fetch->kinmu_area_cd);
			$monitor_id = $fetch->mn_monitor_id;
			while ($i < $nrow)
			{
				if ($monitor_id != $fetch->mn_monitor_id)
					break;
				$enquete_id = $fetch->en_enquete_id;
				$question_no = $fetch->eq_question_no;
				$question_type = $fetch->eq_question_type;
				$hyousoku_no = $fetch->hs_hyousoku_no;
				//kim_start seq no=67 表頭追加
				$hyoutou_no = $fetch->ht_hyoutou_no;
				//kim_end seq no=67
				$answer = array ();
				$fa = '';
				$fa_flag = $fetch->eq_fa_flag;
				while ($i < $nrow)
				{
					$fetch = pg_fetch_object($result, $i);
					if ($monitor_id != $fetch->mn_monitor_id || $enquete_id != $fetch->en_enquete_id || $question_no != $fetch->eq_question_no || $hyousoku_no != $fetch->hs_hyousoku_no)
						break;
					if ($fetch->as_sel_no)
						$answer[] = $fetch->as_sel_no;
					elseif ($fetch->ax_hyoutou_no) $answer[] = $fetch->ax_hyoutou_no;

					if ($fetch->as_free_answer != '')
					{
						if ($fa_flag == 't')
						{
							if ($fa != '')
								$fa .= ',';
							$fa .= $fetch->as_free_answer;
						}
						else
						{
							$answer[] = $fetch->as_free_answer;
						}
					}
					$i++;
				}
				if ($question_type == 2)
				{
					if (is_array($ma[$enquete_id][$question_no]))
					{
						foreach ($ma[$enquete_id][$question_no] as $sel_no)
							set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');
					}
				}
				elseif ($question_type == 5)
				{
					if (is_array($ma[$enquete_id][$question_no][$hyousoku_no]))
					{
						foreach ($ma[$enquete_id][$question_no][$hyousoku_no] as $sel_no)
						set_csv($csv, in_array($sel_no, $answer) ? '1' : '0');

					}
				}
				else
					set_csv($csv, join(',', $answer));
				if ($fa_flag == 't')
					set_csv($csv, $fa);
			}
			output_csv($csv);
		}
	}
}
*/

function get_question_type2($type) {
    switch ($type) {
    case 1:
        return 'SA';
    case 2:
        return 'MA';
    case 3:
        return 'FA';
    case 4:
        return 'SA';
    case 5:
        return 'MA';
    case 6:
        return 'NA';
    case 7:
        return 'SA';
    case 8:
        return 'FA';
    }
}

function output_question_csv($enquete_id, $fd_flag = false) {
	$blank = '';
    $enquete = new enquete_class();
    $enquete->read_db($enquete_id);

	$qt = $fd_flag ? 'FDQ' : 'Q';

	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			$question_text = trim(str_replace("\n", '', str_replace("\r", '', $question->question_text)));

			switch ($question->question_type) {
			case 1:
			case 2:
			case 7:
				set_csv($csv, "■$qt$qno.$question_text");
				set_csv($csv, get_question_type2($question->question_type));
				output_csv($csv);
				if (is_array($question->sel_text)) {
					foreach ($question->sel_text as $a_num=>$sel_text) {
						set_csv($csv, $sel_text);
						output_csv($csv);
					}
				}
				if ($question->fa_flag == DBTRUE) {
					$csv = "$qt$qno.{$question->sel_text[$question->fa_sno]},FA";
					output_csv($blank);
					output_csv($csv);
				}
				output_csv($blank);
				break;
			case 3:
			case 6:
			case 8:
				set_csv($csv, "■$qt$qno.$question_text");
				set_csv($csv, get_question_type2($question->question_type));
				output_csv($csv);
				output_csv($blank);
				break;
			case 4:
			case 5:
				if (is_array($question->hyousoku)) {
					foreach ($question->hyousoku as $sno => $hyousoku) {
						if (is_array($question->hyoutou)) {
							set_csv($csv, "■$qt$qno-$sno.{$question_text}（{$hyousoku}）");
							set_csv($csv, get_question_type2($question->question_type));
							output_csv($csv);
							foreach ($question->hyoutou as $a_num=>$hyoutou) {
								output_csv($hyoutou);
							}
							output_csv($blank);

						}
					}
				}
				break;
			}
		}
	}
}

function output_attribute_csv() {
	$blank = '';

	$csv = '"ID",FA';
	output_csv($csv);
	output_csv($blank);

	$csv = '性別,SA';
	output_csv($csv);
	$csv = '男性';
	output_csv($csv);
	$csv = '女性';
	output_csv($csv);
	output_csv($blank);

	$csv = '年齢,NA';
	output_csv($csv);
	output_csv($blank);

	$csv = '未既婚,SA';
	output_csv($csv);
	$csv = '未婚';
	output_csv($csv);
	$csv = '既婚';
	output_csv($csv);
	$csv = 'その他';
	output_csv($csv);
	output_csv($blank);

	$csv = '居住地域,SA';
	output_csv($csv);
	$sql = "select ar_area_name from m_area order by ar_area_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch=pg_fetch_object($result, $i);
		output_csv($fetch->ar_area_name);
	}
	output_csv($blank);

	$csv = '職業,SA';
	output_csv($csv);
	$sql = "select sg_shokugyou_name from m_shokugyou order by sg_shokugyou_cd";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i=0;$i<$nrow;$i++){
		$fetch=pg_fetch_object($result, $i);
		output_csv($fetch->sg_shokugyou_name);
	}
	output_csv($blank);
}
?>