<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
' Content:My���󥱡��ȴ�Ϣ
'******************************************************/

define('MAX_ENQ', 15);		// ���󥱡��ȼ�������
define('MAX_SEL', 20);	// ���������

class my_enquete_class {
	var $staff_id;
	var $my_enq_no;
	var $enquete_id;
	var $search_id;
	var $send_num;
	var $mail_title;
	var $mail_header;
	var $mail_contents;
	var $mail_footer;
	var $ans_cond;
	var $age_type;

	var $enquete;
	var $search;

	var $save_enquete;
	var $save_search;

	// ���������
	function my_enquete_class() {
		$this->enquete = new enquete_class;
		$this->search = new search_class;
		$this->enquete->enq_kind = 2;
	}

	// DB�����ɤ߹���
	function read_db($staff_id, $my_enq_no) {
		if (!$staff_id || !$my_enq_no)
			return;

		// �����
		$this->my_enquete_class();

		// My���󥱡��Ⱦ������
		$sql = "SELECT * FROM t_my_enquete WHERE me_staff_id=$staff_id AND me_my_enq_no=$my_enq_no";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->staff_id = $fetch->me_staff_id;
			$this->my_enq_no = $fetch->me_my_enq_no;
			$this->make_step = $fetch->me_make_step;
			$this->enquete_id = $fetch->me_enquete_id;
			$this->search_id = $fetch->me_search_id;
			$this->send_num = $fetch->me_send_num;
			$this->mail_title = $fetch->me_mail_title;
			$this->mail_header = $fetch->me_mail_header;
			$this->mail_contents = $fetch->me_mail_contents;
			$this->mail_footer = $fetch->me_mail_footer;
			$this->ans_cond = $fetch->me_ans_cond;
		}

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
		if ($this->save_enquete)
			$this->enquete_id = $this->enquete->write_db($this->enquete_id);

		// �������񤭹���
		if ($this->save_search)
			$this->search_id = $this->search->write_db($this->search_id);

		// My���󥱡��Ⱦ���񤭹���
		if ($this->my_enq_no == '') {
			$sql = "SELECT MAX(me_my_enq_no) FROM t_my_enquete WHERE me_staff_id=$this->staff_id";
			$result = db_exec($sql);
			if (pg_num_rows($result)) {
				$fetch = pg_fetch_row($result, 0);
				$this->my_enq_no = $fetch[0] + 1;
			} else
				$this->my_enq_no = 1;

			$sql = "INSERT INTO t_my_enquete (me_staff_id,me_my_enq_no,me_make_step,me_enquete_id,me_search_id,me_send_num,me_mail_title,me_mail_header,me_mail_contents,me_mail_footer,me_ans_cond) VALUES ($this->staff_id,$this->my_enq_no,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_my_enquete SET me_make_step=%s,me_enquete_id=%s,me_search_id=%s,me_send_num=%s,me_mail_title=%s,me_mail_header=%s,me_mail_contents=%s,me_mail_footer=%s,me_ans_cond=%s WHERE me_staff_id=$this->staff_id AND me_my_enq_no=$this->my_enq_no";
		}
		$sql = sprintf($sql,
				sql_number($this->make_step),
				sql_number($this->enquete_id),
				sql_number($this->search_id),
				sql_number($this->send_num),
				sql_char($this->mail_title),
				sql_char($this->mail_header),
				sql_char($this->mail_contents),
				sql_char($this->mail_footer),
				sql_char($this->ans_cond));
		db_exec($sql);

		db_commit_trans();
	}
}

function get_myenq1(&$myenq) {
	$enquete = &$myenq->enquete;

	$enquete->title = trim($_POST['title']);
	$enquete->enq_type = $_POST['enq_type'];
	$enquete->start_date_y = trim(mb_convert_kana($_POST['start_date_y'], 'n'));
	$enquete->start_date_m = trim(mb_convert_kana($_POST['start_date_m'], 'n'));
	$enquete->start_date_d = trim(mb_convert_kana($_POST['start_date_d'], 'n'));
	$enquete->end_date_y = trim(mb_convert_kana($_POST['end_date_y'], 'n'));
	$enquete->end_date_m = trim(mb_convert_kana($_POST['end_date_m'], 'n'));
	$enquete->end_date_d = trim(mb_convert_kana($_POST['end_date_d'], 'n'));
	$enquete->end_date_h = trim(mb_convert_kana($_POST['end_date_h'], 'n'));
	$enquete->point = trim(mb_convert_kana($_POST['point'], 'n'));
	$myenq->send_num = trim(mb_convert_kana($_POST['send_num'], 'n'));
	if (isset($_POST['ans_cond']))
		$myenq->ans_cond = join("\n", $_POST['ans_cond']);

	$myenq->save_enquete = true;
}

function check_myenq1(&$myenq, &$msg) {
	$enquete = &$myenq->enquete;

	if ($enquete->title == '' )
		$msg[] = '���󥱡��ȥ����ȥ�����Ϥ��Ƥ���������';

	if ($enquete->enq_type == '')
		$msg[] = '���󥱡��ȼ�������򤷤Ƥ���������';

	$date = strtotime(date('Y/m/d'));
	$start_date_ok = true;
	$end_date_ok = true;

	if ($enquete->start_date_y == '') {
		$msg[] = '����������ǯ�����Ϥ��Ƥ���������';
		$start_date_ok = false;
	} elseif (!check_num($enquete->start_date_y, date('Y'), 2999)) {
		$msg[] = '����������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$start_date_ok = false;
	}

	if ($enquete->start_date_m == '') {
		$msg[] = '���������η�����Ϥ��Ƥ���������';
		$start_date_ok = false;
	} elseif (!check_num($enquete->start_date_m, 1, 12)) {
		$msg[] = '���������η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$start_date_ok = false;
	}

	if ($enquete->start_date_d == '') {
		$msg[] = '�����������������Ϥ��Ƥ���������';
		$start_date_ok = false;
	} elseif (!check_num($enquete->start_date_d, 1, 31)) {
		$msg[] = '�����������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$start_date_ok = false;
	}

	if ($start_date_ok) {
		$start_date = strtotime("$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d");
		if ($start_date == -1) {
			$msg[] = '�������դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
			$start_date_ok = false;
		} else if ($start_date < $date) {
			$msg[] = '���������������ʹߤ����Ϥ��Ƥ���������';
			$start_date_ok = false;
		}
	}

	if ($enquete->end_date_y == '') {
		$msg[] = '��λ������ǯ�����Ϥ��Ƥ���������';
		$end_date_ok = false;
	} elseif (!check_num($enquete->end_date_y, date('Y'), 2999)) {
		$msg[] = '��λ������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	}

	if ($enquete->end_date_m == '') {
		$msg[] = '��λ�����η�����Ϥ��Ƥ���������';
		$end_date_ok = false;
	} elseif (!check_num($enquete->end_date_m, 1, 12)) {
		$msg[] = '��λ�����η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	}

	if ($enquete->end_date_d == '') {
		$msg[] = '��λ�������������Ϥ��Ƥ���������';
		$end_date_ok = false;
	} elseif (!check_num($enquete->end_date_d, 1, 31)) {
		$msg[] = '��λ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	}

	if ($enquete->end_date_h == '') {
		$msg[] = '��λ�����λ������Ϥ��Ƥ���������';
		$end_date_ok = false;
	} elseif (!check_num($enquete->end_date_h, 0, 23)) {
		$msg[] = '��λ�����λ������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	}

	if ($end_date_ok) {
		$end_date = strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d");
		if ($end_date == -1) {
			$msg[] = '��λ���դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
			$end_date_ok = false;
		} else if ($start_date_ok && $end_date < $start_date) {
			$msg[] = '��λ�����ϳ��������ʹߤ����Ϥ��Ƥ���������';
			$end_date_ok = false;
		}
	}

	if ($enquete->point == '')
		$msg[] = '�ݥ���ȿ������Ϥ��Ƥ���������';
	elseif (!check_num($enquete->point, 10, 200))
		$msg[] = '�ݥ���ȿ���10��200�����Ϥ��Ƥ���������';

	if ($myenq->send_num <= 0)
		$msg[] = '����ȯ���������Ϥ��Ƥ���������';
	elseif (!check_num($myenq->send_num, 0, 300))
		$msg[] = '����ȯ������300�Ͱʲ������Ϥ��Ƥ���������';
}

function get_myenq2(&$myenq) {
	$search = &$myenq->search;

	$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
	$myenq->age_type = $_POST['age_type'];
	if ($myenq->age_type == '1') {
		$search->age_cd = get_multi_data($_POST['age_cd']);
		$search->age_from = '';
		$search->age_to = '';
	} elseif ($myenq->age_type == '2') {
		$search->age_cd = '';
		$search->age_from = $_POST['age_from'];
		$search->age_to = $_POST['age_to'];
	} else {
		$search->age_cd = '';
		$search->age_from = '';
		$search->age_to = '';
	}
	$search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
	$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
	$search->shokugyou = get_multi_data($_POST['shokugyou']);
	$search->chain = get_multi_data($_POST['chain']);

	$myenq->save_search = true;
}

function check_myenq2(&$myenq, &$msg) {
	$search = &$myenq->search;

	if ($myenq->age_type == '1') {
		if ($search->age_cd == '')
			$msg[] = 'ǯ������򤷤Ƥ���������';
	} elseif ($myenq->age_type == '2') {
		if ($search->age_from == '' && $search->age_to == '')
			$msg[] = 'ǯ�����ꤷ�Ƥ���������';
		else {
			if ($search->age_from != '' && !check_num($search->age_from))
				$msg[] = 'ǯ��Ͽ��������Ϥ��Ƥ���������';
			if ($search->age_to != '' && !check_num($search->age_to))
				$msg[] = 'ǯ��Ͽ��������Ϥ��Ƥ���������';
			if (!$msg && $search->age_from != '' && $search->age_to != '' && (int)$search->age_from > (int)$search->age_to)
				$msg[] = 'ǯ����ϰϤ�����������ޤ���';
		}
	}
}

function get_myenq3(&$myenq) {
	$enquete = &$myenq->enquete;

	$enquete->description = trim($_POST['description']);
	for ($i = 1; $i <= MAX_ENQ; $i++) {
		$enquete->question[$i] = new question_class;
		$question = &$enquete->question[$i];
		$question->question_text = trim($_POST["question_text$i"]);
		$question->question_type = $_POST["question_type$i"];
		for ($j = 1; $j <= MAX_SEL; $j++) {
			$question->sel_text[$j] = trim($_POST["sel_text$i"][$j - 1]);
		}
		$question->fa_flag = $_POST["fa_flag$i"];
	}

	$myenq->save_enquete = true;
}

function check_myenq3(&$myenq, &$msg) {
	$enquete = &$myenq->enquete;

	if ($enquete->enq_type == 2) {
		if ($enquete->description == '')
			$msg[] = '����ʸ�����Ϥ��Ƥ���������';
	}

	if ($enquete->question[1].question_text == '')
		$msg[] = '�䣱�μ���ʸ�����Ϥ��Ƥ���������';

	for ($i = 1; $i <= MAX_ENQ; $i++) {
		$question = &$enquete->question[$i];
		if ($question->question_text == '') {
			for ($j = 1; $j <= MAX_SEL; $j++) {
				if ($question->sel_text[$j] != '') {
					$msg[] = '��' . mb_convert_kana($i, 'N') . '�μ���ʸ�����Ϥ���Ƥ��ޤ���';
					break;
				}
			}
		} else {
			$max_select = 0;
			for ($j = 1; $j <= MAX_SEL; $j++) {
				if ($question->sel_text[$j] != '')
					$max_select = $j;
			}
			if ($question->question_type != 3) {
				if ($max_select == 0)
					$msg[] = '��' . mb_convert_kana($i, 'N') . '������褬���Ϥ���Ƥ��ޤ���';
				else {
					unset($err_select);
					for ($j = 1; $j <= $max_select; $j++) {
						if ($question->sel_text[$j] == '')
							$err_select[] = mb_convert_kana($j, 'N');
					}
					if ($err_select)
						$msg[] = '��' . mb_convert_kana($i, 'N') . '�������' . join(',', $err_select) . '�����Ϥ���Ƥ��ޤ���';
				}
			} else {
				if ($max_select != 0)
					$msg[] = '��' . mb_convert_kana($i, 'N') . '�ϼ�ͳ����(FA)�ʤΤ����������ϤǤ��ޤ���';
			}
		}
	}
}

function get_myenq4(&$myenq) {
	$myenq->mail_title = trim($_POST['mail_title']);
	$myenq->mail_header = isset($_POST['mail_header']) ? trim($_POST['mail_header']) : trim($_POST['mail_header2']);
	$myenq->mail_contents = trim($_POST['mail_contents']);
	$myenq->mail_footer = isset($_POST['mail_footer']) ? trim($_POST['mail_footer']) : trim($_POST['mail_footer2']);
}

function check_myenq4(&$myenq, &$msg) {
	if ($myenq->mail_title == '')
		$msg[] = '�����ȥ�����Ϥ��Ƥ���������';

	if ($myenq->mail_contents == '')
		$msg[] = '��ʸ�����Ϥ��Ƥ���������';
}

// ��®ȯ��������������
function get_quickest_date(&$send_date_y, &$send_date_m, &$send_date_d, &$send_date_h) {
	$time = time();
	if (date('H', $time) >= 11)
		$time += 60 * 60 * 24;

	$send_date_y = date('Y', $time);
	$send_date_m = date('m', $time);
	$send_date_d = date('d', $time);
	$send_date_h = 18;
}
?>