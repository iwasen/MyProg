<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ͳ�ޥ����ǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class resign_reason_data_class extends data_class {
	public $resign_id;
	public $status;
	public $reason_text;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($resign_id) {
		$sql = sprintf("SELECT * FROM m_resign_reason WHERE rsg_resign_id = %s", sql_number($resign_id));
		$fetch = $this->fetch_db($sql);

		$this->resign_id = $fetch->rsg_resign_id;
		$this->status = $fetch->rsg_status;
		$this->reason_text = $fetch->rsg_reason_text;
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->resign_id = $_POST['resign_id'];
		$this->status = $_POST['status'];
		$this->reason_text = $_POST['reason_text'];
	}

	// ��DB���ɲ�
	public function insert_db() {
		$rec['rsg_resign_id'] = "(SELECT (COALESCE(MAX(rsg_resign_id), 0) + 1) FROM m_resign_reason)";
		$rec['rsg_status'] = sql_number($this->status);
		$rec['rsg_reason_text'] = sql_char($this->reason_text);
		db_insert('m_resign_reason', $rec);
	}

	// ��DB�򹹿�
	public function update_db() {
		$rec['rsg_status'] = sql_number($this->status);
		$rec['rsg_reason_text'] = sql_char($this->reason_text);
		db_update('m_resign_reason', $rec, "rsg_resign_id=$this->resign_id");
	}

	// ��DB������
	public function delete_db() {
		db_delete('m_resign_reason', "rsg_resign_id=$this->resign_id");
	}
}
?>