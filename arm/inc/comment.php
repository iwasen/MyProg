<?
/******************************************************
* system  : xxx リスク診断
* title   : 診断コメント処理クラス
* content : 診断コメントの生成と成型を行う
* version : 1.0
* author  : aizawa
*******************************************************/

// ▼コメント処理クラス
class comment_class {
	var $comment_ary;

	// ■コンストラクタ
	function comment_class() {
		$this->comment_ary = array();
	}

	// ■コメント設定
	function set($id, $sel = '', $pri = 1, $con = null, $ins = null) {
		if (!isset($this->comment_ary[$id]) || $pri < $this->comment_ary[$id]->pri) {
			$this->comment_ary[$id]->sel = $sel;
			$this->comment_ary[$id]->pri = $pri;
			$this->comment_ary[$id]->con = $con;
			$this->comment_ary[$id]->ins = $ins;
		}
	}

	// ■コメント取得
	function get() {
		$i = 0;
		$ary = array();
		foreach ($this->comment_ary as $id => $obj) {
			$i++;
			$tag = "コメント$i";

			if ($obj->sel)
				$ary[$tag]->attr['id'] = "{$id}_{$obj->sel}";
			else
				$ary[$tag]->attr['id'] = $id;

			if ($obj->con != null && count($ary) > 1)
				$ary[$tag]->attr['接続詞'] = $obj->con;

			if ($obj->ins != null)
				$ary[$tag]->child = $obj->ins;
		}

		return $ary;
	}
}
?>