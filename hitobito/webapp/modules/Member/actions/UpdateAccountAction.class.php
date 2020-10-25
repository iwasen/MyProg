<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: UpdateAccountAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class UpdateAccountAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
		$MemberManager = HNb::getHandler('MemberManager');
		$master = $MemberManager->get($user->getAttribute('id', 'Member'));

		$vars = $user->getAttribute('confirmVars', 'Member');
		
		$master->setAttributes($vars);
		$MemberManager->insert($master);
		
		$user->setAttributes($master->getAttributes());

		
        return View::SUCCESS;
    }


    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		$controller->forward('Member', 'Edit');
        return View::NONE;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }

    public function isSecure()
    {
        return true;
    }
}

?>