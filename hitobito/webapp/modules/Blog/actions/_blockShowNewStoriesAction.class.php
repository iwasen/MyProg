<?php
/**
 * [ShortDescription]
 *
 * [Description]
 * * 
 * @package Blog
 * @author Ryuji<ryuji@rhym.biz>
 * @version $Id: _blockShowNewStoriesAction.class.php,v 1.6 2006/01/12 08:23:09 ryu Exp $
 */
class _blockShowNewStoriesAction extends Action
{
	private $blog_id,
			$navipage_id,
			$category_id;
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
    	if($this->getContext()->getRequest()->hasParameter('category_id')){
    		$this->category_id = intval($this->getContext()->getRequest()->getParameter('category_id'));
    	}
    }
    
    private function getCriteria()
    {
        $criteria = new HNbCriteria();
        $criteria->setLimit(4);
        $criteria->setOrder('ORDER BY bla_date DESC');
        $where = 'bla_status =1 AND bla_open_flag=1 AND bla_keisai_flag=1 AND bla_navi_select_flag != 2';
        
        if($this->blog_id){
        	$where .= ' AND bla_blog_id='.$this->blog_id;
        }
        if($this->category_id){
        	$where .=' AND nvp_channel_id='.$this->category_id ;
        }
        
        $criteria->setWhere($where);
    	
    	return $criteria;
    }
}

?>