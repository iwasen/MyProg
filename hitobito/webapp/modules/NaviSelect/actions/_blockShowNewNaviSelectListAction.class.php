<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package NaviSelect
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewNaviSelectListAction.class.php,v 1.3 2006/01/31 09:37:40 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
require_once MO_WEBAPP_DIR.'/modules/NaviSelect/filters/NaviSelectFilter.class.php';

class _blockShowNewNaviSelectListAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		$manager = new BlogStoryManager();
		
		$filter = new NaviSelectFilter();
		$start = 0;
		$limit = 5;
		
		$filter->fetch($this->getContext());
		$criteria = $filter->getCriteria();
		$criteria->setLimit($limit);
		$criteria->setStart($start);
		
		
		$stories = $manager->getOpenStories($criteria);    	
    	$request->setAttribute('itemlist', $stories);
    	
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
}

?>