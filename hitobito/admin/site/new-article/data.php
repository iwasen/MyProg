<?php
/**
 * �ҤȤӤȎ�net ��̳�ɥڡ���
 *
 * �ʥӵ����ǿ���ƥǡ����������饹
 *
 *
 * @package
 * @author
 * @version
 */

class new_article_data_class extends data_class {
	public $keisai_flag;
	public $article_id;

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
		$this->article_id = $_POST['article_id'];
	}

	// ��DB���ɲ�
	public function insert_db() {
	}

	// ��DB�򹹿�
	public function update_db() {
		$article_id_ary = explode(':', $this->article_id);
		if (is_array($article_id_ary)) {
			foreach ($article_id_ary as $article_id) {
				if ($article_id) {
					$keisai_flag = in_array($article_id, $this->keisai_flag) ? 0 : 1;
					$rec['bla_keisai_flag'] = sql_number($keisai_flag);
					db_update('t_blog_article', $rec, "bla_article_id=$article_id AND bla_keisai_flag<>$keisai_flag");
				}
			}
		}
	}

	// ��DB������
	public function delete_db() {
	}
}
?>