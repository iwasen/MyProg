<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminStoryAction.class.php,v 1.3 2006/01/26 06:52:25 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class adminStoryAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
		$id = $request->getParameter('blog_story_id');
		$manager = new BlogStoryManager();
		
		$obj= $manager->get($id);
		$blog_command = $request->getParameter('blog_command');
		if(empty($blog_command)){
			$blog_command = $request->getAttribute('blog_command');
		}
		switch($blog_command){
			case "orderup":
				$manager->orderUp($obj);
//				$request->setAttribute('message', '上へ移動しました');
				$controller->redirect('index.php?module=Blog&action=adminStories&navipage_id='.hitobito::getNaviPageInfo()->getId());
				return View::NONE;
			case 'orderdown':
				$manager->orderDown($obj);
//				$request->setAttribute('message', '下へ移動しました');
				$controller->redirect('index.php?module=Blog&action=adminStories&navipage_id='.hitobito::getNaviPageInfo()->getId());
				return View::NONE;
			case 'delete':
				$manager->delete($obj);
				$request->setAttribute('message', '削除しました');
				break;
			default:
				return View::ERROR;
		}
        
        return View::SUCCESS;
    }


    public function getDefaultView ()
    {
    	Navi::GuardNaviEdit($this->getContext()->getUser());
    	return View::ERROR;
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