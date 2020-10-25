<?
/******************************************************
' System :�֤���������� �������������ӥ��ץХå�����
' Content:����ʾ�������ã����L�ǰ��Ѥ�
'******************************************************/

$inc = "../inc";
include("$inc/database.php");

db_begin_trans();

$sql = "SELECT ust_user_id,urs_user_id"
		. " FROM t_user_status"
		. " LEFT JOIN t_user_result ON urs_user_id=ust_user_id"
		. " WHERE ust_status=1"
		. " AND EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=ust_user_id AND cml_err_mail_flag=true)"
		. " AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=ust_user_id AND cml_err_mail_flag=false)";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$user_id = $fetch->ust_user_id;

	// ���ơ���������ã���顼�˥��å�
	$rec['ust_status'] = 7;
	$rec['ust_handover_date'] = 'current_timestamp';
	db_update('t_user_status', $rec, "ust_user_id=$user_id");

	// �桼���б������Ͽ
	$rec['urs_kecchaku'] = sql_number(3);
	$rec['urs_rank'] = sql_char('L');
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
?>