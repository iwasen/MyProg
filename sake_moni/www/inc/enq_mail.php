<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
' Content:アンケートメール生成
'******************************************************/

define('EM_QUESTION', '▼Ｑ');
define('EM_ANSWER', '◎→Ａ');
define('EM_FA_START', '--------（回答ここから）--------');
define('EM_FA_END', '--------（回答ここまで）--------');

// アンケートメール生成
function make_enq_mail($enquete, $header, $body, $footer) {
	return trim($header) . "\n\n" . str_replace('%ENQUETE%', make_question($enquete), trim($body)) . "\n\n\n" . trim($footer);
}

// メール質問文作成
function make_question($enquete) {
	if (is_array($enquete->question)) {
		foreach ($enquete->question as $qno => $question) {
			$question_text = trim($question->question_text);
			if ($question_text != '') {
				// 質問文
				$text .= format_line(EM_QUESTION . mb_convert_kana($qno, 'N') . '．' . $question_text);
				$text .= "\n\n";

				// 選択肢
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
							$text .= '　' . str_pad(mb_convert_kana($sno, 'N'), 4, " ", STR_PAD_LEFT) . "．$sel_text\n";
							$sel_num++;
					}

					if ($question->question_type != 3)
						$text .= "\n";
				}

				// 回答欄
				switch ($question->question_type) {
				case 1:		// 単一選択肢
					$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . "．＜　＞（選択肢をひとつお選びください）\n";
					if ($question->fa_flag == 't')
						$text .= "　　　　${last_sel_text}｛　　　　　　　　｝\n";
					break;
				case 2:		// 複数選択肢
					$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . '．' . str_repeat('＜　＞', min($sel_num, 10)) . "\n";
					if ($sel_num > 10)
						$text .= "　　　　" . str_repeat('＜　＞', min($sel_num-10, 10)) . "\n";
					if ($question->fa_flag == 't')
						$text .= "　　　　${last_sel_text}｛　　　　　　　　｝\n";
					$text .= "　　　（あてはまるもの全てをお選びください）\n";
					break;
				case 3:		// 自由回答
					if ($sel_num == 0) {
						$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . "．（自由にご記入ください）\n";
						$text .= EM_FA_START . "\n\n\n\n\n";
						$text .= EM_FA_END . "\n";
					} else {
						$text .= EM_ANSWER . mb_convert_kana($qno, 'N') . "．（カッコ内にご記入ください）\n";
						foreach ($question->sel_text as $sno => $sel_text) {
							$text .= '　' . str_pad(mb_convert_kana($sno, 'N'), 4, " ", STR_PAD_LEFT) . "．${sel_text}｛　　　　　　　　｝\n";
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

// 行折り返し処理
function format_line($text) {
	return $text;
}
?>