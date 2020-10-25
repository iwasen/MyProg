<?
/******************************************************
' System :ICP会議室ページ
' Content:発言録関連
'******************************************************/

// ニックネーム配列の取得
function get_nickname_ary($job_id) {
	$ary = array();

	$where = sprintf("jm_job_id=%s AND jm_status=1", sql_number($job_id));
	if ($_SESSION[SCD]['room_type'] == ROOM_PIR)
		$where .= sprintf(" AND (jm_member_id=%s OR " . cond_participant_auth('司会者') . ")", sql_number($_SESSION[SCD]['disp_member_id']));

	$sql = "SELECT pm_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,jm_participant_auth"
			. " FROM t_job_member"
			. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
			. " WHERE $where"
			. " ORDER BY CASE WHEN " . cond_participant_auth('司会者') . " THEN 0 ELSE pm_member_id END";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$ary[$fetch->pm_member_id] = $fetch->pm_nickname;
	}

	return $ary;
}

// 発言録データ配列の取得
function get_record_ary($job_id, $remark_id) {
	$remark_ary = array();
	$count_ary = array();

	get_remark_id_ary($job_id, $remark_id_ary, $remark_id);
	if ($remark_id_ary) {
		$sql = "SELECT rm_member_id,rm_seq_no,rm_content"
				. " FROM l_remark"
				. " WHERE rm_remark_id IN (" . join(',', $remark_id_ary) . ")"
				. " ORDER BY rm_seq_no";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$member_id = $fetch->rm_member_id;
			$row = (int)$count_ary[$member_id]++;

			$remark_ary[$row][$member_id] = $fetch->rm_content;
		}
	}

	return $remark_ary;
}

// スレッド表示
function get_remark_id_ary($job_id, &$ary, $remark_id, $parent_remark_id = 0) {
	$sql = sprintf(
				"SELECT rm_remark_id,rm_remark_type,rm_child_flag"
			. " FROM l_remark"
			. " WHERE rm_job_id=%s AND %s=%s AND rm_status=1 AND rm_disp_type=1"
			. " ORDER BY rm_remark_id",
			sql_number($job_id),
			$remark_id != 0 ? 'rm_remark_id' : 'rm_parent_remark_id',
			sql_number($remark_id != 0 ? $remark_id : $parent_remark_id));
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		if ($fetch->rm_remark_type == 3)
			$ary[] = $fetch->rm_remark_id;

		if ($fetch->rm_child_flag == DBTRUE)
			get_remark_id_ary($job_id, $ary, 0, $fetch->rm_remark_id);
	}
}
?>