<?
function get_marketer_info($select) {
	$sql = "SELECT $select FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_marketer_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$fetch = pg_fetch_object($result, 0);
	return $fetch;
}

function get_owner_info($select) {
	$sql = "SELECT $select FROM t_marketer WHERE mr_marketer_id={$_SESSION['ss_owner_id']}";
	$result = db_exec($sql);
	if (pg_numrows($result))
		$fetch = pg_fetch_object($result, 0);
	return $fetch;
}
?>