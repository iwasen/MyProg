<?
//=== 合計ポイント取得 ===
function GetTotalPoint($member_id) {
	$sql = "SELECT SUM(PT_total_point) point FROM T_POINT_TOTAL WHERE PT_member_id='$member_id'";
	$ds = CreateDynaset($sql, ORADYN_READONLY);
	if ($ds->EOF())
		$point = 0;
	else
		$point = NVL($ds->GetValue('point'), 0);

	return $point;
}

//=== ポイント追加処理 ===
function AddPoint($member_id, $point_type, $point) {

	BeginTrans();

	while (true) {
		$sql = "SELECT MAX(PL_seq_no) seq_no FROM T_POINT_LOG"
				. " WHERE PL_member_id='$member_id' AND PL_point_type='$point_type'";
		$ds = CreateDynaset($sql, ORADYN_READONLY);
		if ($ds->EOF())
			$seq_no = 1;
		else
			$seq_no = NVL($ds->GetValue('seq_no'), 0) + 1;

		$sql = "SELECT * FROM T_POINT_LOG"
				. " WHERE PL_member_id='$member_id' AND PL_point_type='$point_type' AND PL_seq_no=$seq_no";
		$ds = CreateDynaset($sql, ORADYN_DEFAULT);
		if ($ds->EOF()) {
			$sql = sprintf("INSERT INTO T_POINT_LOG (PL_member_id,PL_point_type,PL_seq_no,PL_point,PL_date) VALUES (%s,%s,%s,%s,%s)",
					SqlStr($member_id),
					SqlStr($point_type),
					SqlNum($seq_no),
					SqlNum($point),
					"SYSDATE");
			ExecuteSQL($sql);
			break;
		}
	}

	$sql = "SELECT * FROM T_POINT_TOTAL"
			. " WHERE PT_member_id='$member_id' AND PT_point_type='$point_type'";
	$ds = CreateDynaset($sql, ORADYN_DEFAULT);
	if ($ds->EOF()) {
		$sql = sprintf("INSERT INTO T_POINT_TOTAL (PT_member_id,PT_point_type,PT_total_point) VALUES (%s,%s,%s)",
				SqlStr($member_id),
				SqlStr($point_type),
				SqlNum($point));
	} else {
		$sql = sprintf("UPDATE T_POINT_TOTAL SET PT_total_point=PT_total_point+%s WHERE PT_member_id='$member_id' AND PT_point_type='$point_type'",
				SqlNum($point));
	}
	ExecuteSQL($sql);

	CommitTrans();
}
?>