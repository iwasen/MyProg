<?
/******************************************************
' System :�֤���������� �������������ӥ��ץХå�����
' Content:��³��졼�����᡼����������
'******************************************************/

$inc = "../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("$inc/rel_mail.php");

define('LAST_SEND_WEEK', 48);		// �ǽ��᡼�����������ޤǤν�
define('FOLLOW_END_WEEK', 2);		// �ǽ��᡼�뤫����M�ǰ����Ѥ��ޤǤν�

// ��ŵ�ư�����å�
if (!dup_check('lock_relation_mail'))
	exit;

// ����¹Ի��֡ʣ����֡�
set_time_limit(3600);

send_relation_mail();
follow_end();

exit;

// ��³��졼�����᡼������
function send_relation_mail() {
	// �ǽ�����������
	$sql = "SELECT CAST(rll_last_date AS DATE) FROM t_relation_last";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$last_date = pg_fetch_result($result, 0, 0);
		$update = true;
	}

	// �ۿ�̵ͭȽ��
	$sql = "SELECT CAST(rld_send_date AS DATE) FROM t_relation_date WHERE rld_send_date<=current_timestamp";
	if ($last_date != '')
		$sql .= " AND CAST(rld_send_date AS DATE)>" . sql_date($last_date);
	$sql .= " ORDER BY rld_send_date DESC LIMIT 1";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		return;
	$send_date = pg_fetch_result($result, 0, 0);

	// ��������������������
	$sql = sprintf("SELECT COUNT(*) FROM t_relation_date WHERE to_char(rld_send_date, 'YYYYMM')=%s AND rld_send_date<%s",
			sql_char(date('Ym', sql_time($send_date))),
			sql_date($send_date));
	$result = db_exec($sql);
	$rel_week = (pg_fetch_result($result, 0, 0) + 1) % 2;

	// ��³��졼�����᡼���������򥷡������ֹ����
	$sql = "SELECT COALESCE(MAX(rsl_seq_no),0)+1 FROM t_relation_send_log";
	$seq_no = db_fetch1($sql);

	db_begin_trans();

	// �ǽ��ۿ������򹹿�
	$rec['rll_last_date'] = 'current_timestamp';
	if ($update)
		db_update('t_relation_last', $rec, 'true');
	else
		db_insert('t_relation_last', $rec);

	// from���ɥ쥹����
	$from_addr = get_system_info('addr_rel_mail');

	// �᡼�������ơ��֥�˥��å�
	$send_mail_id = send_mail_data('��³��졼�����᡼��', $from_addr, '', '%CONTENTS%', '');

	// ���������ꥢ
	$send_count = 0;

	// �ۿ��оݼ����
	$sql = "SELECT ust_user_id,COALESCE(ust_rel_mail_vol,repeat('0'," . MAX_RELATION_MAIL . ")) AS ust_rel_mail_vol,ust_follow_end_date,ups_mail_addr,cast(urg_follow_date as date) + interval '" . LAST_SEND_WEEK . " week'<=current_date AS end_flag"
			. " FROM t_user_status"
			. " JOIN t_user_personal ON ups_user_id=ust_user_id"
			. " JOIN t_user_manage ON umg_user_id=ust_user_id"
			. " JOIN t_user_regist ON urg_user_id=ust_user_id"
			. " WHERE ust_status=2 AND ust_rel_stop_flag=false AND ust_rel_start_date<=current_date AND ust_rel_week=$rel_week"
			. " AND NOT EXISTS (SELECT * FROM t_relation_jogai WHERE rlj_send_date='$send_date' AND rlj_dealer_cd=umg_dealer_cd)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->ust_user_id;
		$rel_mail_vol = $fetch->ust_rel_mail_vol;

		$pos = strpos($rel_mail_vol, '0');
		if ($pos === false)
			continue;
		$vol_no = $pos + 1;

		// �᡼�뼱��ID����
		$comm_id = get_next_seq('t_comm_log', 'cml_comm_id');
		$mail_id = '-C' . encrypt_id($comm_id);

		// �ե�������λ��å���������
		if ($fetch->end_flag == DBTRUE) {
			$sql = "SELECT cem_message FROM t_comm_end_msg WHERE cem_msg_id=1";
			$follow_end_msg = db_fetch1($sql);
		} else
			$follow_end_msg = '';

		if (!get_relation_mail($user_id, $vol_no, $mail_id, $subject, $contents, $from_name, $from_addr, $follow_end_msg))
			continue;

		// ���ߥ�˥�����������ƥơ��֥����¸
		$rec['cmc_comm_type'] = 3;
		$rec['cmc_subject'] = sql_char($subject);
		$rec['cmc_body'] = sql_char($contents);
		$rec['cmc_vol_no'] = sql_number($vol_no);
		db_insert('t_comm_contents', $rec);
		$contents_id = get_current_seq('t_comm_contents', 'cmc_contents_id');

		// ���ߥ�˥��������������ɲ�
		$rec['cml_comm_id'] = $comm_id;
		$rec['cml_thread_comm_id'] = "currval('t_comm_log_cml_comm_id_seq')";
		$rec['cml_contents_id'] = $contents_id;
		$rec['cml_user_id'] = $user_id;
		$rec['cml_send_recv'] = sql_char('S');
		$rec['cml_answer_flag'] = 'false';
		$rec['cml_from_addr'] = sql_char($from_addr);
		$rec['cml_from_name'] = sql_char($from_name);
		$rec['cml_to_addr'] = sql_char($fetch->ups_mail_addr);
		db_insert('t_comm_log', $rec);

		// ������ꥹ�Ƚ񤭹���
		$sub_seq = $i + 1;
		$mail_header = "X-Relation-Seq: $seq_no\nX-Relation-Vol: $vol_no";
		send_mail_list($send_mail_id, $fetch->ups_mail_addr, $sub_seq, $mail_header);

		// �����ѿ�
		send_mail_embed($send_mail_id, $sub_seq, '%%FROM_ADDR%%', "$from_name <$from_addr>");
		send_mail_embed($send_mail_id, $sub_seq, '%%SUBJECT%%', $subject);
		send_mail_embed($send_mail_id, $sub_seq, '%CONTENTS%', $contents);

		// ��졼�����λ�����å�
		if ($fetch->end_flag == DBTRUE) {
			// �᡼���ۿ����
			if ($fetch->ust_follow_end_date == null)
				$rec['ust_follow_end_date'] = 'current_date';
			$rec['ust_rel_stop_flag'] = 'true';
		}

		// ��³��졼�����᡼��VolNo.����
		$rel_mail_vol{$pos} = '1';
		$rec['ust_rel_mail_vol'] = sql_char($rel_mail_vol);
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// Vol���ۿ����������
		$send_vol[$vol_no]++;

		$send_count++;
	}

	// ���ơ�������������
	send_mail_ok($send_mail_id);

	// ��³��졼�����᡼���ۿ����񤭹���
	relation_send_log($seq_no, $send_count, $send_vol);

	// ���顼�����å�
	$err_msg = db_errormessage();
	if ($err_msg) {
		db_rollback();

		// �Хå����񤭹��ߡʥ��顼��
		$desc = "��³��졼�����᡼�����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
		batch_log(9, __FILE__, $desc);
	} else {
		// �Хå����񤭹��ߡ������
		$desc = "��³��졼�����᡼���{$send_count}���������ޤ�����";
		batch_log(0, __FILE__, $desc);

		db_commit_trans();
	}
}

// ��³��졼�����᡼���ۿ����񤭹���
function relation_send_log($seq_no, $send_count, &$send_vol) {
	// ��³��졼�����᡼���ۿ�����
	$rec['rsl_seq_no'] = sql_number($seq_no);
	$rec['rsl_send_num'] = sql_number($send_count);
	$rec['rsl_err_num'] = 0;
	db_insert('t_relation_send_log', $rec);

	// ��³��졼�����᡼��Vol���ۿ�����
	if (is_array($send_vol)) {
		foreach ($send_vol as $vol_no => $send_num) {
			$rec['rsv_seq_no'] = sql_number($seq_no);
			$rec['rsv_vol_no'] = sql_number($vol_no);
			$rec['rsv_send_num'] = sql_number($send_num);
			$rec['rsv_err_num'] = 0;
			db_insert('t_relation_send_vol', $rec);
		}
	}
}

// ��졼�����λ����
function follow_end() {
	db_begin_trans();

	$sql = "SELECT ust_user_id,urs_user_id,urs_commant1,urs_commant2,urs_commant3,urs_commant4"
			. " FROM t_user_status"
			. " LEFT JOIN t_user_result ON urs_user_id=ust_user_id"
			. " WHERE ust_status=2 AND ust_follow_end_date + interval '" . FOLLOW_END_WEEK . " week'<=current_date"
			. " AND NOT EXISTS (SELECT * FROM t_comm_log WHERE cml_user_id=ust_user_id AND cml_send_recv='R' AND cml_date>=ust_follow_end_date)";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$user_id = $fetch->ust_user_id;

		// ���ơ���������Ѥ��Ѥߤ˥��å�
		$rec['ust_status'] = 3;
		$rec['ust_handover_date'] = 'current_timestamp';
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// �����ȥ��å�
		if ($fetch->urs_commant1 == '' && $fetch->urs_commant2 == '' && $fetch->urs_commant3 == '' && $fetch->urs_commant4 == '') {
			$rec['urs_commant1'] = sql_char('��ǯ�֥ե����᡼����������ޤ��������ֿ���ĺ���Ƥ���ޤ���');
			$rec['urs_commant2'] = sql_char('����Τ�Ϣ��ε�����ĺ���Ƥ��ʤ������ͤʤΤǡ�');
			$rec['urs_commant3'] = sql_char('DM�����äʤɤˤ��Ķȳ�ư�ϵ�CS�ˤʤ��ǽ��������ޤ���');
			$rec['urs_commant4'] = sql_char('����դ���������');
		}

		// �桼���б������Ͽ
		$rec['urs_kecchaku'] = sql_number(6);
		$rec['urs_rank'] = sql_char('M');
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