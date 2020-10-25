<?php
/**
 * ナビエディット・リンク一覧
 *
 * [Description]
 * * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: adminShowItemsAction.class.php,v 1.1 2006/05/08 19:32:23 ryu Exp $
 */
require_once MO_WEBAPP_DIR .'/modules/Weblinks/lib/link.class.php';
class adminShowItemsAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		Navi::GuardNaviEdit($this->getContext()->getUser());


        $itemManager = new WeblinksLinkManager();
        $category_id = intval($request->getParameter('weblinks_category_id'));
        $itemlist = $itemManager->getItemsByCategoryId($category_id);
        $request->setAttribute('itemlist', $itemlist);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return true;
    }
}

?>