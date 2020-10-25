<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package NaviSelect
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewNaviSelectAction.class.php,v 1.3 2006/01/04 10:03:10 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
require_once MO_WEBAPP_DIR.'/modules/NaviSelect/filters/NaviSelectFilter.class.php';

class _blockShowNewNaviSelectAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();

		$manager = new BlogStoryManager();
		
		$filter = new NaviSelectFilter();
		$start = 0;
		$limit = 3;
		
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