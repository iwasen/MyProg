<?php
/**
 *
 *
 * 
 * 
 * @package Waiwai
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: RoomSearchAction.class.php,v 1.4 2006/06/07 07:32:07 aizawa Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Waiwai/lib/RoomSearch.class.php';
require_once MO_WEBAPP_DIR.'/modules/Waiwai/filters/SearchFilter.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoPageNavigation.class.php';
class RoomSearchAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    
    
        $manager = new RoomSearchManager();
        
        if($user->isAuthenticated()){
        	$filter = new RoomSearchRegistUserFilter();
        }else{
        	$filter = new RoomSearchGuestUserFilter();
        }
        $start = intval($request->getParameter('waiwai_start'));
        $limit = 5;
        
        $filter->fetch($this->getContext());
        $criteria = $filter->getCriteria();
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        
        $total = $manager->getCount($criteria);
        $request->setAttribute('total', $total);
        $stories = $manager->getObjects($criteria);
        $request->setAttribute('itemlist', $stories);
        
        $request->setAttribute('start_num', $start + 1);
        $request->setAttribute('end_num', $start + count($stories));
        
        $navigation = new hitobitoPageNavigation($total, $limit, $start, 'waiwai_start');
        $navigation->addParameter('module', 'hitobito2');
        $navigation->addParameter('action', 'Search');
        $navigation->addParameter('search_target', 'room');
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