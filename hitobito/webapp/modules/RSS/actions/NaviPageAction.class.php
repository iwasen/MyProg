<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package RSS
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: NaviPageAction.class.php,v 1.2 2006/01/09 17:32:18 ryu Exp $
 */
//require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogStoryManager.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoNavipage.class.php';
require_once MO_WEBAPP_DIR.'/hitobito/hitobitoChannel.class.php';
require_once MO_WEBAPP_DIR.'/modules/RSS/lib/RssChannel.class.php';
class NaviPageAction extends Action
{
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
    
		mb_http_output('UTF-8');
		ob_start('mb_output_handler');
		header ('Content-Type:text/xml; charset=utf-8');
    
        $manager = new hitobitoNaviPageManager();
        
        $start = 0;
        $limit = 20;
        
        $channel = $this->getChannel();
        $criteria = $channel->getCriteria($request);
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        
        $itemlist = $manager->getObjects($criteria);
        $request->setAttribute('itemlist', $itemlist);
        
        $request->setAttribute('channel', $channel);
        
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    
    private function getChannel()
    {
        $request = $this->getContext()->getRequest();
//        if($request->hasParameter('navipage_id')){
//        	return new NaviPageStoriesRssChannel($this->getContext());
//        }
        if($request->hasParameter('category_id')){
        	return new CategoryNaviPageRssChannel($this->getContext());
        }
        
    	return new GlobalNaviPageRssChannel($this->getContext());
    }
}


?>