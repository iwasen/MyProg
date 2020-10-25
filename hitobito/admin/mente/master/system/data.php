<?php
/**
 * ひとびと･net 事務局ページ
 *
 * システムマスタデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class system_data_class extends data_class {
	public $key;
	public $value;
	public $caption;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($key) {
		$sql = sprintf("SELECT * FROM m_system WHERE sys_key = %s", sql_char($key));
		$fetch = $this->fetch_db($sql);

		$this->key = $fetch->sys_key;
		$this->value = $fetch->sys_value;
		$this->caption = $fetch->sys_caption;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->key = $_POST['key'];
		$this->value = $_POST['value'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		$rec['sys_value'] = sql_char($this->value);
		db_update('m_system', $rec, "sys_key='$this->key'");
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>