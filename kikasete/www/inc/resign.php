<?
/******************************************************
' System :きかせて・net共通
' Content:退会処理
'******************************************************/

function resign_monitor($monitor_id) {
	db_begin_trans();

	// ステータスを退会にセット
	$sql = "UPDATE t_monitor SET mn_status=9,mn_taikai_date=current_timestamp WHERE mn_monitor_id=$monitor_id";
	db_exec($sql);

	// モニター数減算
	$sql = "UPDATE t_sum_data SET su_sum_data=su_sum_data-1 WHERE su_sum_id=1";
	db_exec($sql);

	// ポイントを消去
	$sql = "SELECT mp_curr_point FROM t_monitor_point WHERE mp_monitor_id=$monitor_id";
	$point = db_fetch1($sql, 0);
	if ($point != 0)
		monitor_point($monitor_id, PT_RESIGN, -$point);

	// スペシャルパネルから削除
	$sql = "DELETE FROM t_panel_monitor_list WHERE pnm_monitor_id=$monitor_id";
	db_exec($sql);

	db_commit_trans();
}
?>