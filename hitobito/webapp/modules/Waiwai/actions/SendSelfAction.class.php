<?php
/**
 * ひとびと･net ユーザ／ナビページ
 *
 * ワイワイ会議室 「この発言を自分に送る」アクション
 *
 *
 * @package
 * @author
 * @version
 */

require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');

class SendSelfAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
		$request = $this->getContext()->getRequest();

		// 発言メール送信
		$remark_id = $this->db->getRemarkId($this->room_id, $request->getParameter('waiwai_id'));
		if ($remark_id)
			$this->sendRemark($remark_id, $this->member_id);

		// 閲覧共通設定
		$this->setBrowseCommon();

		// 発言内容設定
		$this->setContents();

		// ログインメンバー情報設定
		$this->setMemberInfo();

		return View::SUCCESS;
	}

	// ■デフォルトビュー表示
	public function getDefaultView() {
		// 閲覧共通設定
		$this->setBrowseCommon();

		// 発言内容設定
		$this->setContents();

		// ログインメンバー情報設定
		$this->setMemberInfo();

		return View::INPUT;
	}

	// ■アクション実行メソッド指定
	public function getRequestMethods() {
		return Request::POST;
	}

	// ■認証を要求
    public function isSecure() {
        return true;
    }
}
?>