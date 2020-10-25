<?php
/**
 * ひとびと･net 事務局ページ
 *
 * NGワードマスタデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class ngword_data_class extends data_class {
	public $ngword_id;
	public $status;
	public $word;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($ngword_id) {
		$sql = sprintf("SELECT * FROM m_ngword WHERE ngw_ngword_id = %s", sql_number($ngword_id));
		$fetch = $this->fetch_db($sql);

		$this->ngword_id = $fetch->ngw_ngword_id;
		$this->status = $fetch->ngw_status;
		$this->word = $fetch->ngw_word;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->ngword_id = $_POST['ngword_id'];
		$this->status = $_POST['status'];
		$this->word = $_POST['word'];
	}

	// ■DBに追加
	public function insert_db() {
		$rec['ngw_ngword_id'] = "(SELECT (COALESCE(MAX(ngw_ngword_id), 0) + 1) FROM m_ngword)";
		$rec['ngw_status'] = sql_number($this->status);
		$rec['ngw_word'] = sql_char($this->word);
		db_insert('m_ngword', $rec);
	}

	// ■DBを更新
	public function update_db() {
		$rec['ngw_status'] = sql_number($this->status);
		$rec['ngw_word'] = sql_char($this->word);
		db_update('m_ngword', $rec, "ngw_ngword_id=$this->ngword_id");
	}

	// ■DBから削除
	public function delete_db() {
		db_delete('m_ngword', "ngw_ngword_id=$this->ngword_id");
	}
}
?>