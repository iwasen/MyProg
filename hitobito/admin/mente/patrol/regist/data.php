<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ѥȥ�����ޥ����ǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class patrol_data_class extends data_class {
	public $patrol_id;
	public $status;
	public $regist_date;
	public $update_date;
	public $name1;
	public $name2;
	public $name1_kana;
	public $name2_kana;
	public $mail_addr;
	public $password;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($patrol_id) {
		$sql = sprintf("SELECT * FROM m_patrol WHERE ptl_patrol_id = %s", sql_number($patrol_id));
		$fetch = $this->fetch_db($sql);

		$this->patrol_id = $fetch->ptl_patrol_id;
		$this->status = $fetch->ptl_status;
		$this->regist_date = $fetch->ptl_regist_date;
		$this->update_date = $fetch->ptl_update_date;
		$this->name1 = $fetch->ptl_name1;
		$this->name2 = $fetch->ptl_name2;
		$this->name1_kana = $fetch->ptl_name1_kana;
		$this->name2_kana = $fetch->ptl_name2_kana;
		$this->mail_addr = $fetch->ptl_mail_addr;
		$this->password = $fetch->ptl_password;
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->patrol_id = $_POST['patrol_id'];
		$this->status = $_POST['status'];
		$this->name1 = $_POST['name1'];
		$this->name2 = $_POST['name2'];
		$this->name1_kana = $_POST['name1_kana'];
		$this->name2_kana = $_POST['name2_kana'];
		$this->mail_addr = strtolower($_POST['mail_addr']);
		$this->password = $_POST['password'];
	}

	// ��DB���ɲ�
	public function insert_db() {
		$rec['ptl_status'] = sql_number($this->status);
		$rec['ptl_name1'] = sql_char($this->name1);
		$rec['ptl_name2'] = sql_char($this->name2);
		$rec['ptl_name1_kana'] = sql_char($this->name1_kana);
		$rec['ptl_name2_kana'] = sql_char($this->name2_kana);
		$rec['ptl_mail_addr'] = sql_char($this->mail_addr);
		$rec['ptl_password'] = sql_char($this->password);
		db_insert('m_patrol', $rec);
	}

	// ��DB�򹹿�
	public function update_db() {
		$rec['ptl_update_date'] = 'current_timestamp';
		$rec['ptl_status'] = sql_number($this->status);
		$rec['ptl_name1'] = sql_char($this->name1);
		$rec['ptl_name2'] = sql_char($this->name2);
		$rec['ptl_name1_kana'] = sql_char($this->name1_kana);
		$rec['ptl_name2_kana'] = sql_char($this->name2_kana);
		$rec['ptl_mail_addr'] = sql_char($this->mail_addr);
		$rec['ptl_password'] = sql_char($this->password);
		db_update('m_patrol', $rec, "ptl_patrol_id=$this->patrol_id");
	}

	// ��DB������
	public function delete_db() {
		db_delete('m_patrol', "ptl_patrol_id=$this->patrol_id");
	}

	// ���ǡ��������å�
	public function check_data($action) {
		if ($action == 'new') {
			// ��Ͽ�Ѥߥ����å�
			$sql = sprintf("SELECT COUNT(*) FROM m_patrol WHERE ptl_mail_addr=%s", sql_char($this->mail_addr));
			if (db_fetch1($sql))
				$msg[] = "���Ϥ����᡼�륢�ɥ쥹�ϴ�����Ͽ����Ƥ��ޤ�";
		}

		return $msg;
	}
}
?>