<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ナビアンケート アンケート編集一覧入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Enquete/lib/EnqueteBaseView.class.php');

class NaviEnqueteInputView extends EnqueteBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('NaviEnqueteInput.html');

		// アンケートリスト設定
		$enquete_list_ary = $request->getAttribute('enquete_list_ary');
		$this->setAttribute('enquete_list_ary', $this->formatList($enquete_list_ary));

		// 選択中のアンケートID設定
		$this->setAttribute('enquete_id', $request->getAttribute('enquete_id'));

		// エラーメッセージ設定
		$this->setAttribute('enquete_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}

	// ■リストを表示用にフォーマット
	private function formatList($list_ary) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			$no = 1;
			foreach ($list_ary as $list) {
				$list['no'] = $no++;
				$list['start_date'] = $this->format_date($list['start_date'], '-');

				switch ($list['status']) {
				case 1:
					$list['status'] = '未実施';
					break;
				case 2:
					$list['status'] = '実施中';
					break;
				case 3:
					$list['status'] = '終了';
					break;
				}

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}
}

?>