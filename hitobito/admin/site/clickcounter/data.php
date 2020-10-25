<?php
/**
 * ひとびと･net 事務局ページ
 *
 * クリックカウンターデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class recruit_data_class extends data_class {
	public $recruit_id;
	public $counter_name;
	public $jump_url;
	public $usage;
	public $click_count;
	public $regist_count;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($recruit_id) {
		$sql = sprintf("SELECT * FROM t_recruit_click JOIN c_recruit_click ON rcc_recruit_id=rct_recruit_id WHERE rct_recruit_id=%s", sql_number($recruit_id));
		$fetch = $this->fetch_db($sql);
		if ($fetch) {
			$this->recruit_id = $fetch->rct_recruit_id;
			$this->counter_name = $fetch->rct_counter_name;
			$this->jump_url = $fetch->rct_jump_url;
			$this->usage = $fetch->rct_usage;
			$this->click_count = $fetch->rcc_click_count;
			$this->regist_count = $fetch->rcc_regist_count;
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->recruit_id = $_POST['recruit_id'];
		$this->counter_name = $_POST['counter_name'];
		$this->jump_url = $_POST['jump_url'];
		$this->usage = $_POST['usage'];
	}

	// ■DBに追加
	public function insert_db() {
		db_begin_trans();

		// 募集用クリックカウンタ登録
		$rec['rct_counter_name'] = sql_char($this->counter_name);
		$rec['rct_jump_url'] = sql_char($this->jump_url);
		$rec['rct_usage'] = sql_char($this->usage);
		db_insert('t_recruit_click', $rec);
		$this->recruit_id = get_current_seq('t_recruit_click', 'rct_recruit_id');

		// 募集用クリックカウント
		$rec['rcc_recruit_id'] = sql_number($this->recruit_id);
		db_insert('c_recruit_click', $rec);

		db_commit_trans();
	}

	// ■DBを更新
	public function update_db() {
		$rec['rct_counter_name'] = sql_char($this->counter_name);
		$rec['rct_jump_url'] = sql_char($this->jump_url);
		$rec['rct_usage'] = sql_char($this->usage);
		db_update('t_recruit_click', $rec, sprintf("rct_recruit_id=%s", sql_number($this->recruit_id)));
	}

	// ■DBから削除
	public function delete_db() {
	}

	// ■クリック数をリセット
	public function reset_click_counter($recruit_id) {
		db_begin_trans();

		// クリック数リセット
		$rec['rcc_click_count'] = 0;
		db_update('c_recruit_click', $rec, sprintf("rcc_recruit_id=%s", sql_number($recruit_id)));

		// クリック履歴削除
		db_delete('l_recruit_click', sprintf("rcl_recruit_id=%s", sql_number($recruit_id)));

		db_commit_trans();
	}
}
?>