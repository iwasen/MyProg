<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �磻�磻��ĺǿ���ƥǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class new_contribution_data_class extends data_class {
	public $keisai_flag;
	public $remark_id;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($channel_id) {
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->keisai_flag = isset($_POST['keisai_flag']) ? $_POST['keisai_flag'] : array();
		$this->remark_id = $_POST['remark_id'];
	}

	// ��DB���ɲ�
	public function insert_db() {
	}

	// ��DB�򹹿�
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

	// ��DB������
	public function delete_db() {
	}
}
?>