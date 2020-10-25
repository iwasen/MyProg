<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:��������Υ᡼�����
'******************************************************/

function send_job_notify_mail($job_id, $template_id, $reorder = false) {
	// �����᡼��ƥ�ץ졼�ȼ���
	get_mail_template($template_id, $subject, $from, $cc, $bcc, $repty_to, $body);

	// �������߽���
	$body = replace_room_var($job_id, $body, $reorder);

	// ICP�����ԤΥ᡼�륢�ɥ쥹����
	$sql = "SELECT op_mail_addr FROM t_operator WHERE op_status=1 AND op_admin_auth=1";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->op_mail_addr;
	}

	// ����ִ����Ը��½�ͭ���ФΥ᡼�륢�ɥ쥹����
	$sql = "SELECT pm_mail_addr"
			. " FROM t_job"
			. " JOIN t_pjt_member ON pm_pjt_id=jb_pjt_id"
			. " WHERE jb_job_id=$job_id AND pm_status=1 AND " . cond_staff_auth('����ִ���');
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$to_ary[]  = $fetch->pm_mail_addr;
	}

	// �����᡼��ǡ������å�
	$send_mail_id = send_mail_data($subject, $from, $repty_to, $body);

	// �ۿ���ꥹ������
	foreach (array_unique($to_ary) as $to)
		send_mail_list($send_mail_id, $to);

	send_mail_ok($send_mail_id);
}
?>