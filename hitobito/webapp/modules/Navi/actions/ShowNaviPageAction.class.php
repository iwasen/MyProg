<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Navi
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowNaviPageAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class ShowNaviPageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>