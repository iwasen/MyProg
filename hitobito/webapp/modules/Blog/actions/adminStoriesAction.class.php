<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminStoriesAction.class.php,v 1.7 2006/01/26 06:52:25 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class AdminStoriesAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        Navi::GuardNaviEdit($this->getContext()->getUser());
        
      	$category_id = $request->getParameter('blog_category_id');
      	  
        if($request->hasParameter('blog_filter_category_x')){
			$url = 'index.php?module=Blog&action=adminStories&navipage_id='.hitobito::getNaviPageInfo()->getId();
			$url .=($category_id > 0) ? '&blog_category_id='.$category_id : '';
			$controller->redirect($url);
			return View::NONE;
		}
        
        if($request->getParameter('blog_modifyCategory_x')){
			$url = sprintf('index.php?module=Blog&action=modifyCategory&blog_category_id=%d&navipage_id=%d',
				intval($request->getParameter('blog_category_id')),
				intval($request->getParameter('navipage_id')));
			$controller->redirect($url);
        }
        
        if($request->getParameter('blog_deleteCategory_x')){
        	$request->setAttribute('blog_command', 'delete');
        	$controller->forward('Blog', 'adminCategory');
        }
        if($request->getParameter('blog_orderUpCategory_x')){
        	$request->setAttribute('blog_command', 'orderup');
        	$controller->forward('Blog', 'adminCategory');
        }
        if($request->getParameter('blog_orderDownCategory_x')){
        	$request->setAttribute('blog_command', 'orderdown');
        	$controller->forward('Blog', 'adminCategory');
        }


        if($request->getParameter('blog_modifyStory_x')){
			$url = sprintf('index.php?module=Blog&action=PostStory&blog_story_id=%d&navipage_id=%d',
				intval($request->getParameter('blog_story_id')),
				intval($request->getParameter('navipage_id')));
			$controller->redirect($url);
        }

        if($request->getParameter('blog_deleteStory_x')){
        	$request->setAttribute('blog_command', 'delete');
        	$controller->forward('Blog', 'adminStory');
        }
        if($request->getParameter('blog_orderUpStory_x')){
        	$request->setAttribute('blog_command', 'orderup');
        	$controller->forward('Blog', 'adminStory');
        }
        if($request->getParameter('blog_orderDownStory_x')){
        	$request->setAttribute('blog_command', 'orderdown');
        	$controller->forward('Blog', 'adminStory');
        }
        
        
        return View::NONE;
    }

    public function getDefaultView()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
        
        $manager = new BlogCategoryManager();
        $categories = $manager->getCategoriesByBlogId($this->blog_id);
        
        $request->setAttribute('blog_categories', $categories);
        
        $storyManager = new BlogOneBlogStoryManager($this->blog_id);
        $category_id = intval($request->getParameter('blog_category_id'));
        $stories = $storyManager->getStoriesByCategoryId($category_id);
        $request->setAttribute('blog_stories', $stories);
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return true;
    }

    public function getRequestMethods()
    {
        
        return Request::POST;
    }
    
}

?>