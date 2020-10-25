<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * ���ȥޥ����ǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class shokugyo_data_class extends data_class {
	public $shokugyo_id;
	public $shokugyo_name;
	public $order;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($shokugyo_id) {
		$sql = sprintf("SELECT * FROM m_shokugyo WHERE skg_shokugyo_id = %s", sql_number($shokugyo_id));
		$fetch = $this->fetch_db($sql);

		$this->shokugyo_id = $fetch->skg_shokugyo_id;
		$this->shokugyo_name = $fetch->skg_shokugyo_name;
		$this->order = $fetch->skg_order;
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->shokugyo_id = $_POST['shokugyo_id'];
		$this->shokugyo_name = $_POST['shokugyo_name'];
		$this->order = $_POST['order'];
	}

	// ��DB���ɲ�
	public function insert_db() {
		$rec['skg_shokugyo_id'] = "(SELECT (COALESCE(MAX(skg_shokugyo_id), 0) + 1) FROM m_shokugyo)";
		$rec['skg_shokugyo_name'] = sql_char($this->shokugyo_name);
		$rec['skg_order'] = sql_number($this->order);
		db_insert('m_shokugyo', $rec);
	}

	// ��DB�򹹿�
	public function update_db() {
		$rec['skg_shokugyo_name'] = sql_char($this->shokugyo_name);
		$rec['skg_order'] = sql_number($this->order);
		db_update('m_shokugyo', $rec, "skg_shokugyo_id=$this->shokugyo_id");
	}

	// ��DB������
	public function delete_db() {
		db_delete('m_shokugyo', "skg_shokugyo_id=$this->shokugyo_id");
	}
}
?>