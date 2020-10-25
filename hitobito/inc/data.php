<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �ǡ����������̥��饹
 *
 *
 * @package
 * @author
 * @version
 */

class data_class {
	public $new = true;
	public $action;
	public $button;

	// �����󥹥ȥ饯��
	public function __construct() {
	}

	// ��DB����ǡ����ɤ߹���
	protected function fetch_db($sql) {
		$this->new = false;

		$result = db_exec($sql);
		return pg_num_rows($result) ? pg_fetch_object($result, 0) : null;
	}

	// ������̾����
	public function action_db($action) {
		switch ($action) {
		case 'new':
			$this->insert_db();
			$this->action = '��Ͽ';
			break;
		case 'update':
			$this->update_db();
			$this->action = '����';
			break;
		case 'delete':
			$this->delete_db();
			$this->action = '���';
			break;
		default:
			$func = "action_$action";
			$this->action = $this->$func();
			break;
		}
	}

	// �����դ�ե����फ�����
	protected function get_date($base_name) {
		if (isset($_POST["{$base_name}_y"]))
			$date['y'] = $_POST["{$base_name}_y"];

		if (isset($_POST["{$base_name}_m"]))
			$date['m'] = $_POST["{$base_name}_m"];

		if (isset($_POST["{$base_name}_d"]))
			$date['d'] = $_POST["{$base_name}_d"];

		if (isset($_POST["{$base_name}_h"]))
			$date['h'] = $_POST["{$base_name}_h"];

		if (isset($_POST["{$base_name}_n"]))
			$date['n'] = $_POST["{$base_name}_n"];

		if (isset($_POST["{$base_name}_s"]))
			$date['s'] = $_POST["{$base_name}_s"];

		return $date;
	}

	// ���ե����फ��ʣ�������򥫥�޶��ڤ�Ǽ���
	protected function get_multi_select($ary) {
		if (is_array($ary))
			return join(',', $ary);
		else
			return '';
	}
}
?>