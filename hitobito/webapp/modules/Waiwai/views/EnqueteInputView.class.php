<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 メンバーアンケート入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EnqueteInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();

		// エラーページ
		if ($user->getAttribute('waiwai_enquete_id') == '') {
			$msg = '該当するアンケートはありません。';
			$this->setAttribute('message', $msg);

			$this->setTemplate('error.html');

		} else {

			// テンプレート設定
			$this->setTemplate('EnqueteInput.html');

			$data =	$request->getAttribute('waiwai_enq_ary');
			$this->setAttribute('waiwai_enq_ary', $data);

			// エラーメッセージ設定
			$this->setAttribute('waiwai_error_ary', $request->getErrors());
		}

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>