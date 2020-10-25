<?
/******************************************************
' System :�������ơ�net����
' Content:���󥱡��ȥ᡼������
'******************************************************/

define('EM_QUESTION', '����');
define('EM_ANSWER', '������');
define('EM_FA_START', '--------�ʲ������������--------');
define('EM_FA_END', '--------�ʲ��������ޤǡ�--------');

// ���󥱡��ȥ᡼������
function make_enq_mail($enquete, $header, $body, $footer) {
	return trim($header) . "\n\n" . str_replace('%ENQUETE%', make_question($enquete), trim($body)) . "\n\n\n" . trim($footer);
}

// �᡼�����ʸ����
function make_question($enquete) {
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			$question_text = trim($question->question_text);
			if ($question_text != '') {
				// ����ʸ
				$text .= format_line(EM_QUESTION . mb_convert_kana($qno, 'N') . '��' . $question_text);
				$text .= "\n\n";

				// �����
				$sel_num = 0;
				if (is_array($question->sel_text)) {
					$ary = array();
					foreach ($question->sel_text as $sno => $sel_text) {
						$sel_text = trim($sel_text);
						if ($sel_text != '')
							$ary[$sno] = $sel_text;
					}

					if ($question->fa_flag == 't') {
						$last_sel_text = end($ary);
						unset($ary[key($ary)]);
					}

					foreach ($ary as $sno => $sel_text) {
						$sel_text = trim($sel_text);
						if ($question->question_type != 3)
							$text .= '��' . str_pad(mb_convert_kana($sno, 'N'), 4, " ", STR_PAD_LEFT) . "��$sel_text\n";
							$sel_num++;
					}

					if ($question->question_type != 3)
						$text .= "\n";
				}

				// ������
				switch ($question->question_type) {
				case 1:		// ñ�������
					$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . "���㡡���������ҤȤĤ����Ӥ���������\n";
					if ($question->fa_flag == 't')
						$text .= "��������${last_sel_text}�С�����������������\n";
					break;
				case 2:		// ʣ�������
					$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . '��' . str_repeat('�㡡��', min($sel_num, 10)) . "\n";
					if ($sel_num > 10)
						$text .= "��������" . str_repeat('�㡡��', min($sel_num-10, 10)) . "\n";
					if ($question->fa_flag == 't')
						$text .= "��������${last_sel_text}�С�����������������\n";
					$text .= "�������ʤ��ƤϤޤ������Ƥ����Ӥ���������\n";
					break;
				case 3:		// ��ͳ����
					if ($sel_num == 0) {
						$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . "���ʼ�ͳ�ˤ���������������\n";
						$text .= EM_FA_START . "\n\n\n\n\n";
						$text .= EM_FA_END . "\n";
					} else {
						$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . "���ʥ��å���ˤ���������������\n";
						foreach ($question->sel_text as $sno => $sel_text) {
							$text .= '��' . str_pad(mb_convert_kana($sno, 'N'), 4, " ", STR_PAD_LEFT) . "��${sel_text}�С�����������������\n";
						}
					}
					break;
				}

				$text .= "\n\n";
			}
		}
	}

	return rtrim($text);
}

// ���ޤ��֤�����
function format_line($text) {
	return $text;
}
?>