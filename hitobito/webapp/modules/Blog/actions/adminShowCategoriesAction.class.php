<?php
/**
 * ナビエディット・カテゴリ一覧
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminShowCategoriesAction.class.php,v 1.1 2006/05/08 19:32:23 ryu Exp $
 */
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class adminShowCategoriesAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		Navi::GuardNaviEdit($this->getContext()->getUser());
        
        $manager = new BlogCategoryManager();
        $categories = $manager->getCategoriesByBlogId($this->blog_id);
        
        $request->setAttribute('blog_categories', $categories);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return true;
    }
}

?>