<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ǿ�����������ƥǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class new_kuchikomi_data_class extends data_class {
	public $keisai_flag;
	public $kuchikomi_id;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db() {
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->keisai_flag = isset($_POST['keisai_flag']) ? $_POST['keisai_flag'] : array();
		$this->kuchikomi_id = $_POST['kuchikomi_id'];
	}

	// ��DB���ɲ�
	public function insert_db() {
	}

	// ��DB�򹹿�
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

	// ��DB������
	public function delete_db() {
	}
}
?>