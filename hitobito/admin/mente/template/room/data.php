<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ��ļ��إå����եå��ƥ�ץ졼�ȥǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class template_data_class extends data_class {
	public $header;
	public $footer;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db() {
		$sql = "SELECT * FROM m_room_header WHERE rmh_header_id = 1";
		$fetch = $this->fetch_db($sql);

		$this->header = $fetch->rmh_header;
		$this->footer = $fetch->rmh_footer;
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->header = $_POST['header'];
		$this->footer = $_POST['footer'];
	}

	// ��DB���ɲ�
	public function insert_db() {
	}

	// ��DB�򹹿�
	public function update_db() {
		$rec['rmh_header'] = sql_char($this->header);
		$rec['rmh_footer'] = sql_char($this->footer);
		db_update('m_room_header', $rec, "rmh_header_id = 1");
	}

	// ��DB������
	public function delete_db() {
	}
}
?>