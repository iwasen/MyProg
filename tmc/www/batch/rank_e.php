<?
$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");

follow_end();
exit;

// ��졼�����λ����
function follow_end() {
	db_begin_trans();

	// ��³��졼�������ǡ��ۿ���߾��֤ǤϤʤ����ۿ���³�ե饰��Ω�äƤ��ʤ�
	$sql = "SELECT ust_user_id,urs_user_id"
			. " FROM t_user_status"
			. " LEFT JOIN t_user_result ON urs_user_id=ust_user_id"
			. " WHERE ust_status=2"
			. " AND ust_rel_stop_flag=false"
			. " AND (ust_continue_flag IS NULL OR ust_continue_flag=false)";

	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->ust_user_id;

		// ���ơ���������Ѥ��Ѥߤ˥��å�
		$rec['ust_status'] = 3;
		$rec['ust_handover_date'] = 'current_timestamp';
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// �桼���б������Ͽ�ʷ����ʬ:6�����:E��
		$rec['urs_kecchaku'] = sql_number(6);
		$rec['urs_rank'] = sql_char('E');
		if ($fetch->urs_user_id == null) {
			$rec['urs_user_id'] = sql_number($user_id);
			db_insert('t_user_result', $rec);
		} else {
			db_update('t_user_result', $rec, "urs_user_id=$user_id");
		}

		// ���Ѥ��������å�
		$rec['urg_handover_date'] = 'current_timestamp';
		db_update('t_user_regist', $rec, "urg_user_id=$user_id");
	}

	db_commit_trans();
}
?>
