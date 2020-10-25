<?php
/**
 *
 *
 * 
 * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: BlogSearchAction.class.php,v 1.1 2006/01/04 02:52:43 ryu Exp $
 */
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/filters/SearchFilter.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoPageNavigation.class.php';
class BlogSearchAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    
    
        $manager = new BlogStoryManager();
        
        $filter = new BlogSearchFilter();
        $start = intval($request->getParameter('blog_start'));
        $limit = 5;
        
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
        
        $navigation = new hitobitoPageNavigation($total, $limit, $start, 'blog_start');
        $navigation->addParameter('module', 'hitobito2');
        $navigation->addParameter('action', 'Search');
        $navigation->addParameter('search_target', 'story');
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