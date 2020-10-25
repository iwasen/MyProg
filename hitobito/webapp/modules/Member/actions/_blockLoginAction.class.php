<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockLoginAction.class.php,v 1.1 2005/11/22 15:47:29 ryu Exp $
 */

class _blockLoginAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		// ログイン済みなら表示しない
		if($user->isAuthenticated()){
			return View::NONE;
		}
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>