<?
/******************************************************
' System :�������ơ�net����
' Content:������
'******************************************************/

function resign_monitor($monitor_id) {
	db_begin_trans();

	// ���ơ����������˥��å�
	$sql = "UPDATE t_monitor SET mn_status=9,mn_taikai_date=current_timestamp WHERE mn_monitor_id=$monitor_id";
	db_exec($sql);

	// ��˥���������
	$sql = "UPDATE t_sum_data SET su_sum_data=su_sum_data-1 WHERE su_sum_id=1";
	db_exec($sql);

	// �ݥ���Ȥ�õ�
	$sql = "SELECT mp_curr_point FROM t_monitor_point WHERE mp_monitor_id=$monitor_id";
	$point = db_fetch1($sql, 0);
	if ($point != 0)
		monitor_point($monitor_id, PT_RESIGN, -$point);

	// ���ڥ����ѥͥ뤫����
	$sql = "DELETE FROM t_panel_monitor_list WHERE pnm_monitor_id=$monitor_id";
	db_exec($sql);

	db_commit_trans();
}
?>