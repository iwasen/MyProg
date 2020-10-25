<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート 最新アンケート表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class _blockNewestEnqueteSuccessView extends EnqueteBaseView
{
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('_blockNewestEnquete.html');

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
	}
}
?>