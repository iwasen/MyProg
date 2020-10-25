<?php
/**
 *
 *
 * 
 * 
 * @package hitobito2
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: SearchAction.class.php,v 1.2 2006/01/25 14:19:01 ryu Exp $
 */
class SearchAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    
		$actionChain = new ActionChain();
		if($request->getParameter('search_target') == 'story' || $request->getParameter('search_target') == 'both'){
			$actionChain->register('blog_search', 'Blog', 'BlogSearch');
		}
		if($request->getParameter('search_target') == 'room' || $request->getParameter('search_target') == 'both'){
			$actionChain->register('room_search', 'Waiwai', 'RoomSearch');
			$actionChain->register('old_ml_search', 'Waiwai', 'MailBBSSearch');
		}
		
		$actionChain->execute($this->getContext());
		
		if($request->getParameter('search_target') == 'story' || $request->getParameter('search_target') == 'both'){
	        $request->setAttribute('blog_search_result', $actionChain->fetchResult('blog_search'));
		}
		if($request->getParameter('search_target') == 'room' || $request->getParameter('search_target') == 'both'){
        	$request->setAttribute('room_search_result', $actionChain->fetchResult('room_search'));
        	$request->setAttribute('old_ml_search_result', $actionChain->fetchResult('old_ml_search'));
		}
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    
}



?>