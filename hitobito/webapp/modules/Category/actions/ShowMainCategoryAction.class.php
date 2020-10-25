<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Category
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ShowMainCategoryAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class ShowMainCategoryAction extends Action
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