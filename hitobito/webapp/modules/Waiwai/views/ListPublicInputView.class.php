<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 一般ユーザーからの投稿リストビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class ListPublicInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('ListPublicInput.html');

		// 一般ユーザーからの投稿リスト設定
		$public_list_ary = $request->getAttribute('waiwai_public_list_ary');
		$this->setAttribute('waiwai_public_list_ary', $this->formatList($public_list_ary));

		// メインテンプレート
		$this->useMainTemplate();
	}

	// ■リストを表示用にフォーマット
	private function formatList($list_ary) {
		$format_list_ary = array();
		if (is_array($list_ary)) {
			foreach ($list_ary as $list) {
				switch ($list['status']) {
				case 1:
					$list['status'] = '未投稿';
					break;
				case 2:
					$list['status'] = '投稿済';
					break;
				case 3:
					$list['status'] = '非承認';
					break;
				case 4:
					$list['status'] = 'お断り';
					break;
				}

				$list['date'] = $this->format_date($list['date'], '-');

				$format_list_ary[] = $list;
			}
		}

		return $format_list_ary;
	}
}

?>