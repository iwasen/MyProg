<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ管理 メルマガ基本設定編集ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseView.class.php');

class SettingMailMagInputView extends MailMagBaseView {
	// ■ビュー実行
	function execute(){
		$this->setTemplate('SettingMailMag.html');
		$this->setMelmagaData();

		$request = $this->getContext()->getRequest();

		// エラーメッセージ設定
		$this->setAttribute('mailmag_error_ary', $request->getErrors());

		$this->useMainTemplate();
	 }
}
?>