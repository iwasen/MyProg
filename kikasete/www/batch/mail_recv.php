<?
/******************************************************
' System :�������ơ�net�Хå�����
' Content:���󥱡��ȥ᡼���������
'******************************************************/

$top = ".";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/encrypt.php");
include("$inc/enquete.php");
include("$inc/enq_mail.php");
include("$inc/point.php");
include("$inc/mail.php");
include("mail_pop3.php");

define('FILE_NAME', __FILE__);

#20071129 PHPver4��5�ˤ�����᡼�륢�󥱡��ȣͣ�������
#�ģ¤�ȿ�Ǥ���ʤ��Ȥ��ä��Զ���äΤ��ᡢ����4��7���ܤ��ɵ�
class answer_class {
	var $sel_no;
	var $free_answer;
	function answer_class() {
		$this->sel_no = array();
	}
}

// ��ŵ�ư�����å�
if (!dup_check('lock_mail_recv'))
	exit;

// ����¹Ի��֡ʣ���ʬ��
set_time_limit(600);

// �᡼���������
recv_enquete();
recv_my_monitor();
exit;

// ���󥱡��ȥ᡼���������
function recv_enquete() {
	// ��������
	$fetch = get_system_info("sy_pop_server,sy_enq_account,sy_enq_pwd");
	recv_enquete_sub($fetch->sy_pop_server, $fetch->sy_enq_account, $fetch->sy_enq_pwd);

	// �Ϥ��᡼��
	$fetch = get_system_info("sy_pop_server,sy_haimail_account,sy_haimail_pwd");
	recv_enquete_sub($fetch->sy_pop_server, $fetch->sy_haimail_account, $fetch->sy_haimail_pwd);
}

function recv_enquete_sub($pop_server, $account, $password) {

	// �᡼�����
	$mail = new mail_pop3();
	$num_msg = $mail->open($pop_server, $account, $password);
	if (!$num_msg)
		return 0;

	// ���������
	$current_time = time();

	db_begin_trans();

	// ���᡼�뤴�ȤΥ롼��
	$num_msg = min($num_msg, 100);
	for ($i = 1; $i <= $num_msg; $i++) {
		// �إå����Τ����
		$headers = $mail->get_all_headers($i);

		// �إå���Ĺ����10KB��Ķ���Ƥ��뤫�������ʥ����ɤ��ޤޤ�Ƥ�����Ͻ������ʤ�
		if (strlen($headers) < 10000 && mb_detect_encoding($headers, 'EUC-JP', true)) {
			// �إå�����ʸ�����
			$header = $mail->get_header($i);
			$body = $mail->get_body($i);

/*************************************
			$skip = false;
			$ary = explode("\n", $headers);
			if (is_array($ary)) {
				foreach ($ary as $line) {
					$line = trim($line);
					if (strncasecmp('Message-ID:', $line, 11) == 0) {
						$sql = "SELECT * FROM l_recv_mail WHERE rm_header LIKE '%$line%'";
						$result = db_exec($sql);
						if (pg_numrows($result))
							$skip = true;
						break;
					}
				}
			}
			if ($skip) {
				$mail->delete($i);
				echo '0';
				continue;
			}
			echo '1';
/*************************************/

			check_kanji($headers);
			check_kanji($body);

			if (mb_detect_encoding($body, 'EUC-JP', true)) {
				$enquete_id = '';
				$monitor_id = '';

				// To:���ɥ쥹���饭�������ɼ���
				$keycd = '';
				if (is_array($header->to)) {
					foreach ($header->to as $to) {
						if (eregi('-([^-]+)$', $to->mailbox, $regs)) {
							$keycd = trim($regs[1]);
							break;
						}
					}
				}

				if ($keycd != '') {
					// ��������������Received:������Ф����̵����и������
					$received = get_received($headers);
					if ($received != '') {
						$enq_date = strtotime($received);
						if ($enq_date == -1)
							$enq_date = $current_time;
					} else
						$enq_date = $current_time;

					// ���������ɤ��饢�󥱡���ID�ȥ�˥���ID������
					decrypt_param($keycd, &$enquete_id, &$monitor_id);
					if ($enquete_id != '' && $monitor_id != '') {
						// ���󥱡��ȥ᡼�����
						if (enq_mail($enquete_id, $monitor_id, $enq_date, $body))
							$count++;
					}
				}

				// �����᡼���
				save_recv_log($enquete_id, $monitor_id, $headers, $body);

				// ���顼�����å�
				if (($err_msg = db_errormessage()) != '')
					break;
			}
		}

		// �᡼�륵���о�Υ᡼�����
		$mail->delete($i);
	}

	if ($err_msg == '') {
		// ���顼̵���ʤ饳�ߥåȤ��ƥ᡼����
		db_commit_trans();
		$mail->close(true);

		// �Хå����񤭹���
		if ($count > 0) {
			$desc = "���󥱡��ȥ᡼���{$count}��������ޤ�����";
			batch_log('0', FILE_NAME, $desc);
		}
	} else {
		// ���顼ͭ��ʤ����Хå����ƥ᡼���ݻ�
		db_rollback();
		$mail->close(false);

		$desc = "���󥱡��ȥ᡼����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
		batch_log(9, FILE_NAME, $desc);
	}
}

// Received:�إå������ռ���
function get_received($header) {
	$received = '';
	$lines = explode("\n", $header);
	if (is_array($lines)) {
		foreach ($lines as $line) {
			if ($received == '' && eregi('^Received:.*; (.+) \+', $line, $regs)) {
				$received = trim($regs[1]);
				break;
			}
		}
	}
	return $received;
}

// ���󥱡��ȥ᡼�����
function enq_mail($enquete_id, $monitor_id, $enq_date, &$body) {
	// �������󥱡��ȼ����ʲ�����ʤ����ä�̤������
	$sql = "SELECT en_end_date,en_point,en_job_id"
			. " FROM t_enquete"
			. " WHERE en_enquete_id=$enquete_id AND (en_status=5 OR en_status=6) AND en_enq_type=1"
			. " AND EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id)"
			. " AND NOT EXISTS (SELECT * FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id)";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// ���ڤ�����å�
		if ($enq_date > sql_time($fetch->en_end_date))
			return;

		// ���󥱡��Ȳ�������
		$ok = enq_analyze($enquete_id, $monitor_id, date('Y/m/d H:i:s', $enq_date), $body);

		// �ݥ������Ϳ
		if ($ok) {
			monitor_point($monitor_id, PT_ENQUETE, $fetch->en_point, null, null, $enquete_id);

			if ($fetch->en_job_id) {
				$sql = "UPDATE t_job SET jb_enq_count=jb_enq_count+1 WHERE jb_job_id=$fetch->en_job_id";
				db_exec($sql);

				response_job($fetch->en_job_id, $monitor_id);
			}
		}
	}
	return $ok;
}

// ���󥱡��Ȳ�������
function enq_analyze($enquete_id, $monitor_id, $enq_date, &$body) {
	$enquete = new enquete_class;
	$enquete->read_db($enquete_id);

	$ary = explode("\n", $body);
	if (is_array($ary)) {
		foreach ($ary as $line) {
			// �����ֹ����
			$no = get_qa_no($line, EM_QUESTION);
			if ($no) {
				if (array_key_exists2($no, $enquete->question)) {
					$question_no = $no;
					$in_answer = false;
				}
			}

			// �����ֹ����
			$no = get_qa_no($line, EM_ANSWER);
			if ($no > 0 && $no == $question_no && !array_key_exists2($question_no, $answer_arry)) {
				$question = &$enquete->question[$question_no];
				$answer_arry[$question_no] = new answer_class;
				$answer = &$answer_arry[$question_no];
				$in_answer = true;
				$in_fa = false;
				$fa_no = 0;
				$in_fa2 = false;
				if (is_array($question->sel_text))
					$last_sno = end(array_keys($question->sel_text));
			}

			// ��ͳ������������å�
			if (!$in_answer && $question_no && mb_strpos($line, EM_FA_START) !== false && !array_key_exists2($question_no, $answer_arry)) {
				$question = &$enquete->question[$question_no];
				$answer_arry[$question_no] = new answer_class;
				$answer = &$answer_arry[$question_no];
				$in_answer = true;
				$in_fa = false;
				$fa_no = 0;
			}

			// ��������
			if ($in_answer) {
				if ($in_fa) {
					if (mb_strpos($line, EM_FA_END) !== false) {
						$in_fa  = false;
						$in_answer = false;
					} else {
						$fa = trim(ltrim($line, ' >'));
						if ($fa != '')
							$answer->free_answer[0] .= "$fa\n";
					}
				} elseif ($in_fa2) {
						$fa = trim(ltrim($line, ' >'));
						if (($p = mb_strpos($fa, '��')) !== false) {
							$answer->free_answer[$fa_no] .= trim(mb_convert_kana(mb_substr($fa, 0, $p), 's'));
							$in_fa2 = false;
						} else
							$answer->free_answer[$fa_no] .= trim(mb_convert_kana($fa, 's'));
				} else {
					switch ($question->question_type) {
					case 1:   // ñ������
						if (count($answer->sel_no) == 0) {
							$sel_no = get_sel_no($question, $line);
							if ($sel_no)
								$answer->sel_no[0] = $sel_no[0];
						}
						if ($question->fa_flag == 't') {
							$fa_no = $last_sno;
							$fa = get_fa($line, $cont);
							if ($fa != '')
								$answer->free_answer[$fa_no] = $fa;
							if ($cont)
								$in_fa2 = true;
						}
						break;
					case 2:   // ʣ������
						$sel_no = get_sel_no($question, $line);
						if ($sel_no)
							$answer->sel_no = array_merge($answer->sel_no, $sel_no);
						if ($question->fa_flag == 't') {
							$fa_no = $last_sno;
							$fa = get_fa($line, $cont);
							if ($fa != '')
								$answer->free_answer[$fa_no] = $fa;
							if ($cont)
								$in_fa2 = true;
						}
						break;
					case 3:   // ��ͳ����
						if (mb_strpos($line, EM_FA_START) !== false)
							$in_fa = true;
						elseif (mb_strpos($line, '��') !== false) {
							$answer->free_answer[++$fa_no] = get_fa($line, $cont);
							if ($cont)
								$in_fa2 = true;
						}
						break;
					}
				}
			}
		}

		if (is_array($answer_arry)) {
			unset($answer);   // PHP�Х��к�
			foreach ($answer_arry as $qno => $answer) {
				if (is_array($answer->sel_no)) {
					foreach (array_unique($answer->sel_no) as $sno) {
						$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
								. " VALUES ($enquete_id,$qno,$sno,$monitor_id," . sql_char($answer->free_answer[$sno]) . ")";
						db_exec($sql);
						$answer->free_answer[$sno] = '';
						$ok = true;
					}
				}
				if (is_array($answer->free_answer)) {
					foreach ($answer->free_answer as $sno => $fa) {
						if ($fa != '') {
							$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
									. " VALUES ($enquete_id,$qno,$sno,$monitor_id," . sql_char($fa) . ")";
							db_exec($sql);
							$ok = true;
						}
					}
				}
			}

			if ($ok) {
				$sql = "INSERT INTO t_answer (an_enquete_id,an_monitor_id,an_date) VALUES ($enquete_id,$monitor_id," . sql_date($enq_date) . ")";
				db_exec($sql);
			}
		}
	}
	return $ok;
}

// ��������ֹ����
function get_qa_no(&$s, $em) {
	if (($p1 = mb_strpos($s, $em)) !== false) {
		if (($p2 = mb_strpos($s, '��', $p1)) !== false) {
			$emlen = mb_strlen($em);
			$len = $p2 - $p1 - $emlen;
			if ($len > 0 && $len < 5)
				$n = (int)mb_convert_kana(mb_substr($s, $p1 + $emlen, $len), 'n');
		}
	}
	return $n;
}

// �����ֹ����
function get_sel_no(&$question, &$line) {
	$s = mb_convert_kana($line, 'ns');
	$len = mb_strlen($s);
	for ($i = 0; $i < $len; $i++) {
		$c = mb_substr($s, $i, 1);
		if ($c == '��') {
			$in_sel_no = true;
			$temp = '';
		} elseif ($c == '��') {
			if ($temp != '') {
				$temp = (int)$temp;
				if (array_key_exists2($temp, $question->sel_text))
					$sel_no[] = $temp;
			}
			$in_sel_no = false;
		} elseif (strstr('0123456789', $c)) {
			if ($in_sel_no)
				$temp .= $c;
		} elseif ($c == ',') {
			if ($temp != '') {
				$temp = (int)$temp;
				if (array_key_exists2($temp, $question->sel_text))
					$sel_no[] = $temp;
				$temp = '';
			}
		} elseif ($c != ' ') {
			$in_sel_no = false;
		}
	}
	return $sel_no;
}

// FA��������
function get_fa(&$s, &$cont) {
	$p2 = 0;
	$cont = false;
	while (true) {
		if (($p1 = mb_strpos($s, '��', $p2)) > 0) {
			$cont = false;
			if (($p2 = mb_strpos($s, '��', $p1)) === false) {
				$p2 = mb_strlen($s);
				$cont = true;
			}
			if ($fa != '')
				$fa .= ',';
			$fa .= trim(mb_convert_kana(mb_substr($s, $p1 + 1, $p2 - $p1 - 1), 's'));
		} else
			break;
	}
	return $fa;
}

// ���󥭡�¸�ߥ����å�
function array_key_exists2(&$key, &$ary) {
	if (is_array($ary))
		return array_key_exists($key, $ary);
	else
		return false;
}

// �����᡼���
function save_recv_log($enquete_id, $monitor_id, &$header, &$body) {
	$sql = sprintf("INSERT INTO l_recv_mail (rm_enquete_id,rm_monitor_id,rm_header,rm_body) VALUES (%s,%s,%s,%s)",
			sql_number($enquete_id),
			sql_number($monitor_id),
			sql_char($header),
			sql_char($body));
	db_exec($sql);
}

// �쥹�ݥ󥹥����
function response_job($job_id, $monitor_id) {
	$sql = "SELECT jb_enquete_id,jb_send_timing,jb_send_timing_h,jb_send_timing_d,jb_enquete_id,jb_start_date,jb_sql"
			. " FROM t_job JOIN t_enquete ON en_enquete_id=jb_enquete_id"
			. " WHERE jb_parent_job_id=$job_id AND jb_job_type=1 AND en_status=5";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->jb_enquete_id;
		$where = $fetch->jb_sql;
		if ($where != '') {
			$sql = "SELECT mn_monitor_id FROM t_monitor WHERE mn_monitor_id=$monitor_id AND $where"
					. " AND NOT EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id)";
			$result2 = db_exec($sql);
			if (pg_numrows($result2)) {
				switch ($fetch->jb_send_timing) {
				case 1:
					$send_date = date('Y/m/d H:i:s', time() + $fetch->jb_send_timing_h * 60 * 60);
					break;
				case 2:
					$send_date = date('Y/m/d H:i:s', time() + $fetch->jb_send_timing_d * 60 * 60 * 24);
					break;
				default:
					$send_date = 'now';
					break;
				}

				$sql = "INSERT INTO t_enquete_list (el_enquete_id,el_monitor_id,el_send_date)"
						. " VALUES ($enquete_id,$monitor_id,'$send_date')";
				db_exec($sql);
			}
		}
	}
}

// My��˥����ֿ��᡼���������
function recv_my_monitor() {
	$fetch = get_system_info("sy_pop_server,sy_mym_account,sy_mym_pwd,sy_mym_point");

	if ($fetch->sy_mym_account == '')
		return;

	// �᡼�����
	$mail = new mail_pop3();
	$num_msg = $mail->open($fetch->sy_pop_server, $fetch->sy_mym_account, $fetch->sy_mym_pwd);
	if (!$num_msg)
		return 0;

	// ���������
	$current_time = time();

	// �ֿ��ݥ���ȼ���
	$point = $fetch->sy_mym_point;

	db_begin_trans();

	// ���᡼�뤴�ȤΥ롼��
	for ($i = 1; $i <= $num_msg; $i++) {
		// �إå�����ʸ�����
		$header = $mail->get_header($i);
		$headers = $mail->get_all_headers($i);
		$body = $mail->get_body($i);

		$msg_id = '';
		$monitor_id = '';

		// To:���ɥ쥹���饭�������ɼ���
		$keycd = '';
		if (is_array($header->to)) {
			foreach ($header->to as $to) {
				if (eregi('-(.+)', $to->mailbox, $regs)) {
					$keycd = trim($regs[1]);
					break;
				}
			}
		}

		if ($keycd != '') {
			// ��������������Received:������Ф����̵����и������
			$received = get_received($headers);
			if ($received != '') {
				$recv_date = strtotime($received);
				if ($recv_date == -1)
					$recv_date = $current_time;
			} else
				$recv_date = $current_time;

			// ���������ɤ�������ID�ȥ�˥���ID������
			$msg_id = decrypt_number(substr($keycd, 0, 8));
			$monitor_id = decrypt_number(substr($keycd, 8, 7));
			if ($msg_id != '' && $monitor_id != '') {
				// �ֿ��᡼�����
				$subject = decode_mime_header($header->subject);
				if (my_monitor_mail($msg_id, $monitor_id, $recv_date, $subject, $body, $point))
					$count++;
			}

			if (($err_msg = db_errormessage()) != '')
				break;
		}

		// �᡼�륵���о�Υ᡼�����
		$mail->delete($i);
	}

	if ($err_msg == '') {
		// ���顼̵���ʤ饳�ߥåȤ��ƥ᡼����
		db_commit_trans();
		$mail->close(true);

		// �Хå����񤭹���
		if ($count > 0) {
			$desc = "My��˥����ֿ��᡼���{$count}��������ޤ�����";
			batch_log('0', FILE_NAME, $desc);
		}
	} else {
		// ���顼ͭ��ʤ����Хå����ƥ᡼���ݻ�
		db_rollback();
		$mail->close(false);

		$desc = "My��˥����ֿ��᡼����������ǥ��顼��ȯ�����ޤ�����($err_msg)";
		batch_log(9, FILE_NAME, $desc);
	}
}

// mime�إå��ǥ�����
function decode_mime_header($header) {
	$s = imap_mime_header_decode($header);
	if ($s[0]->charset == 'default')
		return $s[0]->text;
	else
		return mb_convert_encoding($s[0]->text, 'EUC-JP', $s[0]->charset);
}

// �ֿ��᡼�����
function my_monitor_mail($msg_id, $monitor_id, $recv_date, &$subject, &$body, $point) {
	$sql = "SELECT mm_end_date"
			. " FROM t_my_monitor_msg"
			. " JOIN t_my_monitor ON mm_room_id=rm_room_id"
			. " WHERE rm_msg_id=$msg_id AND (mm_status=3 OR mm_status=4)";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		// ���ڤ�����å�
		if ($recv_date > sql_time($fetch->mm_end_date))
			return;

		// �ơ��֥����¸
		$sql = sprintf("INSERT INTO t_my_monitor_recv (rr_msg_id,rr_monitor_id,rr_title,rr_contents,rr_date)"
				. " VALUES (%s,%s,%s,%s,%s)",
				sql_number($msg_id),
				sql_number($monitor_id),
				sql_char($subject),
				sql_char($body),
				sql_date(date('Y/m/d H:i:s', $recv_date)));
		db_exec($sql);

		$sql = "SELECT mr_mail_addr,mm_room_name"
				. " FROM t_my_monitor_msg"
				. " JOIN t_my_monitor ON mm_room_id=rm_room_id"
				. " JOIN t_marketer ON mr_marketer_id=mm_marketer_id"
				. " WHERE rm_msg_id=$msg_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$mail_addr = $fetch->mr_mail_addr;
			$room_name = $fetch->mm_room_name;

			$sql = "SELECT mn_name1,mn_name2 FROM t_monitor WHERE mn_monitor_id=$monitor_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$monitor_name = "$fetch->mn_name1 $fetch->mn_name2";

				// �ֿ����Υ᡼������
				get_mail_template('mym_mk', $subject, $from, $cc, $bcc, $repty_to, $body);
				$body = str_replace('%ROOM_NAME%', $room_name, $body);
				$body = str_replace('%MEMBER_NAME%', $monitor_name, $body);
				$body = str_replace('%RECV_DATE%', date('Y/m/d H:i:s', $recv_date), $body);
				send_mail($subject, $mail_addr, $from, $body);

				// ���Τߥݥ������Ϳ
				$sql = "SELECT * FROM t_my_monitor_recv WHERE rr_msg_id=$msg_id AND rr_monitor_id=$monitor_id";
				$result = db_exec($sql);
				if (pg_numrows($result) == 1)
					monitor_point($monitor_id, PT_MY_MONITOR, $point);

				$ok = true;
			}
		}
	}

	return $ok;
}

function check_kanji(&$str) {
	$kanji = 0;
	$len = strlen($str);
	for ($i = 0; $i < $len; $i++) {
		$c = $str{$i};
		if ($kanji == 0) {
			if ($c >= "\xa1" && $c <= "\xfe") {
				$kanji = 1;
			} elseif ($c == "\x8e" || $c == "\x93" || $c == "\x94") {
				$kanji = 1;
			} elseif ($c == "\x8f") {
				$kanji = 2;
			} elseif ($c >= "\x80" && $c <= "\xa0") {
				$str{$i} = " ";
			} elseif ($c == "\x00" || $c == "\xff") {
				$str{$i} = " ";
			}
			$p = $i;
		} else {
			if ($c >= "\x00" && $c <= "\x7f") {
				for ($j = $p; $j < $i; $j++) {
					$str{$j} = " ";
				}
				$kanji = 0;
			} else {
				$kanji--;
			}
		}
	}

	if ($kanji) {
		for ($j = $p; $j < $len; $j++) {
			$str{$j} = " ";
		}
	}
}
?>