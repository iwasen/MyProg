<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * クチコミWEB 最新クチコミ情報表示ビュー
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Kuchikomi/lib/KuchikomiBaseView.class.php');

class _blockShowKuchikomiSuccessView extends KuchikomiBaseView
{
	// ■execute
	public function execute() {
		$request = $this->getContext()->getRequest();

		// テンプレート設定
		$this->setTemplate('_blockShowKuchikomi.html');

		$this->setAttribute('kuchikomi_list_ary', $request->getAttribute('kuchikomi_list_ary'));
	}
}
?>