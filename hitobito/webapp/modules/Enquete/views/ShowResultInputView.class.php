<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート結果入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class ShowResultInputView extends EnqueteBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('ShowResultInput.html');

		// アンケート登録情報
		$enquete_data = $request->getAttribute('enquete_data');
		$enquete_data['start_date'] = $this->format_date($enquete_data['start_date']);
		$enquete_data['end_date'] = $this->format_date($enquete_data['end_date']);

		// アンケート選択肢項目
		$enquete_option = $enquete_data['option'];
		$option_ary = array();
		if (is_array($enquete_option)) {
			foreach ($enquete_option as $option) {
				$answer_count = $enquete_data['answer_count'];
				$option['bgcolor'] = $option['option_no'] % 2 ? '#E6F2FF' : '#FFFFFF';
				$option['percent'] = $answer_count ? round($option['answer_count'] / $answer_count * 100) : 0;
				$option['bar_width'] = $answer_count ? round($option['answer_count'] / $answer_count * 200) : 0;
				$option_ary[] = $option;
			}
		}
		$enquete_data['option_ary'] = $option_ary;
		$this->setAttribute('enquete_data', $enquete_data);

		// コメント情報設定
		$enquete_comment = $request->getAttribute('enquete_comment');
		$comment_log = $enquete_comment['log'];
		$comment_num = 0;
		$comment_log_ary = array();
		if (is_array($comment_log)) {
			foreach ($comment_log as $comment) {
				$comment['date'] = $this->format_datetime($comment['date']);
				$comment_log_ary[] = $comment;
				$comment_num++;
			}
		}
		$enquete_comment['comment_num'] = $comment_num;
		$enquete_comment['comment_log_ary'] = $comment_log_ary;
		$this->setAttribute('enquete_comment', $enquete_comment);

		// トラックバック情報設定
		$enquete_trackback = $request->getAttribute('enquete_trackback');
		$trackback_log = $enquete_trackback['log'];
		$trackback_num = 0;
		$trackback_log_ary = array();
		if (is_array($trackback_log)) {
			foreach ($trackback_log as $trackback) {
				$trackback['date'] = $this->format_datetime($trackback['date']);
				$trackback_log_ary[] = $trackback;
				$trackback_num++;
			}
		}
		$enquete_trackback['trackback_num'] = $trackback_num;
		$enquete_trackback['trackback_log_ary'] = $trackback_log_ary;
		$this->setAttribute('enquete_trackback', $enquete_trackback);

		// 上部メッセージ設定
		if ($request->getAttribute('enquete_answer_ok'))
			$this->setAttribute('enquete_answer_msg', 'アンケート回答ありがとうございました。');
		if ($request->getAttribute('enquete_comment_ok'))
			$this->setAttribute('enquete_answer_msg', 'コメント投稿ありがとうございました。');
		if ($request->getAttribute('enquete_comment_ng'))
			$this->setAttribute('enquete_answer_msg', '投稿に際し入力をご遠慮いただきたい言葉がはいっているため、<br>ご投稿を反映できませんでした。');

		// 多重投稿を防止フラグ設定
		$this->setAttribute('enquete_comment_flag', $request->getAttribute('enquete_comment_flag'));

		// エラーメッセージ設定
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>