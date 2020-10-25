<?
/******************************************************
' System :タウンガイドWebアンケート
' Content:アンケート回答管理クラス
'******************************************************/

class enquete_class {
	var $user_id;
	var $group_id;
	var $pattern;
	var $category_num;
	var $current_question_no;
	var $category_ans;
	var $gyoshu_tel;
	var $gyoshu_addr;
	var $todofuken;
	var $sex;
	var $age;
	var $mikikon;
	var $shokugyo;
	var $mail_addr;
	var $answer_id;
	var $free_answer1;
	var $free_answer2;
	var $sel_answer;

	// コンストラクタ
	function enquete_class($user_id, $group_id) {
		// URLパラメータ保存
		$this->user_id = $user_id;
		$this->group_id = $group_id;

		// 質問パターン設定
		$this->pattern = get_nextval('access_counter_seq') % 2;

		// カテゴリ数設定
		$sql = "SELECT COUNT(*) FROM m_category";
		$this->category_num = db_fetch1($sql);

		// 現在質問番号設定
		$this->current_question_no = 1;

		// 回答初期化
		$this->category_ans = array();
		$this->gyoshu_tel = array();
		$this->gyoshu_addr = array();
	}

	// 設問番号取得
	function get_question_no() {
		return $this->current_question_no;
	}

	// 代表業種名取得
	function get_gyoshu_name() {
		$sql = "SELECT gy_gyoshu_name"
				. " FROM m_gyoshu"
				. " WHERE gy_category_id=" . sql_number($this->get_category_id()) .  " AND gy_disp_flag=true"
				. " ORDER BY gy_gyoshu_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$ary[] = $fetch->gy_gyoshu_name;
		}

		return join('・', $ary);
	}

	// 業種選択肢配列取得
	function get_gyoshu_ary() {
		$sql = "SELECT gy_gyoshu_no,gy_gyoshu_name"
				. " FROM m_gyoshu"
				. " WHERE gy_category_id=" . sql_number($this->get_category_id())
				. " ORDER BY gy_gyoshu_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$ary[$fetch->gy_gyoshu_no] = $fetch->gy_gyoshu_name;
		}

		return $ary;
	}

	// 質問番号からカテゴリID取得
	function get_category_id() {
		if ($this->pattern == 0)
			return (int)($this->current_question_no / 2) + 1;
		else
			return $this->category_num - (int)($this->current_question_no / 2);
	}

	// 最大業種設問番号取得
	function get_max_category() {
		return $this->category_num * 2;
	}

	// アンケート回答を保存
	function save_answer() {
		$question_no = $_POST['question_no'];
		$max_category = $this->get_max_category();
		if ($question_no <= $max_category) {
			$this->save_gyoshu_answer();
			$this->current_question_no = $question_no + 2;
		} elseif ($question_no == $max_category + 1) {
			$this->save_f1_answer();
			$this->current_question_no = $question_no + 1;
		} elseif ($question_no == $max_category + 2) {
			$this->save_f2_answer();
			$this->current_question_no = $question_no + 1;
		} elseif ($question_no == $max_category + 3) {
			$this->save_f3_answer();
			$this->current_question_no = $question_no + 1;
		} elseif ($question_no == $max_category + 4) {
			$this->save_answer_db();
			$this->current_question_no = $question_no + 1;
		}
	}

	// チェックボックス回答をカンマ区切りで取得
	function get_checkbox(&$ary) {
		return is_array($ary) ? join(',', $ary) : '';
	}

	// 業種アンケート回答を保存
	function save_gyoshu_answer() {
		$category_id = $_POST['category_id'];

		// カテゴリ回答保存
		$this->category_ans[$category_id] = $this->get_checkbox($_POST['category_ans']);

		// 業種別回答保存
		$this->gyoshu_tel[$category_id] = $this->get_checkbox($_POST['gyoshu_tel']);
		$this->gyoshu_addr[$category_id] = $this->get_checkbox($_POST['gyoshu_addr']);
	}

	function save_f1_answer() {
		$this->free_answer1 = $_POST['fa1'];
		$this->free_answer2 = $_POST['fa2'];
	}

	function save_f2_answer() {
		$this->sel_answer = $_POST['sa1'];
	}

	function save_f3_answer() {
		$this->todofuken = $_POST['f1'];
		$this->sex = $_POST['f2'];
		$this->age = $_POST['f3'];
		$this->mikikon = $_POST['f4'];
		$this->shokugyo = $_POST['f5'];
		$this->mail_addr = $_POST['f6'];
		$this->answer_id = $_POST['f7'];
	}

	// 回答をＤＢに保存
	function save_answer_db() {
		db_begin_trans();

		// 回答者データ保存
		$rec['au_user_id'] = sql_char($this->user_id);
		$rec['au_group_id'] = sql_char($this->group_id);
		$rec['au_pattern'] = sql_number($this->pattern);
		$rec['au_ans_group_id'] = sql_number($this->get_group_id());
		db_insert('t_ans_user', $rec);
		$seq_no = get_currval('t_ans_user_au_seq_no_seq');

		// カテゴリ別回答保存
		for ($i = 1; $i <= $this->category_num; $i++) {
			$rec['ac_seq_no'] = sql_number($seq_no);
			$rec['ac_category_id'] = sql_number($i);
			$rec['ac_category_ans'] = sql_char($this->category_ans[$i]);
			$rec['ac_gyoshu_tel'] = sql_char($this->gyoshu_tel[$i]);
			$rec['ac_gyoshu_addr'] = sql_char($this->gyoshu_addr[$i]);
			db_insert('t_ans_category', $rec);
		}

		// 固定設問保存
		$rec['af_seq_no'] = sql_number($seq_no);
		$rec['af_todofuken'] = sql_number($this->todofuken);
		$rec['af_sex'] = sql_number($this->sex);
		$rec['af_age'] = sql_number($this->age);
		$rec['af_mikikon'] = sql_number($this->mikikon);
		$rec['af_shokugyo'] = sql_number($this->shokugyo);
		$rec['af_mail_addr'] = sql_char($this->mail_addr);
		$rec['af_answer_id'] = sql_char($this->answer_id);
		$rec['af_free_answer1'] = sql_char($this->free_answer1);
		$rec['af_free_answer2'] = sql_char($this->free_answer2);
		$rec['af_sel_answer'] = sql_number($this->sel_answer);
		db_insert('t_ans_fix', $rec);

		db_commit_trans();
	}

	// 都道府県と年齢からグループIDを取得
	function get_group_id() {
		$sql = "SELECT gr_group_id"
				. " FROM m_group"
				. " JOIN m_todofuken ON td_area_id=gr_area_id"
				. " WHERE td_todofuken_id=" . sql_number($this->todofuken) . " AND gr_nendai=" . sql_number((int)($this->age / 10) * 10);
		return db_fetch1($sql);
	}
}
?>