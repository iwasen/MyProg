<?php
/**
 * ひとびと･net 事務局ページ
 *
 * ワイワイ会議最新投稿データ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class new_contribution_data_class extends data_class {
	public $keisai_flag;
	public $remark_id;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($channel_id) {
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->keisai_flag = isset($_POST['keisai_flag']) ? $_POST['keisai_flag'] : array();
		$this->remark_id = $_POST['remark_id'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		$remark_id_ary = explode(':', $this->remark_id);
		if (is_array($remark_id_ary)) {
			foreach ($remark_id_ary as $remark_id) {
				if ($remark_id) {
					$keisai_flag = in_array($remark_id, $this->keisai_flag) ? 0 : 1;
					$rec['rmk_keisai_flag'] = sql_number($keisai_flag);
					db_update('l_room_remark', $rec, "rmk_remark_id=$remark_id AND rmk_keisai_flag<>$keisai_flag");
				}
			}
		}
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>