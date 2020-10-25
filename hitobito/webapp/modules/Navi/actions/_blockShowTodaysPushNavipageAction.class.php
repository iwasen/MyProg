<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowTodaysPushNavipageAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';
class _blockShowTodaysPushNavipageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        // ナビランキング
        $naviManager = new hitobitoNavipageManager();
        $todayspush = $naviManager->getTodaysPushNavipage();
        $request->setAttribute('navi_todays_push', $todayspush);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>