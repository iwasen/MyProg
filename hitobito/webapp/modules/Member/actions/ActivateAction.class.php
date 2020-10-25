<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Member
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: ActivateAction.class.php,v 1.5 2006/01/22 13:12:17 ryu Exp $
 */

class ActivateAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		
		$id = intval($request->getParameter('member_id'));
		$actkey = $request->getParameter('member_actkey');
		
		$handler = HNb::getHandler('MemberManager');
		if ($handler->activate($id, $actkey)){
			$navipage_id = intval($request->getParameter('navipage_id'));
			if($navipage_id > 0){
				$controller->redirect('index.php?module=Waiwai&action=NewRegistStep2&navipage_id='.$navipage_id);
				return View::NONE;
			}
			return View::SUCCESS;
		}else{
			  return View::ERROR;
		}

        
    }


    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        return View::INPUT;
    }

    public function getRequestMethods()
    {
        
        return Request::GET;
    }

    public function isSecure()
    {
        return false;
    }
}

?>