<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 スレッド移動入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class MoveThreadInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('MoveThreadInput.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 発言ID設定
		$this->setAttribute('waiwai_move', $request->getAttribute('waiwai_move'));

		// エラーメッセージ設定
		$this->setAttribute('waiwai_error_ary', $request->getErrors());

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>