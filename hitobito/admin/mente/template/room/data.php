<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 会議室ヘッダ・フッタテンプレートデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class template_data_class extends data_class {
	public $header;
	public $footer;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db() {
		$sql = "SELECT * FROM m_room_header WHERE rmh_header_id = 1";
		$fetch = $this->fetch_db($sql);

		$this->header = $fetch->rmh_header;
		$this->footer = $fetch->rmh_footer;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->header = $_POST['header'];
		$this->footer = $_POST['footer'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		$rec['rmh_header'] = sql_char($this->header);
		$rec['rmh_footer'] = sql_char($this->footer);
		db_update('m_room_header', $rec, "rmh_header_id = 1");
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>