<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �����ƥ�ޥ����ǡ����������饹
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

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($key) {
		$sql = sprintf("SELECT * FROM m_system WHERE sys_key = %s", sql_char($key));
		$fetch = $this->fetch_db($sql);

		$this->key = $fetch->sys_key;
		$this->value = $fetch->sys_value;
		$this->caption = $fetch->sys_caption;
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->key = $_POST['key'];
		$this->value = $_POST['value'];
	}

	// ��DB���ɲ�
	public function insert_db() {
	}

	// ��DB�򹹿�
	public function update_db() {
		$rec['sys_value'] = sql_char($this->value);
		db_update('m_system', $rec, "sys_key='$this->key'");
	}

	// ��DB������
	public function delete_db() {
	}
}
?>