<?php
/**
  * ワイワイ会議室　メンバー未回答アンケートリスト
  * @package waiwai
  *
  * @auther Ryuji
  * @version $Id: EnqueteListAction.class.php,v 1.2 2006/06/07 07:32:07 aizawa Exp $
  */


require_once(MO_MODULE_DIR . '/Waiwai/lib/WaiwaiBaseAction.class.php');
require_once(MO_MODULE_DIR . '/Waiwai/lib/RoomEnquete.class.php');

class EnqueteListAction extends WaiwaiBaseAction {
	// ■アクション実行
	public function execute() {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $enqueteManager = new RoomEnqueteManager();
        $enquete_list = $enqueteManager->getNoAnswerEnqueteList($user->getAttribute('mbr_member_id'));

        $request->setAttribute('enquete_list', $enquete_list);
        return View::SUCCESS;
	}


	public function getRequestMethods() {
		return Request::POST| Request::GET;
	}

    public function isSecure() {
        return true;
    }

}
?>