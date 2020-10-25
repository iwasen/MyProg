<?php

/**
 * 
 * 
 * 
 * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockCategoryMenuAction.class.php,v 1.2 2006/01/18 20:42:34 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/category.class.php';
class _blockCategoryMenuAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

        $manager = new WeblinksCategoryManager();
        $navipage_id = hitobito :: getNaviPageInfo()->getId();
//        if ($navipage_id > 0)
//        {
            $itemlist = $manager->getCategoriesByNavipageId($navipage_id);
//            $itemlist = $manager->getCategories();
            $request->setAttribute('itemlist', $itemlist);
            return View::SUCCESS;
//        }

//        return View :: NONE;
    }
}
?>