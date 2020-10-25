<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 今日のイチオシナビデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class recommend_data_class extends data_class {
	public $channel_id;
	public $selection_mode;
	public $fix_navi_page_id;
	public $loop_navi_page_id;
	public $update_mode;
	public $update_time;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($channel_id) {
		$this->channel_id = (int)$channel_id;

		// 本日のイチオシナビ取得
		$sql = "SELECT * FROM t_ichioshi_navi WHERE inv_channel_id=" . sql_number($this->channel_id);
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$position = $fetch->inv_position;

			$this->selection_mode[$position] = $fetch->inv_selection_mode;
			$this->fix_navi_page_id[$position] = $fetch->inv_fix_navi_page_id;
			$this->loop_navi_page_id[$position] = $fetch->inv_loop_navi_page_id;
		}

		// イチオシナビ更新設定取得
		$sql = "SELECT * FROM t_ichioshi_navi_update";
		$fetch = $this->fetch_db($sql);
		if ($fetch) {
			$this->update_mode = $fetch->inu_update_mode;
			$this->update_time = $fetch->inu_update_time;
		}
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		for ($i = 1; $i <= 3; $i++) {
			$this->selection_mode[$i] = $_POST['selection_mode'][$i];
			$this->fix_navi_page_id[$i] = $_POST['fix_navi_page_id'][$i];
			$this->loop_navi_page_id[$i] = $this->get_multi_select($_POST['loop_navi_page_id'][$i]);
		}

		$this->update_mode = $_POST['update_mode'];
		$this->update_time = $_POST['update_time'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		db_begin_trans();

		db_delete('t_ichioshi_navi', "inv_channel_id=$this->channel_id");

		for ($i = 1; $i <= 3; $i++) {
			$rec['inv_channel_id'] = sql_number($this->channel_id);
			$rec['inv_position'] = sql_number($i);
			$rec['inv_selection_mode'] = sql_number($this->selection_mode[$i]);
			$rec['inv_fix_navi_page_id'] = sql_number($this->fix_navi_page_id[$i]);
			$rec['inv_loop_navi_page_id'] = sql_char($this->loop_navi_page_id[$i]);
			db_insert('t_ichioshi_navi', $rec);
		}

		db_delete('t_ichioshi_navi_update', "true");

		$rec['inu_update_mode'] = sql_number($this->update_mode);
		$rec['inu_update_time'] = sql_number($this->update_time);
		db_insert('t_ichioshi_navi_update', $rec);

		db_commit_trans();
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>