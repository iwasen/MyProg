<?php
/**
 * BlogStoryClass
 *
 * 
 * 
 * @package Blog
 * @author  Ryuji<ryuji@rhym.biz>
 * @version $Id: BlogStory.class.php,v 1.11 2007/10/29 07:33:46 cvshitobito Exp $
 */
require_once MO_WEBAPP_DIR.'/hitobito/db/t_blog_article.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogComment.class.php';
require_once MO_WEBAPP_DIR.'/modules/Blog/lib/BlogTrackback.class.php';
class BlogStory extends t_blog_articleObject
{
    private $trackbackList = array();
    protected $pkey = 'bla_article_id';
	protected $table = 't_blog_article';
	private $navipage;

	public function __construct()
	{
		parent::__construct();
		$this->attribute = array(
                    'bla_article_id' => 0,
                    'bla_blog_id' => 0,
			'bla_status'  => 1,
			'bla_title'  => '',
			'bla_article_text'  => '',
			'bla_ng_word'  => '',
			'bla_patrol_check'  => 1,
			'bla_patrol_date'  => null,
			'bla_patrol_id'  => 0,
			'bla_admin_check'  => 1,
			'bla_admin_date'  => null,
			'bla_admin_id'  => 0,
			'bla_open_flag'  => 1,
			'bla_date'  => date("Y-m-d H:i:s"),
			'bla_communication_msg'  => '',
			'bla_pickup'  => null,
			'bla_keisai_flag'  => 1,
			'bla_navi_select_flag'  => 0,
			'bla_comment_flag'  => 1,
			'bla_trackback_flag'  => 1,
			'bla_pv_id'  => 0,
			'bla_member_id'  => 0,
			'bla_blog_category_id' => 0,
			'bla_order' => 1);
	}
	
	public function updatePageView()
	{
		hitobito::countPageView($this->getAttribute('bla_pv_id'));
	}
	
	public function canPoxxxomment()
	{
		return ($this->getAttribute('bla_comment_flag') ==1) ? TRUE : FALSE;
	}
	public function canTrackback()
	{
		return ($this->getAttribute('bla_trackback_flag') ==1) ? TRUE : FALSE;
	}
	
	public function getImageId()
	{
		return $this->getAttribute('bla_blog_image_id');
	}
	
	public function getNaviPageInfo()
	{
		if(!$this->navipage){
			$criteria = new HNbCriteria();
			$criteria->setWhere('nvp_blog_id='.$this->getAttribute('bla_blog_id'));
			$navipageHandler = new hitobitoNavipageManager();
			$this->navipage = $navipageHandler->getOne($criteria);
		}
		return $this->navipage;
	}
	
	public function getNaviPageId()
	{
		return $this->getNaviPageInfo()->getId();
	}
	
	public function getComments()
	{
		$commentManager = new BlogCommentManager();
		return $commentManager->getCommentsByStoryId($this->getId());
	}
	public function getTrackbacks()
	{
		$manager = new BlogTrackbackManager();
		return $manager->getTrackbacksByStoryId($this->getId());
	}
	public function getCommentsNum()
	{
		$commentManager = new BlogCommentManager();
		return $commentManager->getCommentsNumByStoryId($this->getId());
	}
	
	public function getTrackbacksNum()
	{
		$manager = new BlogTrackbackManager();
		return $manager->getTrackbacksNumByStoryId($this->getId());
	}
	
	public function getTrackbackUrl()
	{
		$url = hitobito::getUrl() . 'index.php?module=Blog&amp;action=receiveTrackback&amp;story_id='.$this->getId();
		
		return $url;
	}
	
	
	public function getTrackBackList()
	{
		return $this->trackbackList;
	}
	
	public function getBlog_id ()
	{
    	return $this->getAttribute('bla_blog_id');
    }
    
    public function getDate()
    {
    	return $this->getAttribute('bla_date'); 
    }

	
	
 	
    

    
    
    
    
    public function getTitle()
    {
        return $this->getAttribute('bla_title');
    }
    
    public function getBody()
    {
        return $this->getAttribute('bla_article_text');
    }
    
    public function getShortText()
    {
    	$text = strip_tags($this->getAttribute('bla_article_text'));
    	return mb_substr($text, 0, 20);
    }
    
    public function getText4trackback()
    {
    	$text = strip_tags($this->getAttribute('bla_article_text'));
    	return mb_substr($text, 0, 255);
    }
    
    public function getPoster()
    {
    	$handler = HNb::getHandler('MemberManager');
    	$poster = $handler->get($this->getAttribute('bla_member_id'));
    	return $poster;
    }
    
    public function getPosterName()
    {
    	$poster = $this->getPoster();
    	return $poster->getName();
    }
    
    public function getOpenStatusText()
    {
    	switch($this->getAttribute('bla_open_flag')){
    		case 0:
    			return HNb::tr('非公開');
    		case 1:
    			return HNb::tr('公開');
    		case 2:
    			return HNb::tr('直打ちのみ');
    	}
    }

    public function getBlogCategoryId()
    {
        return $this->getAttribute('bla_blog_category_id');
    }
    
    public function getBlogCategoryName()
    {
        $code = $this->getBlogCategoryId();
        $sql = "SELECT category_name FROM blog_category WHERE blog_category_id = $code";
        $exchangeCategoryId = $this->db->GetOne($sql);
        return $exchangeCategoryId;
    }

	public function unsetNew()
	{
		parent::setNew(FALSE);
	}

	/**
	 * 一般公開のセット
	 */
	public function setPublic($status)
	{
		$this->setAttribute('bla_open_flag', $status);
	}
	
	public function setBlog_id($blog_id)
	{
		$this->setAttribute('bla_blog_id', $blog_id );
	}
    
	public function setTrackBackLIst($list)
	{
		foreach($list as $url){
			if(empty($url))continue;
			$this->trackbackList[] = $url;
		}
	}
    public function setId($id)
    {
        $this->setAttribute('bla_article_id', $id);
    }
    
    
    
    public function setTitle($title)
    {
        $this->setAttribute('bla_title', $title);
    }
    
    public function setBody($body)
    {
        $this->setAttribute('bla_article_text', $body);
    }
    
    public function setPoster($member_id)
    {
        $this->setAttribute('bla_member_id', $member_id);
    }
}
?>