<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowProfileAction.class.php,v 1.1 2005/11/27 22:59:18 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';
class ShowProfileAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $id = intval($request->getParameter('navipage_id'));
        $handler = new hitobitoNavipageManager();
        $obj = $handler->get($id);
        $request->setAttribute('navipage', $obj);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>