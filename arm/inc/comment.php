<?
/******************************************************
* system  : xxx �ꥹ������
* title   : ���ǥ����Ƚ������饹
* content : ���ǥ����Ȥ�������������Ԥ�
* version : 1.0
* author  : aizawa
*******************************************************/

// �������Ƚ������饹
class comment_class {
	var $comment_ary;

	// �����󥹥ȥ饯��
	function comment_class() {
		$this->comment_ary = array();
	}

	// ������������
	function set($id, $sel = '', $pri = 1, $con = null, $ins = null) {
		if (!isset($this->comment_ary[$id]) || $pri < $this->comment_ary[$id]->pri) {
			$this->comment_ary[$id]->sel = $sel;
			$this->comment_ary[$id]->pri = $pri;
			$this->comment_ary[$id]->con = $con;
			$this->comment_ary[$id]->ins = $ins;
		}
	}

	// �������ȼ���
	function get() {
		$i = 0;
		$ary = array();
		foreach ($this->comment_ary as $id => $obj) {
			$i++;
			$tag = "������$i";

			if ($obj->sel)
				$ary[$tag]->attr['id'] = "{$id}_{$obj->sel}";
			else
				$ary[$tag]->attr['id'] = $id;

			if ($obj->con != null && count($ary) > 1)
				$ary[$tag]->attr['��³��'] = $obj->con;

			if ($obj->ins != null)
				$ary[$tag]->child = $obj->ins;
		}

		return $ary;
	}
}
?>