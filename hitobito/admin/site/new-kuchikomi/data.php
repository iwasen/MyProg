<?php
/**
 * ひとびと･net 事務局ページ
 *
 * 最新クチコミ投稿データ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class new_kuchikomi_data_class extends data_class {
	public $keisai_flag;
	public $kuchikomi_id;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db() {
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->keisai_flag = isset($_POST['keisai_flag']) ? $_POST['keisai_flag'] : array();
		$this->kuchikomi_id = $_POST['kuchikomi_id'];
	}

	// ■DBに追加
	public function insert_db() {
	}

	// ■DBを更新
	public function update_db() {
		$kuchikomi_id_ary = explode(':', $this->kuchikomi_id);
		if (is_array($kuchikomi_id_ary)) {
			foreach ($kuchikomi_id_ary as $kuchikomi_id) {
				if ($kuchikomi_id) {
					$keisai_flag = in_array($kuchikomi_id, $this->keisai_flag) ? 0 : 1;
					$rec['kck_keisai_flag'] = sql_number($keisai_flag);
					db_update('t_kuchikomi', $rec, "kck_kuchikomi_id=$kuchikomi_id AND kck_keisai_flag<>$keisai_flag");
				}
			}
		}
	}

	// ■DBから削除
	public function delete_db() {
	}
}
?>