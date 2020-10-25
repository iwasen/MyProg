<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminShowCategoriesAction.class.php,v 1.1 2006/05/08 19:32:23 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/category.class.php';

class adminShowCategoriesAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
		Navi::GuardNaviEdit($this->getContext()->getUser());
        
		$categoryManager = new WeblinksCategoryManager();

		// カテゴリ一覧には現在のナビページで作成されたカテゴリのみを表示する
		$categories = $categoryManager->getCategoriesByModifyNavipageId(hitobito::getNaviPageInfo()->getId());
		        
        $request->setAttribute('categories', $categories);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return true;
    }
}

?>