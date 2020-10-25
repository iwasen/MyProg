<?
/******************************************************
' System :きかせて・net共通
' Content:アンケートクリーニング処理クラス
'******************************************************/

class enq_cleaning_class {
	var $enquete_id;
	var $valid_flag;
	var $cleaning_cond;
	var $exist_flag;

	// 初期化処理
	function enq_cleaning_class() {
		$this->valid_flag = DBFALSE;
		$this->cleaning_cond = array();
		$this->exist_flag = false;
	}

	// DBから読み込み
	function read_db($enquete_id) {
		if ($enquete_id) {
			$this->enquete_id = $enquete_id;

			$sql = "SELECT ecl_valid_flag FROM t_enq_cleaning WHERE ecl_enquete_id=$enquete_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);

				$this->valid_flag = $fetch->ecl_valid_flag;

				// アンケートクリーニング条件取得
				$sql = "SELECT ecc_cond_no,ecc_and_or,ecq_seq_no,ecq_ans_enquete_id,ecq_question_no,ecq_hyousoku_no,ecq_select_no,ecq_and_or,ecq_not_cond"
						. " FROM t_enq_cleaning_cond"
						. " JOIN t_enq_cleaning_question ON ecq_enquete_id=ecc_enquete_id AND ecq_cond_no=ecc_cond_no"
						. " WHERE ecc_enquete_id=$enquete_id"
						. " ORDER BY ecc_cond_no,ecq_question_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cleaning_cond = &$this->cleaning_cond[$fetch->ecc_cond_no];

					$cleaning_cond->and_or = $fetch->ecc_and_or;

					$ans_cond = &$cleaning_cond->ans_cond[$fetch->ecq_seq_no];
					$ans_cond->ans_enquete_id = $fetch->ecq_ans_enquete_id;
					$ans_cond->question_no = $fetch->ecq_question_no;
					$ans_cond->hyousoku_no = $fetch->ecq_hyousoku_no;
					$ans_cond->select_no = $fetch->ecq_select_no;
					$ans_cond->and_or = $fetch->ecq_and_or;
					$ans_cond->not_cond = $fetch->ecq_not_cond;

					$this->exist_flag = true;
				}
			}
		}
	}

	// クリーニング条件書き込み
	function write_db($enquete_id = 0) {
		if ($enquete_id)
			$this->enquete_id = $enquete_id;

		$enquete_id = $this->enquete_id;

		db_begin_trans();

		db_delete('t_enq_cleaning', "ecl_enquete_id=$enquete_id");
		db_delete('t_enq_cleaning_cond', "ecc_enquete_id=$enquete_id");
		db_delete('t_enq_cleaning_question', "ecq_enquete_id=$enquete_id");

		if (is_array($this->cleaning_cond)) {
			$exist1 = false;
			$cond_no = 1;
			foreach ($this->cleaning_cond as $cleaning_cond) {
				$exist2 = false;
				if (is_array($cleaning_cond->ans_cond)) {
					foreach ($cleaning_cond->ans_cond as $seq_no => $ans_cond) {
						if ($ans_cond->question_no && $ans_cond->select_no) {
							$rec['ecq_enquete_id'] = sql_number($enquete_id);
							$rec['ecq_cond_no'] = sql_number($cond_no);
							$rec['ecq_seq_no'] = sql_number($seq_no);
							$rec['ecq_ans_enquete_id'] = sql_number($ans_cond->ans_enquete_id);
							$rec['ecq_question_no'] = sql_number($ans_cond->question_no);
							$rec['ecq_hyousoku_no'] = sql_char($ans_cond->hyousoku_no);
							$rec['ecq_select_no'] = sql_char($ans_cond->select_no);
							$rec['ecq_and_or'] = sql_char($ans_cond->and_or);
							$rec['ecq_not_cond'] = sql_char($ans_cond->not_cond);
							db_insert('t_enq_cleaning_question', $rec);
							$exist2 = true;
						}
					}
				}

				if ($exist2) {
					$rec['ecc_enquete_id'] = sql_number($enquete_id);
					$rec['ecc_cond_no'] = sql_number($cond_no++);
					$rec['ecc_and_or'] = sql_char($cleaning_cond->and_or);
					db_insert('t_enq_cleaning_cond', $rec);
					$exist1 = true;
				}
			}

			if ($exist1) {
				$rec['ecl_enquete_id'] = sql_number($enquete_id);
				$rec['ecl_valid_flag'] = sql_bool($this->valid_flag);
				db_insert('t_enq_cleaning', $rec);
			}
		}

		db_commit_trans();
	}

	// 有効判定
	function check_answer($monitor_id) {
		if (!$this->exist_flag)
			return true;

		$cond_expr = '';

		foreach ($this->cleaning_cond as $cleaning_cond) {
			$cond_ok = true;

			foreach ($cleaning_cond->ans_cond as $ans_cond) {
				$select_no_ary = explode(',', $ans_cond->select_no);

				$match_count = 0;
				$unmatch_count = 0;
				if ($ans_cond->hyousoku_no == 0) {
					$sql = "SELECT as_sel_no AS select_no"
							. " FROM t_ans_select"
							. " WHERE as_enquete_id=$ans_cond->ans_enquete_id AND as_monitor_id=$monitor_id AND as_question_no=$ans_cond->question_no";
				} else {
					$sql = "SELECT ax_hyoutou_no AS select_no"
							. " FROM t_ans_matrix"
							. " WHERE ax_enquete_id=$ans_cond->ans_enquete_id AND ax_monitor_id=$monitor_id AND ax_question_no=$ans_cond->question_no AND ax_hyousoku_no=$ans_cond->hyousoku_no";
				}
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);

					if (in_array($fetch->select_no, $select_no_ary))
						$match_count++;
					else
						$unmatch_count++;
				}

				$select_count = count($select_no_ary);

				switch ($ans_cond->and_or) {
				case 'A':
					$ans_ok = ($match_count == $select_count);
					break;
				case 'O':
					$ans_ok = ($match_count >= 1);
					break;
				case 'F':
					$ans_ok = ($match_count == $select_count && $unmatch_count == 0);
					break;
				}

				if ($ans_cond->not_cond == DBTRUE)
					$ans_ok = !$ans_ok;

				if (!$ans_ok) {
					$cond_ok = false;
					break;
				}
			}

			if ($cond_expr != '')
				$cond_expr .= $cleaning_cond->and_or == 'A' ? ' && ' : ' || ';
			$cond_expr .= $cond_ok ? 'true' : 'false';
		}

		$cleaning_ok = eval("return $cond_expr;");

		if ($this->valid_flag == DBFALSE)
			$cleaning_ok = !$cleaning_ok;

		return $cleaning_ok;
	}
}
?>