<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowUserMenuAction.class.php,v 1.3 2007/01/31 08:02:25 shimizu Exp $
 */

require_once MO_WEBAPP_DIR .'/modules/Waiwai/lib/RoomEnquete.class.php';
class _blockShowUserMenuAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		// ログインしてなければ表示しない
		if(!$user->isAuthenticated()){
			return View::NONE;
		}
		// waiwai 未回答アンケートリスト
        $enqueteManager = new RoomEnqueteManager();
        $enquete_list = $enqueteManager->getNoAnswerEnqueteList($user->getAttribute('mbr_member_id'));
        $request->setAttribute('enquete_list', $enquete_list);

        return View::SUCCESS;
    }
    
    
    public function isSecure()
    {
        return false;
    }
}

?>