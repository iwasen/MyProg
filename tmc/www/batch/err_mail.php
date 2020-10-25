<?
/******************************************************
' System :�֤���������� �������������ӥ��ץХå�����
' Content:��ã�᡼�����
'******************************************************/

$inc = "../inc";
include("$inc/database.php");
include("$inc/encrypt.php");
include("$inc/batch.php");
include("pop3.php");

define('MAX_ERR_COUNT', 3);

// ��ŵ�ư�����å�
if (!dup_check('lock_err_mail'))
	exit;

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

// ��ã�᡼���������
$count = recv_err_mail();

// �Хå����񤭹���
if ($count > 0) {
	$desc = "��ã�᡼���{$count}��������ޤ�����";
	batch_log('0', __FILE__, $desc);
}
exit;

// ��ã�᡼���������
function recv_err_mail() {
	// �����ƥ�������
	$server = get_system_info('server_pop3');
	$account = explode('/', get_system_info('account_err_mail'), 2);

	// ���顼�᡼�����
	$mail = new mail_pop3();
	$num_msg = $mail->open($server, $account[0], $account[1]);
	if (!$num_msg)
		return 0;

	$count = 0;
	$num_msg = min($num_msg, 500);
	for ($i = 1; $i <= $num_msg; $i++) {
		// �᡼����ʸ����
		$body = $mail->get_body($i);

		$warning = false;

		// ��ã�ʳ������
		if (strpos($body, '(warning-timeout)') !== false)
			$warning = true;

		if (!$warning) {
			// �������줿�᡼��إå������
			$header = imap_rfc822_parse_headers($body);

			// from���ɥ쥹������å�
			if (is_array($header->from)) {
				foreach ($header->from as $from) {
					$pos = strpos($from->mailbox, '-');
					if ($pos) {
						// ���̥����ɤ����ä�����ã�᡼�������Ԥ�
						err_mail_proc(substr($from->mailbox, $pos + 1), $body);
						$count++;
						break;
					}
				}
			}
		}

		// �᡼�륵���о�Υ᡼�����
		$mail->delete($i);
	}
	$mail->close(true);

	return $count;
}

// ���顼�᡼�����
function err_mail_proc($code, &$body) {
	$code2 = substr($code, 1);

	switch ($code{0}) {
	case 'C':
		err_mail_comm_id($code2, $body);
		break;
	case 'L':
		err_mail_login_id($code2);
		break;
	}
}

// ���ߥ�˥������������ID�ˤ�륨�顼�᡼�����
function err_mail_comm_id($code, &$body) {
	// ���ߥ�˥������������ID�����
	$comm_id = decrypt_id($code);
	if (!$comm_id)
		return;

	// ���ߥ�˥���������������ã�ե饰�򥻥å�
	$rec['cml_err_mail_flag'] = 'true';
	db_update('t_comm_log', $rec, "cml_comm_id=$comm_id");

	// �桼��ID�����
	$sql = "SELECT cml_user_id FROM t_comm_log WHERE cml_comm_id=$comm_id AND cml_user_id IS NOT NULL";
	$user_id = db_fetch1($sql);
	if ($user_id) {
		// ��ã��������
		$sql = "SELECT uem_cont_count,uem_comm_id FROM t_user_errmail WHERE uem_user_id=$user_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		if ($nrow) {
			$fetch = pg_fetch_object($result, 0);

			if ($comm_id == $fetch->uem_comm_id)
				return;

			if ($comm_id == get_next_comm_id($user_id, $fetch->uem_comm_id))
				$cont_count = $fetch->uem_cont_count + 1;
			else
				$cont_count = 1;

			// �쥳���ɤ�¸�ߤ���й���
			$rec['uem_total_count'] = 'uem_total_count+1';
			$rec['uem_cont_count'] = $cont_count;
			$rec['uem_comm_id'] = $comm_id;
			$rec['uem_last_date'] = 'current_timestamp';
			db_update('t_user_errmail', $rec, "uem_user_id=$user_id");
		} else {
			// �쥳���ɤ�¸�ߤ��ʤ��������
			$cont_count = 1;
			$rec['uem_user_id'] = $user_id;
			$rec['uem_total_count'] = 1;
			$rec['uem_cont_count'] = 1;
			$rec['uem_comm_id'] = $comm_id;
			$rec['uem_last_date'] = 'current_timestamp';
			db_insert('t_user_errmail', $rec);
		}

		// ������ã�����ã��������֥����ɤ���ã�˥��å�
		if ($cont_count >= MAX_ERR_COUNT)
			set_error_status($user_id);

		// ����ե���������³��졼�����᡼������Vol�ֹ��ᤷ����
		reset_send_mail($user_id, $body);
	}
}

// ���Υ��ߥ�˥������������ID����
function get_next_comm_id($user_id, $comm_id) {
	$sql = "SELECT cml_comm_id FROM t_comm_log WHERE cml_user_id=$user_id AND cml_comm_id>$comm_id ORDER BY cml_comm_id LIMIT 1";
	return db_fetch1($sql);
}

// ������ID�ˤ�륨�顼�᡼�����
function err_mail_login_id($login_id) {
	// �桼��ID�����
	$sql = "SELECT ulg_user_id FROM t_user_login WHERE ulg_login_id='$login_id'";
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	if ($nrow) {
		$user_id = pg_fetch_result($result, 0, 0);

		// ��ã����򥫥����
		$sql = "SELECT uem_user_id FROM t_user_errmail WHERE uem_user_id=$user_id";
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		if ($nrow) {
			// �쥳���ɤ�¸�ߤ���й���
			$rec['uem_total_count'] = 'uem_total_count+1';
			$rec['uem_comm_id'] = $comm_id;
			$rec['uem_last_date'] = 'current_timestamp';
			db_update('t_user_errmail', $rec, "uem_user_id=$user_id");
		} else {
			// �쥳���ɤ�¸�ߤ��ʤ��������
			$rec['uem_user_id'] = $user_id;
			$rec['uem_total_count'] = 1;
			$rec['uem_cont_count'] = 0;
			$rec['uem_comm_id'] = 0;
			$rec['uem_last_date'] = 'current_timestamp';
			db_insert('t_user_errmail', $rec);
		}
	}
}

// ��ã���顼�������
function set_error_status($user_id) {
	$sql = "SELECT COUNT(*) FROM t_user_status WHERE ust_user_id=$user_id AND (ust_status=1 OR ust_status=2)";
	$result = db_exec($sql);
	if (pg_fetch_result($result, 0, 0) != 0) {
		// ���ơ���������ã���顼�˥��å�
		$rec['ust_status'] = 7;
		$rec['ust_handover_date'] = 'current_timestamp';
		db_update('t_user_status', $rec, "ust_user_id=$user_id");

		// �᡼������������ˤ������ʬ�ȥ�󥯤�������
		$sb1 = "SELECT cml_err_mail_flag FROM t_comm_log WHERE cml_user_id=$user_id AND cml_send_recv='S' ORDER BY cml_comm_id LIMIT " . MAX_ERR_COUNT;
		$sql = "SELECT COUNT(*) FROM ($sb1) sb1 WHERE cml_err_mail_flag=false";
		if (db_fetch1($sql, 0) > 0) {
			// ����ʾ����郎����
			$kecchaku = 6;
			$rank = 'E';
		} else {
			// ��󤫤�������ã
			$kecchaku = 3;
			$rank = 'L';
		}

		// �桼���б������Ͽ
		$rec['urs_kecchaku'] = sql_number($kecchaku);
		$rec['urs_rank'] = sql_char($rank);
		$sql = "SELECT COUNT(*) FROM t_user_result WHERE urs_user_id=$user_id";
		$result = db_exec($sql);
		if (pg_fetch_result($result, 0, 0) == 0) {
			$rec['urs_user_id'] = sql_number($user_id);
			db_insert('t_user_result', $rec);
		} else {
			db_update('t_user_result', $rec, "urs_user_id=$user_id");
		}

		$rec['urg_handover_date'] = 'current_timestamp';
		db_update('t_user_regist', $rec, "urg_user_id=$user_id");
	}
}

// ����ե���������³��졼�����᡼������Vol�ֹ��ᤷ����
function reset_send_mail($user_id, &$body) {
	// �ղåإå�����
	$ary = explode("\n", $body);
	if (is_array($ary)) {
		foreach ($ary as $str) {
			// ����ե������᡼�륷�������ֹ�
			$val = header_search('X-Follow-Seq', $str);
			if ($val != '')
				$follow_seq_no = $val;

			// ����ե������᡼��Vol�ֹ�
			$val = header_search('X-Follow-Vol', $str);
			if ($val != '')
				$follow_vol = $val;

			// ��³��졼�����᡼�륷�������ֹ�
			$val = header_search('X-Relation-Seq', $str);
			if ($val != '')
				$relation_seq_no = $val;

			// ��³��졼�����᡼��Vol�ֹ�
			$val = header_search('X-Relation-Vol', $str);
			if ($val != '')
				$relation_vol = $val;
		}

		if ($follow_vol != '')
			reset_follow_mail($user_id, $follow_seq_no, $follow_vol);
		elseif ($relation_vol != '')
			reset_relation_mail($user_id, $relation_seq_no, $relation_vol);
	}
}

// �إå�����
function header_search($header, &$str) {
	$header .= ': ';
	$pos = strpos($str, $header);
	if ($pos !== false)
		return trim(substr($str, $pos + strlen($header)));
}

// ����ե������᡼������Vol�ֹ��ᤷ����
function reset_follow_mail($user_id, $seq_no, $vol_no) {
	if (is_numeric($vol_no)) {
		$sql = "SELECT ust_follow_mail_vol,ust_rel_mail_vol,ust_status FROM t_user_status WHERE ust_user_id=$user_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			if ($fetch->ust_follow_mail_vol == $vol_no) {
				// ���ơ�������2�Ƿ�³��졼�����᡼���̤�����ʤ���֤����ե�������᤹
				if ($fetch->ust_status == 2 && strpos($fetch->ust_rel_mail_vol, '1') === false) {
					$rec['ust_status'] = 1;
					$rec['ust_rel_week'] = 'null';
					$rec['ust_rel_start_date'] = 'null';
				}

				// Vol���᤹
				$rec['ust_follow_mail_vol'] = $vol_no - 1;
				db_update('t_user_status', $rec, "ust_user_id=$user_id");

				// ���顼�������
				if (is_numeric($seq_no)) {
					$rec['fsl_err_num'] = 'fsl_err_num+1';
					db_update('t_follow_send_log', $rec, "fsl_seq_no=$seq_no");

					$rec['fsv_err_num'] = 'fsv_err_num+1';
					db_update('t_follow_send_vol', $rec, "fsv_seq_no=$seq_no AND fsv_vol_no=$vol_no");
				}
			}
		}
	}
}

// ��³��졼�����᡼������Vol�ֹ��ᤷ����
function reset_relation_mail($user_id, $seq_no, $vol_no) {
	if (is_numeric($vol_no)) {
		$sql = "SELECT ust_rel_mail_vol FROM t_user_status WHERE ust_user_id=$user_id";
		$rel_mail_vol = db_fetch1($sql);
		$pos = $vol_no - 1;
		if ($rel_mail_vol != '' && $rel_mail_vol{$pos} == '1') {
			// Vol��̤�����ˤ���
			$rel_mail_vol{$pos} = '0';
			$rec['ust_rel_mail_vol'] = sql_char($rel_mail_vol);
			db_update('t_user_status', $rec, "ust_user_id=$user_id");

			// ���顼�������
			if (is_numeric($seq_no)) {
				$rec['rsl_err_num'] = 'rsl_err_num+1';
				db_update('t_relation_send_log', $rec, "rsl_seq_no=$seq_no");

				$rec['rsv_err_num'] = 'rsv_err_num+1';
				db_update('t_relation_send_vol', $rec, "rsv_seq_no=$seq_no AND rsv_vol_no=$vol_no");
			}
		}
	}
}
?>