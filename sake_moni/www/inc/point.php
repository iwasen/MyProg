<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
' Content:�ݥ���ȴ�Ϣ����
'******************************************************/

// �ݥ���ȼ���
define('PT_ENQUETE', 1);		// ���󥱡��Ȳ���
define('PT_SEISAN', 2);			// ����
define('PT_MAIL_RECV', 3);	// ���󥱡��ȥ᡼�����

// ��˥����ݥ���Ȳû�
function monitor_point($monitor_id, $kind, $point) {
	$point = (int)$point;

	// �ݥ���ȥ����ɲ�
	if ($point != 0) {
		$sql = sprintf("INSERT INTO l_point (lp_member_id,lp_point_kind,lp_point) VALUES (%s,%s,%s)",
				sql_number($monitor_id),
				sql_number($kind),
				sql_number($point));
		db_exec($sql);
	}

	// ��˥����ݥ���Ⱦ��󹹿�
	$add_point = max($point, 0);
	$sql = "UPDATE t_monitor_point SET mp_curr_point=mp_curr_point+$point,mp_total_point=mp_total_point+$add_point"
			. " WHERE mp_monitor_id=$monitor_id";
	db_exec($sql);
}
?>