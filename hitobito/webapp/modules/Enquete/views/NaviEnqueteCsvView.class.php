<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 回答CSV出力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class NaviEnqueteCsvView extends EnqueteBaseView {
	// ■ビュー実行
	public function execute() {
	}

	// ■CSV出力
	public function &render() {
		$request = $this->getContext()->getRequest();

		$csv_ary[] = array('SEQ', '回答日時時間', 'メールアドレス', '回答時選択数字', '名前', 'コメント有無', 'コメント内容');

		$seq_no = 1;
		$room_code = $request->getAttribute('enquete_room_code');
		$room_mail_domain = $request->getAttribute('enquete_room_mail_domain');

		$answer_list = $request->getAttribute('enquete_answer_list');
		if (is_array($answer_list)) {
			foreach ($answer_list as $answer) {
				$csv = array();
				$csv[] = $seq_no++;
				$csv[] = $this->format_datetime($answer['date']);
				if ($room_code != '' && $answer['member_code'] != '')
					$csv[] = sprintf("%s-%s@%s", $room_code, $answer['member_code'], $room_mail_domain);
				else
					$csv[] = '登録なし';
				$csv[] = str_replace(',', ' ', $answer['options']);
				$csv[] = $answer['user_name'];
				$csv[] = $answer['comment'] != '' ? '有り' : '無し';
				$csv[] = $answer['comment'];

				$csv_ary[] = $csv;
			}
		}

		$this->outputCSV('enq.csv', $csv_ary);

	    $retval = null;
		return $retval;
	}
}
?>