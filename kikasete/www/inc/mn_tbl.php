<?
/******************************************************
' System :�������ơ�net
' Content:��˥����ơ��֥�̾����
'******************************************************/
include_once("database.php");

// Pro���󥱡����оݥ�˥����ơ��֥�̾����
function get_monitor_tbl_name($enquete_id) {
	// ��˥����ơ��֥�ե饰����
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
// Pro���󥱡����оݥ�˥����ե饰����
function get_monitor_flg($enquete_id) {
	$mon_tbl_flg = 't';
	// ��˥����ơ��֥�ե饰����
	$sql = "SELECT pet_target_flg FROM t_pro_enq_target WHERE pet_enquete_id = $enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mon_tbl_flg = $fetch->pet_target_flg;
	}
	return $mon_tbl_flg;
}

?>
