<?
/******************************************************
' System :�������ơ�net�Хå�����
' Content:���󥱡��ȳ��Ͻ�λ����
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/batch_log.php");
include("$inc/my_enquete.php");
include("$inc/my_partner.php");
include("$inc/search.php");
include("$inc/pro_search.php");
include("$inc/pro_enquete.php");
include("$inc/enquete.php");
include("$inc/mail.php");
include("$inc/enq_mail.php");
include("$inc/enq_send.php");
include("$inc/encrypt.php");
include("$inc/point.php");
include("$inc/pro_enq_target.php");
include("mail_report.php");

define('FILE_NAME', __FILE__);
define('DAY_SEC', 60 * 60 * 24);

// ����¹Ի��֡ʣ�ʬ��
set_time_limit(180);

start_enquete();
pass_enquete();
end_enquete();
exit;

// ���󥱡��ȳ��Ͻ���
function start_enquete() {
	$sql = "SELECT en_enquete_id,en_enq_kind FROM t_enquete WHERE en_status=4 AND en_start_date<=CURRENT_TIMESTAMP";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->en_enquete_id;

		db_begin_trans();

		switch ($fetch->en_enq_kind) {
		case 1:   // �����ͥ��󥱡���
			start_free_enquete($enquete_id);
			break;
		case 2:   // My���󥱡���
			start_my_enquete($enquete_id);
			break;
		case 3:   // �ץ������ȥ��󥱡���
			start_pjt_enquete($enquete_id);
			break;
		case 4:   // My�ѡ��ȥʡ����󥱡���
			start_my_partner($enquete_id);
			break;
		case 5:     // �ե�����ǥ��󥰥��󥱡���
		case 6:     // Pro�ܥ��󥱡���
			start_pro_enquete($enquete_id);
			break;
		}

		$sql = "UPDATE t_enquete SET en_status=5 WHERE en_enquete_id=$enquete_id";
		db_exec($sql);

		batch_log(0, FILE_NAME, "���󥱡���(ID=$enquete_id)�򳫻Ϥ��ޤ�����");

		db_commit_trans();
	}
}

// �����ͥ��󥱡��ȳ��Ͻ���
function start_free_enquete($enquete_id) {
	$sql = "UPDATE t_monitor_point SET mp_enq_chance=mp_enq_chance+1,mp_enq_history=SUBSTR('0'||mp_enq_history,1,10)"
			. " WHERE EXISTS (SELECT * FROM t_monitor WHERE mn_monitor_id=mp_monitor_id AND mn_status<>9)";
	db_exec($sql);

	// �����ͥơ��֥����
	$sql = "SELECT COUNT(*) FROM t_enquete_sum WHERE em_enquete_id=$enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0";
	if (db_fetch1($sql) == 0) {
		$sql = "INSERT INTO t_enquete_sum (em_enquete_id,em_question_no,em_sum_kind,em_sel_no,em_sum) VALUES($enquete_id,0,0,0,0)";
		db_exec($sql);
	}

	for ($i = 1; $i <= 5; $i++) {
		$sql = "INSERT INTO t_enquete_sum (em_enquete_id,em_question_no,em_sum_kind,em_sel_no,em_sum)"
				. " SELECT eq_enquete_id,eq_question_no,$i,0,0"
				. " FROM t_enq_question"
				. " WHERE eq_enquete_id=$enquete_id AND NOT EXISTS"
				. " (SELECT * FROM t_enquete_sum"
				. " WHERE em_enquete_id=eq_enquete_id AND em_question_no=eq_question_no AND em_sum_kind=$i AND em_sel_no=0)";
		db_exec($sql);

		$sql = "INSERT INTO t_enquete_sum (em_enquete_id,em_question_no,em_sum_kind,em_sel_no,em_sum)"
				. " SELECT es_enquete_id,es_question_no,$i,es_sel_no,0"
				. " FROM t_enq_select"
				. " WHERE es_enquete_id=$enquete_id AND NOT EXISTS"
				. " (SELECT * FROM t_enquete_sum"
				. " WHERE em_enquete_id=es_enquete_id AND em_question_no=es_question_no AND em_sum_kind=$i AND em_sel_no=es_sel_no)";
		db_exec($sql);
	}
}

// My���󥱡��ȳ��Ͻ���
function start_my_enquete($enquete_id) {
	$sql = "SELECT me_marketer_id,me_my_enq_no FROM t_my_enquete WHERE me_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$marketer_id = $fetch->me_marketer_id;
		$my_enq_no = $fetch->me_my_enq_no;

		// My���󥱡��Ⱦ����ɤ߹���
		$myenq = new my_enquete_class;
		$myenq->read_db($marketer_id, $my_enq_no);
		$enquete = &$myenq->enquete;
		$search = &$myenq->search;
		$cell = &$myenq->cell;

		// ���󥱡��ȥ᡼������
		$mail_text = make_enquete_mail($enquete, $myenq->mail_header, $myenq->mail_contents, $myenq->mail_footer, $enquete->point);

		// ���󥱡����оݼ����
		$send_count = 0;
		$where = $search->make_sql();
		if ($cell->cell_option == 2) {
			// �������ͭ��
			$member_ary = array_chunk($cell->get_search_list($where, $myenq->send_num), 5000);
			if (is_array($member_ary)) {
				foreach ($member_ary as $member_ary2) {
					$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE mn_monitor_id IN (" . join(',', $member_ary2) . ")";

					// ��˥����˥��󥱡��ȥ᡼���ۿ�
					$send_count += send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $myenq->mail_title, $mail_text);
				}
			}
		} else {
			// �������̵��
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE $where ORDER BY RANDOM() LIMIT $myenq->send_num";

			// ��˥����˥��󥱡��ȥ᡼���ۿ�
			$send_count = send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $myenq->mail_title, $mail_text);
		}

		// ���󥱡����оݼԥ�˥���°������¸
		save_enq_monitor_data($enquete_id);

		// ��̳�ɤ�������λ����
		report_admin('�ͣ����󥱡���', $myenq->send_num, $nrow, $myenq->mail_title, $mail_text);

		// �ޡ���������My���󥱡��ȳ�������
		get_mail_template('mye_mk2', $subject, $from, $cc, $bcc, $repty_to, $body);
		if ($subject != '') {
			$sql = "SELECT mr_mail_addr,mr_name1,mr_kinmu_name FROM t_marketer WHERE mr_marketer_id=$marketer_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$body = str_replace('%MARKETER_NAME%', $fetch->mr_name1, $body);
				$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
				$body = str_replace('%TITLE%', $enquete->title, $body);
				$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
				$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
				$body = str_replace('%ENQ_TOTAL%', $enquete->get_question_num(), $body);
				$body = str_replace('%COMPANY_NAME%', $fetch->mr_kinmu_name, $body);
				$body = str_replace('%SEND_NUM%', $myenq->send_num, $body);
				$body = str_replace('%CELL_FLAG%', $myenq->cell->cell_option == 1 ? '�ʤ�' : '����', $body);
				$body = str_replace('%CELL_NUM%', $myenq->cell->cell_num, $body);
				$myenq->get_notify_mail_addr($to, $cc);
				send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
			}
		}

		// ���󥱡��ȥ᡼��ʸ��¸
		$sql = sprintf("UPDATE t_enquete SET en_mail_subject=%s,en_mail_contents=%s WHERE en_enquete_id=$enquete_id",
				sql_char($myenq->mail_title),
				sql_char($mail_text));
		db_exec($sql);

		// ���ۿ�������
		$sql = "UPDATE t_my_enquete SET me_real_send=$send_count WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
		db_exec($sql);

		// ���󥱡��Ȳ������ơ��֥����
		make_ans_count($enquete_id);
	}
}

// �ץ������ȥ��󥱡��ȳ��Ͻ���
function start_pjt_enquete($enquete_id) {
	// ����־������
	$sql = "SELECT jb_job_id,jb_subject,jb_header,jb_body,jb_footer,jb_start_date,jb_send_num,jb_bunkatsu_kankaku,jb_bunkatsu_kaisuu,jb_search_id,jb_virgin_mail,jb_mail_format"
			. " FROM t_job"
			. " WHERE jb_enquete_id=$enquete_id AND jb_send_status=0";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$job_id = $fetch->jb_job_id;
		$send_num = $fetch->jb_send_num;
		$start_date = sql_time($fetch->jb_start_date);
		$bunkatsu_kankaku = $fetch->jb_bunkatsu_kankaku;
		$bunkatsu_kaisuu = $fetch->jb_bunkatsu_kaisuu;
		$mail_format = $fetch->jb_mail_format;

		// �����Ѥ߷������
		$sql = "SELECT COUNT(*) FROM t_enquete_list WHERE el_enquete_id=$enquete_id";
		$send_num2 = db_fetch1($sql);

		// �����Ѥ߷���򸺻�
		$send_num -= $send_num2;

		// ���󥱡��ȥ᡼������
		if ($mail_format == 0) {
			// �ƥ����ȥ᡼��
			$enquete = new enquete_class;
			$enquete->read_db($enquete_id);
			$mail_text = make_enq_mail($enquete, $fetch->jb_header, $fetch->jb_body, $fetch->jb_footer);
		} else {
			// HTML�᡼��
			$mail_text = $fetch->jb_body;
		}

		// ���󥱡��ȥ᡼��ʸ��¸
		$sql = sprintf("UPDATE t_enquete SET en_mail_subject=%s,en_mail_contents=%s WHERE en_enquete_id=$enquete_id",
				sql_char($fetch->jb_subject),
				sql_char($mail_text));
		db_exec($sql);

		// ����������
		$search = new search_class;
		$search->read_db($fetch->jb_search_id);
		$where = $search->make_sql2($job_id);

		// ���󥱡����оݼ����
		if ($send_num > 0 && $fetch->jb_virgin_mail != 1) {
			if ($send_num2 != 0)
				$where .= " AND mn_monitor_id NOT IN (SELECT el_monitor_id FROM t_enquete_list WHERE el_enquete_id=$enquete_id)";
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE $where"
					. " ORDER BY RANDOM() LIMIT $send_num";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$monitor_id = $fetch->mn_monitor_id;

				// ���������׻�
				$send_date = date('Y/m/d H:i:s', $start_date + floor($i * $bunkatsu_kaisuu / $send_num) * DAY_SEC * $bunkatsu_kankaku);

				// ���󥱡����оݼԥꥹ�Ȥ���Ͽ
				$sql = "INSERT INTO t_enquete_list (el_enquete_id,el_monitor_id,el_send_date)"
						. " VALUES ($enquete_id,$monitor_id,'$send_date')";
				db_exec($sql);
			}
		}

		// �����Ե����֤�����
		$sql = "UPDATE t_job SET jb_send_status=1,jb_sql=" . sql_char($where) . " WHERE jb_job_id=$job_id";
		db_exec($sql);
	}
}

// My�ѡ��ȥʡ����Ͻ���
function start_my_partner($enquete_id) {
	$sql = "SELECT ppj_pjt_id FROM t_mp_pjt WHERE ppj_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$pjt_id = $fetch->ppj_pjt_id;

		// My�ѡ��ȥʡ������ɤ߹���
		$myp = new my_partner_class;
		$myp->read_db($pjt_id);
		$enquete = &$myp->enquete;
		$search = &$myp->search;

		// �����Ѥ߷���򸺻�
		$send_num = $myp->send_num - $myp->real_send;

		// ���󥱡��ȥ᡼������
		$mail_text = make_enquete_mail($enquete, $myp->header, $myp->body, $myp->footer, $enquete->point);

		// ��о��
		$where = $search->make_sql();
		if ($myp->real_send != 0)
			$where .= " AND NOT EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=mn_monitor_id)";

		// ���ͥ��⡼��
		if ($search->type == 3)
			$order = 'mn_type,';
		elseif ($search->type == 4)
			$order = 'mn_type DESC,';
		else
			$order = '';
		$order .= 'RANDOM()';

		// ���󥱡����оݼ����
		if ($send_num > 0) {
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE $where ORDER BY $order LIMIT $send_num";

			// ��˥����˥��󥱡��ȥ᡼���ۿ�
			$send_count = send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $myp->subject, $mail_text);
		}

		// ��̳�ɤ�������λ����
		report_admin('�ͣ��ѡ��ȥʡ�', $send_num, $nrow, $myp->subject, $mail_text);

		// ���󥱡��ȥ᡼��ʸ��¸
		$sql = sprintf("UPDATE t_enquete SET en_mail_subject=%s,en_mail_contents=%s WHERE en_enquete_id=$enquete_id",
				sql_char($myp->subject),
				sql_char($mail_text));
		db_exec($sql);

		// ���ۿ����ȥ��ơ�����������
		$rec['ppj_real_send'] = sql_number($myp->real_send + $send_count);
		$rec['ppj_status'] = sql_number(MPS_REC_JISSHI);
		db_update('t_mp_pjt', $rec, "ppj_pjt_id=$pjt_id");
	}
}

// Pro���󥱡��ȳ��Ͻ���
function start_pro_enquete($enquete_id) {
	$sql = "SELECT mep_marketer_id,mep_pro_enq_no,mep_enquete2_id FROM t_pro_enquete WHERE mep_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$marketer_id = $fetch->mep_marketer_id;
		$pro_enq_no = $fetch->mep_pro_enq_no;
		$enquete2_id = $fetch->mep_enquete2_id;

		// Pro���󥱡��Ⱦ����ɤ߹���
		$pro_enq = new pro_enquete_class;
		$pro_enq->read_db($marketer_id, $pro_enq_no);
		$enquete = &$pro_enq->enquete;
		$enquete2 = &$pro_enq->enquete2;
		$search = &$pro_enq->search;
		$cell = &$pro_enq->cell;

		// �������� 2006/02/22 BTI
		// �оݥͥåȥ������
		$pro_enq_target = new pro_enq_target_class;
		$pro_enq_target->read_db($enquete_id);
		$mon_flg = $pro_enq_target->target_flg;
		$send_count = 0;

		// ���󥱡��ȥ᡼������
		$mail_text = make_enquete_mail($enquete, $pro_enq->mail_header, $pro_enq->mail_contents, $pro_enq->mail_footer, $enquete2->point, $pro_enq->finding_flag == DBTRUE ? $enquete->point : 0);

		// ���󥱡����оݼ����
		$where = $search->make_sql();
		if ($cell->cell_option == 2) {
			// �������ͭ��
			$member_ary = array_chunk($cell->get_search_list($where, $pro_enq->send_num, true, $pro_enq->appearance_ratio, $mon_flg), 5000);
			if (is_array($member_ary)) {
				foreach ($member_ary as $member_ary2) {
					$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM ${mon_flg}_monitor WHERE mn_monitor_id IN (" . join(',', $member_ary2) . ")";
					if ($mon_flg == 't')
						// ��˥����˥��󥱡��ȥ᡼���ۿ�
						$send_count += send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $pro_enq->mail_title, $mail_text);
					else {  // GMO
						// ���󥱡����оݼԿ�
						$result = db_exec($sql);
						$send_count += pg_numrows($result);
					}
				}
			}
		} else {
			// �������̵��
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM ${mon_flg}_monitor WHERE $where"
					. " ORDER BY RANDOM() LIMIT $pro_enq->send_num";
			if ($mon_flg == 't')
				// ��˥����˥��󥱡��ȥ᡼���ۿ�
				$send_count = send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $pro_enq->mail_title, $mail_text);
			else {
						// ���󥱡����оݼԿ�
				$result = db_exec($sql);
				$send_count = pg_numrows($result);
			}
		}

		// ���󥱡����оݼԥ�˥���°������¸
		if ($mon_flg == 't')
			save_enq_monitor_data($enquete_id, $enquete2_id);

		// ��̳�ɤ�������λ����
		report_admin('�Уңϥ��󥱡���', $mep->send_num, $nrow, $pro_enq->mail_title, $mail_text);

		// �ޡ��������ڡ�����URL����
		$fetch = get_system_info('sy_url_marketer');
		$url_marketer = $fetch->sy_url_marketer;

		// �ޡ���������Pro���󥱡��ȳ�������
		get_mail_template('pe_mk2', $subject, $from, $cc, $bcc, $repty_to, $body);
		if ($subject != '') {
			$sql = "SELECT mr_mail_addr,mr_name1,mr_kinmu_name FROM t_marketer WHERE mr_marketer_id=$marketer_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);
				$body = str_replace('%MARKETER_NAME%', $fetch->mr_name1, $body);
				$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
				$body = str_replace('%TITLE%', $enquete->title, $body);
				$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
				$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
				$body = str_replace('%ENQ_TOTAL%', $enquete2->get_question_num(), $body);
				$body = str_replace('%COMPANY_NAME%', $fetch->mr_kinmu_name, $body);
				$body = str_replace('%SEND_NUM%', $pro_enq->send_num, $body);
				$body = str_replace('%CELL_FLAG%', $pro_enq->cell->cell_option == 1 ? '�ʤ�' : '����', $body);
				$body = str_replace('%CELL_NUM%', $pro_enq->cell->cell_num, $body);
				$body = str_replace('%FINDING_NUM%', $pro_enq->finding_flag == DBTRUE ? number_format($pro_enq->appearance_ratio ? $pro_enq->sample_num * 100 / $pro_enq->appearance_ratio: 0) : '-', $body);
				$body = str_replace('%APPEARANCE%', $pro_enq->finding_flag == DBTRUE ? "$pro_enq->appearance_ratio%" : '-', $body);
				$body = str_replace('%SAMPLE_NUM%', number_format($pro_enq->sample_num), $body);
				$body = str_replace('%ENQTEST_URL%', "{$url_marketer}pe_remlenq3a.php?m=1&enquete_id=$pro_enq->enquete_id&pro_enq_no=$pro_enq->pro_enq_no", $body);
				$body = str_replace('%ESTIMATION%', number_format($pro_enq->get_total_price()), $body);
				$pro_enq->get_notify_mail_addr($to, $cc);
				send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
			}
		}

		// ���󥱡��ȥ᡼��ʸ��¸
		$sql = sprintf("UPDATE t_enquete SET en_mail_subject=%s,en_mail_contents=%s WHERE en_enquete_id=$enquete_id",
				sql_char($pro_enq->mail_title),
				sql_char($mail_text));
		db_exec($sql);

		// ���ۿ�������
		$rec['mep_real_send'] = "mep_real_send+$send_count";
		$rec['mep_send_count'] = 1;
		$rec['mep_first_send_date'] = "date_trunc('hour', current_timestamp)";
		$rec['mep_last_send_date'] = "date_trunc('hour', current_timestamp)";
		db_update('t_pro_enquete', $rec, "mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no");

		// ���󥱡��Ȳ������ơ��֥����
		make_ans_count($enquete_id);
		if ($enquete2_id != $enquete_id)
			make_ans_count($enquete2_id);

		// �ܥ��󥱡��Ȥγ��Ͻ�λ��������
		if ($pro_enq->finding_flag == DBTRUE) {
			$rec['en_start_date'] = sql_date($enquete->start_date);
			$rec['en_end_date'] = sql_date($enquete->end_date);
			db_update('t_enquete', $rec, "en_enquete_id=$enquete2_id");
		}
	}
}

// ���󥱡�������в����
function pass_enquete() {
	pass_free_enquete();
	pass_pjt_enquete();
	pass_my_enquete();
	pass_pro_enquete();
}

// �����ͥ��󥱡�������в����
function pass_free_enquete() {
	// �����ͥ��󥱡�������в����
	$hour = date('H');
	if ($hour == 9 || $hour == 17) {
		get_mail_template('free_enq', $subject, $from, $cc, $bcc, $repty_to, $body_org);

		$sql = "SELECT fe_seq_no,fe_enquete_id,fe_top_page,fe_report_addr,en_title"
				. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id"
				. " WHERE en_status=5 AND fe_report_addr IS NOT NULL";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$enquete_id = $fetch->fe_enquete_id;
			$report_addr = $fetch->fe_report_addr;

			$sql = "SELECT em_sum FROM t_enquete_sum WHERE em_enquete_id=$enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0";
			$kaitousha_suu = db_fetch1($sql, 0);

			$body = $body_org;
			$body = str_replace('%TITLE%', $fetch->en_title, $body);
			$body = str_replace('%KAITOUSHA_SUU%', $kaitousha_suu, $body);
			$body = str_replace('%DATE_TIME%', date('Y/m/d H:i'), $body);
			$body = str_replace('%URL_PARAM%', "?fno=$fetch->fe_seq_no", $body);

			$sql = "SELECT es_sel_text,em_sum"
					. " FROM t_enq_select JOIN t_enquete_sum ON em_enquete_id=es_enquete_id AND em_question_no=es_question_no AND em_sum_kind=1 AND em_sel_no=es_sel_no"
					. " WHERE es_enquete_id=$enquete_id AND es_question_no=$fetch->fe_top_page AND em_sum_kind=1 ORDER BY em_sel_no";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			$enquete_data = '';
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$percent = ($kaitousha_suu == 0) ? 0 : floor($fetch->em_sum / $kaitousha_suu * 100);
				$enquete_data .= $fetch->es_sel_text . '��' . number_format($fetch->em_sum) . '�͡�' . $percent . "%\r\n";
			}
			$body = str_replace('%ENQUETE_DATA%', trim($enquete_data), $body);

			$ary = explode(',', $report_addr);
			foreach ($ary as $to)
				send_mail($subject, $to, $from, $body);
		}
	}
}

// �ץ������ȥ��󥱡�������в����
function pass_pjt_enquete() {
	// �С�����᡼�����
	$sql = "SELECT vm_monitor_id FROM w_virgin_mail";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$monitor_id = $fetch->vm_monitor_id;

		db_begin_trans();

		$sql = "SELECT jb_enquete_id,jb_sql,jb_virgin_day,jb_virgin_hour"
				. " FROM t_job JOIN t_enquete ON en_enquete_id=jb_enquete_id"
				. " WHERE jb_send_status=1 AND CURRENT_TIMESTAMP BETWEEN jb_start_date AND jb_end_date AND en_status=5 AND (jb_virgin_mail=1 OR jb_virgin_mail=2)";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			$enquete_id = $fetch2->jb_enquete_id;

			if ($fetch2->jb_sql != '') {
				$sql = "SELECT mn_monitor_id FROM t_monitor WHERE mn_monitor_id=$monitor_id AND $fetch2->jb_sql"
						. " AND NOT EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id)";
				$result3 = db_exec($sql);
				if (pg_numrows($result3)) {
					if ($fetch2->jb_virgin_day >= 1)
						$send_date = date('Y/m/d', time() + $fetch2->jb_virgin_day * DAY_SEC) . sprintf(' %02d:0:0', $fetch2->jb_virgin_hour);
					else
						$send_date = 'now';

					// �����оݥꥹ�Ȥ��ɲ�
					$sql = "INSERT INTO t_enquete_list (el_enquete_id,el_monitor_id,el_send_date)"
							. " VALUES ($enquete_id,$monitor_id,'$send_date')";
					db_exec($sql);
				}
			}
		}

		// �С�����᡼���оݤ�����
		$sql = "DELETE FROM w_virgin_mail WHERE vm_monitor_id=$monitor_id";
		db_exec($sql);

		db_commit_trans();
	}

	// �ץ������ȥ��󥱡���������λ����
	$sql = "SELECT jb_job_id FROM t_job WHERE jb_send_status=1 AND jb_end_date<=CURRENT_TIMESTAMP";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		report_pjt_mail($fetch->jb_job_id);

		$sql = "UPDATE t_job SET jb_send_status=9 WHERE jb_job_id=$fetch->jb_job_id";
		db_exec($sql);
	}
}

// My���󥱡�������в����
function pass_my_enquete() {
	// �ɲ��ۿ�����
	$sql = "SELECT mas_seq_no,mas_marketer_id,mas_my_enq_no,mas_send_num,mas_send_date FROM t_myenq_add_send WHERE mas_send_date<=current_timestamp";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		db_begin_trans();

		$marketer_id = $fetch->mas_marketer_id;
		$my_enq_no = $fetch->mas_my_enq_no;
		$send_num = $fetch->mas_send_num;

		// My���󥱡��Ⱦ������
		$myenq = new my_enquete_class;
		$myenq->read_db($marketer_id, $my_enq_no);
		$enquete = &$myenq->enquete;
		$search = &$myenq->search;
		$cell = &$myenq->cell;
		$enquete_id = $enquete->enquete_id;

		// �����᡼�����Ƽ���
		$mail_text = make_enquete_mail($enquete, $myenq->mail_header, $myenq->mail_contents, $myenq->mail_footer, $enquete->point);

		// ���󥱡����оݼ����
		$send_count = 0;
		$where = $search->make_sql() . " AND mn_monitor_id NOT IN (SELECT el_monitor_id FROM t_enquete_list WHERE el_enquete_id=$enquete_id)";
		if ($cell->cell_option == 2) {
			// �������ͭ��
			$member_ary = array_chunk($cell->get_search_list2($where, $send_num), 5000);
			if (is_array($member_ary)) {
				foreach ($member_ary as $member_ary2) {
					$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE mn_monitor_id IN (" . join(',', $member_ary2) . ")";

					// ��˥����˥��󥱡��ȥ᡼���ۿ�
					$send_count += send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $myenq->mail_title, $mail_text);
				}
			}
		} else {
			// �������̵��
			$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM t_monitor WHERE $where ORDER BY RANDOM() LIMIT $send_num";

			// ��˥����˥��󥱡��ȥ᡼���ۿ�
			$send_count = send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $myenq->mail_title, $mail_text);
		}

		// ���󥱡����оݼԥ�˥���°������¸
		save_enq_monitor_data($enquete_id);

		// ���ۿ�������
		$sql = "UPDATE t_my_enquete SET me_real_send=me_real_send+$send_count WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
		db_exec($sql);

		// �ɲ��ۿ��ơ��֥뤫����
		$sql = "DELETE FROM t_myenq_add_send WHERE mas_seq_no=$fetch->mas_seq_no";
		db_exec($sql);

		db_commit_trans();
	}
}

// PRO���󥱡�������в����
function pass_pro_enquete() {
	// �ɲ�ȯ���о����
	$sql = "SELECT mep_marketer_id,mep_pro_enq_no"
			. " FROM t_pro_enquete"
			. " JOIN t_enquete ON en_enquete_id=mep_enquete_id"
			. " WHERE mep_send_count<=2 AND (mep_last_send_date + interval '12 hours')<CURRENT_TIMESTAMP AND en_status=5";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$marketer_id = $fetch->mep_marketer_id;
		$pro_enq_no = $fetch->mep_pro_enq_no;

		// PRO���󥱡��Ⱦ������
		$pro_enq = new pro_enquete_class;
		$pro_enq->read_db($marketer_id, $pro_enq_no);

		// �ֿ���������
		$hour_a = round((time() - sql_time($pro_enq->first_send_date)) / (60 * 60));
		if ($hour_a < 0)
			$hour_a = 0;
		$sql = "SELECT mas_factor FROM m_addition_send WHERE mas_hour<=$hour_a ORDER BY mas_hour DESC LIMIT 1";
		$reply_factor = db_fetch1($sql) / 100;

		// ��������ɸ����ץ������
		$sample_cell1 = $pro_enq->cell->get_send_cell();

		// �оݥͥåȥ������
		$pro_enq_target = new pro_enq_target_class;
		$pro_enq_target->read_db($pro_enq->enquete_id);
		$mon_flg = $pro_enq_target->target_flg;

		// �����̥���ץ�Ѥ߿�����
		$sample_cell2 = $pro_enq->cell->get_sample_cell($pro_enq->enquete2_id, $pro_enq->enquete->start_date, $mon_flg);

		// ��­���륻�뤬���뤫�����å�
		$short = false;
		foreach ($sample_cell1 as $sex => $tmp1) {
			foreach ($tmp1 as $age => $tmp2) {
				if ($tmp2[0] > $sample_cell2[$sex][$age] / $reply_factor)
					$short = true;
			}
		}

		db_begin_trans();

		if ($short) {
			if ($pro_enq->finding_flag == DBTRUE) {
				// �ե�����ǥ��󥰥��󥱡��ȥ���ץ������
				$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$pro_enq->enquete_id AND an_valid_flag";
				$finding_sample_num = db_fetch1($sql, 0);

				// �ܥ��󥱡��ȥ���ץ������
				$sql = "SELECT COUNT(*) FROM t_answer WHERE an_enquete_id=$pro_enq->enquete2_id AND an_valid_flag";
				$enquete_sample_num = db_fetch1($sql, 0);

				if ($finding_sample_num != 0) {
					// �и�Ψ�����å�
					if ($pro_enq->appearance_ratio > $enquete_sample_num / $finding_sample_num * 100) {
						// �ݾ��ֿ�Ψ�����å�
						$send_ary = $pro_enq->cell->get_send_cell(true, $pro_enq->appearance_ratio);
						$finding_sample_ary = $pro_enq->cell->get_sample_cell($pro_enq->enquete_id, $pro_enq->enquete->start_date);
						$reply_rate_ary = $pro_enq->cell->get_reply_rete();
						$auto_send_ary = array();
						$auto_send_flag = false;
						$mail_send_flag = false;
						$pro_enq->cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);
						for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
							for ($age = $age_s; $age <= $age_e; $age++) {
								if ($send_ary[$sex][$age][0] != 0) {
									if ($finding_sample_ary[$sex][$age] / $reply_factor / $send_ary[$sex][$age][0] >= $reply_rate_ary[$sex][$age]) {
										$auto_send_ary[$sex][$age] = false;
										$mail_send_flag = true;
									} else {
										$auto_send_ary[$sex][$age] = true;
										$auto_send_flag = true;
									}
								}
							}
						}

						// PRO���󥱡����ɲ�ȯ������
						if ($auto_send_flag)
							pro_enquete_add_send($pro_enq, $reply_factor, $auto_send_ary);

						// �ޡ����������ɲ�ȯ����ǧ�᡼������
						if ($mail_send_flag)
							pro_enquete_send_mail($pro_enq);
					} else {
						// PRO���󥱡����ɲ�ȯ������
						pro_enquete_add_send($pro_enq, $reply_factor, false);
					}
				}
			} else {
				// PRO���󥱡����ɲ�ȯ������
				pro_enquete_add_send($pro_enq, $reply_factor, false);
			}
		}

		// ��������������������
		$rec['mep_send_count'] = "mep_send_count+1";
		$rec['mep_last_send_date'] = "date_trunc('hour', current_timestamp)";
		db_update('t_pro_enquete', $rec, "mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no");

		db_commit_trans();
	}
}

// PRO���󥱡����ɲ�ȯ������
function pro_enquete_add_send(&$pro_enq, $reply_factor, $auto_send_ary) {
	$enquete = &$pro_enq->enquete;
	$enquete2 = &$pro_enq->enquete2;
	$search = &$pro_enq->search;
	$cell = &$pro_enq->cell;
	$enquete_id = $pro_enq->enquete_id;
	$enquete2_id = $pro_enq->enquete2_id;

	// �������� 2006/02/22 BTI
	// �оݥͥåȥ������
	$pro_enq_target = new pro_enq_target_class;
	$pro_enq_target->read_db($enquete_id);
	$mon_flg = $pro_enq_target->target_flg;

	// �����̥���ץ�Ѥ߿�����
	$real_sample_cell = $cell->get_sample_cell($enquete_id, $enquete->start_date, $mon_flg);

	// �ǽ�ͽ¬����ץ���׻�
	$final_sample_cell = array();
	$total_sample_num = 0;
	foreach ($real_sample_cell as $sex => $tmp1) {
		foreach ($tmp1 as $age => $sample_num) {
			$final_sample_num = round($sample_num / $reply_factor);
			$total_sample_num += $final_sample_num;
			$final_sample_cell[$sex][$age] = $final_sample_num;
		}
	}

	// �ҤȤĤ�������ʤ�����ͽ¬�Ǥ��ʤ��Τǲ��⤷�ʤ�
	if ($total_sample_num == 0 || $pro_enq->real_send == 0)
		return;

	// �ǽ�����Ψ
	$reply_rate = $total_sample_num / $pro_enq->real_send;

	// ��������ɸ����ץ������
	$goal_sample_cell = $cell->get_send_cell(false, $pro_enq->appearance_ratio);

	// �ɲ�ȯ�����׻�
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);
	$send_cell = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		for ($age = $age_s; $age <= $age_e; $age++) {
			$send_num = ($goal_sample_cell[$sex][$age][0] - $final_sample_cell[$sex][$age]) / $reply_rate;
			if ($send_num < 0)
				$send_num = 0;

			if (is_array($auto_send_ary)) {
				if (!$auto_send_ary[$sex][$age])
					$send_num = 0;
			}

			if ($cell->rate_option == 1)
				$send_cell[$sex][$age][0] = ceil($send_num);
			else {
				for ($rate = $rate_s; $rate <= $rate_e; $rate++)
					$send_cell[$sex][$age][$rate] = ceil($send_num * $cell->send_rate[$rate] / 100);
			}
		}
	}

	// �ɲ�ȯ��������С����
	$member_ary = array();
	list($select, $join, $groupby) = $cell->get_sql($enquete->start_date);
	$where = $search->make_sql();
	if ($mon_flg == 't') {
		$sql = "SELECT $select,mn_monitor_id"
				. " FROM t_monitor"
				. " $join"
				. " WHERE $where AND NOT EXISTS (SELECT * FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=mn_monitor_id)"
				. " ORDER BY RANDOM()";
	} else {
		$sql = "SELECT $select,mn_monitor_id"
				. " FROM ${mon_flg}_monitor"
				. " $join"
				. " WHERE $where"
				. " ORDER BY RANDOM()";
	}
	// �����ޤ� 2006/02/22 BTI

	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$sex = $fetch->sex;
		$age = $fetch->age;
		$rate = $fetch->rate;

		if ($send_cell[$sex][$age][$rate])
			$member_ary[$sex][$age][$rate][] = $fetch->mn_monitor_id;
	}

	// ­��ʤ�����˳������
	$assign_ary = array();
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		for ($age = $age_s; $age <= $age_e; $age++) {
			for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
				while ($send_cell[$sex][$age][$rate] > 0) {
					$member_id = 0;
					if (isset($member_ary[$sex][$age][$rate]) && count($member_ary[$sex][$age][$rate]) > 0)
						$member_id = array_pop($member_ary[$sex][$age][$rate]);
					else {
						for ($rate2 = $rate_s; $rate2 <= $rate_e; $rate2++) {
							if (isset($member_ary[$sex][$age][$rate2]) && count($member_ary[$sex][$age][$rate2]) > 0)
								$member_id = array_pop($member_ary[$sex][$age][$rate2]);
						}
					}
					if ($member_id) {
						$assign_ary[] = $member_id;
						$send_cell[$sex][$age][$rate]--;
					} else
						break;
				}
			}
		}
	}

	if (count($assign_ary) == 0)
		return;

// �������� 2006/02/22 BTI

	$sql = "SELECT mn_monitor_id,mn_mail_addr,mn_name1 FROM ${mon_flg}_monitor WHERE mn_monitor_id IN (" . join(',', $assign_ary) . ")";

	// ���󥱡��ȥ᡼������
	$mail_text = make_enquete_mail($enquete, $pro_enq->mail_header, $pro_enq->mail_contents, $pro_enq->mail_footer, $enquete2->point, $pro_enq->finding_flag == DBTRUE ? $enquete->point : 0);

	if ($mon_flg == 't') {
		// ��˥����˥��󥱡��ȥ᡼���ۿ�
		$send_count = send_enquete_mail($enquete_id, $enquete->enq_type, $enq_account, $sql, $pro_enq->mail_title, $mail_text, $pro_enq->send_count);

		// ���󥱡����оݼԥ�˥���°������¸
		save_enq_monitor_data($enquete_id, $enquete2_id);
	} else {
		// ���󥱡����оݼԿ�
		$result = db_exec($sql);
		$send_count = pg_numrows($result);
	}
// �����ޤ� 2006/02/22 BTI

	// ���ۿ�������
	$rec['mep_real_send'] = "mep_real_send+$send_count";
	$rec['mep_add_send_date'] = 'CURRENT_TIMESTAMP';
	db_update('t_pro_enquete', $rec, "mep_marketer_id=$pro_enq->marketer_id AND mep_pro_enq_no=$pro_enq->pro_enq_no");
}

// �ޡ����������ɲ�ȯ����ǧ�᡼������
function pro_enquete_send_mail(&$pro_enq) {
	$enquete = &$pro_enq->enquete;

	// �ޡ��������ڡ�����URL����
	$fetch = get_system_info('sy_url_marketer');
	$url_marketer = $fetch->sy_url_marketer;
	$judg_url = $url_marketer . "pe_sj.php?" . encrypt_param($pro_enq->pro_enq_no, $pro_enq->marketer_id) . $pro_enq->send_count;

	get_mail_template('pe_judg', $subject, $from, $cc, $bcc, $repty_to, $body);
	if ($subject != '') {
		$sql = "SELECT mr_mail_addr,mr_name1,ag_mail_addr"
			. " FROM t_marketer"
			. " LEFT JOIN t_agent ON ag_agent_id=mr_agent_id"
			. " WHERE mr_marketer_id=$pro_enq->marketer_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$body = str_replace('%MARKETER_NAME%', $fetch->mr_name1, $body);
			$body = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $body);
			$body = str_replace('%TITLE%', $enquete->title, $body);
			$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
			$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
			$body = str_replace('%JUDG_URL%', $judg_url, $body);
			$body = str_replace('%FINDING_NUM%', $pro_enq->finding_flag == DBTRUE ? number_format($pro_enq->appearance_ratio ? $pro_enq->sample_num * 100 / $pro_enq->appearance_ratio: 0) : '-', $body);
			$body = str_replace('%APPEARANCE%', $pro_enq->finding_flag == DBTRUE ? "$pro_enq->appearance_ratio%" : '-', $body);
			$body = str_replace('%SAMPLE_NUM%', number_format($pro_enq->sample_num), $body);
			$body = str_replace('%ENQTEST_URL%', "{$url_marketer}pe_remlenq3a.php?m=1&enquete_id=$pro_enq->enquete_id&pro_enq_no=$pro_enq->pro_enq_no", $body);
			$body = str_replace('%ESTIMATION%', number_format($pro_enq->get_total_price()), $body);

			// ô������������Ȥ�bcc�˲ä���
			if ($fetch->ag_mail_addr != '') {
				if ($bcc != '')
					$bcc .= ',';
				$bcc .= $fetch->ag_mail_addr;
			}

			// ��̳�ɤ�bcc�˲ä��� 2006/04/20 BTI
			$fetch_bcc = get_system_info('sy_myenq_to');
			if ($bcc != '')
				$bcc .= ',';
			$bcc .= $fetch_bcc->sy_myenq_to;

			$pro_enq->get_notify_mail_addr($to, $cc);
			send_mail($subject, $to, $from, $body, $cc, $bcc, $reply_to);
		}
	}
}

// ���󥱡��Ƚ�λ����
function end_enquete() {
	$sql = "SELECT en_enquete_id,en_enq_kind FROM t_enquete WHERE (en_status=5 OR en_status=6) AND en_end_date<=CURRENT_TIMESTAMP";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$enquete_id = $fetch->en_enquete_id;

		db_begin_trans();

		switch ($fetch->en_enq_kind) {
		case 1:   // �����ͥ��󥱡���
			break;
		case 2:   // My���󥱡���
			end_my_enquete($enquete_id);
			break;
		case 3:   // �ץ������ȥ��󥱡���
			end_pjt_enquete($enquete_id);
			break;
		case 4:   // My�ѡ��ȥʡ����󥱡���
			end_my_partner($enquete_id);
			break;
		case 5:     // �ե�����ǥ��󥰥��󥱡���
		case 6:     // Pro�ܥ��󥱡���
			end_pro_enquete($enquete_id);
			break;
		}

		$sql = "UPDATE t_enquete SET en_status=7 WHERE en_enquete_id=$enquete_id";
		db_exec($sql);

		batch_log(0, FILE_NAME, "���󥱡���(ID=$enquete_id)��λ���ޤ�����");

		db_commit_trans();
	}
}

// My���󥱡��Ƚ�λ����
function end_my_enquete($enquete_id) {
	// �ޡ��������ȥ���������ȤΥ᡼�륢�ɥ쥹����
	$sql = "SELECT me_my_enq_no,me_enquete_id,mr_marketer_id,mr_mail_addr,mr_name1,ag_mail_addr,ag_name1,ag_name2"
			. " FROM t_my_enquete JOIN t_marketer ON mr_marketer_id=me_marketer_id LEFT JOIN t_agent ON ag_agent_id=mr_agent_id"
			. " WHERE me_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$my_enq_no = $fetch->me_my_enq_no;
		$enquete_id = $fetch->me_enquete_id;
		$marketer_id = $fetch->mr_marketer_id;
		$marketer_addr = $fetch->mr_mail_addr;
		$marketer_name = $fetch->mr_name1;
		$agent_addr = $fetch->ag_mail_addr;
		$agent_name = trim("$fetch->ag_name1 $fetch->ag_name2");
	} else
		return;

	// �ޡ��������ڡ�����URL����
	$fetch = get_system_info('sy_url_marketer');
	$url = $fetch->sy_url_marketer;

	// ��̥ڡ�������
	$ary = file("${url}mkk_resdet_pv2.php?owner_id=$marketer_id&enquete_id=$enquete_id");
	if (!is_array($ary))
		return;
	$html = join("", $ary);
/* 2006/09/25 ��̥᡼���ź�դ��ѻ�
	// ������CSV����
	$ary = file("${url}mkk_enq_csv1.php?owner_id=$marketer_id&enquete_id=$enquete_id&type=send");
	if (!is_array($ary))
		return;
	$csv_send = join("", $ary);

	// ������CSV����
	$ary = file("${url}mkk_enq_csv1.php?owner_id=$marketer_id&enquete_id=$enquete_id&type=recv");
	if (!is_array($ary))
		return;
	$csv_recv = join("", $ary);

	// ���CSV����
	$ary = file("${url}mkk_enq_csv2.php?owner_id=$marketer_id&enquete_id=$enquete_id");
	if (!is_array($ary))
		return;
	$csv_result = join("", $ary);
*/
	// My���󥱡��Ⱦ����ɤ߹���
	$myenq = new my_enquete_class;
	$myenq->read_db($marketer_id, $my_enq_no);
	$enquete = &$myenq->enquete;

	get_mail_template('mye_res', $subject, $from, $cc, $bcc, $repty_to, $body);
	separate_address($from, $from_addr, $from_name);
	$body = nl2br($body);
	$body = str_replace('%MARKETER_NAME%', $marketer_name, $body);
	$body = str_replace('%MAIL_ADDR%', $marketer_addr, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%AGENT%', "$agent_name", $body);
	$body = str_replace('%ENQUETE_RESULT%', $html, $body);

	// �����᡼�륻�å�
	$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name, '1');
/* 2006/09/25 ��̥᡼���ź�դ��ѻ�  
	send_mail_file($send_mail_id, 1, "send.csv", $csv_send);
	send_mail_file($send_mail_id, 2, "receive.csv", $csv_recv);
	send_mail_file($send_mail_id, 3, "result.csv", $csv_result);
*/
	// �ޡ��������ȥ���������Ȥ˥᡼������
	if ($agent_addr != '')
		send_mail_list($send_mail_id, $agent_addr);

	$myenq->get_notify_mail_addr($to, $cc);
	if ($to) {
		if ($cc)
			$cc = "Cc: $cc";
		send_mail_list($send_mail_id, $to, '', $cc);
	}

	send_mail_ok($send_mail_id);
}

// �ץ������ȥ��󥱡��Ƚ�λ����
function end_pjt_enquete($enquete_id) {
	// ̤�����ꥹ�Ⱥ��
	$sql = "DELETE FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_status=0";
	db_exec($sql);

	// �᡼���������֤�λ
	$sql = "SELECT jb_job_id FROM t_job WHERE jb_enquete_id=$enquete_id AND jb_send_status=1";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		report_pjt_mail($fetch->jb_job_id);

		$sql = "UPDATE t_job SET jb_send_status=9 WHERE jb_job_id=$fetch->jb_job_id";
		db_exec($sql);
	}
	db_exec($sql);
}

// My�ѡ��ȥʡ���λ����
function end_my_partner($enquete_id) {
	// My���󥱡��ȥץ������ȤΥ��ơ������򥻥å�
	$rec['ppj_status'] = sql_number(MPS_MEM_SENTEI);
	db_update('t_mp_pjt', $rec, "ppj_enquete_id=$enquete_id");
}

// Pro���󥱡��Ƚ�λ����
function end_pro_enquete($enquete_id) {
	// �ޡ��������ȥ���������ȤΥ᡼�륢�ɥ쥹����
	$sql = "SELECT mep_pro_enq_no,mep_finding_flag,mr_marketer_id,mr_mail_addr,mr_name1,ag_mail_addr,ag_name1,ag_name2"
			. " FROM t_pro_enquete JOIN t_marketer ON mr_marketer_id=mep_marketer_id LEFT JOIN t_agent ON ag_agent_id=mr_agent_id"
			. " WHERE mep_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$pro_enq_no = $fetch->mep_pro_enq_no;
		$finding_flag = $fetch->mep_finding_flag;
		$marketer_id = $fetch->mr_marketer_id;
		$marketer_addr = $fetch->mr_mail_addr;
		$marketer_name = $fetch->mr_name1;
		$agent_addr = $fetch->ag_mail_addr;
		$agent_name = trim("$fetch->ag_name1 $fetch->ag_name2");
	} else
		return;

	// �ޡ��������ڡ�����URL����
	$fetch = get_system_info('sy_url_marketer');
	$url_marketer = $fetch->sy_url_marketer;

	// ��̥ڡ�������
	$ary = file("${url_marketer}pe_resdet_pv2.php?owner_id=$marketer_id&pro_enq_no=$pro_enq_no");
	if (!is_array($ary))
		return;
	$html = join("", $ary);

/* 2006/09/25 ��̥᡼���ź�դ��ѻ�
	// �ե�����ǥ��󥰲����ܲ�����°����ʣ�������ϥ���޶��ڤ��CSV����
	$ary = file("${url_marketer}pe_enq_csv2.php?owner_id=$marketer_id&pro_enq_no=$pro_enq_no&mode=1");
	if (!is_array($ary))
		return;
	$csv_qr = join("", $ary);

	// �ե�����ǥ��󥰲����ܲ�����°����ʣ�������ϣ�����������CSV����
	$ary = file("${url_marketer}pe_enq_csv3.php?owner_id=$marketer_id&pro_enq_no=$pro_enq_no&mode=1");
	if (!is_array($ary))
		return;
	$csv_qr01 = join("", $ary);

	if ($finding_flag == DBTRUE) {
		// �ե�����ǥ��󥰡���Ĵ�������ܲ�����°����ʣ�������ϥ���޶��ڤ��CSV����
		$ary = file("${url_marketer}pe_enq_csv2.php?owner_id=$marketer_id&pro_enq_no=$pro_enq_no&mode=2");
		if (!is_array($ary))
			return;
		$csv_qrdr = join("", $ary);

		// �ե�����ǥ��󥰡���Ĵ�������ܲ�����°����ʣ�������ϣ�����������CSV����
		$ary = file("${url_marketer}pe_enq_csv3.php?owner_id=$marketer_id&pro_enq_no=$pro_enq_no&mode=2");
		if (!is_array($ary))
			return;
		$csv_qrdr01 = join("", $ary);
	}
*/
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($marketer_id, $pro_enq_no);
	$enquete = &$pro_enq->enquete;

	get_mail_template('pe_res', $subject, $from, $cc, $bcc, $repty_to, $body);
	separate_address($from, $from_addr, $from_name);
	$body = nl2br($body);
	$body = str_replace('%MARKETER_NAME%', $marketer_name, $body);
	$body = str_replace('%MAIL_ADDR%', $marketer_addr, $body);
	$body = str_replace('%TITLE%', $enquete->title, $body);
	$body = str_replace('%START_DATE%', "$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d", $body);
	$body = str_replace('%END_DATE%', "$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d", $body);
	$body = str_replace('%AGENT%', "$agent_name", $body);
	$body = str_replace('%ENQUETE_RESULT%', $html, $body);
	$body = str_replace('%FINDING_NUM%', $pro_enq->finding_flag == DBTRUE ? number_format($pro_enq->appearance_ratio ? $pro_enq->sample_num * 100 / $pro_enq->appearance_ratio: 0) : '-', $body);
	$body = str_replace('%APPEARANCE%', $pro_enq->finding_flag == DBTRUE ? "$pro_enq->appearance_ratio%" : '-', $body);
	$body = str_replace('%SAMPLE_NUM%', number_format($pro_enq->sample_num), $body);
	$body = str_replace('%ENQTEST_URL%', "{$url_marketer}pe_remlenq3a.php?m=1&enquete_id=$pro_enq->enquete_id&pro_enq_no=$pro_enq->pro_enq_no", $body);
	$body = str_replace('%ESTIMATION%', number_format($pro_enq->get_total_price()), $body);

	// �����᡼�륻�å�
	$send_mail_id = send_mail_data($subject, $from_addr, $repty_to, $body, '', '', $from_name, '1');
/* 2006/09/25 ��̥᡼���ź�դ��ѻ�
	send_mail_file($send_mail_id, 1, "qr_{$enquete_id}.csv", $csv_qr);
	send_mail_file($send_mail_id, 2, "qr01_{$enquete_id}.csv", $csv_qr01);
	if ($finding_flag == DBTRUE) {
		send_mail_file($send_mail_id, 3, "qrdr_{$enquete_id}.csv", $csv_qrdr);
		send_mail_file($send_mail_id, 4, "qrdr01_{$enquete_id}.csv", $csv_qrdr01);
	}
*/
	// �ޡ��������ȥ���������Ȥ˥᡼������
	if ($agent_addr != '')
		send_mail_list($send_mail_id, $agent_addr);

	$pro_enq->get_notify_mail_addr($to, $cc);
	if ($to) {
		if ($cc)
			$cc = "Cc: $cc";
		send_mail_list($send_mail_id, $to, '', $cc);
	}

	send_mail_ok($send_mail_id);
}

// ���󥱡��Ȳ������ơ��֥����
function make_ans_count($enquete_id) {
	$sql = "DELETE FROM t_ans_count WHERE anc_enquete_id=$enquete_id";
	db_exec($sql);
	$sql = "INSERT INTO t_ans_count (anc_enquete_id,anc_ans_count) SELECT $enquete_id,COUNT(*) FROM t_answer WHERE an_enquete_id=$enquete_id AND an_valid_flag";
	db_exec($sql);
}
?>