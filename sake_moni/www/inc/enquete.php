<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
' Content:アンケート情報クラス
'******************************************************/

define('ENQ_TYPE_MAIL', 1);
define('ENQ_TYPE_WEB', 2);

class question_class {
	var $question_text;
	var $question_type;
	var $fa_flag;
	var $url;
	var $image_id;
	var $dup_flag;
	var $sel_text;
	var $sel_text_s;
	var $hyousoku;
	var $hyoutou;
}

class enquete_class {
	var $status;
	var $enq_kind;
	var $enq_type;
	var $title;
	var $description;
	var $start_date_y;
	var $start_date_m;
	var $start_date_d;
	var $start_date_h;
	var $end_date_y;
	var $end_date_m;
	var $end_date_d;
	var $end_date_h;
	var $point;
	var $question;
	var $max_question_no;

	// 初期化処理
	function enquete_class() {
		$this->status = 0;
	}

	// DBから読み込み
	function read_db($enquete_id) {
		// アンケート登録情報取得
		$sql = "SELECT * FROM t_enquete WHERE en_enquete_id=$enquete_id";
		$result = db_exec($sql);
		if (pg_num_rows($result) == 0)
			return false;

		$fetch = pg_fetch_object($result, 0);
		$this->status = $fetch->en_status;
		$this->enq_kind = $fetch->en_enq_kind;
		$this->enq_type = $fetch->en_enq_type;
		$this->title = $fetch->en_title;
		$this->description = $fetch->en_description;
		$this->start_date_y = get_datepart('Y', $fetch->en_start_date);
		$this->start_date_m = get_datepart('M', $fetch->en_start_date);
		$this->start_date_d = get_datepart('D', $fetch->en_start_date);
		$this->start_date_h = get_datepart('h', $fetch->en_start_date);
		$this->end_date_y = get_datepart('Y', $fetch->en_end_date);
		$this->end_date_m = get_datepart('M', $fetch->en_end_date);
		$this->end_date_d = get_datepart('D', $fetch->en_end_date);
		$this->end_date_h = get_datepart('h', $fetch->en_end_date);
		$this->point = $fetch->en_point;

		// 質問内容取得
		$this->max_question_no = 0;
		$sql = "SELECT eq_question_no,eq_question_text,eq_question_type,eq_fa_flag,eq_url,eq_image_id,eq_dup_flag"
				. " FROM t_enq_question"
				. " WHERE eq_enquete_id=$enquete_id"
				. " ORDER BY eq_question_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$question_no = $fetch->eq_question_no;
			if ($question_no >= 1) {
				$this->question[$question_no] = new question_class;
				$question = &$this->question[$question_no];
				$question->question_text = $fetch->eq_question_text;
				$question->question_type = $fetch->eq_question_type;
				$question->fa_flag = $fetch->eq_fa_flag;
				$question->url = $fetch->eq_url;
				$question->image_id = $fetch->eq_image_id;
				$question->dup_flag = $fetch->eq_dup_flag;

				// 選択項目取得
				$sql = "SELECT es_sel_no,es_sel_text,es_sel_text_s"
						. " FROM t_enq_select"
						.	" WHERE es_enquete_id=$enquete_id AND es_question_no=$question_no"
						. " ORDER BY es_sel_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_num_rows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					$sel_no = $fetch2->es_sel_no;
					if ($sel_no >= 1) {
						$question->sel_text[$sel_no] = $fetch2->es_sel_text;
						$question->sel_text_s[$sel_no] = $fetch2->es_sel_text_s;
					}
				}

				// 表側項目取得
				$question->hyousoku = array();
				$sql = "SELECT hs_hyousoku_no,hs_hyousoku_text"
						. " FROM t_enq_hyousoku"
						.	" WHERE hs_enquete_id=$enquete_id AND hs_question_no=$question_no"
						. " ORDER BY hs_hyousoku_no";
				$result2 = db_exec($sql);
				$nrow2 = pg_num_rows($result2);
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
				$nrow2 = pg_num_rows($result2);
				for ($j = 0; $j < $nrow2; $j++) {
					$fetch2 = pg_fetch_object($result2, $j);
					$sel_no = $fetch2->ht_hyoutou_no;
					$question->hyoutou[$sel_no] = $fetch2->ht_hyoutou_text;
				}

				$this->max_question_no = $question_no;
			}
		}

		return true;
	}

	// DBに書き込み
	function write_db($enquete_id = '') {
		db_begin_trans();

		// アンケート登録情報書き込み
		if ($enquete_id == '') {
			$sql = "INSERT INTO t_enquete (en_status,en_enq_kind,en_enq_type,en_title,en_description,en_start_date,en_end_date,en_point) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_enquete SET en_status=%s,en_enq_kind=%s,en_enq_type=%s,en_title=%s,en_description=%s,en_start_date=%s,en_end_date=%s,en_point=%s WHERE en_enquete_id=$enquete_id";
		}
		$sql = sprintf($sql,
				sql_number($this->status),
				sql_number($this->enq_kind),
				sql_number($this->enq_type),
				sql_char($this->title),
				sql_char($this->description),
				sql_date2($this->start_date_y, $this->start_date_m, $this->start_date_d, $this->start_date_h),
				sql_date2($this->end_date_y, $this->end_date_m, $this->end_date_d, $this->end_date_h),
				sql_number($this->point));
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
			foreach (array_keys($this->question) as $i) {
				$question = &$this->question[$i];

				// 選択肢書き込み
				$sel_exist = false;
				if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 3) {
					if (is_array($question->sel_text)) {
						foreach (array_keys($question->sel_text) as $j) {
							if ($question->sel_text[$j] != '') {
								$sql = sprintf("INSERT INTO t_enq_select (es_enquete_id,es_question_no,es_sel_no,es_sel_text,es_sel_text_s) VALUES (%s,%s,%s,%s,%s)",
										sql_number($enquete_id),
										sql_number($i),
										sql_number($j),
										sql_char($question->sel_text[$j]),
										sql_char($question->sel_text_s[$j]));
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
										sql_number($i),
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
										sql_number($i),
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
					$sql = sprintf("INSERT INTO t_enq_question (eq_enquete_id,eq_question_no,eq_question_text,eq_question_type,eq_fa_flag,eq_url,eq_image_id,eq_dup_flag) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
							sql_number($enquete_id),
							sql_number($i),
							sql_char($question->question_text),
							sql_number($question->question_type),
							sql_bool($question->fa_flag, "'f'"),
							sql_char($question->url),
							sql_number($question->image_id),
							sql_bool($question->dup_flag));
					db_exec($sql);
				}
			}
		}

		db_commit_trans();

		return $enquete_id;
	}

	// 質問数取得
	function get_question_num() {
		$num = 0;

		if (is_array($this->question)) {
			foreach (array_keys($this->question) as $i) {
				$question = &$this->question[$i];
				if ($question->question_text != '')
					$num++;
			}
		}

		return $num;
	}
}
?>