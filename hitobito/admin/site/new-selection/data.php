<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӥ��쥯�Ⱥǿ���ƥǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class new_selection_data_class extends data_class {
	public $navi_select_flag;

	// �����󥹥ȥ饯��
	public function __construct() {
		parent::__construct();
	}

	// ��DB����ǡ����ɤ߹���
	public function read_db($channel_id) {
	}

	// ���ե����फ��ǡ����ɤ߹���
	public function read_form() {
		$this->navi_select_flag = isset($_POST['navi_select_flag']) ? $_POST['navi_select_flag'] : array();
	}

	// ��DB���ɲ�
	public function insert_db() {
	}

	// ��DB�򹹿�
	public function update_db() {
		foreach ($this->navi_select_flag as $article_id) {
			if ($article_id) {
				$rec['bla_navi_select_flag'] = sql_number(0);
				db_update('t_blog_article', $rec, "bla_article_id=$article_id");
			}
		}
	}

	// ��DB������
	public function delete_db() {
	}
}
?>