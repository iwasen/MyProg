<?
/******************************************************
' System :きかせて・net共通
' Content:アンケート情報クラス
'******************************************************/

define('ENQ_TYPE_MAIL', 1);
define('ENQ_TYPE_WEB', 2);

class question_class {
	var $question_no;
	var $question_text;
	var $question_type;
	var $question_type_org;
	var $fa_flag;
	var $url;
	var $image_id;
	var $dup_flag;
	var $must_flag;
	var $fa_sno;
	var $ex_sno;
	var $sel_text;
	var $sel_text_s;
	var $hyousoku;
	var $hyoutou;
	var $delete_flag;
	var $page_break;
	var $pre_text;
	var $post_text;
}

class enquete_class {
	var $enquete_id;
	var $status;
	var $sub_status;
	var $enq_kind;
	var $enq_type;
	var $title;
	var $description;
	var $start_date;
	var $start_date_y;
	var $start_date_m;
	var $start_date_d;
	var $start_date_h;
	var $end_date;
	var $end_date_y;
	var $end_date_m;
	var $end_date_d;
	var $end_date_h;
	var $point;
	var $job_id;

	var $question;
	var $max_question_no;

	var $branch_cond;

	// 初期化処理
	function enquete_class() {
		$this->status = 0;
		$this->sub_status = 0;
		$this->branch_cond_num = 1;
	}

	// DBから読み込み
	function read_db($enquete_id) {
		// アンケート登録情報取得
		$sql = "SELECT * FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$result = db_exec($sql);
		if (pg_numrows($result) == 0)
			return false;

		$fetch = pg_fetch_object($result, 0);
		$this->status = $fetch->en_status;
		$this->sub_status = $fetch->en_sub_status;
		$this->enq_kind = $fetch->en_enq_kind;
		$this->enq_type = $fetch->en_enq_type;
		$this->title = $fetch->en_title;
		$this->description = $fetch->en_description;
		$this->start_date = $fetch->en_start_date;
		$this->start_date_y = get_datepart('Y', $this->start_date);
		$this->start_date_m = get_datepart('M', $this->start_date);
		$this->start_date_d = get_datepart('D', $this->start_date);
		$this->start_date_h = get_datepart('h', $this->start_date);
		$this->end_date = $fetch->en_end_date;
		$this->end_date_y = get_datepart('Y', $this->end_date);
		$this->end_date_m = get_datepart('M', $this->end_date);
		$this->end_date_d = get_datepart('D', $this->end_date);
		$this->end_date_h = get_datepart('h', $this->end_date);
		$this->point = $fetch->en_point;
		$this->job_id = $fetch->en_job_id;

		// 質問内容取得
		$this->max_question_no = 0;
		$sql = "SELECT eq_question_no,eq_question_text,eq_question_type,eq_fa_flag,eq_url,eq_image_id,eq_dup_flag,eq_must_flag,eq_page_break,eq_pre_text,eq_post_text"
				. " FROM t_enq_question"
				. " WHERE eq_enquete_id=$enquete_id"
				. " ORDER BY eq_question_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$question_no = $fetch->eq_question_no;
			if ($question_no >= 1) {
				$this->question[$question_no] = new question_class;
				$question = &$this->question[$question_no];
				$question->question_no = $fetch->eq_question_no;
				$question->question_text = $fetch->eq_question_text;
				$question->question_type = $fetch->eq_question_type;
				$question->question_type_org = $fetch->eq_question_type;
				$question->fa_flag = $fetch->eq_fa_flag;
				$question->url = $fetch->eq_url;
				$question->image_id = $fetch->eq_image_id;
				$question->dup_flag = $fetch->eq_dup_flag;
				$question->must_flag = $fetch->eq_must_flag;
				$question->page_break = $fetch->eq_page_break;
				$question->pre_text = $fetch->eq_pre_text;
				$question->post_text = $fetch->eq_post_text;

				$question->fa_sno = 0;
				$question->ex_sno = 0;

				// 選択項目取得
				$sql = "SELECT es_sel_no,es_sel_text,es_sel_text_s,es_ex_flag"
						. " FROM t_enq_select"
						.	" WHERE es_enquete_id=$enquete_id AND es_question_no=$question_no"
						. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					$sel_no = $fetch2->es_sel_no;
					if ($sel_no >= 1) {
						$question->sel_text[$sel_no] = $fetch2->es_sel_text;
						$question->sel_text_s[$sel_no] = $fetch2->es_sel_text_s;

						if ($fetch2->es_ex_flag == DBTRUE)
							$question->ex_sno = $sel_no;
						elseif ($question->fa_flag == DBTRUE)
							$question->fa_sno = $sel_no;
					}
				}

				// 表側項目取得
				$question->hyousoku = array();
				$sql = "SELECT hs_hyousoku_no,hs_hyousoku_text"
						. " FROM t_enq_hyousoku"
						.	" WHERE hs_enquete_id=$enquete_id AND hs_question_no=$question_no"
						. " ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					$sel_no = $fetch2->hs_hyousoku_no;
					$question->hyousoku[$sel_no] = $fetch2->hs_hyousoku_text;
				}

				// 表頭項目取得
				$question->hyoutou = array();
				$sql = "SELECT ht_hyoutou_no,ht_hyoutou_text"
						. " FROM t_enq_hyoutou"
						.	" WHERE ht_enquete_id=$enquete_id AND ht_question_no=$question_no"
						. " ORDER BY ht_hyoutou_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_numrows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					$sel_no = $fetch2->ht_hyoutou_no;
					$question->hyoutou[$sel_no] = $fetch2->ht_hyoutou_text;
				}

				$this->max_question_no = $question_no;
			}
		}

		// アンケート分岐条件取得
		$sql = "SELECT ebc_question_no,ebc_and_or,ebq_cond_no,ebq_cond_question_no,ebq_select_no,ebq_and_or,ebq_not_cond"
				. " FROM t_enq_branch_cond"
				. " JOIN t_enq_branch_question ON ebq_enquete_id=ebc_enquete_id AND ebq_question_no=ebc_question_no"
				. " WHERE ebc_enquete_id=$enquete_id"
				. " ORDER BY ebc_question_no,ebq_cond_no";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$branch_cond = &$this->branch_cond[$fetch->ebc_question_no];

			$branch_cond->and_or = $fetch->ebc_and_or;

			$cond = &$branch_cond->cond[$fetch->ebq_cond_no];
			$cond->cond_question_no = $fetch->ebq_cond_question_no;
			$cond->select_no = $fetch->ebq_select_no;
			$cond->and_or = $fetch->ebq_and_or;
			$cond->not_cond = $fetch->ebq_not_cond;
		}

		$this->enquete_id = $enquete_id;

		return true;
	}

	// DBに書き込み
	function write_db($enquete_id = '') {
		db_begin_trans();

		// アンケート登録情報書き込み
		if ($enquete_id == '') {
			$sql = "INSERT INTO t_enquete (en_status,en_sub_status,en_enq_kind,en_enq_type,en_title,en_description,en_start_date,en_end_date,en_point,en_job_id) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_enquete SET en_status=%s,en_sub_status=%s,en_enq_kind=%s,en_enq_type=%s,en_title=%s,en_description=%s,en_start_date=%s,en_end_date=%s,en_point=%s,en_job_id=%s WHERE en_enquete_id=$enquete_id";
		}
		$sql = sprintf($sql,
				sql_number($this->status),
				sql_number($this->sub_status),
				sql_number($this->enq_kind),
				sql_number($this->enq_type),
				sql_char($this->title),
				sql_char($this->description),
				sql_date2($this->start_date_y, $this->start_date_m, $this->start_date_d, $this->start_date_h),
				sql_date2($this->end_date_y, $this->end_date_m, $this->end_date_d, $this->end_date_h),
				sql_number($this->point),
				sql_number($this->job_id));
		db_exec($sql);

		// アンケートID取得
		if ($enquete_id == '')
			$enquete_id = get_currval('t_enquete_en_enquete_id_seq');

		// 既存の質問項目削除
		$sql = "DELETE FROM t_enq_question WHERE eq_enquete_id=$enquete_id";
		db_exec($sql);
		$sql = "DELETE FROM t_enq_select WHERE es_enquete_id=$enquete_id";
		db_exec($sql);
		$sql = "DELETE FROM t_enq_hyousoku WHERE hs_enquete_id=$enquete_id";
		db_exec($sql);
		$sql = "DELETE FROM t_enq_hyoutou WHERE ht_enquete_id=$enquete_id";
		db_exec($sql);

		// アンケート質問項目書き込み
		if (is_array($this->question)) {
			$qno = 1;
			foreach (array_keys($this->question) as $i) {
				$question = &$this->question[$i];

				if ($question->delete_flag)
					continue;

				// 選択肢書き込み
				$sel_exist = false;
				if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
					if (is_array($question->sel_text)) {
						foreach (array_keys($question->sel_text) as $j) {
							if ($question->sel_text[$j] != '') {
								$sql = sprintf("INSERT INTO t_enq_select (es_enquete_id,es_question_no,es_sel_no,es_sel_text,es_sel_text_s,es_ex_flag) VALUES (%s,%s,%s,%s,%s,%s)",
										sql_number($enquete_id),
										sql_number($qno),
										sql_number($j),
										sql_char($question->sel_text[$j]),
										sql_char($question->sel_text_s[$j]),
										sql_bool($question->ex_sno == $j ? DBTRUE : ''));
								db_exec($sql);

								$sel_exist = true;
							}
						}
					}
				}

				// 表側表頭選択肢書き込み
				if ($question->question_type == 4 || $question->question_type == 5) {
					if (is_array($question->hyousoku)) {
						foreach (array_keys($question->hyousoku) as $j) {
							if ($question->hyousoku[$j] != '') {
								$sql = sprintf("INSERT INTO t_enq_hyousoku (hs_enquete_id,hs_question_no,hs_hyousoku_no,hs_hyousoku_text) VALUES (%s,%s,%s,%s)",
										sql_number($enquete_id),
										sql_number($qno),
										sql_number($j),
										sql_char($question->hyousoku[$j]));
								db_exec($sql);

								$sel_exist = true;
							}
						}
					}

					if (is_array($question->hyoutou)) {
						foreach (array_keys($question->hyoutou) as $j) {
							if ($question->hyoutou[$j] != '') {
								$sql = sprintf("INSERT INTO t_enq_hyoutou (ht_enquete_id,ht_question_no,ht_hyoutou_no,ht_hyoutou_text) VALUES (%s,%s,%s,%s)",
										sql_number($enquete_id),
										sql_number($qno),
										sql_number($j),
										sql_char($question->hyoutou[$j]));
								db_exec($sql);

								$sel_exist = true;
							}
						}
					}
				}

				// 質問内容書き込み
				if ($sel_exist || $question->question_text != '') {
					$sql = sprintf("INSERT INTO t_enq_question (eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,eq_fa_flag,eq_url,eq_image_id,eq_dup_flag,eq_must_flag,eq_page_break,eq_pre_text,eq_post_text) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							sql_number($enquete_id),
							sql_number($qno++),
							sql_char($question->question_text),
							sql_number($question->question_type),
							sql_bool($question->fa_flag, "'f'"),
							sql_char($question->url),
							sql_number($question->image_id),
							sql_bool($question->dup_flag),
							sql_bool($question->must_flag),
							sql_bool($question->page_break),
							sql_char($question->pre_text),
							sql_char($question->post_text));
					db_exec($sql);
				}
			}
		}

		db_commit_trans();

		$this->enquete_id = $enquete_id;

		return $enquete_id;
	}

	// 質問数取得
	function get_question_num() {
		$num = 0;

		if (is_array($this->question)) {
			foreach (array_keys($this->question) as $i) {
				$question = &$this->question[$i];
				if ($question->question_text != '' && !$question->delete_flag)
					$num++;
			}
		}

		return $num;
	}

	// アンケートポイント取得
	function get_enquete_point() {
		$point = 0;
		if (is_array($this->question)) {
			foreach ($this->question as $question) {
				if ($question->question_text != '' && !$question->delete_flag) {
					switch ($question->question_type) {
					case 1:		// SA
					case 2:		// MA
					case 7:		// プルダウン
						$point += 2;
						break;
					case 3:		// FA
						$point += 2;
						break;
					case 4:		// Matrix SA
					case 5:		// Matrix MA
						$point += 2;
						break;
					case 6:		// NA
					case 8:		// １行テキスト
						$point += 2;
						break;
					}
				}
			}
		}

		return $point;
	}

	// ファインディングアンケートポイント取得
	function get_finding_point() {
		if ($this->get_question_num() <= 3)
			$point = 1;
		else
			$point = 2;

		return $point;
	}

	// 分岐条件書き込み
	function write_branch_cond($qno = 0) {
		$enquete_id = $this->enquete_id;

		db_begin_trans();

		if ($qno) {
			db_delete('t_enq_branch_cond', "ebc_enquete_id=$enquete_id AND ebc_question_no=$qno");
			db_delete('t_enq_branch_question', "ebq_enquete_id=$enquete_id AND ebq_question_no=$qno");

			$this->write_branch_cond2($qno);
		} else {
			db_delete('t_enq_branch_cond', "ebc_enquete_id=$enquete_id");
			db_delete('t_enq_branch_question', "ebq_enquete_id=$enquete_id");

			if (is_array($this->branch_cond)) {
				foreach (array_keys($this->question) as $qno)
					$this->write_branch_cond2($qno);
			}
		}

		db_commit_trans();
	}

	function write_branch_cond2($qno) {
		$enquete_id = $this->enquete_id;
		$branch_cond = &$this->branch_cond[$qno];

		if (isset($branch_cond)) {
			if (is_array($branch_cond->cond)) {
				$exist = false;

				$cno = 1;
				foreach ($branch_cond->cond as $cond) {
					if ($cond->cond_question_no && $cond->select_no) {
						$rec['ebq_enquete_id'] = sql_number($enquete_id);
						$rec['ebq_question_no'] = sql_number($qno);
						$rec['ebq_cond_no'] = sql_number($cno++);
						$rec['ebq_cond_question_no'] = sql_number($cond->cond_question_no);
						$rec['ebq_select_no'] = sql_char($cond->select_no);
						$rec['ebq_and_or'] = sql_char($cond->and_or);
						$rec['ebq_not_cond'] = sql_char($cond->not_cond);
						db_insert('t_enq_branch_question', $rec);
						$exist = true;
					}
				}

				if ($exist) {
					$rec['ebc_enquete_id'] = sql_number($enquete_id);
					$rec['ebc_question_no'] = sql_number($qno);
					$rec['ebc_and_or'] = sql_char($branch_cond->and_or);
					db_insert('t_enq_branch_cond', $rec);
				}
			}
		}
	}

	// 分岐条件文生成
	function branch_cond_text($qno, $post_text, $question_text_flag = false, $sel_text_flag = true) {
		$branch_cond = &$this->branch_cond[$qno];

		$t1 = '';
		if (is_array($branch_cond->cond)) {
			foreach ($branch_cond->cond as $cno => $cond) {
				$t2 = '';
				if ($cond->cond_question_no && $cond->select_no) {
					$question = &$this->question[$cond->cond_question_no];

					foreach (explode(',', $cond->select_no) as $sno) {
						if ($t2 != '') {
							switch ($cond->and_or) {
							case 'A':
							case 'F':
								$t2 .= 'と';
								break;
							case 'O':
								$t2 .= 'または';
								break;
							}
						}

						switch ($question->question_type) {
						case 1:		// SA
						case 2:		// MA
						case 7:		// プルダウン
							if ($sel_text_flag)
								$t2 .= '「' . $question->sel_text[$sno] . '」';
							else
								$t2 .= '「' . mb_convert_kana($sno, 'N') . '」';
							break;
						case 4:		// Matrix SA
						case 5:		// Matrix MA
							list($sno, $tno) = explode(':', $sno);
							if ($sel_text_flag)
								$t2 .= '「' . $question->hyousoku[$sno] . 'で' . $question->hyoutou[$tno] . '」';
							else
								$t2 .= '「' . mb_convert_kana($sno, 'N') . '：' . mb_convert_kana($tno, 'N') . '」';
							break;
						}
					}

					if ($t2 != '') {
						if ($question_text_flag)
							$t3 = "「Ｑ" . mb_convert_kana($cond->cond_question_no, 'N') . '．' . str_trim($question->question_text, 20) . '」';
						else
							$t3 = "Ｑ" . mb_convert_kana($cond->cond_question_no, 'N');
						$t2 = "{$t3}で{$t2}";
					}
				}

				if ($cond->and_or == 'F')
					$t2 .= 'のみ';

				if ($cond->not_cond == DBTRUE)
					$t2 .= '以外';

				if ($t2 != '') {
					if ($t1 != '') {
						if ($branch_cond->and_or == 'A')
							$t1 .= '、かつ';
						else
							$t1 .= '、または';
					}

					$t1 .= $t2;
				}
			}

			if ($t1 != '')
				$t1 .= $post_text;
		}

		return $t1;
	}

	// 画像数取得
	function get_image_num() {
		$image_num = 0;

		if (is_array($this->question)) {
			foreach ($this->question as $question) {
				if ($question->image_id)
					$image_num++;
			}
		}

		return $image_num;
	}

	// 画像複製
	function duplicate_image() {
		if (is_array($this->question)) {
			foreach (array_keys($this->question) as $qno) {
				$image_id = $this->question[$qno]->image_id;
				if ($image_id) {
					$sql = "SELECT im_size,im_name,im_type,im_data FROM t_image WHERE im_image_id=$image_id";
					$result = db_exec($sql);
					if (pg_numrows($result)) {
						$fetch = pg_fetch_object($result, 0);

						$rec['im_size'] = sql_number($fetch->im_size);
						$rec['im_name'] = sql_char($fetch->im_name);
						$rec['im_type'] = sql_char($fetch->im_type);
						$rec['im_data'] = sql_char($fetch->im_data);
						db_insert('t_image', $rec);
						$this->question[$qno]->image_id = get_currval('t_image_im_image_id_seq');
					} else
						$this->question[$qno]->image_id = 0;
				}
			}
		}
	}
}

// アンケート削除
function delete_enquete($enquete_id) {
	db_begin_trans();

	$sql = "DELETE FROM t_enquete WHERE en_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_question WHERE eq_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_select WHERE es_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_hyousoku WHERE hs_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_hyoutou WHERE ht_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_branch_cond WHERE ebc_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_branch_question WHERE ebq_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_cleaning WHERE ecl_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_cleaning_cond WHERE ecc_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "DELETE FROM t_enq_cleaning_question WHERE ecq_enquete_id=$enquete_id";
	db_exec($sql);

	db_commit_trans();
}
?>