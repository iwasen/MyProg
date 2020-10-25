<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 ワイワイ会議室編集メニュービュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseView.class.php');

class EditMenuInputView extends WaiwaiBaseView {
	// ■ビュー実行
	function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('EditMenuInput.html');

		// お知らせメッセージ
		$waiwai_notice_ary = array();
		$waiwai_notice_flag = $request->getAttribute('waiwai_notice_flag');
		if ($waiwai_notice_flag['guest_member'] == 't')
			$waiwai_notice_ary[] = 'ゲスト登録メンバーがいます。承認作業を行なってください。';
//		if ($waiwai_notice_flag['public_post'] == 't')
//			$waiwai_notice_ary[] = '一般ユーザーからの投稿待ちがあります。承認作業を行なってください。';
		$this->setAttribute('waiwai_notice_ary', $waiwai_notice_ary);
		$this->setAttribute('waiwai_notice_flag', $waiwai_notice_flag);

		// メインテンプレート
		$this->useMainTemplate();
	}
}

?>