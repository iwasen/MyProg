<?php
/**
 * ひとびと･net 事務局ページ
 *
 * エージェントマスタデータ処理クラス
 *
 *
 * @package
 * @author
 * @version
 */

class admin_data_class extends data_class {
	public $admin_id;
	public $status;
	public $regist_date;
	public $update_date;
	public $type;
	public $name1;
	public $name2;
	public $name1_kana;
	public $name2_kana;
	public $unit;
	public $mail_addr;
	public $password;

	// ■コンストラクタ
	public function __construct() {
		parent::__construct();
	}

	// ■DBからデータ読み込み
	public function read_db($admin_id) {
		$sql = sprintf("SELECT * FROM m_admin WHERE adm_admin_id = %s", sql_number($admin_id));
		$fetch = $this->fetch_db($sql);

		$this->admin_id = $fetch->adm_admin_id;
		$this->status = $fetch->adm_status;
		$this->regist_date = $fetch->adm_regist_date;
		$this->update_date = $fetch->adm_update_date;
		$this->type = $fetch->adm_type;
		$this->name1 = $fetch->adm_name1;
		$this->name2 = $fetch->adm_name2;
		$this->name1_kana = $fetch->adm_name1_kana;
		$this->name2_kana = $fetch->adm_name2_kana;
		$this->unit = $fetch->adm_unit;
		$this->mail_addr = $fetch->adm_mail_addr;
		$this->password = $fetch->adm_password;
	}

	// ■フォームからデータ読み込み
	public function read_form() {
		$this->admin_id = $_POST['admin_id'];
		$this->status = $_POST['status'];
		$this->type = 3;
		$this->name1 = $_POST['name1'];
		$this->name2 = $_POST['name2'];
		$this->name1_kana = $_POST['name1_kana'];
		$this->name2_kana = $_POST['name2_kana'];
		$this->unit = $_POST['unit'];
		$this->mail_addr = strtolower($_POST['mail_addr']);
		$this->password = $_POST['password'];
	}

	// ■DBに追加
	public function insert_db() {
		$rec['adm_status'] = sql_number($this->status);
		$rec['adm_type'] = sql_number($this->type);
		$rec['adm_name1'] = sql_char($this->name1);
		$rec['adm_name2'] = sql_char($this->name2);
		$rec['adm_name1_kana'] = sql_char($this->name1_kana);
		$rec['adm_name2_kana'] = sql_char($this->name2_kana);
		$rec['adm_unit'] = sql_char($this->unit);
		$rec['adm_mail_addr'] = sql_char($this->mail_addr);
		$rec['adm_password'] = sql_char($this->password);
		db_insert('m_admin', $rec);
	}

	// ■DBを更新
	public function update_db() {
		$rec['adm_update_date'] = 'current_timestamp';
		$rec['adm_status'] = sql_number($this->status);
		$rec['adm_type'] = sql_number($this->type);
		$rec['adm_name1'] = sql_char($this->name1);
		$rec['adm_name2'] = sql_char($this->name2);
		$rec['adm_name1_kana'] = sql_char($this->name1_kana);
		$rec['adm_name2_kana'] = sql_char($this->name2_kana);
		$rec['adm_unit'] = sql_char($this->unit);
		$rec['adm_mail_addr'] = sql_char($this->mail_addr);
		$rec['adm_password'] = sql_char($this->password);
		db_update('m_admin', $rec, "adm_admin_id=$this->admin_id");
	}

	// ■DBから削除
	public function delete_db() {
		db_delete('m_admin', "adm_admin_id=$this->admin_id");
	}

	// ■データチェック
	public function check_data($action) {
		if ($action == 'new') {
			// 登録済みチェック
			$sql = sprintf("SELECT COUNT(*) FROM m_admin WHERE adm_mail_addr=%s", sql_char($this->mail_addr));
			if (db_fetch1($sql))
				$msg[] = "入力したメールアドレスは既に登録されています";
		}

		return $msg;
	}
}
?>