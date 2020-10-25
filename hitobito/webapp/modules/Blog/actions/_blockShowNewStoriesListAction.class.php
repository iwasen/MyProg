<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewStoriesListAction.class.php,v 1.3 2006/01/31 09:37:40 ryu Exp $
 */
class _blockShowNewStoriesListAction extends Action
{
	private $blog_id,
			$navipage_id;
    public function execute()
    {
        $controller = $this->getContext()->getController();
        $request = $this->getContext()->getRequest();
        $user = $this->getContext()->getUser();
        
        $storyManager = new BlogStoryManager();
        
        $this->fetch();
		$criteria = $this->getCriteria();        

        $list = $storyManager->getOpenStories($criteria);
        $request->setAttribute('blog_new_stories', $list);
        return View::SUCCESS;
    }
    
    public function isSecure()
    {
        return false;
    }
    
    private function fetch()
    {
    	$navipage = hitobito::getNaviPageInfo();
    	$this->navipage_id = $navipage->getId();
    	$this->blog_id = $navipage->getBlog_id();
    }
    
    private function getCriteria()
    {
        $criteria = new HNbCriteria();
        $criteria->setLimit(10);
        $criteria->setOrder('ORDER BY bla_date DESC');
        $where = 'bla_status =1 AND bla_open_flag=1 AND bla_keisai_flag=1 AND bla_navi_select_flag != 2';
        
        if($this->blog_id){
        	$where .= ' AND bla_blog_id='.$this->blog_id;
        } 
        
        $criteria->setWhere($where);
    	
    	return $criteria;
    }
}

?>