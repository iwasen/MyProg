<?php
/**
 * ナビエディット・記事一覧
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminShowStoriesAction.class.php,v 1.1 2006/05/08 19:32:23 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class adminShowStoriesAction extends BlogBaseAction
{

    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
        
        
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

    
}

?>