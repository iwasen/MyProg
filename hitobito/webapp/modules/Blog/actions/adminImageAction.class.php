<?php
/**
 * [ShortDescription]てすと
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminImageAction.class.php,v 1.5 2006/06/02 01:15:18 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogImage.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoPageNavigation.class.php';

class adminImageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        Navi::GuardNaviEdit($this->getContext()->getUser());
		$id = intval($request->getParameter('blog_image_id'));
		$manager = new BlogImageManager();
		
		$obj= $manager->get($id);
		switch($this->getCommand()){
			case 'delete':
				$manager->delete($obj);
				$request->setAttribute('message', '削除しました');
				$request->setAttribute('return_url', 'index.php?module=Blog&amp;action=adminImage&amp;navipage_id='.hitobito::getNaviPageInfo()->getId());
				break;
			case 'edit':
				$controller->redirect('index.php?module=Blog&action=editImage&navipage_id='.hitobito::getNaviPageInfo()->getId().'&blog_image_id='.$id);
				return View::NONE;
			default:
				return View::ERROR;
		}
        
        return View::SUCCESS;
    }

	private function getCommand()
	{
        $request = $this->getContext()->getRequest();
        if($request->hasParameter('blog_image_delete_x')){
        	return 'delete';
        }
        if($request->hasParameter('blog_image_edit_x')){
        	return 'edit';
        }
	}
		

    public function getDefaultView ()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		$blogId = hitobito::getNaviPageInfo()->getBlog_id();
		
		$handler = new BlogImageManager();
		$criteria = new HNbCriteria();
		$criteria->setWhere('bli_blog_id='.$blogId);
		
		

		$start = intval($request->getParameter('blog_start'));
		$limit = 20;
		
		$criteria->setLimit($limit);
		$criteria->setStart($start);

		$itemlist = $handler->getObjects($criteria);
		$request->setAttribute('itemlist', $itemlist);
		$total = $handler->getCount($criteria);

        $navigation = new hitobitoPageNavigation($total, $limit, $start, 'blog_start');
        $navigation->addParameter('module', 'Blog');
        $navigation->addParameter('action', 'adminImage');
        $navigation->addParameter('navipage_id', hitobito::getNaviPageInfo()->getId());
        $request->setAttribute('navigation', $navigation);

        return View::INPUT;
    }
    
//    private function isEdit()
//    {
//        $user = $this->getContext()->getUser();
//        if($user->isMyNaviPage()){
//        	return TRUE;
//        }else{
//        	return FALSE;
//        }
//    }
    	

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