<?
/******************************************************
' System :きかせて・net共通
' Content:ＰＲＯアンケート関連
'******************************************************/

class pro_enq_target_class {
	var $enquete_id;
	var $target_flg;

	function pro_enq_target_class() {
		$this->target_flg = 't';
	}

	function read_db($enquete_id) {
		$sql = "SELECT pet_target_flg FROM t_pro_enq_target WHERE pet_enquete_id=$enquete_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->target_flg = $fetch->pet_target_flg;
		}
	}

	function write_db() {
		$sql = "SELECT * FROM t_pro_enq_target WHERE pet_enquete_id=$this->enquete_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$sql = "UPDATE t_pro_enq_target SET pet_target_flg = '$this->target_flg' WHERE pet_enquete_id=$this->enquete_id";
		} else {
			$sql = "INSERT INTO t_pro_enq_target (pet_enquete_id, pet_target_flg) VALUES ($this->enquete_id, '$this->target_flg')";
		}
		db_exec($sql);
	}
}
?>