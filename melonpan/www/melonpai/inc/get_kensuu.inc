<?
function get_shori_kensuu($melonpai_id, &$mitsukuroi, &$suisenbun) {
	$sql = "SELECT COUNT(*) AS COUNT FROM T_CHOICE WHERE MELONPAI_ID = $melonpai_id AND cho_flg='0'";
	$result = db_exec($sql);
/* **
	$select = new select_sql($sql);
	if ($select->fetch())
		$mitsukuroi = $select->field("COUNT");
*/
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mitsukuroi = $fetch->count;
	} else {
		$mitsukuroi = 0;
	}
	$sql = "SELECT COUNT(*) AS COUNT FROM T_RECOMEND WHERE open_flg='0'";
	$result = db_exec($sql);
/* **
	$select = new select_sql($sql);
	if ($select->fetch())
		$suisenbun = $select->field("COUNT");
*/
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$suisenbun = $fetch->count;
	} else {
		$suisenbun = 0;
	}
}
?>