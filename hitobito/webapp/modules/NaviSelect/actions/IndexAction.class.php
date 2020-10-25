<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package NaviSelect
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: IndexAction.class.php,v 1.2 2006/01/31 09:37:40 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
require_once MO_WEBAPP_DIR.'/modules/NaviSelect/filters/NaviSelectFilter.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoPageNavigation.class.php';
class IndexAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    
    
		$manager = new BlogStoryManager();
		
		$filter = new NaviSelectFilter();
		$start = intval($request->getParameter('navi_select_start'));
		$limit = 15;
		
		$filter->fetch($this->getContext());
		$criteria = $filter->getCriteria();
		$criteria->setLimit($limit);
		$criteria->setStart($start);
		
		$total = $manager->getOpenCount($criteria);
		$request->setAttribute('total', $total);
		
		$stories = $manager->getOpenStories($criteria);    	
    	$request->setAttribute('itemlist', $stories);
    	
    	$request->setAttribute('start_num', $start + 1);
    	$request->setAttribute('end_num', $start + count($stories));
    	
    	$navigation = new hitobitoPageNavigation($total, $limit, $start, 'navi_select_start');
    	$navigation->addParameter('module', 'NaviSelect');
    	$navigation->addParameters($filter->getExtraParameters());
    	$request->setAttribute('navigation', $navigation);
    	
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    

    
}



?>