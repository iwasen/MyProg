<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: LogoutAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class LogoutAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		$user->clearCredentials();
		$user->clearAttributes();
        $user->setAuthenticated(FALSE);
		
        return View::SUCCESS;
    }



    public function getRequestMethods()
    {
        
        return Request::POST | Request::GET;
    }

    public function isSecure()
    {
        return false;
    }
}

?>