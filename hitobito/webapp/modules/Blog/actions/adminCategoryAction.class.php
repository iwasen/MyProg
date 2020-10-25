<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminCategoryAction.class.php,v 1.4 2006/01/26 06:52:25 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class adminCategoryAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        Navi::GuardNaviEdit($user);

		$id = $request->getParameter('blog_category_id');
		$manager = new BlogCategoryManager();
		
		$obj= $manager->get($id);
		if(!$obj->getId()){
			$request->setAttribute('message', HNb::tr('カテゴリが選択されていません'));
			return View::ERROR;
		}
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
                $category_id = $obj->getId();
                $BlogStoryManager = new BlogStoryManager();
                if($BlogStoryManager->getCount('bla_blog_category_id='.$category_id)){
                	$request->setAttribute('message', HNb::tr('このカテゴリの記事が存在するので削除できません'));
                	return View::ERROR;
                }
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