<?php
/**
 *
 *
 * 
 * 
 * @package Weblinks
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexAction.class.php,v 1.5 2006/02/14 16:57:49 ryu Exp $
 */
 
define('WEBLINKS_INDEX_LIST_LIMIT', 15);
require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/link.class.php';
require_once MO_WEBAPP_DIR.'/modules/Weblinks/lib/category.class.php';
require_once MO_WEBAPP_DIR.'/modules/Weblinks/filters/linklistFilter.class.php';
class IndexAction extends Action
{
	public function execute()
	{
		$list = new WeblinksLinkList($this->getContext());
		$list->execute();
		
		// category list
        $categoryManager = new WeblinksCategoryManager();
        $categories = $categoryManager->getCategoriesByNaviPageId(hitobito::getNaviPageInfo()->getId());
        $this->getContext()->getRequest()->setAttribute('categories', $categories);
		
		return View::SUCCESS;
	}
 
    
    public function isSecure()
    {
        return false;
    }
    

    
}
class WeblinksLinkList extends HNbAbstractListUseManagerTemplate
{
	protected function getManager()
	{
		return new WeblinksLinkManager();
	}
	protected function getFilter()
	{
		return new WeblinkLinkListFilter();
	}
	protected function getStartParameterName()
	{
		return 'weblinks_statrt';
	}
	protected function getLimit()
	{
		return WEBLINKS_INDEX_LIST_LIMIT;
	}
}


?>