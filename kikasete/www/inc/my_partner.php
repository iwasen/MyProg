<?
/******************************************************
' System :�������ơ�net����
' Content:My�ѡ��ȥʡ���Ϣ
'******************************************************/

define('MPS_REC_JUNBI', 1);
define('MPS_REC_KANRYOU', 2);
define('MPS_REC_SHINSEI', 3);
define('MPS_REC_CHECK', 4);
define('MPS_REC_SHOUNIN', 5);
define('MPS_REC_JISSHI', 6);
define('MPS_MEM_SENTEI', 7);
define('MPS_MEM_CHECK', 8);
define('MPS_MEM_SHOUNIN', 9);
define('MPS_ROOM_JUNBI', 10);
define('MPS_ROOM_JISSHI', 11);
define('MPS_ROOM_END', 12);

// ��ư���շ׻�
function calc_date($recruit_start, &$recruit_end, &$decision_date, &$room_start) {
	// ��Ķ����Σ���
	$next_day = next_working_day($recruit_start);
	$ary = getdate($next_day);
	$next_day = mktime(9, 0, 0, $ary['mon'], $ary['mday'], $ary['year']);

	// �ꥯ�롼�Ƚ�λ������ꥯ�롼�ȳ������ܣ��Ķ����Σ���
	$recruit_end = next_working_day($next_day);

	// ��������ꥯ�롼�Ƚ�λ��
	$decision_date = $recruit_end;

	// ��ļ���������������ܣ��Ķ���
	$room_start = next_working_day($decision_date);
}

// ��Ķ�������
function next_working_day($time) {
	for (;;) {
		$time += 24 * 60 * 60;
		$date = getdate($time);
		if ($date['wday'] != 6) {
			// �����ޥ�������
			$sql = "SELECT COUNT(*) FROM m_holiday WHERE hd_holiday='{$date['year']}/{$date['mon']}/{$date['mday']}'";
			if (db_fetch1($sql) == 0) {
				if ($date['wday'] != 0)
					break;
			} else {
				// �����ؤ�����
				if ($date['wday'] == 0)
					$time += 24 * 60 * 60;
			}
		}
	}

	return $time;
}

// �ץ������Ⱦ���
function decode_mypartner_status($code, $def='����ʤ�') {
	switch ($code) {
	case MPS_REC_JUNBI:
		return '�ꥯ�롼�Ƚ�����';
	case MPS_REC_KANRYOU:
		return '�ꥯ�롼�Ⱥ�����λ';
	case MPS_REC_SHINSEI:
		return '�ꥯ�롼�ȿ�����';
	case MPS_REC_CHECK:
		return '�ꥯ�롼�Ȼ�̳�ɥ����å���';
	case MPS_REC_SHOUNIN:
		return '�ꥯ�롼�Ⱦ�ǧ�Ѥ�';
	case MPS_REC_JISSHI:
		return '�ꥯ�롼�ȼ»���';
	case MPS_MEM_SENTEI:
		return '�оݼ�������';
	case MPS_MEM_CHECK:
		return '�оݼԻ�̳�ɥ����å���';
	case MPS_MEM_SHOUNIN:
		return '�оݼ����꾵ǧ��';
	case MPS_ROOM_JUNBI:
		return '��ļ�������';
	case MPS_ROOM_JISSHI:
		return '��ļ�������';
	case MPS_ROOM_END:
		return '��ļ���λ';
	}
	return $def;
}

// �ꥯ�롼�Ⱦ���
function decode_recruit_status($code, $def='����ʤ�') {
	switch ($code) {
	case MPS_REC_JUNBI:
		return '������';
	case MPS_REC_KANRYOU:
		return '�ꥯ�롼�Ⱥ�����λ';
	case MPS_REC_SHINSEI:
		return '�ꥯ�롼�ȿ�����';
	case MPS_REC_CHECK:
		return '�ꥯ�롼�ȥ����å���';
	case MPS_REC_SHOUNIN:
		return '�ꥯ�롼�Ⱦ�ǧ��';
	case MPS_REC_JISSHI:
		return '�ꥯ�롼�ȼ»���';
	case MPS_MEM_SENTEI:
		return '�оݼ�������';
	case MPS_MEM_CHECK:
		return '�оݼ����꿽����';
	case MPS_MEM_SHOUNIN:
		return '�оݼ����꾵ǧ��';
	case MPS_ROOM_JUNBI:
	case MPS_ROOM_JISSHI:
	case MPS_ROOM_END:
		return '��λ';
	}
	return $def;
}

// �»ܾ���
function decode_room_status($code, $def='����ʤ�') {
	switch ($code) {
	case MPS_REC_JUNBI:
	case MPS_REC_KANRYOU:
	case MPS_REC_SHINSEI:
	case MPS_REC_CHECK:
	case MPS_REC_SHOUNIN:
	case MPS_REC_JISSHI:
	case MPS_MEM_SENTEI:
	case MPS_MEM_CHECK:
	case MPS_MEM_SHOUNIN:
	case MPS_ROOM_JUNBI:
		return '������';
	case MPS_ROOM_JISSHI:
		return '�»���';
	case MPS_ROOM_END:
		return '��λ';
	}
	return $def;
}

// My�ѡ��ȥʡ����
function delete_mypartner($pjt_id) {
	$sql = "SELECT ppj_enquete_id,ppj_search_id FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);

		db_begin_trans();

		// ���󥱡��Ⱦ�����
		if ($fetch->ppj_enquete_id) {
			$rec['en_status'] = sql_number(9);
			db_update('t_enquete', $rec, "en_enquete_id=$fetch->ppj_enquete_id");
		}

		// ���������
		if ($fetch->ppj_search_id)
			db_delete('t_search', "sr_search_id=$fetch->ppj_search_id");

		// My�ѡ��ȥʡ��ǡ������
		db_delete('t_mp_pjt', "ppj_pjt_id=$pjt_id");
		db_delete('t_mp_sanka_jouken', "psj_pjt_id=$pjt_id");
		db_delete('t_mp_monitor', "pmn_pjt_id=$pjt_id");
		db_delete('t_mp_point_log', "ppl_pjt_id=$pjt_id");
		db_delete('t_mp_monitor_point', "pmp_pjt_id=$pjt_id");
		db_delete('t_mp_select', "psl_pjt_id=$pjt_id");

		db_commit_trans();
	}
}

// ICP��ļ����ɥ쥹����
function get_icp_room_addr($icp_job_id) {
	$sql = "SELECT pj_pjt_cd,jb_job_cd"
			. " FROM t_job"
			. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
			. " WHERE jb_job_id=" . sql_number($icp_job_id);
	$result = icp_db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$room_addr = get_room_mail_addr($fetch->pj_pjt_cd, $fetch->jb_job_cd);
	}

	return $room_addr;
}

class my_partner_class {
	var $pjt_id;
	var $status;
	var $marketer_id;
	var $room_name;
	var $purpose;
	var $enquete_id;
	var $search_id;
	var $recruit_start;
	var $recruit_end;
	var $room_start;
	var $room_end;
	var $member_num;
	var $point_timing;
	var $point_rule;
	var $point_max;
	var $point_unit;
	var $point_bonus;
	var $policy;
	var $send_num;
	var $real_send;
	var $subject;
	var $from;
	var $header;
	var $body;
	var $footer;
	var $recruit_flag;
	var $and_or;
	var $select_sql;
	var $icp_member_num;
	var $icp_job_id;
	var $decision_date;
	var $add_send_date;

	var $recruit_start_y;
	var $recruit_start_m;
	var $recruit_start_d;
	var $recruit_start_h;
	var $recruit_end_y;
	var $recruit_end_m;
	var $recruit_end_d;
	var $recruit_end_h;
	var $room_start_y;
	var $room_start_m;
	var $room_start_d;
	var $room_end_y;
	var $room_end_m;
	var $room_end_d;
	var $decision_date_y;
	var $decision_date_m;
	var $decision_date_d;
	var $add_send_date_y;
	var $add_send_date_m;
	var $add_send_date_d;
	var $add_send_date_h;

	var $sanka_jouken;
	var $sanka_jouken_num;
	var $save_sanka_jouken;

	var $recruit_jouken;
	var $recruit_jouken_num;
	var $save_recruit_jouken;

	var $save_enquete;
	var $save_search;

	var $age_type;

	// ���������
	function my_partner_class() {
		$this->status = MPS_REC_JUNBI;
		$this->sanka_jouken = array();
		$this->recruit_jouken = array();

		$year = date('Y');
		$this->recruit_start_y = $year;
		$this->recruit_start_h = 18;
		$this->recruit_end_y = $year;
		$this->room_start_y = $year;
		$this->room_end_y = $year;
		$this->decision_date_y = $year;

		$this->enquete = new enquete_class;
		$this->search = new search_class;
		$this->enquete->enq_kind = 4;
		$this->enquete->enq_type = 2;
		$this->recruit_flag = 0;
	}

	// DB�����ɤ߹���
	function read_db($pjt_id) {
		// �ץ������Ⱦ����ɤ߹���
		$sql = "SELECT * FROM t_mp_pjt WHERE ppj_pjt_id=$pjt_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->pjt_id = $fetch->ppj_pjt_id;
			$this->status = $fetch->ppj_status;
			$this->marketer_id = $fetch->ppj_marketer_id;
			$this->room_name = $fetch->ppj_room_name;
			$this->purpose = $fetch->ppj_purpose;
			$this->enquete_id = $fetch->ppj_enquete_id;
			$this->search_id = $fetch->ppj_search_id;
			$this->recruit_start = $fetch->ppj_recruit_start;
			$this->recruit_end = $fetch->ppj_recruit_end;
			$this->room_start = $fetch->ppj_room_start;
			$this->room_end = $fetch->ppj_room_end;
			$this->member_num = $fetch->ppj_member_num;
			$this->point_timing = $fetch->ppj_point_timing;
			$this->point_rule = $fetch->ppj_point_rule;
			$this->point_max = $fetch->ppj_point_max;
			$this->point_unit = $fetch->ppj_point_unit;
			$this->point_bonus = $fetch->ppj_point_bonus;
			$this->policy = $fetch->ppj_policy;
			$this->send_num = $fetch->ppj_send_num;
			$this->real_send = $fetch->ppj_real_send;
			$this->subject = $fetch->ppj_subject;
			$this->from = $fetch->ppj_from;
			$this->header = $fetch->ppj_header;
			$this->body = $fetch->ppj_body;
			$this->footer = $fetch->ppj_footer;
			$this->recruit_flag = $fetch->ppj_recruit_flag;
			$this->and_or = $fetch->ppj_and_or;
			$this->select_sql = $fetch->ppj_select_sql;
			$this->icp_job_id = $fetch->ppj_icp_job_id;
			$this->icp_member_num = $fetch->ppj_icp_member_num;
			$this->decision_date = $fetch->ppj_decision_date;
			$this->add_send_date = $fetch->ppj_add_send_date;

			// ����ʬ��
			$this->get_datepart2($this->recruit_start, $this->recruit_start_y, $this->recruit_start_m, $this->recruit_start_d, $this->recruit_start_h);
			$this->get_datepart2($this->recruit_end, $this->recruit_end_y, $this->recruit_end_m, $this->recruit_end_d, $this->recruit_end_h);
			$this->get_datepart($this->room_start, $this->room_start_y, $this->room_start_m, $this->room_start_d);
			$this->get_datepart($this->room_end, $this->room_end_y, $this->room_end_m, $this->room_end_d);
			$this->get_datepart($this->decision_date, $this->decision_date_y, $this->decision_date_m, $this->decision_date_d);
			$this->get_datepart2($this->add_send_date, $this->add_send_date_y, $this->add_send_date_m, $this->add_send_date_d, $this->add_send_date_h);
		}

		// ���þ���ɤ߹���
		$this->sanka_jouken = array();
		$sql = "SELECT psj_seq_no,psj_sanka_jouken FROM t_mp_sanka_jouken WHERE psj_pjt_id=$pjt_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$seq_no = $fetch->psj_seq_no;
			$this->sanka_jouken[$seq_no] = $fetch->psj_sanka_jouken;
		}
		$this->sanka_jouken_num = $i;

		// �ꥯ�롼�Ⱦ���ɤ߹���
		$this->recruit_jouken = array();
		$sql = "SELECT prj_seq_no,prj_recruit_jouken FROM t_mp_recruit_jouken WHERE prj_pjt_id=$pjt_id";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$seq_no = $fetch->prj_seq_no;
			$this->recruit_jouken[$seq_no] = $fetch->prj_recruit_jouken;
		}
		$this->recruit_jouken_num = $i;

		// ���󥱡�����Ͽ�������
		if ($this->enquete_id)
			$this->enquete->read_db($this->enquete_id);

		// ����������
		if ($this->search_id)
			$this->search->read_db($this->search_id);

		// ǯ�������ˡ
		if ($this->search->age_cd != '')
			$this->age_type = '1';
		elseif ($this->search->age_from != '' || $this->search->age_to != '')
			$this->age_type = '2';
		else
			$this->age_type = '';
	}

	// DB�˽񤭹���
	function write_db() {
		db_begin_trans();

		// ���󥱡�����Ͽ����񤭹���
		if ($this->save_enquete) {
			$this->enquete_id = $this->enquete->write_db($this->enquete_id);
			$this->save_enquete = false;
		}

		// �������񤭹���
		if ($this->save_search) {
			$this->search_id = $this->search->write_db($this->search_id);
			$this->save_search = false;
		}

		// �ץ������Ⱦ���񤭹���
		$rec['ppj_status'] = sql_number($this->status);
		$rec['ppj_marketer_id'] = sql_number($this->marketer_id);
		$rec['ppj_room_name'] = sql_char($this->room_name);
		$rec['ppj_purpose'] = sql_char($this->purpose);
		$rec['ppj_enquete_id'] = sql_number($this->enquete_id);
		$rec['ppj_search_id'] = sql_number($this->search_id);
		$rec['ppj_recruit_start'] = sql_date2($this->recruit_start_y, $this->recruit_start_m, $this->recruit_start_d, $this->recruit_start_h);
		$rec['ppj_recruit_end'] = sql_date2($this->recruit_end_y, $this->recruit_end_m, $this->recruit_end_d, $this->recruit_end_h);
		$rec['ppj_room_start'] = sql_date2($this->room_start_y, $this->room_start_m, $this->room_start_d);
		$rec['ppj_room_end'] = sql_date2($this->room_end_y, $this->room_end_m, $this->room_end_d);
		$rec['ppj_member_num'] = sql_number($this->member_num);
		$rec['ppj_point_timing'] = sql_number($this->point_timing);
		$rec['ppj_point_rule'] = sql_number($this->point_rule);
		$rec['ppj_point_max'] = sql_number($this->point_max);
		$rec['ppj_point_unit'] = sql_number($this->point_unit);
		$rec['ppj_point_bonus'] = sql_bool($this->point_bonus);
		$rec['ppj_policy'] = sql_number($this->policy);
		$rec['ppj_send_num'] = sql_number($this->send_num);
		$rec['ppj_real_send'] = sql_number($this->real_send);
		$rec['ppj_subject'] = sql_char($this->subject);
		$rec['ppj_from'] = sql_char($this->from);
		$rec['ppj_header'] = sql_char($this->header);
		$rec['ppj_body'] = sql_char($this->body);
		$rec['ppj_footer'] = sql_char($this->footer);
		$rec['ppj_recruit_flag'] = sql_number($this->recruit_flag);
		$rec['ppj_and_or'] = sql_char($this->and_or);
		$rec['ppj_select_sql'] = sql_char($this->select_sql);
		$rec['ppj_decision_date'] = sql_date2($this->decision_date_y, $this->decision_date_m, $this->decision_date_d);
		$rec['ppj_add_send_date'] = sql_date2($this->add_send_date_y, $this->add_send_date_m, $this->add_send_date_d, $this->add_send_date_h);

		if ($this->pjt_id)
			db_update('t_mp_pjt', $rec, "ppj_pjt_id=$this->pjt_id");
		else {
			db_insert('t_mp_pjt', $rec);
			$this->pjt_id = get_currval('t_mp_pjt_ppj_pjt_id_seq');
		}

		// ���þ��񤭹���
		if ($this->save_sanka_jouken) {
			db_delete('t_mp_sanka_jouken', "psj_pjt_id=$this->pjt_id");

			if (is_array($this->sanka_jouken)) {
				foreach ($this->sanka_jouken as $seq_no => $sanka_jouken) {
					$rec['psj_pjt_id'] = sql_number($this->pjt_id);
					$rec['psj_seq_no'] = sql_number($seq_no);
					$rec['psj_sanka_jouken'] = sql_char($sanka_jouken);
					db_insert('t_mp_sanka_jouken', $rec);
				}
			}

			$this->save_sanka_jouken = false;
		}

		// �ꥯ�롼�Ⱦ��񤭹���
		if ($this->save_recruit_jouken) {
			db_delete('t_mp_recruit_jouken', "prj_pjt_id=$this->pjt_id");

			if (is_array($this->recruit_jouken)) {
				foreach ($this->recruit_jouken as $seq_no => $recruit_jouken) {
					$rec['prj_pjt_id'] = sql_number($this->pjt_id);
					$rec['prj_seq_no'] = sql_number($seq_no);
					$rec['prj_recruit_jouken'] = sql_char($recruit_jouken);
					db_insert('t_mp_recruit_jouken', $rec);
				}
			}

			$this->save_recruit_jouken = false;
		}

		db_commit_trans();
	}

	// ǯ����ʬ��
	function get_datepart($date, &$year, &$month, &$day) {
		if ($date != '')
			sscanf($date, "%d-%d-%d", &$year, &$month, &$day);
	}

	// ǯ������ʬ��
	function get_datepart2($date, &$year, &$month, &$day, &$hour) {
		if ($date != '')
			sscanf($date, "%d-%d-%d %d:", &$year, &$month, &$day, &$hour);
	}
}
?>