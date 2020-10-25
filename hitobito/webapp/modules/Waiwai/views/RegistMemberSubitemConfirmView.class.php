<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 属性入力ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class RegistMemberSubitemConfirmView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('RegistMemberSubitemConfirm.html');

		// 閲覧共通設定
		$this->setBowseCommon();

		// 属性情報設定
		$this->setSubitem();

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>