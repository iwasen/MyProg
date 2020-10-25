<?
/******************************************************
' System :�������ơ�net����
' Content:���󥱡������ƥ����å�
'******************************************************/

// ���󥱡��ȵ���
define('EM_ENQ_START', '=�ʥ��󥱡��Ȥ��������=');
define('EM_ENQ_END', '=�ʥ��󥱡��Ȥ����ޤǡ�=');
define('EM_QUESTION', '����');
define('EM_ANSWER', '������');
define('EM_FA_START', '-�ʲ������������-');
define('EM_FA_END', '-�ʲ��������ޤǡ�-');

// state
define('ST_NULL', 0);
define('ST_ENQUETE', 1);
define('ST_QUESTION', 2);
define('ST_ANSWER', 3);
define('ST_FA', 4);

// event
define('EV_NULL', 0);
define('EV_ENQ_START', 1);
define('EV_ENQ_END', 2);
define('EV_QUESTION', 3);
define('EV_ANSWER', 4);
define('EV_FA_START', 5);
define('EV_FA_END', 6);

// ���󥱡������ƥ����å�
function enq_check($body) {
	$ary = explode("\n", $body);

	$st = ST_NULL;
	$next_qno = 1;

	foreach ($ary as $lno => $line) {
		$line = trim(mb_convert_kana($line, 's'));

		$ev = EV_NULL;
		if (mb_strpos($line, EM_ENQ_START) !== false)
			$ev = EV_ENQ_START;
		elseif (mb_strpos($line, EM_ENQ_END) !== false)
			$ev = EV_ENQ_END;
		elseif (($no = get_qa_no($line, EM_QUESTION)) != 0) {
			$qno = $no;
			$ev = EV_QUESTION;
		} elseif (($no = get_qa_no($line, EM_ANSWER)) != 0) {
			$ano = $no;
			$ev = EV_ANSWER;
		} elseif (mb_strpos($line, EM_FA_START) !== false)
			$ev = EV_FA_START;
		elseif (mb_strpos($line, EM_FA_END) !== false)
			$ev = EV_FA_END;

		// ��������
		switch ($st) {
		case ST_NULL:
			switch ($ev) {
			case EV_ENQ_START:
				$st = ST_ENQUETE;
				break;
			}
			break;
		case ST_ENQUETE:
			switch ($ev) {
			case EV_ENQ_START:
				enq_err('�����ʥ��󥱡��ȳ��ϵ���Ǥ�', $lno);
				break;
			case EV_ENQ_END:
				$st = ST_NULL;
				break;
			case EV_QUESTION:
				if ($qno != $next_qno)
					enq_err('�����ֹ椬Ϣ³���Ƥ��ޤ���', $lno);
				$next_qno = $qno + 1;
				$next_sno = 1;
				$question[$qno] = new question_class;
				$q = &$question[$qno];
				$st = ST_QUESTION;
				break;
			case EV_ANSWER:
				enq_err('���䵭�椬����ޤ���', $lno - 1);
				break;
			case EV_FA_START:
				enq_err('���䵭�椬����ޤ���', $lno - 1);
				break;
			case EV_FA_END:
				enq_err('�ƣ����ϵ��椬����ޤ���', $lno - 1);
				break;
			}
			break;
		case ST_QUESTION:
			switch ($ev) {
			case EV_ENQ_START:
				enq_err('�����ʥ��󥱡��ȳ��ϵ���Ǥ�', $lno);
				break;
			case EV_ENQ_END:
				enq_err('�����ʥ��󥱡��Ƚ�λ����Ǥ�', $lno);
				break;
			case EV_QUESTION:
				enq_err('�����󤬤���ޤ���', $lno - 1);
				break;
			case EV_ANSWER:
				if ($ano != $qno)
					enq_err('�����ֹ椬�����ֹ�Ȱ��פ��Ƥ��ޤ���', $lno);
				$sa_count = 0;
				$fa_count = 0;
				$st = ST_ANSWER;
				break;
			case EV_FA_START:
				if ($q->sel_text)
					enq_err('�����ȼ�ͳ�������Ʊ���˽񤱤ޤ���', $lno);
				$st = ST_FA;
				break;
			case EV_FA_END:
				$q->question_type = 3;
				enq_err('�ƣ����ϵ��椬����ޤ���', $lno - 1);
				break;
			}
			break;
		case ST_ANSWER:
			switch ($ev) {
			case EV_ENQ_START:
				enq_err('�����ʥ��󥱡��ȳ��ϵ���Ǥ�', $lno);
				break;
			case EV_ENQ_END:
				question_end_check($q, $lno - 1);
				$st = ST_NULL;
				break;
			case EV_QUESTION:
				question_end_check($q, $lno - 1);
				if ($qno != $next_qno)
					enq_err('�����ֹ椬Ϣ³���Ƥ��ޤ���', $lno);
				$next_qno = $qno + 1;
				$next_sno = 1;
				$question[$qno] = new question_class;
				$q = &$question[$qno];
				$st = ST_QUESTION;
				break;
			case EV_ANSWER:
				enq_err('���䵭�椬����ޤ���', $lno);
				break;
			case EV_FA_START:
				if ($q->sel_text)
					enq_err('�����ȼ�ͳ�������Ʊ���˽񤱤ޤ���', $lno);
				$q->question_type = 3;
				$st = ST_FA;
				break;
			case EV_FA_END:
				$q->question_type = 3;
				enq_err('�ƣ����ϵ��椬����ޤ���', $lno - 1);
				break;
			}
			break;
		case ST_FA:
			switch ($ev) {
			case EV_ENQ_START:
				enq_err('�����ʥ��󥱡��ȳ��ϵ���Ǥ�', $lno);
				break;
			case EV_ENQ_END:
				enq_err('�����ʥ��󥱡��Ƚ�λ����Ǥ�', $lno);
				break;
			case EV_QUESTION:
				enq_err('�ƣ���λ���椬����ޤ���', $lno - 1);
				if ($qno != $next_qno)
					enq_err('�����ֹ椬Ϣ³���Ƥ��ޤ���', $lno);
				$next_qno = $qno + 1;
				$next_sno = 1;
				$question[$qno] = new question_class;
				$q = &$question[$qno];
				$st = ST_QUESTION;
				break;
			case EV_ANSWER:
				enq_err('�ƣ���λ���椬����ޤ���', $lno - 1);
				break;
			case EV_FA_START:
				enq_err('�ƣ���λ���椬����ޤ���', $lno - 1);
				break;
			case EV_FA_END:
				$q->question_type = 3;
				question_end_check($q, $lno);
				$st = ST_ENQUETE;
				break;
			}
			break;
		}

		// ���֤��Ȥν���
		switch ($st) {
		case ST_QUESTION:
			$ary = get_sel_no($line);
			if (is_array($ary)) {
				foreach ($ary as $sno => $sel_text) {
					if ($sno != $next_sno)
						enq_err('������ֹ椬Ϣ³���Ƥ��ޤ���', $lno);
					$next_sno = $sno + 1;
					$q->sel_text[$sno] = $sel_text;
				}
			} else {
				if ($next_sno == 1)
					$q->question_text .= "$line\n";
			}
			break;
		case ST_ANSWER:
			$sa_count += sa_count($line);
			if ($sa_count == 1)
				$q->question_type = 1;
			elseif ($sa_count > 1)
				$q->question_type = 2;

			if (($fa_text = get_fa_text($line)) != '') {
				if ($q->question_type == 1 || $q->question_type == 2) {
					if ($q->fa_flag == 't')
						enq_err('����¾�μ�ͳ������ϣ��İʾ�񤱤ޤ���', $lno);
					$q->sel_text[$next_sno++] = $fa_text;
					$q->fa_flag = 't';
				} else {
					$q->sel_text[++$fa_count] = $fa_text;
					$q->question_type = 3;
				}
			}
			break;
		}
	}

	if (is_array($question)) {
		foreach (array_keys($question) as $qno) {
			$q = &$question[$qno];
			$q->question_text = trim($q->question_text);
		}
	}

	if ($next_qno == 1)
		enq_err('���󥱡��Ȥ�����ޤ���', $lno);

	if ($st != ST_NULL) {
		if ($st == ST_ANSWER)
			question_end_check($q, $lno);

		enq_err('���󥱡��Ƚ�λ���椬����ޤ���', $lno);
	}

	return $question;
}

// ���䡢�����ֹ����
function get_qa_no(&$s, $em) {
	if (strncmp($s, $em, strlen($em)) == 0) {
		if (($p = mb_strpos($s, '��')) !== false) {
			$emlen = mb_strlen($em);
			$len = $p - $emlen;
			if ($len > 0 && $len < 5) {
				$ns = mb_convert_kana(mb_substr($s, $emlen, $len), 'n');
				if (is_numeric($ns)) {
					$s = mb_substr($s, $p + 1);
					$n = (int)$ns;
				}
			}
		}
	}
	return $n;
}

// ������ֹ����
function get_sel_no(&$s) {
	$ary = explode(' ', $s);
	if (is_array($ary)) {
		foreach ($ary as $ss) {
			if (($p = mb_strpos($ss, '��')) !== false) {
				$ns = mb_convert_kana(mb_substr($ss, 0, $p), 'n');
				if (is_numeric($ns))
					$sno = (int)$ns;
				$p++;
			}
			if ($sno) {
				if ($sno_ary[$sno] != '')
					$sno_ary[$sno] .= ' ';
				$sno_ary[$sno] .= mb_substr($ss, $p);
			}
		}
	}
	return $sno_ary;
}

// ����������������
function sa_count(&$s) {
	return substr_count(str_replace(' ', '', $s), '���');
}

// ��ͳ���������
function get_fa_text(&$s) {
	if (($p1 = mb_strpos($s, '��')) !== false) {
		if (($p2 = mb_strpos($s, '��', $p1)) !== false) {
			$fa_text = mb_substr($s, 0, $p1);
		}
	}
	return $fa_text;
}

// ���佪λ�����å�
function question_end_check(&$q, $lno) {
	if ($q->question_text == '')
		enq_err('���󥱡��ȼ���ʸ������ޤ���', $lno);

	if ($q->question_type != 3 && !$q->sel_text)
		enq_err('����褬����ޤ���', $lno);
}

// ���󥱡��ȥ��顼����
function enq_err($msg, $lno) {
	global $enq_err;

	$enq_err[$lno][] = $msg;
}
?>