<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * メルマガ登録/解除アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/MailMag/lib/MailMagBaseAction.class.php');

class _blockRegistMailMagAction extends MailMagBaseAction {
	// ■アクション実行
	public function execute() {
		if(hitobito::getNaviPageInfo()->getMelmagaStatus() !=1){
			return View::NONE;
		}
		return View::INPUT;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		if(hitobito::getNaviPageInfo()->getMelmagaStatus() !=1){
			return View::NONE;
		}
		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}
}
?>