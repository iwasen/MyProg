<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 メンバーアンケート確認ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EnqueteConfirmView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EnqueteConfirm.html');

		$data =	$request->getAttribute('waiwai_enq_ary');
		$this->setAttribute('waiwai_enq_ary', $data);


		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>