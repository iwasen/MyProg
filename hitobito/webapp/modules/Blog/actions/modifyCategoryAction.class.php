<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: modifyCategoryAction.class.php,v 1.2 2006/01/05 09:02:04 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/forms/categoryForm.class.php';
require_once MO_WEBAPP_DIR . '/modules/Blog/lib/BlogBaseAction.class.php';

class ModifyCategoryAction extends BlogBaseAction
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        Navi::GuardNaviEdit($this->getContext()->getUser());
        $editform = new BlogCategoryForm();

		$manager = new BlogCategoryManager();
		
		$id = $request->getParameter('blog_category_id', 0);
//		echo $id;
		
		$master = $manager->get($id);
		if($master->isNew()){
			$master->setBlog_id($this->blog_id);
		}
		
		if($editform->init($master, $request) === HNB_ACTIONFORM_POST_SUCCESS){
			// update
			$editform->update($master, $request);
			$manager->insert($master);

			return View::SUCCESS;
		}else{
			$request->setAttribute('editform', $editform);
			return View::INPUT;
		}
    }
    
    public function isSecure()
    {
        return true;
    }
}

?>