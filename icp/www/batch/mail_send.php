<?
/******************************************************
' System :ICP�Хå�����
' Content:�᡼����������
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch.php");
include("$inc/auth.php");
include("$inc/decode.php");
include("$inc/status.php");
include("$inc/mail.php");
include("$inc/room.php");
include("$inc/format.php");
include("$inc/define.php");
include("$inc/variable.php");
include("$inc/remark.php");

// ��ŵ�ư�����å�
if (!dup_check('lock_mail_send'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// ���������
$g_time = time();
$g_sql_time = sql_date(date('Y-m-d H:i:s', $g_time));

send_guide_mail();
send_start_mail();
send_end_mail();

send_all_mail();

exit;

// ����᡼����������
function send_guide_mail() {
	global $g_job_status, $g_sql_time;

	// ������ǽ�᡼�����
	$sql = "SELECT jb_job_id,jb_job_cd,jb_guide_subject,jb_guide_from_name,jb_guide_content,jb_guide_add_mail,jb_room_header,jb_room_footer,pj_pjt_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_guide_status=2 AND jb_status IN ({$g_job_status['�Ե���']},{$g_job_status['�»���']}) AND jb_guide_send_date<=$g_sql_time";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$job_id = $fetch->jb_job_id;

		// ���������ѿ�����
		$content = replace_room_var($job_id, $fetch->jb_guide_content);
		$header = replace_room_var($job_id, $fetch->jb_room_header);
		$footer = replace_room_var($job_id, $fetch->jb_room_footer);

		db_begin_trans();

		// ȯ�������ɲ�
		$rec['rm_job_id'] = sql_number($job_id);
		$rec['rm_remark_type'] = sql_number(RT_GUIDE);
		$rec['rm_from'] = sql_char(join_mail_from(get_help_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_guide_from_name));
		$rec['rm_message_id'] = sql_char(make_message_id());
		$rec['rm_subject'] = sql_char($fetch->jb_guide_subject);
		$rec['rm_content'] = sql_char($content);
		$rec['rm_room_header'] = sql_char($header);
		$rec['rm_room_footer'] = sql_char($footer);
		$rec['rm_web_mail'] = sql_number(RM_BATCH);
		$rec['rm_add_send_to'] = sql_char($fetch->jb_guide_add_mail);
		db_insert('l_remark', $rec);
		$remark_id = get_current_seq('l_remark', 'rm_remark_id');

		// ����᡼��������Ѥߤ�����
		$rec['jb_guide_status'] = sql_number(9);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "JOBID={$job_id}�ΰ���᡼��ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "JOBID={$job_id}�ΰ���᡼���������ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// ���ϥ᡼����������
function send_start_mail() {
	global $g_job_status, $g_sql_time;

	// ������ǽ�᡼�����
	$sql = "SELECT jb_job_id,jb_job_cd,jb_start_subject,jb_start_from_name,jb_start_content,jb_start_add_mail,jb_room_header,jb_room_footer,pj_pjt_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_start_status=2 AND jb_status IN ({$g_job_status['�Ե���']},{$g_job_status['�»���']}) AND jb_start_send_date<=$g_sql_time";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$job_id = $fetch->jb_job_id;

		// ���������ѿ�����
		$content = replace_room_var($job_id, $fetch->jb_start_content);
		$header = replace_room_var($job_id, $fetch->jb_room_header);
		$footer = replace_room_var($job_id, $fetch->jb_room_footer);

		db_begin_trans();

		// ȯ�������ɲ�
		$rec['rm_job_id'] = sql_number($job_id);
		$rec['rm_remark_type'] = sql_number(RT_START);
		$rec['rm_disp_type'] = sql_number(DT_REMARK);
		$rec['rm_disp_member_id'] = sql_number(0);
		$rec['rm_seq_no'] = "(SELECT COALESCE(max(rm_seq_no),0)+1 FROM l_remark WHERE rm_job_id=$job_id AND rm_disp_type=1)";
		$rec['rm_parent_remark_id'] = sql_number(0);
		if ($fetch->jb_room_type == ROOM_PIR)
			$rec['rm_from'] = sql_char(join_mail_from(get_mc_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_start_from_name));
		else
			$rec['rm_from'] = sql_char(join_mail_from(get_room_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_start_from_name));
		$rec['rm_message_id'] = sql_char(make_message_id());
		$rec['rm_subject'] = sql_char($fetch->jb_start_subject);
		$rec['rm_content'] = sql_char($content);
		$rec['rm_room_header'] = sql_char($header);
		$rec['rm_room_footer'] = sql_char($footer);
		$rec['rm_web_mail'] = sql_number(RM_BATCH);
		$rec['rm_add_send_to'] = sql_char($fetch->jb_start_add_mail);
		db_insert('l_remark', $rec);
		$remark_id = get_current_seq('l_remark', 'rm_remark_id');

		// ���ϥ᡼��������Ѥߤ�����
		$rec['jb_start_status'] = sql_number(9);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "JOBID={$job_id}�γ��ϥ᡼��ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "JOBID={$job_id}�γ��ϥ᡼���������ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// ��λ�᡼����������
function send_end_mail() {
	global $g_job_status, $g_sql_time;

	// ������ǽ�᡼�����
	$sql = "SELECT jb_job_id,jb_job_cd,jb_end_subject,jb_end_from_name,jb_end_content,jb_end_add_mail,jb_room_header,jb_room_footer,pj_pjt_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_end_status=2 AND jb_status IN ({$g_job_status['�»���']},{$g_job_status['�ٻ���']},{$g_job_status['ͱͽ��']}) AND jb_end_send_date<=$g_sql_time";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		$job_id = $fetch->jb_job_id;

		// ���������ѿ�����
		$content = replace_room_var($job_id, $fetch->jb_end_content);
		$header = replace_room_var($job_id, $fetch->jb_room_header);
		$footer = replace_room_var($job_id, $fetch->jb_room_footer);

		db_begin_trans();

		// ȯ�������ɲ�
		$rec['rm_job_id'] = sql_number($job_id);
		$rec['rm_remark_type'] = sql_number(RT_END);
		$rec['rm_from'] = sql_char(join_mail_from(get_help_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd), $fetch->jb_end_from_name));
		$rec['rm_message_id'] = sql_char(make_message_id());
		$rec['rm_subject'] = sql_char($fetch->jb_end_subject);
		$rec['rm_content'] = sql_char($content);
		$rec['rm_room_header'] = sql_char($header);
		$rec['rm_room_footer'] = sql_char($footer);
		$rec['rm_web_mail'] = sql_number(RM_BATCH);
		$rec['rm_add_send_to'] = sql_char($fetch->jb_end_add_mail);
		db_insert('l_remark', $rec);
		$remark_id = get_current_seq('l_remark', 'rm_remark_id');

		// ��λ�᡼��������Ѥߤ�����
		$rec['jb_end_status'] = sql_number(9);
		db_update('t_job', $rec, "jb_job_id=$job_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "JOBID={$job_id}�ν�λ�᡼��ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "JOBID={$job_id}�ν�λ�᡼���������ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}

// �᡼���ۿ�����
function send_all_mail() {
	// ̤�ۿ��᡼�����
	$sql = "SELECT rm_remark_id,rm_remark_type,rm_disp_member_id,rm_add_send_to,jb_room_type"
			. " FROM l_remark"
			. " JOIN t_job ON jb_job_id=rm_job_id"
			. " WHERE rm_send_flag=false";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$remark_id = $fetch->rm_remark_id;

		// �ۿ�����о�����
		switch ($fetch->rm_remark_type) {
		case RT_GUIDE:
		case RT_START:
		case RT_END:
			$where = '';
			break;
		case RT_REMARK:
			if ($fetch->jb_room_type == ROOM_PIR)
				$where = "(jm_member_id=$fetch->rm_disp_member_id OR " . cond_participant_auth('�ʲ��') . ") AND " . cond_participant_auth('�᡼�����') . " AND " . cond_participant_auth('ȯ��ON/OFF');
			else
				$where = cond_participant_auth('�᡼�����') . " AND " . cond_participant_auth('ȯ��ON/OFF');
			break;
		case RT_HELP:
		case RT_ANSWER:
			$where = "(jm_member_id=$fetch->rm_disp_member_id OR " . cond_participant_auth('�ʲ��') . ")";
			break;
		default:
			$where = "false";
			break;
		}

		db_begin_trans();

		// �оݼԤ��ۿ�
		send_remark($remark_id, $where, $fetch->rm_add_send_to);

		// ȯ�����ۿ��Ѥߤ˥��å�
		$rec['rm_send_flag'] = sql_bool(true);
		db_update('l_remark', $rec, "rm_remark_id=$remark_id");

		// ���顼�����å�
		$err_msg = db_errormessage();
		if ($err_msg) {
			db_rollback();

			// �Хå����񤭹��ߡʥ��顼��
			$desc = "REMARKID={$remark_id}�����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
			batch_log(9, __FILE__, $desc);
		} else {
			// �Хå����񤭹��ߡ������
			$desc = "REMARKID={$remark_id}�������������ޤ�����";
			batch_log(0, __FILE__, $desc);

			db_commit_trans();
		}
	}
}
?>