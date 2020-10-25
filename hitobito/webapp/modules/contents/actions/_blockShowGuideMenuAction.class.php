<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package contents
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowGuideMenuAction.class.php,v 1.1 2006/03/11 11:53:23 ryu Exp $
 */

class _blockShowGuideMenuAction extends Action
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