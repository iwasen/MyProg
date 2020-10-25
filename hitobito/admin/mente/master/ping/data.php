<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * Ping�ޥ����ǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class ping_data_class extends data_class {
	public $ping_id;
	public $status;
	public $name;
	public $url;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($ping_id) {
		$sql = sprintf("SELECT * FROM m_ping WHERE png_ping_id = %s", sql_number($ping_id));
		$fetch = $this->fetch_db($sql);

		$this->ping_id = $fetch->png_ping_id;
		$this->status = $fetch->png_status;
		$this->name = $fetch->png_name;
		$this->url = $fetch->png_url;
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->ping_id = $_POST['ping_id'];
		$this->status = $_POST['status'];
		$this->name = $_POST['name'];
		$this->url = $_POST['url'];
	}

	// ��DB���ɲ�
	public function insert_db() {
		$rec['png_ping_id'] = "(SELECT (COALESCE(MAX(png_ping_id), 0) + 1) FROM m_ping)";
		$rec['png_status'] = sql_number($this->status);
		$rec['png_name'] = sql_char($this->name);
		$rec['png_url'] = sql_char($this->url);
		db_insert('m_ping', $rec);
	}

	// ��DB�򹹿�
	public function update_db() {
		$rec['png_status'] = sql_number($this->status);
		$rec['png_name'] = sql_char($this->name);
		$rec['png_url'] = sql_char($this->url);
		db_update('m_ping', $rec, "png_ping_id=$this->ping_id");
	}

	// ��DB������
	public function delete_db() {
		db_delete('m_ping', "png_ping_id=$this->ping_id");
	}
}
?>