<?
/******************************************************
' System :きかせて・net
' Content:モニターテーブル名取得
'******************************************************/
include_once("database.php");

// Proアンケート対象モニターテーブル名取得
function get_monitor_tbl_name($enquete_id) {
	// モニターテーブルフラグ取得
	$sql = "SELECT pet_target_flg FROM t_pro_enq_target WHERE pet_enquete_id = $enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mon_tbl_flg = $fetch->pet_target_flg;
		return $mon_tbl_flg."_monitor";
	} else {
		return ("t_monitor");
	}
}
// Proアンケート対象モニターフラグ取得
function get_monitor_flg($enquete_id) {
	$mon_tbl_flg = 't';
	// モニターテーブルフラグ取得
	$sql = "SELECT pet_target_flg FROM t_pro_enq_target WHERE pet_enquete_id = $enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mon_tbl_flg = $fetch->pet_target_flg;
	}
	return $mon_tbl_flg;
}

?>
