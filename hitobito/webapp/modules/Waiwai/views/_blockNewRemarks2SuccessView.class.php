<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 最新ワイワイ会議投稿ブロックビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class _blockNewRemarks2SuccessView extends WaiwaiBaseView {
	// ■ビュー実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('_blockNewRemarks2.html');

		$waiwai_list_ary = $request->getAttribute('waiwai_list_ary');
		$this->setAttribute('waiwai_list_ary', $waiwai_list_ary);
	}
}
?>