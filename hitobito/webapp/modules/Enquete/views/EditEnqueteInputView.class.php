<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート編集入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class EditEnqueteInputView extends EnqueteBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditEnqueteInput.html');

		// アンケート登録情報
		$enquete_data = $request->getAttribute('enquete_data');
		$this->setYMD($enquete_data, 'start_date');
		$this->setYMD($enquete_data, 'end_date');

		// アンケート選択肢項目
		$enquete_option = $enquete_data['option'];
		$option_ary = array();
		$option_no = 1;
		if (is_array($enquete_option)) {
			foreach ($enquete_option as $option) {
				$option_ary[$option_no++] = $option['option_text'];
			}
		}
		while ($option_no <= 9)
			$option_ary[$option_no++] = '';
		$enquete_data['option_ary'] = $option_ary;
		$this->setAttribute('enquete_data', $enquete_data);

		// 日付選択肢
		$this->setAttribute('enquete_year_options', $this->getYearOptions());
		$this->setAttribute('enquete_month_options', $this->getMonthOptions());
		$this->setAttribute('enquete_day_options', $this->getDayOptions());

		// エラーメッセージ設定
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>